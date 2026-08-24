<form method="POST" action="{{ $vendor->exists ? route('vendors.update', $vendor) : route('vendors.store') }}">
    @csrf
    @if ($vendor->exists)
        @method('PUT')
    @endif

    <div class="card">
        <div class="card-header">
            <div class="card-header-text">
                <div class="card-title">{{ $vendor->exists ? 'Edit Vendor' : 'New Vendor' }}</div>
                <div class="card-desc">Vendor name, category, and contact details</div>
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Vendor name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $vendor->name) }}" required />
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    @php $selectedCategory = old('category', $vendor->category); @endphp
                    <select id="category" name="category" required>
                        <option value="" disabled {{ $selectedCategory ? '' : 'selected' }}>Select category</option>
                        @foreach (App\Models\Vendor::CATEGORIES as $name)
                            <option value="{{ $name }}" {{ $selectedCategory === $name ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $vendor->phone) }}" />
                    @error('phone')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $vendor->email) }}" />
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('vendors.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">{{ $vendor->exists ? 'Save Changes' : 'Add Vendor' }}</button>
        </div>
    </div>
</form>
