@extends('layouts.admin')

@section('title', 'Quản lý Sliders')

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
    .banner-preview {
        width: 140px;
        height: 70px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #edf2f7;
    }
    .slider-title {
        font-weight: 800;
        color: #1e293b;
        font-size: 14px;
        margin-bottom: 4px;
    }
    .slider-subtitle {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .pos-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        background: #f1f5f9;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        color: #475569;
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
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 40px;">
    <div>
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800;">Quản lý Banners</h1>
        <p class="admin-subtitle">Cấu hình các slider chiến lược trên trang chủ của bạn.</p>
    </div>
    <a href="{{ route('admin.slider.new') }}" class="btn" style="padding: 14px 28px; font-size: 14px; border-radius: 12px;">
        <i class="fa-solid fa-plus" style="margin-right: 8px;"></i> Thêm Slider mới
    </a>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 180px;">Banner</th>
                <th>Thông tin chiến dịch</th>
                <th>Thứ tự</th>
                <th>Trạng thái</th>
                <th style="text-align: right; padding-right: 35px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sliders as $slider)
            <tr>
                <td>
                    <img src="{{ asset($slider->image) }}" class="banner-preview">
                </td>
                <td>
                    <div class="slider-title">{{ $slider->title ?? 'Banner không có tiêu đề' }}</div>
                    <div class="slider-subtitle">{{ $slider->subtitle ?? 'Không có mô tả phụ' }}</div>
                </td>
                <td>
                    <span class="pos-badge">
                        <i class="fa-solid fa-sort" style="margin-right: 5px; opacity: 0.5;"></i>
                        Vị trí: {{ $slider->position }}
                    </span>
                </td>
                <td>
                    <span class="status-badge {{ $slider->is_active ? 'status-active' : 'status-inactive' }}">
                        <span class="status-dot"></span>
                        {{ $slider->is_active ? 'Đang hiển thị' : 'Đang ẩn' }}
                    </span>
                </td>
                <td style="text-align: right; padding-right: 35px;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <a href="{{ route('admin.slider.edit', $slider->id) }}" class="action-btn" title="Chỉnh sửa">
                            <i class="fa-solid fa-pen-fancy"></i>
                        </a>
                        <form action="{{ route('admin.slider.delete', $slider->id) }}" method="POST" onsubmit="event.preventDefault(); window.confirmDelete(this);" style="display: inline;">
                            @csrf
                            <button type="submit" class="action-btn action-btn-danger" title="Xóa banner">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 100px 20px;">
                    <div style="opacity: 0.2; margin-bottom: 20px;">
                        <i class="fa-solid fa-images" style="font-size: 80px;"></i>
                    </div>
                    <h3 style="color: #64748b; font-weight: 800;">Chưa có banner nào</h3>
                    <p style="color: #94a3b8; font-size: 14px; margin-top: 10px;">Banner giúp trang chủ của bạn thu hút khách hàng hơn.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
