<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Event;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $quotations = Quotation::query()
            ->with(['event.client', 'items'])
            ->when($search, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('quotation_number', 'like', "%{$search}%")
                        ->orWhereHas('event', fn ($event) => $event->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($request->input('status'), fn ($query, string $status) => $query->whereHas('event', fn ($event) => $event->where('status', $status)))
            ->when($request->input('event'), fn ($query, string $event) => $query->where('event_id', $event))
            ->when(
                $request->input('sort') === 'highest',
                fn ($query) => $query->orderByDesc('grand_total'),
                fn ($query) => $query->latest('created_at')
            )
            ->get();

        return view('quotations.index', [
            'quotations' => $quotations,
            'search' => $search,
            'status' => $request->input('status'),
            'eventFilter' => $request->input('event'),
            'sort' => $request->input('sort'),
            'events' => Event::orderBy('title')->get(),
            'totalQuotations' => Quotation::count(),
            'awaitingApproval' => Quotation::whereHas('event', fn ($event) => $event->where('status', 'sent'))->where('is_approved', false)->count(),
            'approvedCount' => Quotation::where('is_approved', true)->count(),
        ]);
    }

    public function create(): View
    {
        $setting = CompanySetting::current();

        return view('quotations.create', [
            'quotation' => new Quotation([
                'organizing_fee_percent' => $setting->organizing_fee_percent ?? 10,
                'ppn_percent' => $setting->ppn_percent ?? 11,
            ]),
            'events' => Event::with('client')->orderBy('title')->get(),
            'vendors' => Vendor::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $setting = CompanySetting::current();

        $quotation = DB::transaction(function () use ($request, $data, $setting) {
            $quotation = Quotation::create([
                'event_id' => $data['event_id'],
                'signed_by' => $request->user()->id,
                'quotation_number' => Quotation::generateNumber(),
                'variant_label' => $data['variant_label'],
                'variant_start_date' => $data['variant_start_date'],
                'variant_end_date' => $data['variant_end_date'],
                'organizing_fee_percent' => $setting->organizing_fee_percent ?? 10,
                'ppn_percent' => $setting->ppn_percent ?? 11,
                'notes' => $data['notes'] ?? null,
            ]);

            $this->syncItems($quotation, $data['items']);
            $quotation->recalculate();

            return $quotation;
        });

        return redirect()->route('quotations.index')->with('status', "Quotation {$quotation->quotation_number} created.");
    }

    public function show(Quotation $quotation): View
    {
        $quotation->load(['items.vendor', 'event.client', 'event.eventType', 'signedBy']);

        $groupedItems = $quotation->items
            ->groupBy('section')
            ->sortBy(fn ($items, $section) => array_search($section, QuotationItem::SECTIONS));

        return view('quotations.show', [
            'quotation' => $quotation,
            'groupedItems' => $groupedItems,
        ]);
    }

    public function edit(Quotation $quotation): View
    {
        return view('quotations.edit', [
            'quotation' => $quotation->load('items'),
            'events' => Event::with('client')->orderBy('title')->get(),
            'vendors' => Vendor::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Quotation $quotation): RedirectResponse
    {
        $data = $this->validated($request, $quotation);

        DB::transaction(function () use ($quotation, $data) {
            $quotation->update(collect($data)->except('items')->all());
            $quotation->items()->delete();
            $this->syncItems($quotation, $data['items']);
            $quotation->recalculate();
        });

        return redirect()->route('quotations.index')->with('updated', "Quotation {$quotation->quotation_number} updated.");
    }

    public function destroy(Quotation $quotation): RedirectResponse
    {
        $number = $quotation->quotation_number;

        DB::transaction(function () use ($quotation) {
            $quotation->items()->delete();
            $quotation->delete();
        });

        return redirect()->route('quotations.index')->with('error', "Quotation {$number} deleted.");
    }

    public function approve(Request $request, Quotation $quotation): RedirectResponse
    {
        DB::transaction(function () use ($quotation) {
            Quotation::where('event_id', $quotation->event_id)
                ->whereKeyNot($quotation->getKey())
                ->update(['is_approved' => false]);

            $quotation->forceFill(['is_approved' => ! $quotation->is_approved])->save();
        });

        $message = $quotation->is_approved
            ? "Quotation {$quotation->quotation_number} approved as the winning variant."
            : "Approval removed from quotation {$quotation->quotation_number}.";

        return redirect()->route('quotations.index')->with('status', $message);
    }

    private function syncItems(Quotation $quotation, array $items): void
    {
        foreach (array_values($items) as $index => $item) {
            $quotation->items()->create([
                'section' => $item['section'],
                'sub_section' => $item['sub_section'] ?? null,
                'description' => $item['description'],
                'item_detail' => $item['item_detail'] ?? null,
                'qty' => $item['qty'],
                'unit' => $item['unit'] ?? null,
                'length' => $item['length'],
                'length_unit' => $item['length_unit'] ?? null,
                'price' => $item['price'],
                'vendor_id' => $item['vendor_id'] ?? null,
                'total' => round(((float) $item['qty']) * ((float) $item['length']) * ((float) $item['price']), 2),
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function validated(Request $request, ?Quotation $quotation = null): array
    {
        return $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'variant_label' => ['required', 'string', 'max:100'],
            'variant_start_date' => ['required', 'date'],
            'variant_end_date' => ['required', 'date', 'after_or_equal:variant_start_date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.section' => ['required', Rule::in(QuotationItem::SECTIONS)],
            'items.*.sub_section' => ['nullable', 'string', 'max:100'],
            'items.*.description' => ['required', 'string', 'max:200'],
            'items.*.item_detail' => ['nullable', 'string', 'max:200'],
            'items.*.qty' => ['required', 'numeric', 'min:0'],
            'items.*.unit' => ['nullable', 'string', 'max:50'],
            'items.*.length' => ['required', 'numeric', 'min:0'],
            'items.*.length_unit' => ['nullable', 'string', 'max:20'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.vendor_id' => ['nullable', 'exists:vendors,id'],
        ]);
    }
}
