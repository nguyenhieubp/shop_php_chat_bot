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
    .payment-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        margin-top: 5px;
    }
    .payment-paid {
        background: #dcfce7;
        color: #15803d;
    }
    .payment-pending {
        background: #fef9c3;
        color: #a16207;
    }
    .payment-failed {
        background: #fee2e2;
        color: #b91c1c;
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
    .payment-select {
        padding: 6px 12px;
        border: 1px solid transparent;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
        text-transform: uppercase;
        margin-top: 5px;
        display: inline-flex;
        width: auto;
        min-width: 110px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 10px;
        padding-right: 24px;
        transition: var(--transition);
    }
    .payment-select.paid { background: #ecfdf5; color: #059669; }
    .payment-select.pending { background: #fffbeb; color: #b45309; }
    .payment-select.failed { background: #fef2f2; color: #dc2626; }
    .payment-select.failed { background: #fef2f2; color: #dc2626; }
    .payment-select:hover { filter: brightness(0.95); }

    /* Pagination Styles */
    .pagination-wrapper {
        padding: 20px;
        display: flex;
        justify-content: center;
        border-top: 1px solid #f1f5f9;
        background: #fcfcfd;
    }
    .pagination {
        display: flex;
        gap: 5px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .page-item .page-link {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: var(--transition);
    }
    .page-item.active .page-link {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .page-item:not(.active):not(.disabled) .page-link:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: #fff9f9;
    }

    .search-row input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 12px;
        background: white;
        transition: all 0.15s ease-in-out;
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
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800; margin-bottom: 5px;">Quản lý Đơn Hàng</h1>
        <p class="admin-subtitle" style="color: var(--text-secondary); margin: 0;">Theo dõi và xử lý các yêu cầu tư vấn và mua sắm từ khách hàng.</p>
    </div>
    <a href="{{ route('admin.orders.trash') }}" class="btn btn-danger btn-sm" style="padding: 12px 24px; font-size: 14px; border-radius: 12px; display: inline-flex; align-items: center; gap: 8px; font-weight: 700; text-decoration: none;">
        <i class="fa-solid fa-trash-can"></i> Đơn hàng đã xóa
    </a>
</div>

<form method="GET" action="{{ route('admin.orders') }}" id="searchForm" style="margin: 0;">
    <div class="card" style="padding: 0; overflow-x: auto; border-radius: 20px;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 140px;">Thời gian</th>
                    <th style="width: 180px;">Thông tin khách</th>
                    <th>Sản phẩm & Giá</th>
                    <th style="width: 140px;">Thanh toán</th>
                    <th style="width: 150px;">Trạng thái</th>
                    <th style="text-align: right; padding-right: 20px; width: 220px;">Xử lý</th>
                </tr>
                <tr class="search-row">
                    <th><input type="date" name="search_date" class="column-search" value="{{ request('search_date') }}"></th>
                    <th><input type="text" name="search_customer" class="column-search" placeholder="Tìm tên/SĐT..." value="{{ request('search_customer') }}"></th>
                    <th><input type="text" name="search_product" class="column-search" placeholder="Tìm sản phẩm..." value="{{ request('search_product') }}"></th>
                    <th></th>
                    <th>
                        <select name="filter_status" class="column-search" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 12px; font-weight: 700; background: white; cursor: pointer; transition: all 0.15s ease-in-out;">
                            <option value="">Tất cả</option>
                            <option value="new" @if(request('filter_status') == 'new') selected @endif>Mới nhận</option>
                            <option value="pending" @if(request('filter_status') == 'pending') selected @endif>Đang xử lý</option>
                            <option value="completed" @if(request('filter_status') == 'completed') selected @endif>Đã hoàn thành</option>
                        </select>
                    </th>
                    <th style="background: #f8fafc; text-align: center; vertical-align: middle;">
                        @if(request()->anyFilled(['search_date', 'search_customer', 'search_product', 'filter_status']))
                            <a href="{{ route('admin.orders') }}" class="btn btn-sm" style="background: #f1f5f9; color: #475569; padding: 6px 12px; font-size: 11px; border-radius: 6px; border: 1px solid #cbd5e1; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; justify-content: center; box-shadow: var(--shadow-sm);">
                                <i class="fa-solid fa-xmark"></i> Xóa lọc
                            </a>
                        @endif
                    </th>
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
                    <div style="font-size: 12px; color: #94a3b8; font-weight: 600; margin-top: 4px;">
                        {{ number_format($order->total_amount) }}₫
                    </div>
                </td>
                <td>
                    <div style="font-size: 12px; font-weight: 700; color: #1e293b;">
                        {{ strtoupper($order->payment_method) }}
                    </div>
                    <form action="{{ route('admin.order.update', $order->id) }}" method="POST">
                        @csrf
                        <select name="payment_status" class="payment-select {{ $order->payment_status }}" onchange="this.form.submit()">
                            <option value="pending" @if($order->payment_status == 'pending') selected @endif>Chờ TT</option>
                            <option value="paid" @if($order->payment_status == 'paid') selected @endif>Đã thanh toán</option>
                            <option value="failed" @if($order->payment_status == 'failed') selected @endif>Thất bại</option>
                        </select>
                    </form>
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
                <td style="text-align: right; padding-right: 20px;">
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                        <form action="{{ route('admin.order.update', $order->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            <select name="status" class="status-select" onchange="this.form.submit()" style="margin: 0; min-width: 130px;">
                                <option value="new" @if($order->status == 'new') selected @endif>Đánh dấu mới</option>
                                <option value="pending" @if($order->status == 'pending') selected @endif>Đang xử lý</option>
                                <option value="completed" @if($order->status == 'completed') selected @endif>Đã hoàn thành</option>
                            </select>
                        </form>
                        
                        <form action="{{ route('admin.order.delete', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');" style="margin: 0; display: inline-block;">
                            @csrf
                            <button type="submit" style="background-color: #ef4444; color: white; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; transition: background-color 0.2s ease;" title="Xóa đơn hàng">
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
                        <i class="fa-solid fa-receipt" style="font-size: 80px;"></i>
                    </div>
                    <h3 style="color: #64748b; font-weight: 800;">Chưa có đơn hàng nào</h3>
                    <p style="color: #94a3b8; font-size: 14px; margin-top: 10px;">Khi khách hàng đặt mua, thông tin sẽ xuất hiện ngay tại đây.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
    <div class="pagination-wrapper">
        {{ $orders->links() }}
    </div>
    @endif
</div>
</form>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('searchForm');
        const inputs = form.querySelectorAll('.column-search');
        
        inputs.forEach(input => {
            // Trigger search on change event (e.g. date select, status select or blur for text inputs)
            input.addEventListener('change', function() {
                form.submit();
            });
            
            // Submit on Enter key for text inputs
            if (input.type === 'text') {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        form.submit();
                    }
                });
            }
        });
    });
</script>
@endsection
@endsection
