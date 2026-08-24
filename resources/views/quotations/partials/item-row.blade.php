<div class="quotation-item">
    <div class="item-grid">
        <div class="form-group">
            <label>Section</label>
            <select name="items[{{ $i }}][section]" required>
                <option value="" disabled {{ ($item['section'] ?? '') ? '' : 'selected' }}>Select section</option>
                @foreach (\App\Models\QuotationItem::SECTIONS as $section)
                    <option value="{{ $section }}" {{ ($item['section'] ?? '') === $section ? 'selected' : '' }}>{{ \App\Models\QuotationItem::SECTION_LABELS[$section] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Sub section</label>
            <input type="text" name="items[{{ $i }}][sub_section]" value="{{ $item['sub_section'] ?? '' }}" placeholder="e.g. Documentations" />
        </div>
        <div class="form-group span-2">
            <label>Description</label>
            <input type="text" name="items[{{ $i }}][description]" value="{{ $item['description'] ?? '' }}" required placeholder="e.g. Banner 3x2 meter" />
        </div>
    </div>
    <div class="item-grid">
        <div class="form-group span-2">
            <label>Item detail</label>
            <input type="text" name="items[{{ $i }}][item_detail]" value="{{ $item['item_detail'] ?? '' }}" placeholder="Optional notes" />
        </div>
        <div class="form-group">
            <label>Qty</label>
            <input type="number" step="any" min="0" name="items[{{ $i }}][qty]" value="{{ $item['qty'] ?? '' }}" class="calc-input" data-calc="qty" required />
        </div>
        <div class="form-group">
            <label>Unit</label>
            <input type="text" name="items[{{ $i }}][unit]" value="{{ $item['unit'] ?? '' }}" placeholder="e.g. pcs" />
        </div>
    </div>
    <div class="item-grid">
        <div class="form-group">
            <label>Length</label>
            <input type="number" step="any" min="0" name="items[{{ $i }}][length]" value="{{ $item['length'] ?? '' }}" class="calc-input" data-calc="length" required />
        </div>
        <div class="form-group">
            <label>Length unit</label>
            <input type="text" name="items[{{ $i }}][length_unit]" value="{{ $item['length_unit'] ?? '' }}" placeholder="e.g. day" />
        </div>
        <div class="form-group">
            <label>Price (Rp)</label>
            <input type="number" step="any" min="0" name="items[{{ $i }}][price]" value="{{ $item['price'] ?? '' }}" class="calc-input" data-calc="price" required />
        </div>
        <div class="form-group">
            <label>Vendor (supplier)</label>
            <select name="items[{{ $i }}][vendor_id]">
                <option value="">— none —</option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ old("items.$i.vendor_id", $item['vendor_id'] ?? '') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="item-footer">
        <div class="item-total">Rp 0</div>
        <button type="button" class="btn-outline remove-item">Remove</button>
    </div>
    @error("items.$i.section")
        <div class="form-error">{{ $message }}</div>
    @enderror
    @error("items.$i.description")
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>
