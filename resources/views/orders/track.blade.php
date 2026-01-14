<x-ramarama-layout>
    <div class="hero-section" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding-top: 100px; padding-bottom: 5rem;">
        <div class="container" style="max-width: 900px;">
            <div style="text-align: center; margin-bottom: 4rem;">
                <h1 style="font-size: 3.5rem; margin-bottom: 1rem;">Track Your Order</h1>
                <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Enter your Order ID below to see the real-time status of your purchase.</p>
            </div>

            <!-- Search Form -->
            <div class="glass" style="padding: 2.5rem; border-radius: 24px; margin-bottom: 3rem; background: rgba(255,255,255,0.03);">
                <form action="{{ route('orders.track') }}" method="GET" style="display: flex; gap: 1rem;">
                    <div style="flex: 1; position: relative;">
                        <i class="fa-solid fa-hashtag" style="position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: var(--primary);"></i>
                        <input type="text" name="order_id" value="{{ request('order_id') }}" placeholder="Enter Order ID (e.g. 1)" 
                            style="width: 100%; padding: 1.2rem 1.2rem 1.2rem 3rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 16px; color: white; font-size: 1.1rem; outline: none; transition: var(--transition);"
                            onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 20px rgba(139, 92, 246, 0.2)'"
                            onblur="this.style.borderColor='var(--glass-border)'; this.style.boxShadow='none'">
                    </div>
                    <button type="submit" class="btn-primary" style="padding: 0 2.5rem; font-size: 1.1rem; border-radius: 16px;">
                        Track Now
                    </button>
                </form>
                @if(session('error'))
                    <p style="color: #ef4444; margin-top: 1rem; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                    </p>
                @endif
            </div>

            @if($order)
                <div class="glass" style="padding: 3rem; border-radius: 32px; background: rgba(255,255,255,0.02); animation: fadeInUp 0.6s ease-out;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 3rem; flex-wrap: wrap; gap: 2rem;">
                        <div>
                            <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">Order #{{ $order->id }}</h2>
                            <p style="color: var(--text-muted);">Placed on {{ $order->created_at->format('F d, Y') }}</p>
                        </div>
                        <div style="text-align: right;">
                            <span style="display: block; color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">Current Status</span>
                            <div style="padding: 0.6rem 1.5rem; border-radius: 50px; background: rgba(139, 92, 246, 0.1); color: var(--primary); font-weight: 700; border: 1px solid rgba(139, 92, 246, 0.2); display: inline-block;">
                                {{ $order->status }}
                            </div>
                        </div>
                    </div>

                    <!-- Visual Stepper -->
                    <div style="margin-bottom: 4rem; padding: 0 1rem;">
                        @php
                            $statuses = ['Pending', 'Processing', 'Shipped', 'Completed'];
                            $currentIdx = array_search($order->status, $statuses);
                            if ($order->status == 'Cancelled') $currentIdx = -1;
                        @endphp
                        
                        <div style="display: flex; justify-content: space-between; position: relative; margin-bottom: 1rem;">
                            <!-- Progress Line -->
                            <div style="position: absolute; top: 15px; left: 0; right: 0; hieght: 4px; background: rgba(255,255,255,0.05); height: 4px; border-radius: 2px;"></div>
                            <div style="position: absolute; top: 15px; left: 0; width: {{ $currentIdx >= 0 ? ($currentIdx / (count($statuses) - 1)) * 100 : 0 }}%; height: 4px; background: linear-gradient(90deg, #8b5cf6, #d946ef); border-radius: 2px; transition: width 1s ease-in-out;"></div>

                            @foreach($statuses as $index => $status)
                                <div style="position: relative; z-index: 1; text-align: center; width: 100px;">
                                    <div style="width: 34px; height: 34px; border-radius: 50%; background: {{ $index <= $currentIdx ? 'var(--primary)' : 'rgba(255,255,255,0.1)' }}; margin: 0 auto 1rem; border: 4px solid var(--bg-main); display: flex; align-items: center; justify-content: center; box-shadow: {{ $index <= $currentIdx ? '0 0 20px rgba(139, 92, 246, 0.4)' : 'none' }}; transition: all 0.3s ease;">
                                        @if($index < $currentIdx)
                                            <i class="fa-solid fa-check" style="font-size: 0.8rem; color: white;"></i>
                                        @elseif($index == $currentIdx)
                                            <div style="width: 8px; height: 8px; border-radius: 50%; background: white; animation: pulse 2s infinite;"></div>
                                        @endif
                                    </div>
                                    <span style="font-size: 0.85rem; font-weight: 600; color: {{ $index <= $currentIdx ? 'white' : 'var(--text-muted)' }};">{{ $status }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                        <!-- Shipping Info -->
                        <div style="background: rgba(255,255,255,0.03); padding: 2rem; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05);">
                            <h4 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid fa-truck" style="color: var(--primary);"></i> Shipping Info
                            </h4>
                            <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">
                                <strong>{{ $order->shipping_name }}</strong><br>
                                {!! nl2br(e($order->shipping_address)) !!}
                            </p>
                        </div>

                        <!-- Tracking Number -->
                        <div style="background: rgba(255,255,255,0.03); padding: 2rem; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05);">
                            <h4 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid fa-barcode" style="color: var(--primary);"></i> Tracking Number
                            </h4>
                            @if($order->tracking_number)
                                <div style="background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 12px; border: 1px dashed rgba(255,255,255,0.1); text-align: center;">
                                    <span style="font-family: monospace; font-size: 1.4rem; letter-spacing: 2px; color: var(--accent);">{{ $order->tracking_number }}</span>
                                </div>
                                <div style="margin-top: 1.5rem; text-align: center;">
                                    <a href="https://tracking.my/track/{{ $order->tracking_number }}" target="_blank" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; font-size: 0.9rem;">
                                        <i class="fa-solid fa-external-link-alt"></i> Track on Tracking.my
                                    </a>
                                </div>

                                @if(isset($trackingData) && !empty($trackingData))
                                    <div style="margin-top: 2rem; text-align: left; background: rgba(0,0,0,0.1); padding: 1rem; border-radius: 12px;">
                                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1rem;">Latest Updates (API):</p>
                                        {{-- 
                                          Generic display since structure varies. 
                                          Adjust keys based on actual API response e.g. 'data' -> 'events'
                                        --}}
                                        <div style="font-size: 0.85rem; max-height: 200px; overflow-y: auto;">
                                            <pre style="white-space: pre-wrap; color: var(--text-muted);">{{ json_encode($trackingData, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <p style="color: var(--text-muted); font-style: italic; text-align: center; padding: 1rem;">Tracking number will be available once the order is shipped.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</x-ramarama-layout>
