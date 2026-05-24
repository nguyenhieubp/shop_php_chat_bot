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
                <form action="{{ route('order.store') }}" method="POST" id="checkout-form" novalidate>
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
                            @if(!empty($item['image']))
                                <img src="/{{ ltrim($item['image'], '/') }}" class="order-item-img" alt="{{ $item['name'] }}">
                            @else
                                <div class="order-item-img" style="background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-size: 20px;">✨</div>
                            @endif
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

<!-- Order Confirmation Modal -->
<div id="order-confirm-modal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; border: 2px solid #000000; width: 100%; max-width: 800px; padding: 35px; position: relative; animation: modalFadeIn 0.3s ease-out; box-shadow: 10px 10px 0px rgba(0,0,0,0.15); display: flex; flex-direction: column;">
        <h3 style="font-size: 22px; font-weight: 800; text-transform: uppercase; margin-bottom: 25px; border-bottom: 2px solid #000000; padding-bottom: 12px; display: flex; align-items: center; gap: 10px; color: #1f2937;">
            <i class="fa-solid fa-file-invoice" style="color: #ff0000;"></i> Xác nhận thông tin đơn hàng
        </h3>
        
        <p style="font-size: 14px; color: #4b5563; margin-bottom: 25px; line-height: 1.6;">Vui lòng kiểm tra kỹ các thông tin giao hàng dưới đây để tránh sai sót khi vận chuyển:</p>
        
        <div class="modal-body-layout">
            <!-- Left Column: Ordered Products -->
            <div>
                <div style="font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-box" style="color: #ff0000;"></i> Sản phẩm đặt mua
                </div>
                <div style="max-height: 200px; overflow-y: auto; border: 1px solid #e5e7eb; padding: 12px; display: flex; flex-direction: column; gap: 12px; background: #fafafa;">
                    @foreach($cart as $item)
                        <div style="display: flex; gap: 12px; align-items: center;">
                            @if(!empty($item['image']))
                                <img src="/{{ ltrim($item['image'], '/') }}" style="width: 45px; height: 45px; object-fit: cover; border: 1px solid #e5e7eb; border-radius: 4px;" alt="{{ $item['name'] }}">
                            @else
                                <div style="width: 45px; height: 45px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 18px; border-radius: 4px;">✨</div>
                            @endif
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-size: 13px; font-weight: 700; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item['name'] }}</div>
                                <div style="font-size: 12px; color: #6b7280;">Số lượng: {{ $item['quantity'] }}</div>
                            </div>
                            <div style="font-size: 13px; font-weight: 700; color: #1f2937;">{{ number_format($item['price'] * $item['quantity']) }}đ</div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Right Column: Delivery Details -->
            <div>
                <div style="font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-truck" style="color: #ff0000;"></i> Thông tin nhận hàng
                </div>
                <div style="display: flex; flex-direction: column; gap: 14px; background: #fbfbfb; border: 1px solid #e5e7eb; padding: 15px;">
                    <div style="display: grid; grid-template-columns: 100px 1fr; gap: 10px; font-size: 14px;">
                        <span style="font-weight: 700; color: #4b5563;">Họ và tên:</span>
                        <span id="confirm-name" style="color: #1f2937; word-break: break-word;"></span>
                    </div>
                    <div style="display: grid; grid-template-columns: 100px 1fr; gap: 10px; font-size: 14px;">
                        <span style="font-weight: 700; color: #4b5563;">Số điện thoại:</span>
                        <span id="confirm-phone" style="color: #ff0000; font-weight: 700;"></span>
                    </div>
                    <div style="display: grid; grid-template-columns: 100px 1fr; gap: 10px; font-size: 14px;">
                        <span style="font-weight: 700; color: #4b5563;">Địa chỉ:</span>
                        <span id="confirm-address" style="color: #1f2937; word-break: break-word;"></span>
                    </div>
                    <div style="display: grid; grid-template-columns: 100px 1fr; gap: 10px; font-size: 14px;">
                        <span style="font-weight: 700; color: #4b5563;">Thanh toán:</span>
                        <span id="confirm-payment" style="color: #1f2937; font-weight: 600;"></span>
                    </div>
                    <div style="display: grid; grid-template-columns: 100px 1fr; gap: 10px; font-size: 14px; border-top: 1px dashed #cbd5e1; padding-top: 12px; margin-top: 5px;">
                        <span style="font-weight: 800; color: #1f2937; font-size: 15px;">Tổng cộng:</span>
                        <span id="confirm-total" style="color: #ff0000; font-weight: 800; font-size: 17px;"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 30px;">
            <button id="modal-cancel-btn" type="button" style="padding: 14px; border: 1px solid #d1d5db; background: #ffffff; color: #374151; font-weight: 700; cursor: pointer; transition: 0.2s;">
                Quay lại chỉnh sửa
            </button>
            <button id="modal-submit-btn" type="button" style="padding: 14px; border: none; background: #000000; color: #ffffff; font-weight: 800; text-transform: uppercase; cursor: pointer; transition: 0.2s;">
                Xác nhận & Đặt hàng
            </button>
        </div>
    </div>
