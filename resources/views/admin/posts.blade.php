@extends('layouts.admin')

@section('title', 'Quản lý Bài viết')

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
    .post-cover {
        width: 80px;
        height: 55px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #edf2f7;
    }
    .post-title {
        font-weight: 800;
        color: #1e293b;
        font-size: 14px;
        margin-bottom: 4px;
        line-height: 1.4;
    }
    .post-excerpt {
        font-size: 11px;
        color: #94a3b8;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 400px;
    }
    .date-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
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
    .status-published {
        background: #ecfdf5;
        color: #059669;
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
    .filter-bar {
        background: white;
        padding: 20px;
        border-radius: 20px;
        border: 1px solid var(--border);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .filter-input {
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        background: #f8fafc;
        transition: var(--transition);
    }
    .filter-input:focus {
        border-color: var(--primary);
        outline: none;
        background: white;
    }
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 40px;">
    <div>
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800;">Blog & Bản Tin</h1>
        <p class="admin-subtitle">Chia sẻ bí quyết làm đẹp và cập nhật tin tức tới khách hàng.</p>
    </div>
    <a href="{{ route('admin.post.new') }}" class="btn" style="padding: 14px 28px; font-size: 14px; border-radius: 12px;">
        <i class="fa-solid fa-feather-pointed" style="margin-right: 8px;"></i> Viết bài mới
    </a>
</div>

<div class="filter-bar">
    <div style="position: relative; flex: 1;">
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px;"></i>
        <input type="text" class="filter-input" style="width: 100%; padding-left: 45px;" placeholder="Tìm bài viết theo tiêu đề...">
    </div>
    <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 13px; font-weight: 700; color: #475569;">Ngày đăng:</span>
        <input type="date" class="filter-input">
    </div>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 100px;">Bìa</th>
                <th>Nội dung bài viết</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th style="text-align: right; padding-right: 35px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr>
                <td>
                    @if($post->image)
                        <img src="{{ asset($post->image) }}" class="post-cover">
                    @else
                        <div class="post-cover" style="background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 24px;">📰</div>
                    @endif
                </td>
                <td>
                    <div class="post-title">{{ $post->title }}</div>
                    <div class="post-excerpt">{{ strip_tags($post->content) }}</div>
                </td>
                <td>
                    <div class="date-badge">
                        <i class="fa-solid fa-calendar-day"></i>
                        {{ $post->created_at->format('d/m/Y') }}
                    </div>
                </td>
                <td>
                    <span class="status-badge status-published">
                        <span class="status-dot"></span>
                        Đã xuất bản
                    </span>
                </td>
                <td style="text-align: right; padding-right: 35px;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <a href="{{ route('admin.post.edit', $post->id) }}" class="action-btn" title="Chỉnh sửa">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="{{ route('blog.show', $post->id) }}" target="_blank" class="action-btn" title="Xem trên web">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                        <form action="{{ route('admin.post.delete', $post->id) }}" method="POST" onsubmit="event.preventDefault(); window.confirmDelete(this);" style="display: inline;">
                            @csrf
                            <button type="submit" class="action-btn action-btn-danger" title="Xóa bài">
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
                        <i class="fa-solid fa-newspaper" style="font-size: 80px;"></i>
                    </div>
                    <h3 style="color: #64748b; font-weight: 800;">Chưa có bài viết nào</h3>
                    <p style="color: #94a3b8; font-size: 14px; margin-top: 10px;">Hãy viết bài báo đầu tiên để thu hút thêm nhiều khách hàng hơn.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
