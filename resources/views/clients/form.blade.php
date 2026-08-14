<form method="POST" action="{{ $client->exists ? route('clients.update', $client) : route('clients.store') }}">
    @csrf
    @if ($client->exists)
        @method('PUT')
    @endif

    <div class="card">
        <div class="card-header">
            <div class="card-header-text">
                <div class="card-title">{{ $client->exists ? 'Edit Client' : 'New Client' }}</div>
                <div class="card-desc">Company and person-in-charge details</div>
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="company_name">Company name</label>
                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name', $client->company_name) }}" required />
                    @error('company_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="pic_name">PIC name</label>
                    <input id="pic_name" name="pic_name" type="text" value="{{ old('pic_name', $client->pic_name) }}" required />
                    @error('pic_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $client->phone) }}" />
                    @error('phone')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $client->email) }}" />
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row full">
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address">{{ old('address', $client->address) }}</textarea>
                    @error('address')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('clients.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">{{ $client->exists ? 'Save Changes' : 'Add Client' }}</button>
        </div>
    </div>
</form>
