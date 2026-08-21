<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Event;
use App\Models\EventType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $events = Event::query()
            ->with(['client', 'eventType', 'createdBy'])
            ->when($search, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhereHas('client', fn ($client) => $client->where('company_name', 'like', "%{$search}%"));
                });
            })
            ->when($request->input('type'), fn ($query, string $type) => $query->where('event_type_id', $type))
            ->when($request->input('status'), fn ($query, string $status) => $query->where('status', $status))
            ->when(
                $request->input('sort') === 'newest',
                fn ($query) => $query->orderByDesc('start_date')->orderByDesc('id'),
                fn ($query) => $query->orderBy('start_date')->orderBy('id')
            )
            ->get();

        return view('events.index', [
            'events' => $events,
            'search' => $search,
            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'sort' => $request->input('sort'),
            'eventTypes' => EventType::orderBy('name')->get(),
            'totalEvents' => Event::count(),
            'totalTypes' => EventType::count(),
        ]);
    }

    public function create(): View
    {
        return view('events.create', [
            'event' => new Event,
            'clients' => Client::orderBy('company_name')->get(),
            'eventTypes' => EventType::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Event::create($this->validated($request) + ['created_by' => $request->user()->id]);

        return redirect()->route('events.index')->with('status', 'Event created.');
    }

    public function edit(Event $event): View
    {
        return view('events.edit', [
            'event' => $event,
            'clients' => Client::orderBy('company_name')->get(),
            'eventTypes' => EventType::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $event->update($this->validated($request));

        return redirect()->route('events.index')->with('updated', 'Event updated.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('events.index')->with('error', 'Event deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'event_type_id' => ['required', 'exists:event_types,id'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'venue' => ['nullable', 'string', 'max:200'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(Event::STATUSES)],
        ]);
    }
}
