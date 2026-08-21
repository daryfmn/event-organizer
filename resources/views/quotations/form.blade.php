<form method="POST" action="{{ $quotation->exists ? route('quotations.update', $quotation) : route('quotations.store') }}" id="quotation-form">
    @csrf
    @if ($quotation->exists)
        @method('PUT')
    @endif

    <div class="card">
        <div class="card-header">
            <div class="card-header-text">
                <div class="card-title">{{ $quotation->exists ? 'Edit Quotation' : 'New Quotation' }}</div>
                <div class="card-desc">
                    @if ($quotation->exists)
                        {{ $quotation->quotation_number }} — the number is permanent
                    @else
                        The quotation number is generated automatically when you save
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="event_id">Event</label>
                    <select id="event_id" name="event_id" required>
                        <option value="" disabled {{ old('event_id', $quotation->event_id) ? '' : 'selected' }}>Select event</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id', $quotation->event_id) == $event->id ? 'selected' : '' }}>
                                {{ $event->title }} — {{ $event->client->company_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('event_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="variant_label">Variant label</label>
                    <input id="variant_label" name="variant_label" type="text" value="{{ old('variant_label', $quotation->variant_label) }}" required placeholder="e.g. 2 Hari / Senin – Selasa" />
                    @error('variant_label')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="variant_start_date">Coverage start</label>
                    <input id="variant_start_date" name="variant_start_date" type="date" value="{{ old('variant_start_date', $quotation->variant_start_date?->format('Y-m-d')) }}" required />
                    @error('variant_start_date')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="variant_end_date">Coverage end</label>
                    <input id="variant_end_date" name="variant_end_date" type="date" value="{{ old('variant_end_date', $quotation->variant_end_date?->format('Y-m-d')) }}" required />
                    @error('variant_end_date')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="organizing_fee_percent">Organizing fee (%)</label>
                    <input id="organizing_fee_percent" name="organizing_fee_percent" type="number" step="any" min="0" max="100" value="{{ old('organizing_fee_percent', $quotation->organizing_fee_percent) }}" class="calc-input" data-calc="fee" required />
                    @error('organizing_fee_percent')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="ppn_percent">PPN (%)</label>
                    <input id="ppn_percent" name="ppn_percent" type="number" step="any" min="0" max="100" value="{{ old('ppn_percent', $quotation->ppn_percent) }}" class="calc-input" data-calc="ppn" required />
                    @error('ppn_percent')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="items-header">
                <div class="card-title">Items</div>
                <button type="button" class="btn-outline" id="add-item">+ Add Item</button>
            </div>

            <div id="items-list">
                @php
                    $oldItems = old('items');
                    $rows = $oldItems !== null
                        ? $oldItems
                        : ($quotation->exists
                            ? $quotation->items->map(fn ($item) => $item->only(['section', 'sub_section', 'description', 'item_detail', 'qty', 'unit', 'length', 'length_unit', 'price', 'vendor_id']))->all()
                            : []);
                    if ($rows === []) {
                        $rows = [[]];
                    }
                @endphp
                @foreach ($rows as $i => $item)
                    @include('quotations.partials.item-row', ['i' => $i, 'item' => $item, 'vendors' => $vendors])
                @endforeach
            </div>

            <template id="item-template">
                @include('quotations.partials.item-row', ['i' => '__INDEX__', 'item' => [], 'vendors' => $vendors])
            </template>

            <div class="totals-box">
                <div class="totals-row"><span>Subtotal</span><span id="calc-subtotal">Rp 0</span></div>
                <div class="totals-row"><span>Organizing fee (<span id="calc-fee-label">{{ $quotation->organizing_fee_percent }}</span>%)</span><span id="calc-fee">Rp 0</span></div>
                <div class="totals-row"><span>Total</span><span id="calc-total">Rp 0</span></div>
                <div class="totals-row"><span>PPN (<span id="calc-ppn-label">{{ $quotation->ppn_percent }}</span>%)</span><span id="calc-ppn">Rp 0</span></div>
                <div class="totals-row grand"><span>Grand Total</span><span id="calc-grand">Rp 0</span></div>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" placeholder="Terms, payment schedule, or other notes...">{{ old('notes', $quotation->notes) }}</textarea>
                @error('notes')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('quotations.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">{{ $quotation->exists ? 'Save Changes' : 'Create Quotation' }}</button>
        </div>
    </div>
</form>

<script>
    function formatRp(value) {
        return 'Rp ' + Math.round(value).toLocaleString('id-ID');
    }

    function itemTotal(card) {
        const get = (name) => parseFloat(card.querySelector('[data-calc="' + name + '"]').value) || 0;
        return get('qty') * get('length') * get('price');
    }

    function recalculate() {
        let subtotal = 0;
        document.querySelectorAll('#items-list .quotation-item').forEach((card) => {
            const total = itemTotal(card);
            card.querySelector('.item-total').textContent = formatRp(total);
            subtotal += total;
        });

        const feePercent = parseFloat(document.querySelector('[data-calc="fee"]').value) || 0;
        const ppnPercent = parseFloat(document.querySelector('[data-calc="ppn"]').value) || 0;
        const fee = subtotal * feePercent / 100;
        const total = subtotal + fee;
        const ppn = total * ppnPercent / 100;

        document.getElementById('calc-subtotal').textContent = formatRp(subtotal);
        document.getElementById('calc-fee').textContent = formatRp(fee);
        document.getElementById('calc-total').textContent = formatRp(total);
        document.getElementById('calc-ppn').textContent = formatRp(ppn);
        document.getElementById('calc-grand').textContent = formatRp(total + ppn);
    }

    document.getElementById('add-item').addEventListener('click', () => {
        const template = document.getElementById('item-template');
        const index = document.querySelectorAll('#items-list .quotation-item').length;
        const html = template.innerHTML.replaceAll('__INDEX__', index);
        document.getElementById('items-list').insertAdjacentHTML('beforeend', html);
    });

    document.addEventListener('click', (event) => {
        if (event.target.classList.contains('remove-item')) {
            event.target.closest('.quotation-item').remove();
            recalculate();
        }
    });

    document.addEventListener('input', (event) => {
        if (event.target.classList.contains('calc-input')) {
            recalculate();
        }
    });

    recalculate();
</script>
