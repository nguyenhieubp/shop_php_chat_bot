@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('styles')
<style>
    .admin-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .admin-table th {
        background: #f8fafc;
        padding: 16px 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .admin-table td {
        padding: 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        transition: var(--transition);
    }
    .admin-table tr:hover td {
        background: #fcfcfd;
    }
    .product-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .product-img {
        width: 54px;
        height: 54px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #edf2f7;
    }
    .product-name {
        font-weight: 800;
        color: #1e293b;
        font-size: 14px;
        margin-bottom: 2px;
    }
    .product-id {
        font-size: 10px;
        font-weight: 700;
        color: #94a3b8;
        letter-spacing: 0.02em;
    }
    .price-tag {
        font-weight: 800;
        color: var(--primary);
        font-size: 15px;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        gap: 6px;
    }
    .status-active {
        background: #ecfdf5;
        color: #059669;
    }
    .status-inactive {
        background: #fef2f2;
        color: #dc2626;
    }
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }
    .action-btn {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        transition: var(--transition);
        cursor: pointer;
    }
    .action-btn:hover {
        background: white;
        border-color: var(--primary);
        color: var(--primary);
        box-shadow: var(--shadow-sm);
    }
    .action-btn-danger:hover {
        border-color: #ef4444;
        color: #ef4444;
    }
    .search-row input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12px;
        background: white;
        transition: var(--transition);
    }
    .search-row input:focus {
        border-color: var(--primary);
        outline: none;
    }
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 40px;">
    <div>
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800;">Kho Sản Phẩm</h1>
        <p class="admin-subtitle">Toàn bộ danh mục mỹ phẩm bạn đang quản lý.</p>
    </div>
    <a href="{{ route('admin.product.new') }}" class="btn" style="padding: 14px 28px; font-size: 14px; border-radius: 12px;">
        <i class="fa-solid fa-plus" style="margin-right: 8px;"></i> Thêm sản phẩm mới
    </a>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 350px;">Sản phẩm</th>
                <th>Phân loại</th>
                <th>Giá niêm yết</th>
                <th>Trạng thái</th>
                <th style="text-align: right; padding-right: 35px;">Thao tác</th>
            </tr>
            <tr class="search-row">
                <th><input type="text" placeholder="Tìm tên sản phẩm..."></th>
                <th><input type="text" placeholder="Lọc danh mục..."></th>
                <th><input type="text" placeholder="Khoảng giá..."></th>
                <th><input type="text" placeholder="Trạng thái..."></th>
                <th style="background: #f8fafc;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>
                    <div class="product-info">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" class="product-img">
                        @else
                            <div class="product-img" style="background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 20px;">📦</div>
                        @endif
                        <div>
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-id">SKU: #BEAUTY-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span style="font-weight: 700; color: #475569; font-size: 13px;">
                        <i class="fa-solid fa-folder-open" style="font-size: 11px; opacity: 0.5; margin-right: 5px;"></i>
                        {{ $product->category->name }}
                    </span>
                </td>
                <td>
                    <div class="price-tag">{{ number_format($product->price) }}đ</div>
                </td>
                <td>
                    <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                        <span class="status-dot"></span>
                        {{ $product->is_active ? 'Đang kinh doanh' : 'Tạm ẩn' }}
                    </span>
                    @if($product->is_featured)
                        <span style="font-size: 10px; color: #f59e0b; font-weight: 800; margin-left: 5px; display: block; margin-top: 4px;">
                            <i class="fa-solid fa-star"></i> NỔI BẬT
                        </span>
                    @endif
                </td>
                <td style="text-align: right; padding-right: 35px;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <a href="{{ route('admin.product.edit', $product->id) }}" class="action-btn" title="Chỉnh sửa">
                            <i class="fa-solid fa-pen-nib"></i>
                        </a>
                        <form action="{{ route('admin.product.toggle', $product->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="action-btn" title="{{ $product->is_active ? 'Tạm ẩn' : 'Hiển thị' }}">
                                <i class="fa-solid {{ $product->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.product.delete', $product->id) }}" method="POST" onsubmit="event.preventDefault(); window.confirmDelete(this);" style="display: inline;">
                            @csrf
                            <button type="submit" class="action-btn action-btn-danger" title="Xóa bỏ">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 100px 20px;">
                    <div style="opacity: 0.3; margin-bottom: 20px;">
                        <i class="fa-solid fa-box-open" style="font-size: 80px;"></i>
                    </div>
                    <h3 style="color: #64748b; font-weight: 800;">Chưa có sản phẩm nào</h3>
                    <p style="color: #94a3b8; font-size: 14px; margin-top: 10px;">Bắt đầu bằng việc thêm những mẫu mỹ phẩm đầu tiên của bạn.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
