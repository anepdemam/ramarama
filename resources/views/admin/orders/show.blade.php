<x-ramarama-layout>
    <div style="display: flex; min-height: 100vh; padding-top: 80px;">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar">
            <h2 style="font-size: 1.5rem; margin-bottom: 2rem;">Admin Panel</h2>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="admin-nav-link">
                    <i class="fa-solid fa-box"></i> Products
                </a>
                <a href="{{ route('admin.orders.index') }}" class="admin-nav-link active">
                    <i class="fa-solid fa-shopping-cart"></i> Orders
                </a>
                <a href="{{ url('/') }}" class="admin-nav-link">
                    <i class="fa-solid fa-globe"></i> View Site
                </a>
            </nav>
        </aside>

        <main style="flex: 1; padding: 3rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
                <div>
                    <a href="{{ route('admin.orders.index') }}"
                        style="color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; transition: var(--transition);"
                        onmouseover="this.style.color='var(--primary)'"
                        onmouseout="this.style.color='var(--text-muted)'">
                        <i class="fa-solid fa-arrow-left"></i> Back to Orders
                    </a>
                    <h1 style="font-size: 2.5rem;">Order #{{ $order->id }}</h1>
                    <p style="color: var(--text-muted);">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                    </p>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button class="btn-primary"
                        style="background: rgba(255, 255, 255, 0.1); border: 1px solid var(--glass-border); box-shadow: none;">
                        <i class="fa-solid fa-print"></i> Print Invoice
                    </button>
                    <div
                        style="padding: 0.5rem 1.5rem; border-radius: 50px; background: {{ $order->status === 'Completed' ? 'rgba(34, 197, 94, 0.2)' : 'rgba(139, 92, 246, 0.2)' }}; color: {{ $order->status === 'Completed' ? '#22c55e' : '#a78bfa' }}; font-weight: 600; display: flex; align-items: center;">
                        {{ $order->status }}
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">

                <!-- Order Items -->
                <div class="glass" style="padding: 2rem; border-radius: 20px; align-self: start;">
                    <h3
                        style="font-size: 1.2rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-box-open" style="color: var(--primary);"></i> Items
                    </h3>
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Size</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th style="text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 1rem;">
                                            <img src="{{ asset($item->product->images[0] ?? 'images/placeholder.jpg') }}"
                                                alt="{{ $item->product->name }}"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                            <span style="font-weight: 500;">{{ $item->product->name }}</span>
                                        </div>
                                    </td>
                                    <td style="color: var(--text-muted); font-size: 0.9rem;">{{ $item->product->category }}
                                    </td>
                                    <td>
                                        <span
                                            style="background: rgba(255,255,255,0.1); padding: 2px 8px; border-radius: 4px; font-size: 0.85rem;">{{ $item->size }}</span>
                                    </td>
                                    <td style="color: var(--text-muted);">RM{{ number_format($item->price, 2) }}</td>
                                    <td>x{{ $item->quantity }}</td>
                                    <td style="text-align: right; font-weight: 600;">
                                        RM{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" style="text-align: right; padding-top: 2rem; color: var(--text-muted);">
                                    Subtotal</td>
                                <td style="text-align: right; padding-top: 2rem; font-weight: 600;">
                                    RM{{ number_format($order->total, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5"
                                    style="text-align: right; border-bottom: none; color: var(--text-muted);">Shipping
                                </td>
                                <td style="text-align: right; border-bottom: none; color: #22c55e;">Free</td>
                            </tr>
                            <tr>
                                <td colspan="5"
                                    style="text-align: right; border-bottom: none; padding-top: 1rem; font-size: 1.2rem; font-weight: 700;">
                                    Total</td>
                                <td
                                    style="text-align: right; border-bottom: none; padding-top: 1rem; font-size: 1.2rem; font-weight: 700; color: var(--primary);">
                                    RM{{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Info Sidebar -->
                <div style="display: flex; flex-direction: column; gap: 2rem;">

                    <!-- Customer Info -->
                    <div class="glass" style="padding: 2rem; border-radius: 20px;">
                        <h3
                            style="font-size: 1.2rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-solid fa-user" style="color: var(--primary);"></i> Customer Details
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div>
                                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.2rem;">Name</p>
                                <p style="font-weight: 500;">{{ $order->shipping_name }}</p>
                            </div>
                            <div>
                                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.2rem;">Email
                                </p>
                                <p style="font-weight: 500;">{{ $order->user->email }}</p>
                            </div>
                            <div>
                                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.2rem;">Phone
                                </p>
                                <p style="font-weight: 500;">{{ $order->shipping_phone }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="glass" style="padding: 2rem; border-radius: 20px;">
                        <h3
                            style="font-size: 1.2rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-solid fa-truck-fast" style="color: var(--primary);"></i> Shipping Address
                        </h3>
                        <p style="line-height: 1.6; color: var(--text-muted);">
                            {!! nl2br(e($order->shipping_address)) !!}
                        </p>
                        @if($order->tracking_number)
                            <div
                                style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--glass-border);">
                                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem;">Tracking
                                    Number</p>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fa-solid fa-barcode" style="color: var(--accent);"></i>
                                    <span
                                        style="font-family: monospace; font-size: 1.1rem; letter-spacing: 1px;">{{ $order->tracking_number }}</span>
                                </div>
                            </div>
                        @else
                            <div
                                style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--glass-border);">
                                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem;">Tracking
                                    Status</p>
                                <span style="font-style: italic; color: rgba(255,255,255,0.3);">Not shipped yet</span>
                            </div>
                        @endif
                    </div>

                    <!-- Payment Info -->
                    <div class="glass" style="padding: 2rem; border-radius: 20px;">
                        <h3
                            style="font-size: 1.2rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-solid fa-credit-card" style="color: var(--primary);"></i> Payment Info
                        </h3>
                        <div>
                            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.2rem;">Payment
                                Method</p>
                            <div style="display: flex; align-items: center; gap: 0.8rem;">
                                <div style="background: rgba(255,255,255,0.05); padding: 0.5rem; border-radius: 8px;">
                                    <i class="fa-solid fa-building-columns" style="color: var(--text-main);"></i>
                                </div>
                                <p style="font-weight: 500;">{{ $order->payment_method }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</x-ramarama-layout>