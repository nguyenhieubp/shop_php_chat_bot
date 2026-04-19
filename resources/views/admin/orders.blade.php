@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

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
    .status-select {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        background: #f8fafc;
        cursor: pointer;
        transition: var(--transition);
    }
    .status-select:hover {
        border-color: var(--primary);
    }
    .search-row input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12px;
        background: white;
    }
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 40px;">
    <div>
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800;">Quản lý Đơn Hàng</h1>
        <p class="admin-subtitle">Theo dõi và xử lý các yêu cầu tư vấn và mua sắm từ khách hàng.</p>
    </div>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Thời gian</th>
                <th>Thông tin khách</th>
                <th>Sản phẩm quan tâm</th>
                <th>Trạng thái</th>
                <th style="text-align: right; padding-right: 35px;">Xử lý nhanh</th>
            </tr>
            <tr class="search-row">
                <th><input type="date"></th>
                <th><input type="text" placeholder="Tìm tên/số điện thoại..."></th>
                <th><input type="text" placeholder="Tìm tên sản phẩm..."></th>
                <th><input type="text" placeholder="Lọc trạng thái..."></th>
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
                <td style="text-align: right; padding-right: 35px;">
                    <form action="{{ route('admin.order.update', $order->id) }}" method="POST">
                        @csrf
                        <select name="status" class="status-select" onchange="this.form.submit()">
                            <option value="new" @if($order->status == 'new') selected @endif>Đánh dấu mới</option>
                            <option value="pending" @if($order->status == 'pending') selected @endif>Đang xử lý</option>
                            <option value="completed" @if($order->status == 'completed') selected @endif>Đã hoàn thành</option>
                        </select>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 100px 20px;">
                    <div style="opacity: 0.2; margin-bottom: 20px;">
                        <i class="fa-solid fa-receipt" style="font-size: 80px;"></i>
                    </div>
                    <h3 style="color: #64748b; font-weight: 800;">Chưa có đơn hàng nào</h3>
                    <p style="color: #94a3b8; font-size: 14px; margin-top: 10px;">Khi khách hàng đặt mua, thông tin sẽ xuất hiện ngay tại đây.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
