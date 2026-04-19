@extends('layouts.admin')

@section('title', 'Quản lý Danh mục')

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
    .cat-name {
        font-weight: 800;
        color: #1e293b;
        font-size: 14px;
    }
    .slug-badge {
        background: #f1f5f9;
        color: var(--primary);
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
    }
    .count-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        background: #eff6ff;
        color: #1d4ed8;
        gap: 6px;
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
        border-color: #ef4444;
        color: #ef4444;
        box-shadow: var(--shadow-sm);
    }
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 40px;">
    <div>
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800;">Phân Loại Sản Phẩm</h1>
        <p class="admin-subtitle">Tổ chức các dòng mỹ phẩm của bạn theo danh mục chuyên nghiệp.</p>
    </div>
    <a href="{{ route('admin.category.new') }}" class="btn" style="padding: 14px 28px; font-size: 14px; border-radius: 12px;">
        <i class="fa-solid fa-folder-plus" style="margin-right: 8px;"></i> Tạo danh mục mới
    </a>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 20px; max-width: 900px;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Tên danh mục</th>
                <th>Slug / Link</th>
                <th>Số lượng SP</th>
                <th style="text-align: right; padding-right: 35px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>
                    <div class="cat-name">
                        <i class="fa-solid fa-folder" style="color: var(--primary); margin-right: 10px; opacity: 0.5;"></i>
                        {{ $category->name }}
                    </div>
                </td>
                <td><span class="slug-badge">{{ $category->slug }}</span></td>
                <td>
                    <span class="count-badge">
                        <i class="fa-solid fa-box-archive" style="font-size: 10px;"></i>
                        {{ $category->products_count }} sản phẩm
                    </span>
                </td>
                <td style="text-align: right; padding-right: 35px;">
                    <form action="{{ route('admin.category.delete', $category->id) }}" method="POST" onsubmit="event.preventDefault(); window.confirmDelete(this);">
                        @csrf
                        <button type="submit" class="action-btn" title="Xóa danh mục">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 80px 20px;">
                    <div style="opacity: 0.2; margin-bottom: 20px;">
                        <i class="fa-solid fa-folder-open" style="font-size: 60px;"></i>
                    </div>
                    <h3 style="color: #64748b; font-weight: 800;">Chưa có danh mục nào</h3>
                    <p style="color: #94a3b8; font-size: 13px; margin-top: 10px;">Hãy tạo danh mục đầu tiên để bắt đầu phân loại mỹ phẩm của bạn.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container" style="margin-top: 30px; display: flex; justify-content: center;">
    {{ $categories->links() }}
</div>

<style>
    /* Pagination Styling */
    .pagination {
        display: flex;
        gap: 8px;
        list-style: none;
        padding: 0;
    }
    .page-item {
        margin: 0;
    }
    .page-link {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: white;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-weight: 700;
        font-size: 14px;
        transition: var(--transition);
        text-decoration: none;
    }
    .page-item.active .page-link {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        box-shadow: 0 4px 12px rgba(188, 143, 143, 0.3);
    }
    .page-link:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: #fdfdfd;
    }
    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endsection
