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

    /* Payment Method Styles */
    .payment-methods {
        display: grid;
        gap: 15px;
        margin-top: 15px;
    }

    .payment-method-item {
        border: 1px solid var(--border);
        padding: 20px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
    }

    .payment-method-item:hover {
        border-color: var(--primary);
        background: rgba(59, 130, 246, 0.02);
    }

    .payment-method-item input[type="radio"] {
        width: 20px;
        height: 20px;
        accent-color: #000;
        cursor: pointer;
    }

    .payment-method-item.active {
        border-color: #000;
        background: #f8fafc;
        border-width: 2px;
    }

    .payment-method-icon {
        font-size: 24px;
        width: 40px;
        text-align: center;
    }

    .payment-method-text {
        flex: 1;
    }

    .payment-method-title {
        display: block;
        font-weight: 700;
        font-size: 15px;
        color: var(--text);
        margin-bottom: 2px;
    }

    .payment-method-desc {
        display: block;
        font-size: 13px;
        color: var(--text-muted);
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

                    <div class="form-group" style="margin-top: 40px;">
                        <label class="form-label">Phương thức thanh toán</label>
                        <div class="payment-methods">
                            <label class="payment-method-item active">
                                <input type="radio" name="payment_method" value="cod" checked>
                                <div class="payment-method-icon"><i class="fa-solid fa-truck-fast"></i></div>
                                <div class="payment-method-text">
                                    <span class="payment-method-title">Thanh toán khi nhận hàng (COD)</span>
                                    <span class="payment-method-desc">Thanh toán tiền mặt khi nhận hàng tại nhà</span>
                                </div>
                            </label>

                            <label class="payment-method-item">
                                <input type="radio" name="payment_method" value="vnpay">
                                <div class="payment-method-icon">
                                    <img src="https://sandbox.vnpayment.vn/paymentv2/Images/brands/logo-vnpay.png" alt="VNPay" style="height: 20px;">
                                </div>
                                <div class="payment-method-text">
                                    <span class="payment-method-title">Thanh toán Online (VNPay)</span>
                                    <span class="payment-method-desc">Thanh toán qua ứng dụng ngân hàng, thẻ ATM, Visa, Master...</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <input type="hidden" name="is_cart_order" value="1">
                    <input type="hidden" name="total_amount" value="{{ $total }}">
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

@section('scripts')
<script>
    document.querySelectorAll('.payment-method-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.payment-method-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            this.querySelector('input').checked = true;
        });
    });
</script>
@endsection
