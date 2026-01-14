<x-ramarama-layout>
    <div style="display: flex; min-height: 100vh; padding-top: 80px;">
        <x-admin-sidebar active="vouchers" />
        <main class="admin-main">
            <div class="admin-header">
                <h1>Promotion Vouchers</h1>
                <a href="{{ route('admin.vouchers.create') }}" class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Create New Voucher
                </a>
            </div>

            @if(session('success'))
                <div class="glass"
                    style="padding: 1rem 2rem; border-radius: 12px; margin-bottom: 2rem; border-left: 4px solid #4ade80; background: rgba(74, 222, 128, 0.1);">
                    {{ session('success') }}
                </div>
            @endif

            <div class="admin-glass-panel" style="padding: 0; overflow: hidden;">
                <table class="cart-table">
                    <thead style="background: rgba(255, 255, 255, 0.02);">
                        <tr>
                            <th style="padding-left: 2rem;">Voucher Details</th>
                            <th>Discount</th>
                            <th>Min Spend</th>
                            <th>Expiry</th>
                            <th style="padding-right: 2rem; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $voucher)
                            <tr>
                                <td style="padding-left: 2rem;">
                                    <div
                                        style="font-family: monospace; font-size: 1.1rem; color: var(--primary); font-weight: 700;">
                                        {{ $voucher->code }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">
                                        {{ $voucher->discount_type === 'percentage' ? $voucher->discount_value . '%' : 'RM' . number_format($voucher->discount_value, 2) }}
                                    </div>
                                </td>
                                <td>
                                    <span
                                        style="color: var(--text-muted);">RM{{ number_format($voucher->min_spend, 2) }}</span>
                                </td>
                                <td>
                                    @if($voucher->expiration_date)
                                        <span
                                            style="{{ \Carbon\Carbon::parse($voucher->expiration_date)->isPast() ? 'color: #ef4444;' : 'color: var(--text-muted);' }}">
                                            {{ \Carbon\Carbon::parse($voucher->expiration_date)->format('d M Y') }}
                                        </span>
                                    @else
                                        <span style="color: var(--text-muted); opacity: 0.5;">No Expiry</span>
                                    @endif
                                </td>
                                <td style="padding-right: 2rem; text-align: right;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn-secondary"
                                            style="padding: 0.5rem 1rem; font-size: 0.8rem;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this voucher?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-secondary"
                                                style="padding: 0.5rem 1rem; font-size: 0.8rem; color: #ef4444; border-color: rgba(239, 68, 68, 0.2);">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-muted);">
                                    No active vouchers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</x-ramarama-layout>