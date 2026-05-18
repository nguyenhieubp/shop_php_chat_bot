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

    /* Detail View Button styling */
    .btn-detail {
        background: #f1f5f9;
        color: #475569;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-detail:hover {
        background: #e2e8f0;
        color: var(--primary);
    }

    /* Modal Styles */
    .order-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(8px);
        z-index: 1000000;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .order-modal {
        background: #ffffff;
        width: 100%;
        max-width: 600px;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        transform: scale(0.9);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .order-modal.active {
        transform: scale(1);
    }
    .order-modal-header {
        background: #0f172a;
        color: #ffffff;
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .order-modal-title {
        font-size: 18px;
        font-weight: 800;
        margin: 0;
    }
    .order-modal-close {
        background: rgba(255,255,255,0.1);
        border: none;
        color: #ffffff;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .order-modal-close:hover {
        background: #ef4444;
        color: #ffffff;
    }
    .order-modal-body {
        padding: 24px;
        max-height: 70vh;
        overflow-y: auto;
    }
    .info-section {
        margin-bottom: 24px;
    }
    .info-section-title {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.05em;
        margin-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 6px;
    }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .info-item {
        margin-bottom: 12px;
    }
    .info-label {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 14px;
        color: #1e293b;
        font-weight: 750;
    }
    .product-detail-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 16px;
        display: flex;
        gap: 16px;
        align-items: center;
        margin-top: 10px;
        border: 1px solid #f1f5f9;
    }
    .product-detail-img {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        object-fit: cover;
        background: #ffffff;
        border: 1px solid #e2e8f0;
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
                    <th><input type="text" name="search_customer" class="column-search" placeholder="Tìm tên/SĐT/địa chỉ..." value="{{ request('search_customer') }}"></th>
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
                    <div style="font-size: 11px; color: #64748b; margin-top: 5px; display: flex; align-items: flex-start; gap: 4px; max-width: 180px;">
                        <i class="fa-solid fa-location-dot" style="font-size: 10px; color: #94a3b8; margin-top: 2px;"></i>
                        <span style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;" title="{{ $order->address }}">
                            {{ $order->address ?? 'Chưa cung cấp địa chỉ' }}
                        </span>
                    </div>
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
                        
                        <button type="button" class="btn-detail" onclick="openOrderModal({{ json_encode($order) }})" title="Xem chi tiết đơn hàng">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        
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
    
    function openOrderModal(order) {
        document.getElementById('modalTitle').textContent = 'Chi Tiết Đơn Hàng #' + order.id;
        document.getElementById('modalCustomerName').textContent = order.customer_name || 'Khách chưa nhập tên';
        
        const phoneEl = document.getElementById('modalCustomerPhone');
        phoneEl.textContent = order.phone;
        phoneEl.href = 'tel:' + order.phone;
        
        document.getElementById('modalCustomerAddress').textContent = order.address || 'Chưa cung cấp địa chỉ';
        
        const prod = order.product;
        if (prod) {
            document.getElementById('modalProductName').textContent = prod.name;
            document.getElementById('modalProductPrice').textContent = new Intl.NumberFormat('vi-VN').format(order.total_amount) + '₫';
            
            const imgEl = document.getElementById('modalProductImg');
            if (prod.image) {
                if (prod.image.startsWith('http')) {
                    imgEl.src = prod.image;
                } else {
                    imgEl.src = '/' + prod.image.replace(/^\/+/, '');
                }
                imgEl.style.display = 'block';
            } else {
                imgEl.src = 'https://placehold.co/60x60?text=No+Img';
            }
        }
        
        document.getElementById('modalPaymentMethod').textContent = order.payment_method.toUpperCase();
        
        let payStatusText = 'Chưa thanh toán';
        if (order.payment_status === 'paid') payStatusText = 'Đã thanh toán';
        if (order.payment_status === 'failed') payStatusText = 'Thất bại';
        document.getElementById('modalPaymentStatus').textContent = payStatusText;
        
        let orderStatusText = 'Mới nhận';
        if (order.status === 'pending') orderStatusText = 'Đang xử lý';
        if (order.status === 'completed') orderStatusText = 'Đã hoàn thành';
        document.getElementById('modalOrderStatus').textContent = orderStatusText;
        
        const date = new Date(order.created_at);
        const dateStr = date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'});
        document.getElementById('modalOrderTime').textContent = dateStr;
        
        const backdrop = document.getElementById('orderDetailModalBackdrop');
        backdrop.style.display = 'flex';
        setTimeout(() => {
            backdrop.style.opacity = '1';
            backdrop.querySelector('.order-modal').classList.add('active');
        }, 10);
    }
    
    function closeOrderModal(e) {
        const backdrop = document.getElementById('orderDetailModalBackdrop');
        backdrop.style.opacity = '0';
        backdrop.querySelector('.order-modal').classList.remove('active');
        setTimeout(() => {
            backdrop.style.display = 'none';
        }, 300);
    }
</script>

<!-- Modal Detail Đơn Hàng -->
<div id="orderDetailModalBackdrop" class="order-modal-backdrop" onclick="closeOrderModal(event)">
    <div class="order-modal" onclick="event.stopPropagation()">
        <div class="order-modal-header">
            <h2 class="order-modal-title" id="modalTitle">Chi Tiết Đơn Hàng</h2>
            <button class="order-modal-close" onclick="closeOrderModal(event)">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="order-modal-body">
            <div class="info-section">
                <h3 class="info-section-title">Thông tin giao hàng</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Khách hàng</div>
                        <div class="info-value" id="modalCustomerName">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Số điện thoại</div>
                        <div class="info-value">
                            <a href="#" id="modalCustomerPhone" style="color: var(--primary); text-decoration: none;">-</a>
                        </div>
                    </div>
                </div>
                <div class="info-item" style="margin-top: 10px;">
                    <div class="info-label">Địa chỉ nhận hàng</div>
                    <div class="info-value" id="modalCustomerAddress" style="line-height: 1.5; font-size: 14px;">-</div>
                </div>
            </div>
            
            <div class="info-section">
                <h3 class="info-section-title">Sản phẩm đặt mua</h3>
                <div class="product-detail-card">
                    <img id="modalProductImg" src="" alt="Sản phẩm" class="product-detail-img">
                    <div style="flex: 1;">
                        <div id="modalProductName" style="font-weight: 800; color: #1e293b; font-size: 15px;">-</div>
                        <div id="modalProductPrice" style="color: #64748b; font-weight: 600; font-size: 13px; margin-top: 4px;">-</div>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <h3 class="info-section-title">Thanh toán & Trạng thái</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Phương thức</div>
                        <div class="info-value" id="modalPaymentMethod">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Trạng thái thanh toán</div>
                        <div class="info-value" id="modalPaymentStatus">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Trạng thái đơn hàng</div>
                        <div class="info-value" id="modalOrderStatus">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Thời gian đặt</div>
                        <div class="info-value" id="modalOrderTime">-</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@endsection
