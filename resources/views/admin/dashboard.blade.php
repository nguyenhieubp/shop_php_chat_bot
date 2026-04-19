@extends('layouts.admin')

@section('title', 'Admin Dashboard Overview')

@section('styles')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    .stat-card {
        background: var(--white);
        border-radius: 24px;
        padding: 30px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: #f8fafc;
        color: var(--primary);
        transition: var(--transition);
    }
    .stat-card:hover .stat-icon {
        background: var(--primary);
        color: white;
    }
    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
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
        padding: 18px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        transition: var(--transition);
    }
    .admin-table tr:hover td {
        background: #fcfcfd;
    }
    .order-customer {
        font-weight: 800;
        color: #1e293b;
        font-size: 14px;
    }
    .order-phone {
        font-size: 12px;
        color: #64748b;
        display: block;
        margin-top: 2px;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        gap: 5px;
    }
    .status-new { background: #eef2ff; color: #4338ca; }
    .status-pending { background: #fffbeb; color: #b45309; }
    .status-completed { background: #ecfdf5; color: #059669; }
    .status-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
    }
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 40px;">
    <div>
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800;">Tổng Quan Kinh Doanh</h1>
        <p class="admin-subtitle">Chào mừng trở lại! Dưới đây là tóm tắt hoạt động của cửa hàng hôm nay.</p>
    </div>
    <div style="display: flex; gap: 15px;">
        <a href="{{ route('admin.orders') }}" class="btn" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
            <i class="fa-solid fa-file-invoice"></i> Đơn hàng
        </a>
        <a href="{{ route('admin.product.new') }}" class="btn">
            <i class="fa-solid fa-plus"></i> Sản phẩm
        </a>
    </div>
</div>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fa-solid fa-spa"></i></div>
        <div>
            <div class="stat-value">{{ $productCount }}</div>
            <div class="stat-label">Sản phẩm</div>
        </div>
    </div>
    <div class="stat-card" style="border-bottom: 3px solid #f59e0b;">
        <div class="stat-icon" style="color: #f59e0b;"><i class="fa-solid fa-cart-shopping"></i></div>
        <div>
            <div class="stat-value">{{ $orderCount }}</div>
            <div class="stat-label">Đơn hàng mới</div>
        </div>
    </div>
    <div class="stat-card" style="border-bottom: 3px solid #10b981;">
        <div class="stat-icon" style="color: #10b981;"><i class="fa-solid fa-users"></i></div>
        <div>
            <div class="stat-value">1,280+</div>
            <div class="stat-label">Khách hàng</div>
        </div>
    </div>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <div style="padding: 25px 30px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #fff;">
        <h3 style="font-size: 16px; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-list-check" style="color: var(--primary);"></i> Xử Lý Đơn Hàng Gần Đây
        </h3>
        <a href="{{ route('admin.orders') }}" style="font-size: 13px; font-weight: 700; color: var(--primary); text-decoration: none;">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Khách hàng</th>
                <th>Sản phẩm đặt mua</th>
                <th>Trạng thái</th>
                <th style="text-align: right; padding-right: 35px;">Thời gian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $order)
            <tr>
                <td>
                    <div class="order-customer">{{ $order->customer_name ?? 'Khách lẻ' }}</div>
                    <span class="order-phone">{{ $order->phone }}</span>
                </td>
                <td>
                    <div style="font-weight: 700; color: #475569; font-size: 13px;">{{ $order->product->name }}</div>
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
                    <div style="font-size: 12px; font-weight: 700; color: #64748b;">
                        {{ $order->created_at->diffForHumans() }}
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 60px 20px;">
                    <div style="opacity: 0.1; margin-bottom: 15px;">
                        <i class="fa-solid fa-inbox" style="font-size: 60px;"></i>
                    </div>
                    <p style="color: #94a3b8; font-weight: 700;">Chưa có đơn hàng nào trong hôm nay.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
