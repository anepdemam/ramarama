<x-ramarama-layout>
    <div
        style="padding-top: 120px; padding-bottom: 4rem; max-width: 1200px; margin: 0 auto; padding-left: 2rem; padding-right: 2rem;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
            <div>
                <a href="{{ route('orders.index') }}"
                    style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-muted); text-decoration: none; margin-bottom: 0.5rem; font-size: 0.9rem; transition: color 0.3s ease;"
                    onmouseover="this.style.color='white'" onmouseout="this.style.color='var(--text-muted)'">
                    <i class="fa-solid fa-arrow-left"></i> Back to My Orders
                </a>
                <h1
                    style="font-size: 2.5rem; margin-top: 0.5rem; margin-bottom: 0.5rem; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Order #{{ $order->id }}</h1>
                <p style="color: var(--text-muted);">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>

            <div style="display: flex; gap: 1rem; align-items: center;">
                <button onclick="openTrackModal('{{ $order->id }}')" class="btn-primary"
                    style="display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-truck-fast"></i> Track Order
                </button>
                <div
                    style="padding: 0.5rem 1.5rem; border-radius: 50px; font-weight: 600; background: {{ $order->status === 'Completed' ? 'rgba(34, 197, 94, 0.2)' : 'rgba(139, 92, 246, 0.2)' }}; color: {{ $order->status === 'Completed' ? '#22c55e' : '#a78bfa' }}; border: 1px solid {{ $order->status === 'Completed' ? 'rgba(34, 197, 94, 0.3)' : 'rgba(139, 92, 246, 0.3)' }};">
                    {{ $order->status }}
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Main Content: Items -->
            <div class="glass" style="padding: 2rem; border-radius: 20px;">
                <h3
                    style="font-size: 1.2rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 1rem;">
                    <i class="fa-solid fa-box-open" style="color: var(--primary);"></i> Order Items
                </h3>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    @foreach($order->items as $item)
                        <div
                            style="display: flex; align-items: center; gap: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <img src="{{ asset($item->product->images[0] ?? 'images/placeholder.jpg') }}"
                                alt="{{ $item->product->name }}"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
                            <div style="flex: 1;">
                                <h4 style="font-size: 1.1rem; margin-bottom: 0.3rem;">{{ $item->product->name }}</h4>
                                <div style="display: flex; gap: 1rem; color: var(--text-muted); font-size: 0.9rem;">
                                    <span>Size: <strong style="color: white;">{{ $item->size }}</strong></span>
                                    <span>Qty: <strong style="color: white;">{{ $item->quantity }}</strong></span>
                                </div>
                            </div>
                            <div style="font-size: 1.1rem; font-weight: 600; color: var(--primary);">
                                RM{{ number_format($item->price * $item->quantity, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 2rem; display: flex; flex-direction: column; gap: 0.8rem;">
                    <div style="display: flex; justify-content: space-between; color: var(--text-muted);">
                        <span>Subtotal</span>
                        <span>RM{{ number_format($order->total, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; color: var(--text-muted);">
                        <span>Shipping</span>
                        <span style="color: #22c55e;">Free</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 700; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem; margin-top: 0.5rem;">
                        <span>Total</span>
                        <span style="color: var(--primary);">RM{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Details -->
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <!-- Shipping Info -->
                <div class="glass" style="padding: 2rem; border-radius: 20px;">
                    <h3
                        style="font-size: 1.2rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-truck" style="color: var(--primary);"></i> Shipping Details
                    </h3>
                    <div style="margin-bottom: 1.5rem;">
                        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.2rem;">Recipient</p>
                        <p style="font-weight: 500;">{{ $order->shipping_name }}</p>
                        <p style="font-size: 0.9rem; color: var(--text-muted);">{{ $order->shipping_phone }}</p>
                    </div>
                    <div>
                        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.2rem;">Address</p>
                        <p style="line-height: 1.5;">{!! nl2br(e($order->shipping_address)) !!}</p>
                    </div>
                    @if($order->tracking_number)
                        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05);">
                            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.2rem;">Tracking Number
                            </p>
                            <p style="font-family: monospace; letter-spacing: 1px; color: var(--accent);">
                                {{ $order->tracking_number }}
                            </p>
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
                        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.2rem;">Method</p>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-brands fa-cc-visa" style="font-size: 1.5rem; color: white;"></i>
                            <span>{{ $order->payment_method ?? 'Secure Payment' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Modal (Reused from Index) -->
    <div id="trackingModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000; backdrop-filter: blur(8px); background: rgba(0,0,0,0.6);">
        <div
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 500px; background: rgba(20, 20, 20, 0.95); border: 1px solid var(--glass-border); border-radius: 24px; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
            <button onclick="closeTrackModal()"
                style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.2rem;">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div id="trackingModalContent"
                style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                <div class="loader">Loading...</div>
            </div>
        </div>
    </div>

    <script>
        function openTrackModal(orderId) {
            const modal = document.getElementById('trackingModal');
            const content = document.getElementById('trackingModalContent');

            modal.style.display = 'block';
            content.innerHTML = '<div style="color: var(--text-muted);"><i class="fa-solid fa-circle-notch fa-spin"></i> Loading tracking info...</div>';

            // Prevent body scroll
            document.body.style.overflow = 'hidden';

            // Fetch tracking data
            fetch(`{{ route('orders.track') }}?order_id=${orderId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    content.innerHTML = data.html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = '<div style="color: #ef4444; text-align: center;"><i class="fa-solid fa-circle-exclamation"></i> Failed to load tracking info.</div>';
                });
        }

        function closeTrackModal() {
            const modal = document.getElementById('trackingModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close on click outside
        window.onclick = function (event) {
            const modal = document.getElementById('trackingModal');
            if (event.target == modal) {
                closeTrackModal();
            }
        }
    </script>
</x-ramarama-layout>