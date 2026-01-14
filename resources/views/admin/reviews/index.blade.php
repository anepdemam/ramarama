<x-ramarama-layout>
    <div style="display: flex; min-height: 100vh; padding-top: 80px;">
        <x-admin-sidebar active="reviews" />
        <main class="admin-main">
            <div class="admin-header">
                <h1>Review Moderation</h1>
                <p style="color: var(--text-muted);">Manage and moderate customer product reviews.</p>
            </div>

            @if(session('success'))
                <div class="glass"
                    style="padding: 1rem 2rem; border-radius: 12px; margin-bottom: 2rem; border-left: 4px solid #4ade80; background: rgba(74, 222, 128, 0.1);">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="admin-glass-panel" style="margin-bottom: 2rem; padding: 1.5rem;">
                <form action="{{ route('admin.reviews.index') }}" method="GET"
                    style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 150px;">
                        <label
                            style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">Search
                            by ID</label>
                        <input type="text" name="id" value="{{ request('id') }}" placeholder="Review or Product ID"
                            style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                    </div>
                    <div style="flex: 2; min-width: 200px;">
                        <label
                            style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">Product
                            Name</label>
                        <input type="text" name="item" value="{{ request('item') }}" placeholder="Search products..."
                            style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                    </div>
                    <div style="flex: 1; min-width: 150px;">
                        <label
                            style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">Category</label>
                        <select name="category"
                            style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white; appearance: none;">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" class="btn-primary" style="padding: 0.8rem 1.5rem;">
                            <i class="fa-solid fa-filter"></i> Filter
                        </button>
                        @if(request()->anyFilled(['id', 'item', 'category']))
                            <a href="{{ route('admin.reviews.index') }}" class="btn-secondary"
                                style="padding: 0.8rem 1.5rem; text-decoration: none; display: flex; align-items: center;">
                                <i class="fa-solid fa-xmark"></i> Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="admin-glass-panel" style="padding: 0; overflow: hidden;">
                <table class="cart-table">
                    <thead style="background: rgba(255, 255, 255, 0.02);">
                        <tr>
                            <th style="padding-left: 2rem;">Product</th>
                            <th>User</th>
                            <th>Rating</th>
                            <th style="width: 40%;">Comment</th>
                            <th>Status</th>
                            <th style="padding-right: 2rem; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td style="padding-left: 2rem;">
                                    <div style="font-weight: 600;">{{ $review->product->name }}</div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">ID:
                                        #{{ $review->product->id }}</div>
                                </td>
                                <td>
                                    <div>{{ $review->user->name }}</div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $review->user->email }}
                                    </div>
                                </td>
                                <td>
                                    <div style="color: #fbbf24;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 0.9rem; line-height: 1.5; color: var(--text-muted);">
                                        {{ $review->comment }}
                                    </div>
                                </td>
                                <td>
                                    @if($review->is_approved)
                                        <span
                                            style="background: rgba(74, 222, 128, 0.1); color: #4ade80; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                            Approved
                                        </span>
                                    @else
                                        <span
                                            style="background: rgba(251, 191, 36, 0.1); color: #fbbf24; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td style="padding-right: 2rem; text-align: right;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        @if(!$review->is_approved)
                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-primary"
                                                    style="padding: 0.5rem 1rem; font-size: 0.8rem; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);">
                                                    <i class="fa-solid fa-check"></i> Approve
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.reviews.delete', $review) }}" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this review?')">
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
                                <td colspan="6" style="padding: 4rem; text-align: center; color: var(--text-muted);">
                                    No reviews found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</x-ramarama-layout>