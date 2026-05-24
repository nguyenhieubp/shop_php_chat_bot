@extends('layouts.admin')

@section('title', 'Danh Sách Khách Hàng')

@section('styles')
<style>
    /* Custom style definitions for customer directory */
    .cust-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .cust-title-group h2 {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .cust-title-group p {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
    }

    /* Modern search bar block */
    .search-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 30px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .search-form {
        display: flex;
        gap: 16px;
        max-width: 600px;
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
    }

    .search-input-wrapper i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
    }

    .search-ctrl {
        width: 100%;
        padding: 12px 16px 12px 46px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        outline: none;
        transition: var(--transition);
    }

    .search-ctrl:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-search {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0 24px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
    }

    .btn-search:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .btn-search-reset {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 18px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        text-decoration: none;
    }

    .btn-search-reset:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* List Table Layout */
    .cust-table-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .cust-table-header {
        padding: 24px 30px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cust-table-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* High-End Styled Custom Pagination Override */
    .pagination {
        display: flex;
        list-style: none;
        gap: 8px;
        margin: 30px 0;
        justify-content: center;
        padding-left: 0;
    }

    .page-item .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid var(--border);
        color: #64748b;
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
        transition: var(--transition);
        background: #ffffff;
    }

    .page-item.active .page-link {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.25);
    }

    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f8fafc;
    }

    .page-item:not(.active):not(.disabled) .page-link:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }
</style>
@endsection

@section('content')
<div class="cust-header">
    <div class="cust-title-group">
        <h2>Danh Sách Khách Hàng</h2>
        <p>Quản lý hồ sơ mua hàng, số lượng đơn đã đặt và tổng chi tiêu trọn đời</p>
    </div>
</div>

<!-- Search Card -->
<div class="search-card">
    <form action="{{ route('admin.customers') }}" method="GET" class="search-form">
        <div class="search-input-wrapper">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" class="search-ctrl" placeholder="Tìm kiếm theo tên hoặc số điện thoại..." value="{{ $search }}">
        </div>
        <button type="submit" class="btn-search">
            <i class="fa-solid fa-search"></i> Tìm kiếm
        </button>
        @if($search)
            <a href="{{ route('admin.customers') }}" class="btn-search-reset" title="Xóa tìm kiếm">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        @endif
    </form>
</div>

<!-- Customers Table Card -->
<div class="cust-table-card">
    <div class="cust-table-header">
        <h3 class="cust-table-title">
            <i class="fa-solid fa-users" style="color: var(--primary);"></i> Thống kê thông tin mua hàng của khách hàng
        </h3>
        <span style="font-size: 13px; font-weight: 700; color: #64748b;">
            Hiển thị trang {{ $customers->currentPage() }} / {{ $customers->lastPage() }} (Tổng {{ $customers->total() }} khách hàng)
        </span>
    </div>

    <div style="overflow-x: auto;">
        <table style="margin-top: 0;">
            <thead>
                <tr>
                    <th style="width: 80px;">STT</th>
                    <th>Tên khách hàng</th>
                    <th>Số điện thoại</th>
                    <th style="text-align: center; width: 180px;">Số đơn đã mua</th>
                    <th style="text-align: right; width: 220px;">Tổng chi tiêu trọn đời</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $index => $cust)
                <tr>
                    <td style="font-weight: 700; color: #94a3b8;">
                        {{ ($customers->currentPage() - 1) * $customers->perPage() + $index + 1 }}
                    </td>
                    <td>
                        <div style="font-weight: 800; color: #0f172a; font-size: 14px;">
                            {{ $cust->name ?? 'Khách lẻ' }}
                        </div>
                    </td>
                    <td>
                        <span style="font-weight: 700; color: #334155; font-size: 13px;">
                            <i class="fa-solid fa-phone" style="font-size: 11px; opacity: 0.6; margin-right: 6px;"></i>{{ $cust->phone }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <span class="status-badge status-active" style="padding: 6px 14px; font-weight: 800; font-size: 12px; background: rgba(59, 130, 246, 0.1); color: var(--primary);">
                            {{ $cust->total_orders }} đơn hàng
                        </span>
                    </td>
                    <td style="text-align: right; font-weight: 800; color: #10b981; font-size: 15px;">
                        {{ number_format($cust->total_spent) }}₫
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 80px 20px;">
                        <div style="opacity: 0.1; margin-bottom: 15px;">
                            <i class="fa-solid fa-user-slash" style="font-size: 60px;"></i>
                        </div>
                        <p style="color: #94a3b8; font-weight: 700;">Không tìm thấy khách hàng nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Custom Styled Pagination -->
@if($customers->hasPages())
    {{ $customers->links('pagination::bootstrap-4') }}
@endif

@endsection
