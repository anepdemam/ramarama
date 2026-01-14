<x-ramarama-layout>
    <div style="display: flex; min-height: 100vh; padding-top: 80px;">
        <x-admin-sidebar active="products" />

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>Edit Product: {{ $product->name }}</h1>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back to Products
                </a>
            </div>

            <div class="admin-glass-panel">
                <form action="{{ route('admin.products.update', $product) }}" method="POST"
                    enctype="multipart/form-data" class="manage-products-form">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
                        <!-- Basic Info Section -->
                        <div style="display: flex; flex-direction: column; gap: 2rem;">
                            <div class="form-group">
                                <label for="name"
                                    style="display: block; margin-bottom: 0.8rem; color: var(--text-muted);">Product
                                    Name</label>
                                <input type="text" name="name" id="name" value="{{ $product->name }}" required>
                            </div>

                            <div class="form-group">
                                <label for="description"
                                    style="display: block; margin-bottom: 0.8rem; color: var(--text-muted);">Description</label>
                                <textarea name="description" id="description" rows="8"
                                    required>{{ $product->description }}</textarea>
                            </div>
                        </div>

                        <!-- Sidebar Info Section -->
                        <div style="display: flex; flex-direction: column; gap: 2rem;">
                            <div class="form-group">
                                <label for="category"
                                    style="display: block; margin-bottom: 0.8rem; color: var(--text-muted);">Category</label>
                                <input type="text" name="category" id="category" value="{{ $product->category }}"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="price"
                                    style="display: block; margin-bottom: 0.8rem; color: var(--text-muted);">Price
                                    (RM)</label>
                                <input type="number" step="0.01" name="price" id="price" value="{{ $product->price }}"
                                    required>
                            </div>

                            <div class="form-group">
                                <label style="display: block; margin-bottom: 0.8rem; color: var(--text-muted);">Current
                                    Images</label>
                                <div class="current-images"
                                    style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
                                    @foreach($product->images as $image)
                                        <div
                                            style="position: relative; width: 60px; height: 60px; border-radius: 8px; overflow: hidden; border: 1px solid var(--glass-border);">
                                            <img src="{{ asset($image) }}"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                                <label for="images"
                                    style="display: block; margin-bottom: 0.8rem; color: var(--text-muted);">Add More
                                    Images</label>
                                <div style="position: relative;">
                                    <input type="file" name="images[]" id="images" multiple
                                        style="padding: 2rem; border-style: dashed; border-width: 2px;">
                                    <i class="fa-solid fa-cloud-arrow-up"
                                        style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Management Section -->
                    <div style="margin-top: 4rem; padding-top: 3rem; border-top: 1px solid var(--glass-border);">
                        <h3 style="font-size: 1.5rem; margin-bottom: 2rem;">Inventory Levels</h3>
                        <div class="stocks-grid"
                            style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.5rem;">
                            <div class="form-group">
                                <label for="stock_small"
                                    style="display: block; margin-bottom: 0.8rem; color: var(--text-muted);">Small</label>
                                <input type="number" name="stock_small" id="stock_small"
                                    value="{{ $product->stock_small }}">
                            </div>
                            <div class="form-group">
                                <label for="stock_medium"
                                    style="display: block; margin-bottom: 0.8rem; color: var(--text-muted);">Medium</label>
                                <input type="number" name="stock_medium" id="stock_medium"
                                    value="{{ $product->stock_medium }}">
                            </div>
                            <div class="form-group">
                                <label for="stock_large"
                                    style="display: block; margin-bottom: 0.8rem; color: var(--text-muted);">Large</label>
                                <input type="number" name="stock_large" id="stock_large"
                                    value="{{ $product->stock_large }}">
                            </div>
                            <div class="form-group">
                                <label for="stock_xl"
                                    style="display: block; margin-bottom: 0.8rem; color: var(--text-muted);">XL</label>
                                <input type="number" name="stock_xl" id="stock_xl" value="{{ $product->stock_xl }}">
                            </div>
                            <div class="form-group">
                                <label for="stock_2xl"
                                    style="display: block; margin-bottom: 0.8rem; color: var(--text-muted);">2XL</label>
                                <input type="number" name="stock_2xl" id="stock_2xl" value="{{ $product->stock_2xl }}">
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 4rem; display: flex; gap: 1.5rem;">
                        <button type="submit" class="btn-primary" style="padding-left: 3rem; padding-right: 3rem;">
                            <i class="fa-solid fa-save"></i> Update Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                            Discard Changes
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-ramarama-layout>