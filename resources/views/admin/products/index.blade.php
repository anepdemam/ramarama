<x-ramarama-layout>
    <div style="display: flex; min-height: 100vh; padding-top: 80px;">
        <x-admin-sidebar active="products" />

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>Manage Products</h1>
                <a href="{{ route('admin.products.create') }}" class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Add New Product
                </a>
            </div>

            @if(session('success'))
                <div
                    style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); padding: 1.2rem 2rem; border-radius: 16px; margin-bottom: 2rem; color: #22c55e; backdrop-filter: blur(10px); display: flex; align-items: center; gap: 1rem;">
                    <i class="fa-solid fa-check-circle" style="font-size: 1.2rem;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="admin-glass-panel" style="padding: 0; overflow: hidden;">
                <table class="cart-table">
                    <thead style="background: rgba(255, 255, 255, 0.02);">
                        <tr>
                            <th style="padding-left: 2rem;">Preview</th>
                            <th>Product Details</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Inventory Status</th>
                            <th style="padding-right: 2rem; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td style="padding-left: 2rem;">
                                    <div
                                        style="width: 60px; height: 60px; border-radius: 12px; overflow: hidden; border: 1px solid var(--glass-border);">
                                        @php
                                            $imageUrl = $product->images[0] ?? 'images/global/placeholder.jpg';
                                            $imageSrc = str_starts_with($imageUrl, 'data:') ? $imageUrl : asset($imageUrl);
                                        @endphp
                                        <img src="{{ $imageSrc }}" alt="{{ $product->name }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; font-size: 1.1rem; color: var(--text-main);">
                                        {{ $product->name }}
                                    </div>
                                    <div style="font-size: 0.85rem; color: var(--text-muted);">ID: #{{ $product->id }}</div>
                                </td>
                                <td>
                                    <span
                                        style="background: rgba(255,255,255,0.05); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">{{ $product->category }}</span>
                                </td>
                                <td>
                                    <span
                                        style="color: var(--primary); font-weight: 700; font-size: 1.1rem;">RM{{ number_format($product->price, 2) }}</span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                                        @php
                                            $stocks = [
                                                'S' => $product->stock_small,
                                                'M' => $product->stock_medium,
                                                'L' => $product->stock_large,
                                                'XL' => $product->stock_xl,
                                                '2XL' => $product->stock_2xl,
                                            ];
                                        @endphp
                                        @foreach($stocks as $label => $count)
                                            <div
                                                style="display: flex; flex-direction: column; align-items: center; background: rgba(255,255,255,0.03); padding: 0.4rem; border-radius: 8px; min-width: 40px; border: 1px solid rgba(255,255,255,0.05);">
                                                <span
                                                    style="font-size: 0.65rem; color: var(--text-muted); font-weight: 700;">{{ $label }}</span>
                                                <span
                                                    style="font-weight: 600; {{ $count <= 5 ? 'color: #ef4444;' : '' }}">{{ $count }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td style="padding-right: 2rem; text-align: right;">
                                    <div style="display: flex; gap: 0.8rem; justify-content: flex-end;">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn-secondary"
                                            style="padding: 0.6rem; border-radius: 12px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Permanently delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); cursor: pointer; padding: 0.6rem; border-radius: 12px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; transition: var(--transition);">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</x-ramarama-layout>