</div>

<style>
    .modal-body-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 25px;
    }
    @media (min-width: 768px) {
        .modal-body-layout {
            grid-template-columns: 1fr 1.2fr;
            gap: 30px;
        }
    }
    @keyframes modalFadeIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    #modal-cancel-btn:hover {
        background: #f3f4f6;
    }
    #modal-submit-btn:hover {
        background: #ff0000;
    }
</style>
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

    const form = document.getElementById('checkout-form');
    const modal = document.getElementById('order-confirm-modal');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        document.querySelectorAll('.error-feedback').forEach(el => el.remove());
        document.querySelectorAll('.form-input, .form-textarea').forEach(el => el.style.borderColor = '');
        
        let isValid = true;
        
        // Validate name
        const nameInput = document.getElementById('customer_name');
        if (!nameInput.value.trim()) {
            showError(nameInput, 'Vui lòng nhập họ và tên.');
            isValid = false;
        }
        
        // Validate phone
        const phoneInput = document.getElementById('phone');
        const phoneVal = phoneInput.value.trim();
        const phoneRegex = /^0[0-9]{9}$/;
        if (!phoneVal) {
            showError(phoneInput, 'Vui lòng nhập số điện thoại.');
            isValid = false;
        } else if (!phoneRegex.test(phoneVal)) {
            showError(phoneInput, 'Số điện thoại không hợp lệ. Số điện thoại phải bắt đầu bằng số 0 và có đúng 10 chữ số.');
            isValid = false;
        }
        
        // Validate address
        const addressInput = document.getElementById('address');
        if (!addressInput.value.trim()) {
            showError(addressInput, 'Vui lòng nhập địa chỉ giao hàng.');
            isValid = false;
        }
        
        if (isValid) {
            // Populate modal
            document.getElementById('confirm-name').textContent = nameInput.value;
            document.getElementById('confirm-phone').textContent = phoneInput.value;
            document.getElementById('confirm-address').textContent = addressInput.value;
            
            // Payment method
            const paymentVal = document.querySelector('input[name="payment_method"]:checked').value;
            const paymentText = paymentVal === 'cod' ? 'COD (Thanh toán khi nhận hàng)' : 'VNPay (Thanh toán Online)';
            document.getElementById('confirm-payment').textContent = paymentText;
            
            // Total amount
            const totalText = document.querySelector('.summary-row.total span:last-child').textContent;
            document.getElementById('confirm-total').textContent = totalText;
            
            // Show modal
            modal.style.display = 'flex';
        }
    });
    
    function showError(inputEl, message) {
        inputEl.style.borderColor = '#ef4444';
        const errorEl = document.createElement('div');
        errorEl.className = 'error-feedback';
        errorEl.style.color = '#ef4444';
        errorEl.style.fontSize = '13px';
        errorEl.style.marginTop = '6px';
        errorEl.style.fontWeight = '600';
        errorEl.textContent = message;
        inputEl.parentNode.appendChild(errorEl);
    }
    
    document.getElementById('modal-cancel-btn').addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    document.getElementById('modal-submit-btn').addEventListener('click', function() {
        modal.style.display = 'none';
        form.submit();
    });
</script>
@endsection
