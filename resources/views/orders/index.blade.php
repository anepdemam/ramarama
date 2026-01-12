<x-ramarama-layout>
    <div style="padding-top: 120px; padding-bottom: 4rem; max-width: 1200px; margin: 0 auto; padding-left: 2rem; padding-right: 2rem;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
            <div>
                <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">My Orders</h1>
                <p style="color: var(--text-muted);">View and track your purchase history</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn-primary" style="text-decoration: none;">
                <i class="fa-solid fa-bag-shopping"></i> Continue Shopping
            </a>
        </div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); padding: 1rem; border-radius: 12px; margin-bottom: 2rem; color: #22c55e; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(count($orders) > 0)
            <div class="glass" style="padding: 2rem; border-radius: 20px;">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                        @foreach($order->items->take(2) as $item)
                                            <div style="display: flex; align-items: center; gap: 1rem;">
                                                <img src="{{ asset($item->product->images[0] ?? 'images/placeholder.jpg') }}" alt="{{ $item->product->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                                                <div>
                                                    <span style="font-weight: 500; display: block; font-size: 0.95rem;">{{ $item->product->name }}</span>
                                                    <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $item->size }} x {{ $item->quantity }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($order->items->count() > 2)
                                            <span style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.2rem;">+ {{ $order->items->count() - 2 }} more items...</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="font-weight: 600; color: var(--primary);">RM{{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span style="padding: 0.3rem 0.8rem; border-radius: 50px; font-size: 0.85rem; background: {{ $order->status === 'Completed' ? 'rgba(34, 197, 94, 0.2)' : 'rgba(139, 92, 246, 0.2)' }}; color: {{ $order->status === 'Completed' ? '#22c55e' : '#a78bfa' }}; border: 1px solid {{ $order->status === 'Completed' ? 'rgba(34, 197, 94, 0.3)' : 'rgba(139, 92, 246, 0.3)' }};">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td style="color: var(--text-muted);">{{ $order->created_at->format('d M Y') }}</td>
                                <td style="text-align: right;">
                                    <a href="{{ route('orders.show', $order) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-main); text-decoration: none; padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border-radius: 8px; transition: var(--transition); border: 1px solid var(--glass-border);" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                                        Details <i class="fa-solid fa-arrow-right" style="font-size: 0.8rem;"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-container" style="margin-top: 2rem;">
                @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $orders->links() }}
                @endif
            </div>
        @else
            <div class="glass" style="padding: 4rem; border-radius: 20px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 1.5rem;">
                <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-box-open" style="font-size: 2.5rem; color: var(--text-muted);"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">No orders yet</h3>
                    <p style="color: var(--text-muted);">Looks like you haven't placed any orders yet.</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn-primary" style="margin-top: 1rem;">
                    Start Shopping <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        @endif
    </div>
</x-ramarama-layout>