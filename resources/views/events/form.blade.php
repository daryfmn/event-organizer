<form method="POST" action="{{ $event->exists ? route('events.update', $event) : route('events.store') }}">
    @csrf
    @if ($event->exists)
        @method('PUT')
    @endif

    <div class="card">
        <div class="card-header">
            <div class="card-header-text">
                <div class="card-title">{{ $event->exists ? 'Edit Event' : 'New Event' }}</div>
                <div class="card-desc">Client, schedule, venue, and status</div>
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="client_id">Client</label>
                    <select id="client_id" name="client_id" required>
                        <option value="" disabled {{ old('client_id', $event->client_id) ? '' : 'selected' }}>Select client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $event->client_id) == $client->id ? 'selected' : '' }}>{{ $client->company_name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="event_type_id">Event type</label>
                    <select id="event_type_id" name="event_type_id" required>
                        <option value="" disabled {{ old('event_type_id', $event->event_type_id) ? '' : 'selected' }}>Select type</option>
                        @foreach ($eventTypes as $eventType)
                            <option value="{{ $eventType->id }}" {{ old('event_type_id', $event->event_type_id) == $eventType->id ? 'selected' : '' }}>{{ $eventType->name }}</option>
                        @endforeach
                    </select>
                    @error('event_type_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="title">Event title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $event->title) }}" required />
                @error('title')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="venue">Venue</label>
                    <input id="venue" name="venue" type="text" value="{{ old('venue', $event->venue) }}" placeholder="e.g. Hotel Mercure Jakarta Sabang" />
                    @error('venue')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        @foreach (\App\Models\Event::STATUSES as $statusOption)
                            <option value="{{ $statusOption }}" {{ old('status', $event->status ?? 'draft') === $statusOption ? 'selected' : '' }}>{{ ucfirst($statusOption) }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="start_date">Start date</label>
                    <input id="start_date" name="start_date" type="date" value="{{ old('start_date', $event->start_date?->format('Y-m-d')) }}" required />
                    @error('start_date')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="end_date">End date</label>
                    <input id="end_date" name="end_date" type="date" value="{{ old('end_date', $event->end_date?->format('Y-m-d')) }}" required />
                    @error('end_date')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Brief description of the event...">{{ old('description', $event->description) }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('events.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">{{ $event->exists ? 'Save Changes' : 'Create Event' }}</button>
        </div>
    </div>
</form>
