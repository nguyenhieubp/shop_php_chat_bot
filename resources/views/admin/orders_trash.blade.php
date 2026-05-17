@extends('layouts.admin')

@section('title', 'Lịch sử Đơn hàng đã xóa')

@section('styles')
<style>
    .admin-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .admin-table th {
        background: #f8fafc;
        padding: 12px 15px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
        white-space: nowrap;
    }
    .admin-table td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        transition: var(--transition);
    }
    .admin-table tr:hover td {
        background: #fcfcfd;
    }
    .order-date {
        font-weight: 700;
        color: #64748b;
        font-size: 13px;
    }
    .customer-name {
        font-weight: 800;
        color: #1e293b;
        font-size: 14px;
        margin-bottom: 2px;
    }
    .customer-phone {
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
        text-decoration: none;
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
    .status-new {
        background: #eef2ff;
        color: #4338ca;
    }
    .status-pending {
        background: #fffbeb;
        color: #b45309;
    }
    .status-completed {
        background: #ecfdf5;
        color: #059669;
    }
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }
    .search-row input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 12px;
        background: white;
        transition: all 0.15s ease-in-out;
        height: 38px;
    }
    .search-row input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        outline: none;
    }
    .search-row th {
        padding: 8px 10px !important;
        background: #f8fafc !important;
    }
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 40px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
    <div>
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800; margin-bottom: 5px;">Lịch sử Đơn Hàng đã xóa</h1>
        <p class="admin-subtitle" style="color: var(--text-secondary); margin: 0;">Xem danh sách đơn hàng đã xóa, bạn có thể phục hồi hoặc xóa vĩnh viễn.</p>
    </div>
    <a href="{{ route('admin.orders') }}" class="btn btn-sm" style="background-color: #0f172a; color: white; padding: 12px 24px; font-size: 14px; border-radius: 12px; display: inline-flex; align-items: center; gap: 8px; font-weight: 700; text-decoration: none;">
        <i class="fa-solid fa-arrow-left-long"></i> Quay lại Danh sách
    </a>
</div>

<div class="card" style="padding: 0; overflow-x: auto; border-radius: 20px;">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 140px;">Thời gian đặt</th>
                <th style="width: 150px;">Thời gian xóa</th>
                <th style="width: 180px;">Thông tin khách</th>
                <th>Sản phẩm & Giá</th>
                <th style="width: 120px;">Trạng thái</th>
                <th style="text-align: right; padding-right: 20px; width: 220px;">Thao tác xử lý</th>
            </tr>
            <tr class="search-row">
                <th><input type="date" class="column-search"></th>
                <th><input type="date" class="column-search"></th>
                <th><input type="text" class="column-search" placeholder="Tìm..."></th>
                <th><input type="text" class="column-search" placeholder="Tìm sản phẩm..."></th>
                <th><input type="text" class="column-search" placeholder="Lọc..."></th>
                <th style="background: #f8fafc;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>
                    <div class="order-date">
                        <i class="fa-regular fa-clock" style="margin-right: 5px; opacity: 0.5;"></i>
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </div>
                </td>
                <td>
                    <div class="order-date" style="color: #ef4444;">
                        <i class="fa-regular fa-calendar-minus" style="margin-right: 5px; opacity: 0.7;"></i>
                        {{ $order->deleted_at->format('d/m/Y H:i') }}
                    </div>
                </td>
                <td>
                    <div class="customer-name">{{ $order->customer_name ?? 'Khách chưa nhập tên' }}</div>
                    <a href="tel:{{ $order->phone }}" class="customer-phone">
                        <i class="fa-solid fa-phone-volume" style="font-size: 10px; margin-right: 4px;"></i>
                        {{ $order->phone }}
                    </a>
                </td>
                <td>
                    <div style="font-weight: 700; color: #475569; font-size: 13px;">
                        <i class="fa-solid fa-bag-shopping" style="color: var(--primary); opacity: 0.5; margin-right: 8px;"></i>
                        {{ $order->product->name }}
                    </div>
                    <div style="font-size: 12px; color: #94a3b8; font-weight: 600; margin-top: 4px;">
                        {{ number_format($order->total_amount) }}₫
                    </div>
                </td>
                <td>
                    @php
                        $statusClass = 'status-new';
                        $statusText = 'Mới nhận';
                        if($order->status == 'pending') { $statusClass = 'status-pending'; $statusText = 'Đang xử lý'; }
                        if($order->status == 'completed') { $statusClass = 'status-completed'; $statusText = 'Hoàn tất'; }
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        <span class="status-dot"></span>
                        {{ $statusText }}
                    </span>
                </td>
                <td style="text-align: right; padding-right: 20px;">
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                        <form action="{{ route('admin.order.restore', $order->id) }}" method="POST" style="margin: 0; display: inline-block;">
                            @csrf
                            <button type="submit" style="background-color: #10b981; color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s ease;" title="Khôi phục đơn hàng">
                                <i class="fa-solid fa-rotate-left"></i> Khôi phục
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.order.force_delete', $order->id) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn xóa VĨNH VIỄN đơn hàng này khỏi hệ thống? Hành động này không thể khôi phục lại.');" style="margin: 0; display: inline-block;">
                            @csrf
                            <button type="submit" style="background-color: #ef4444; color: white; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; transition: background-color 0.2s ease;" title="Xóa vĩnh viễn">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 100px 20px;">
                    <div style="opacity: 0.2; margin-bottom: 20px;">
                        <i class="fa-solid fa-trash-can-slash" style="font-size: 80px;"></i>
                    </div>
                    <h3 style="color: #64748b; font-weight: 800;">Thùng rác trống</h3>
                    <p style="color: #94a3b8; font-size: 14px; margin-top: 10px;">Chưa có đơn hàng nào được xóa gần đây.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
