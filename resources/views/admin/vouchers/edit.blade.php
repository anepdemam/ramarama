<x-ramarama-layout>
    <div style="display: flex; min-height: 100vh; padding-top: 80px;">
        <x-admin-sidebar active="vouchers" />
        <main class="admin-main">
            <div class="admin-header">
                <h1>Edit Voucher: {{ $voucher->code }}</h1>
                <a href="{{ route('admin.vouchers.index') }}" class="btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back to Vouchers
                </a>
            </div>

            <div class="admin-glass-panel">
                <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST"
                    class="manage-products-form">
                    @csrf
                    @method('PUT')
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div class="form-group">
                            <label for="code">Voucher Code</label>
                            <input type="text" name="code" id="code" value="{{ $voucher->code }}" required
                                style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="discount_type">Discount Type</label>
                            <select name="discount_type" id="discount_type" required>
                                <option value="percentage" {{ $voucher->discount_type === 'percentage' ? 'selected' : '' }}>
                                    Percentage (%)</option>
                                <option value="fixed" {{ $voucher->discount_type === 'fixed' ? 'selected' : '' }}>Fixed
                                    Amount
                                    (RM)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="discount_value">Discount Value</label>
                            <input type="number" name="discount_value" id="discount_value"
                                value="{{ $voucher->discount_value }}" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="min_spend">Minimum Spend (RM)</label>
                            <input type="number" name="min_spend" id="min_spend" value="{{ $voucher->min_spend }}"
                                step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="max_discount">Maximum Discount (RM)</label>
                            <input type="number" name="max_discount" id="max_discount"
                                value="{{ $voucher->max_discount }}" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="expiration_date">Expiration Date</label>
                            <input type="date" name="expiration_date" id="expiration_date"
                                value="{{ $voucher->expiration_date }}">
                        </div>
                    </div>

                    <div
                        style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 2rem; border-top: 1px solid var(--glass-border);">
                        <a href="{{ route('admin.vouchers.index') }}" class="btn-secondary">Discard</a>
                        <button type="submit" class="btn-primary">Update Voucher</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-ramarama-layout>