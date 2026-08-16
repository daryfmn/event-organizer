<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $vendors = Vendor::query()
            ->when($search, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($category, fn ($query, string $category) => $query->where('category', $category))
            ->when(
                $request->input('sort') === 'recent',
                fn ($query) => $query->latest('created_at'),
                fn ($query) => $query->orderBy('name')
            )
            ->get();

        $categoryCounts = Vendor::query()
            ->selectRaw('category, count(*) as total')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('total', 'category');

        return view('vendors.index', [
            'vendors' => $vendors,
            'search' => $search,
            'category' => $category,
            'sort' => $request->input('sort'),
            'categories' => $categoryCounts->keys(),
            'categoryCounts' => $categoryCounts,
            'totalVendors' => Vendor::count(),
        ]);
    }

    public function create(): View
    {
        return view('vendors.create', ['vendor' => new Vendor]);
    }

    public function store(Request $request): RedirectResponse
    {
        Vendor::create($this->validated($request));

        return redirect()->route('vendors.index')->with('status', 'Vendor added.');
    }

    public function edit(Vendor $vendor): View
    {
        return view('vendors.edit', ['vendor' => $vendor]);
    }

    public function update(Request $request, Vendor $vendor): RedirectResponse
    {
        $vendor->update($this->validated($request));

        return redirect()->route('vendors.index')->with('updated', 'Vendor updated.');
    }

    public function destroy(Vendor $vendor): RedirectResponse
    {
        $vendor->delete();

        return redirect()->route('vendors.index')->with('error', 'Vendor deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', Rule::in(Vendor::CATEGORIES)],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
        ]);
    }
}
