@extends('layouts.app')

@section('title', 'Thanh toán & Giao hàng')

@section('styles')
<style>
    .checkout-wrapper {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 60px;
        align-items: start;
        padding-top: 60px;
        margin-bottom: 120px;
    }

    @media (max-width: 1024px) {
        .checkout-wrapper {
            grid-template-columns: 1fr;
            gap: 40px;
        }
    }

    .checkout-section-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 35px;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .checkout-section-title span {
        width: 32px;
        height: 32px;
        background: #000;
        color: white;
        border-radius: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .form-card {
        background: var(--surface);
        padding: 40px;
        border-radius: 0;
        border: 2px solid #000;
        box-shadow: none;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--text);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-input, .form-textarea {
        width: 100%;
        padding: 16px 20px;
        border: 1px solid var(--border);
        background: var(--bg);
        border-radius: var(--radius-md);
        color: var(--text);
        font-family: inherit;
        font-size: 15px;
        transition: var(--transition);
        outline: none;
    }

    .form-input:focus, .form-textarea:focus {
        border-color: var(--primary);
        background: var(--surface);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }

    /* Summary Card Sticky */
    .summary-card-sticky {
        position: sticky;
        top: 100px;
        background: var(--surface);
        border-radius: 0;
        border: 2px solid #000;
        padding: 35px;
        box-shadow: none;
    }

    .order-item {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        align-items: center;
    }

    .order-item-img {
        width: 64px;
        height: 64px;
        border-radius: var(--radius-md);
        object-fit: cover;
        background: var(--bg);
        border: 1px solid var(--border);
    }

    .order-item-info {
        flex: 1;
    }

    .order-item-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }

    .order-item-meta {
        font-size: 12px;
        color: var(--text-muted);
    }

    .order-item-price {
        font-weight: 700;
        font-size: 14px;
        color: var(--text);
    }

    .summary-divider {
        height: 1px;
        background: var(--border);
        margin: 25px 0;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 15px;
        color: var(--text-muted);
    }

    .summary-row.total {
        color: var(--text);
        font-weight: 800;
        font-size: 20px;
        margin-top: 10px;
    }

    .btn-checkout {
        width: 100%;
        padding: 18px;
        border-radius: 0;
        background: #000;
        color: white;
        border: none;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
        transition: var(--transition);
        margin-top: 30px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .btn-checkout:hover {
        background: #ff0000;
        transform: none;
        box-shadow: none;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="checkout-wrapper animate-fade">
        <!-- Left: Form -->
        <div class="checkout-form-section">
            <h2 class="checkout-section-title"><span>01</span> Thông tin nhận hàng</h2>
            
            <div class="form-card">
                <form action="{{ route('order.store') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label" for="customer_name">Họ và tên</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-input" placeholder="VD: Nguyễn Văn A" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Số điện thoại</label>
                        <input type="text" name="phone" id="phone" class="form-input" placeholder="VD: 090xxxxxxx" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="address">Địa chỉ giao hàng chi tiết</label>
                        <textarea name="address" id="address" class="form-textarea" placeholder="Số nhà, tên đường, Phường/Xã, Quận/Huyện, Tỉnh/Thành phố..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="notes">Ghi chú (Tùy chọn)</label>
                        <textarea name="notes" id="notes" class="form-textarea" style="min-height: 80px;" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi đến..."></textarea>
                    </div>

                    <input type="hidden" name="is_cart_order" value="1">
                    @foreach($cart as $id => $item)
                        <input type="hidden" name="cart_items[]" value="{{ $id }}">
                    @endforeach
                </form>
            </div>

            <div style="margin-top: 40px; color: var(--text-muted); font-size: 13px; text-align: center;">
                <i class="fa-solid fa-shield-halved"></i> Thanh toán an toàn & Bảo mật thông tin khách hàng
            </div>
        </div>

        <!-- Right: Summary -->
        <div class="checkout-summary-section">
            <h2 class="checkout-section-title"><span>02</span> Tóm tắt đơn hàng</h2>
            
            <div class="summary-card-sticky">
                <div style="max-height: 350px; overflow-y: auto; padding-right: 5px;">
                    @foreach($cart as $item)
                        <div class="order-item">
                            <img src="{{ asset($item['image'] ?? 'placeholder.png') }}" class="order-item-img" alt="{{ $item['name'] }}">
                            <div class="order-item-info">
                                <div class="order-item-name">{{ $item['name'] }}</div>
                                <div class="order-item-meta">Số lượng: {{ $item['quantity'] }}</div>
                            </div>
                            <div class="order-item-price">{{ number_format($item['price'] * $item['quantity']) }}đ</div>
                        </div>
                    @endforeach
                </div>

                <div class="summary-divider"></div>

                <div class="summary-row">
                    <span>Tạm tính</span>
                    <span>{{ number_format($total) }}đ</span>
                </div>
                <div class="summary-row">
                    <span>Phí vận chuyển</span>
                    <span style="color: var(--primary); font-weight: 700;">Miễn phí</span>
                </div>

                <div class="summary-row total">
                    <span>Tổng cộng</span>
                    <span>{{ number_format($total) }}đ</span>
                </div>

                <button type="submit" form="checkout-form" class="btn-checkout">
                    Xác nhận đặt hàng
                </button>

                <div style="margin-top: 20px; text-align: center;">
                    <a href="{{ route('cart.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 600;">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
