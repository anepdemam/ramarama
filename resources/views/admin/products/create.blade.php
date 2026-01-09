<x-ramarama-layout>
    <div class="admin-container" style="display: flex;">
        <nav class="unique-admin-sidebar" style="width: 250px; background: #333; color: #fff; padding: 20px;">
            <h3>Admin Panel</h3>
            <ul style="list-style: none; padding: 0;">
                <li><a href="{{ route('admin.dashboard') }}"
                        style="color: #fff; display: block; padding: 10px;">Dashboard</a></li>
                <li><a href="{{ route('admin.products.index') }}"
                        style="color: #666; background: #eee; display: block; padding: 10px;">Products</a></li>
                <li><a href="{{ route('admin.orders.index') }}"
                        style="color: #fff; display: block; padding: 10px;">Orders</a></li>
                <li><a href="{{ url('/') }}" style="color: #fff; display: block; padding: 10px;">View Site</a></li>
            </ul>
        </nav>

        <main class="unique-admin-main" style="flex: 1; padding: 20px;">
            <h1>Add New Product</h1>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                class="manage-products-form">
                @csrf
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <input type="text" name="category" id="category" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="price">Price (RM):</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
                </div>

                <div class="stocks-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px;">
                    <div class="form-group">
                        <label for="stock_small">S Stock:</label>
                        <input type="number" name="stock_small" id="stock_small" value="0" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="stock_medium">M Stock:</label>
                        <input type="number" name="stock_medium" id="stock_medium" value="0" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="stock_large">L Stock:</label>
                        <input type="number" name="stock_large" id="stock_large" value="0" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="stock_xl">XL Stock:</label>
                        <input type="number" name="stock_xl" id="stock_xl" value="0" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="stock_2xl">2XL Stock:</label>
                        <input type="number" name="stock_2xl" id="stock_2xl" value="0" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label for="images">Product Images:</label>
                    <input type="file" name="images[]" id="images" class="form-control" multiple>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn">Save Product</button>
                    <a href="{{ route('admin.products.index') }}" style="margin-left: 10px;">Cancel</a>
                </div>
            </form>
        </main>
    </div>
</x-ramarama-layout>