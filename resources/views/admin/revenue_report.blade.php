@extends('layouts.admin')

@section('title', 'Báo Cáo Doanh Thu')

@section('styles')
<style>
    /* Premium statistics design custom styles */
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .report-title-group h2 {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .report-title-group p {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
    }

    .filter-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 30px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .filter-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .filter-ctrl {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        background-color: #ffffff;
        outline: none;
        transition: var(--transition);
        font-family: inherit;
    }

    .filter-ctrl:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 12px;
    }

    .btn-filter-submit {
        flex: 2;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: var(--transition);
    }

    .btn-filter-submit:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .btn-filter-reset {
        flex: 1;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        text-decoration: none;
    }

    .btn-filter-reset:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .btn-export {
        background: #10b981;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    .btn-export:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
    }

    /* KPI Summary Cards */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 30px;
    }

    .kpi-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }

    .kpi-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--primary);
        opacity: 0.8;
    }

    .kpi-card.kpi-revenue::after { background: #ef4444; }
    .kpi-card.kpi-orders::after { background: #3b82f6; }
    .kpi-card.kpi-completed::after { background: #10b981; }
    .kpi-card.kpi-aov::after { background: #8b5cf6; }

    .kpi-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .kpi-revenue .kpi-icon { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .kpi-orders .kpi-icon { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .kpi-completed .kpi-icon { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .kpi-aov .kpi-icon { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

    .kpi-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .kpi-label {
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
    }

    .kpi-value {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
    }

    /* Detailed Table Styling */
    .table-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .table-header {
        padding: 24px 30px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #ffffff;
    }

    .table-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .badge-payment {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-payment.paid { background: #dcfce7; color: #166534; }
    .badge-payment.pending { background: #fef3c7; color: #92400e; }
    .badge-payment.failed { background: #fee2e2; color: #991b1b; }

    .badge-status {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-status.new { background: #e0f2fe; color: #0369a1; }
    .badge-status.pending { background: #fef3c7; color: #92400e; }
    .badge-status.completed { background: #dcfce7; color: #166534; }
</style>
@endsection

@section('content')
<div class="report-header">
    <div class="report-title-group">
        <h2>Báo Cáo Doanh Thu</h2>
        <p>Phân tích hiệu suất bán hàng, trạng thái thanh toán và doanh số thực tế</p>
    </div>
    
    <!-- Export Button -->
    <a href="{{ route('admin.revenue.export', request()->query()) }}" class="btn-export">
        <i class="fa-solid fa-file-excel"></i> Xuất file Excel
    </a>
</div>

<!-- Filters Form -->
<div class="filter-card">
    <form action="{{ route('admin.revenue.report') }}" method="GET">
        <div class="filter-form-grid">
            <div class="filter-group">
                <span class="filter-label">Từ ngày</span>
                <input type="date" name="start_date" class="filter-ctrl" value="{{ $startDateVal }}">
            </div>
            
            <div class="filter-group">
                <span class="filter-label">Đến ngày</span>
                <input type="date" name="end_date" class="filter-ctrl" value="{{ $endDateVal }}">
            </div>
            
            <div class="filter-group">
                <span class="filter-label">Thanh toán</span>
                <select name="payment_status" class="filter-ctrl">
                    <option value="all" {{ $paymentStatus === 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
                    <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="pending" {{ $paymentStatus === 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                    <option value="failed" {{ $paymentStatus === 'failed' ? 'selected' : '' }}>Thanh toán lỗi</option>
                </select>
            </div>
            
            <div class="filter-group">
                <span class="filter-label">Đơn hàng</span>
                <select name="status" class="filter-ctrl">
                    <option value="all" {{ $orderStatus === 'all' ? 'selected' : '' }}>Tất cả đơn hàng</option>
                    <option value="new" {{ $orderStatus === 'new' ? 'selected' : '' }}>Đơn hàng mới</option>
                    <option value="pending" {{ $orderStatus === 'pending' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="completed" {{ $orderStatus === 'completed' ? 'selected' : '' }}>Đã hoàn tất</option>
                </select>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn-filter-submit">
                    <i class="fa-solid fa-filter"></i> Lọc dữ liệu
                </button>
                <a href="{{ route('admin.revenue.report') }}" class="btn-filter-reset" title="Xóa bộ lọc">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- KPI Indicators -->
<div class="kpi-grid">
    <!-- Revenue -->
    <div class="kpi-card kpi-revenue">
        <div class="kpi-icon"><i class="fa-solid fa-circle-dollar-to-slot"></i></div>
        <div class="kpi-info">
            <span class="kpi-label">Doanh Thu Thực Nhận</span>
            <span class="kpi-value">{{ number_format($totalRevenue) }}₫</span>
        </div>
    </div>
    
    <!-- Total Orders -->
    <div class="kpi-card kpi-orders">
        <div class="kpi-icon"><i class="fa-solid fa-receipt"></i></div>
        <div class="kpi-info">
            <span class="kpi-label">Tổng Số Đơn Đặt</span>
            <span class="kpi-value">{{ number_format($totalOrders) }} đơn</span>
        </div>
    </div>
    
    <!-- Completed Orders -->
    <div class="kpi-card kpi-completed">
        <div class="kpi-icon"><i class="fa-solid fa-circle-check"></i></div>
        <div class="kpi-info">
            <span class="kpi-label">Đơn Hàng Thành Công</span>
            <span class="kpi-value">{{ number_format($completedOrders) }} đơn</span>
        </div>
    </div>
    
    <!-- Average Order Value (AOV) -->
    <div class="kpi-card kpi-aov">
        <div class="kpi-icon"><i class="fa-solid fa-scale-balanced"></i></div>
        <div class="kpi-info">
            <span class="kpi-label">Trị Giá Trung Bình Đơn</span>
            <span class="kpi-value">{{ number_format($averageOrderValue) }}₫</span>
        </div>
    </div>
</div>

<!-- Transaction Table -->
<div class="table-card">
    <div class="table-header">
        <h3 class="table-title">
            <i class="fa-solid fa-list-ul" style="color: var(--primary);"></i> Chi Tiết Giao Dịch Trong Khoảng Thời Gian
        </h3>
        <span style="font-size: 13px; font-weight: 700; color: #64748b;">
            Hiển thị {{ $orders->count() }} đơn hàng thỏa mãn
        </span>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="margin-top: 0;">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Khách hàng</th>
                    <th>Sản phẩm đặt mua</th>
                    <th>Phương thức</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th style="text-align: right;">Số tiền</th>
                    <th style="text-align: right;">Doanh thu tính</th>
                </tr>
                <!-- Inline Column Search Header -->
                <tr style="background: #f8fafc;">
                    <th><input type="text" class="column-search" placeholder="Mã..."></th>
                    <th><input type="text" class="column-search" placeholder="Ngày..."></th>
                    <th><input type="text" class="column-search" placeholder="Tên..."></th>
                    <th><input type="text" class="column-search" placeholder="Sản phẩm..."></th>
                    <th><input type="text" class="column-search" placeholder="PT..."></th>
                    <th><input type="text" class="column-search" placeholder="TT..."></th>
                    <th><input type="text" class="column-search" placeholder="Trạng thái..."></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php
                    $isRevenue = ($order->payment_status === 'paid' || $order->status === 'completed');
                @endphp
                <tr>
                    <td style="font-weight: 800; color: #1e293b;">#{{ $order->id }}</td>
                    <td style="font-size: 13px; color: #475569; font-weight: 600;">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #1e293b;">{{ $order->customer_name ?? 'Khách lẻ' }}</div>
                        <span style="font-size: 11px; font-weight: 600; color: #94a3b8;"><i class="fa-solid fa-phone"></i> {{ $order->phone }}</span>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #475569; font-size: 13px;">{{ $order->product->name ?? 'N/A' }}</div>
                    </td>
                    <td style="font-weight: 700; font-size: 11px; color: #475569;">
                        {{ strtoupper($order->payment_method) }}
                    </td>
                    <td>
                        @if($order->payment_status === 'paid')
                            <span class="badge-payment paid"><i class="fa-solid fa-check" style="margin-right: 4px;"></i> Đã thanh toán</span>
                        @elseif($order->payment_status === 'failed')
                            <span class="badge-payment failed"><i class="fa-solid fa-xmark" style="margin-right: 4px;"></i> Lỗi</span>
                        @else
                            <span class="badge-payment pending"><i class="fa-solid fa-clock" style="margin-right: 4px;"></i> Chờ xử lý</span>
                        @endif
                    </td>
                    <td>
                        @if($order->status === 'completed')
                            <span class="badge-status completed">Hoàn tất</span>
                        @elseif($order->status === 'pending')
                            <span class="badge-status pending">Đang xử lý</span>
                        @else
                            <span class="badge-status new">Mới</span>
                        @endif
                    </td>
                    <td style="text-align: right; font-weight: 700; color: #475569;">
                        {{ number_format($order->total_amount) }}₫
                    </td>
                    <td style="text-align: right; font-weight: 800; color: {{ $isRevenue ? '#10b981' : '#64748b' }};">
                        {{ $isRevenue ? number_format($order->total_amount) . '₫' : '0₫' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 80px 20px;">
                        <div style="opacity: 0.1; margin-bottom: 15px;">
                            <i class="fa-solid fa-folder-open" style="font-size: 60px;"></i>
                        </div>
                        <p style="color: #94a3b8; font-weight: 700;">Không có đơn hàng nào thỏa mãn bộ lọc hiện tại.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
