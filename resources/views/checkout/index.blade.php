<x-ramarama-layout>
    <section style="padding-top: 8rem; min-height: 70vh;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <h1 style="font-size: 2.5rem; margin-bottom: 3rem; text-align: center;">Checkout</h1>

            @if(session('error'))
                <div
                    style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); padding: 1rem; border-radius: 12px; margin-bottom: 2rem; color: #ef4444;">
                    <i class="fa-solid fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 3rem;">
                <!-- Shipping Form -->
                <div>
                    <h2 style="font-size: 1.5rem; margin-bottom: 2rem;">Shipping Information</h2>

                    <form action="{{ route('checkout.process') }}" method="POST" class="glass"
                        style="padding: 2rem; border-radius: 20px;">
                        @csrf

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Full Name</label>
                            <input type="text" name="shipping_name"
                                value="{{ old('shipping_name', Auth::user()->name ?? '') }}" required>
                            @error('shipping_name')
                                <span style="color: #ef4444; font-size: 0.9rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Phone Number</label>
                            <input type="tel" name="shipping_phone" value="{{ old('shipping_phone') }}" required>
                            @error('shipping_phone')
                                <span style="color: #ef4444; font-size: 0.9rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Address Line
                                1</label>
                            <input type="text" name="address_1" value="{{ old('address_1') }}"
                                placeholder="Street address, P.O. box, company name" required>
                            @error('address_1')
                                <span style="color: #ef4444; font-size: 0.9rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Address Line 2
                                (Optional)</label>
                            <input type="text" name="address_2" value="{{ old('address_2') }}"
                                placeholder="Apartment, suite, unit, building, floor, etc.">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">City</label>
                                <input type="text" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <span style="color: #ef4444; font-size: 0.9rem;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Postcode</label>
                                <input type="text" name="postcode" value="{{ old('postcode') }}" required>
                                @error('postcode')
                                    <span style="color: #ef4444; font-size: 0.9rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">State</label>
                            <select name="state" required
                                style="width: 100%; background: var(--bg-accent); border: 1px solid var(--glass-border); color: white; padding: 0.8rem 1.2rem; border-radius: 12px;">
                                <option value="">Select State</option>
                                @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Kuala Lumpur', 'Labuan', 'Putrajaya'] as $state)
                                    <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>{{ $state }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state')
                                <span style="color: #ef4444; font-size: 0.9rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn-primary"
                            style="width: 100%; padding: 1.2rem; font-size: 1.1rem;">
                            <i class="fa-solid fa-lock"></i> Place Order
                        </button>
                    </form>
                </div>

                <!-- Order Summary -->
                <div>
                    <h2 style="font-size: 1.5rem; margin-bottom: 2rem;">Order Summary</h2>

                    <div class="glass" style="padding: 2rem; border-radius: 20px;">
                        @foreach($cart as $item)
                            <div
                                style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid var(--glass-border);">
                                <div>
                                    <p style="font-weight: 600;">{{ $item['name'] }}</p>
                                    <p style="color: var(--text-muted); font-size: 0.9rem;">{{ $item['size'] }} ×
                                        {{ $item['quantity'] }}
                                    </p>
                                </div>
                                <p style="font-weight: 600;">RM{{ number_format($item['price'] * $item['quantity'], 2) }}
                                </p>
                            </div>
                        @endforeach

                        <div
                            style="margin-top: 2rem; padding: 1.5rem; border-radius: 12px; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--glass-border);">
                            <h3 style="font-size: 1rem; margin-bottom: 1rem;">Have a Promo Code?</h3>
                            <form action="{{ route('checkout.voucher') }}" method="POST"
                                style="display: flex; gap: 0.5rem;">
                                @csrf
                                <input type="text" name="code" placeholder="Enter code" style="flex: 1;" required>
                                <button type="submit" class="btn-secondary"
                                    style="padding: 0.5rem 1rem; border-radius: 8px;">Apply</button>
                            </form>
                            @if($voucher)
                                <div
                                    style="margin-top: 1rem; display: flex; align-items: center; justify-content: space-between; color: var(--primary);">
                                    <span style="font-size: 0.9rem;"><i class="fa-solid fa-tag"></i> Code
                                        <strong>{{ $voucher['code'] }}</strong> applied</span>
                                    <a href="#" onclick="alert('TODO: remove voucher logic')"
                                        style="color: #ef4444; font-size: 0.8rem;">Remove</a>
                                </div>
                            @endif
                        </div>

                        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid var(--glass-border);">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                                <p style="color: var(--text-muted);">Subtotal</p>
                                <p>RM{{ number_format($total, 2) }}</p>
                            </div>
                            @if($discount > 0)
                                <div
                                    style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: #22c55e;">
                                    <p>Discount</p>
                                    <p>-RM{{ number_format($discount, 2) }}</p>
                                </div>
                            @endif
                            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                                <p style="color: var(--text-muted);">Shipping</p>
                                <p style="color: #22c55e;">FREE</p>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; font-size: 1.5rem; font-weight: 700; color: var(--primary);">
                                <p>Total</p>
                                <p>RM{{ number_format($finalTotal, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-ramarama-layout>