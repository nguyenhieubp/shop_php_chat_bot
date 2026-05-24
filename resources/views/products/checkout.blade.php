@extends('layouts.app')

@section('title', 'Thanh toán - ' . $product->name)

@section('styles')
<style>
    .checkout-page { padding: 100px 0; background: #fdfdfd; min-height: 80vh; }
    .checkout-card { background: white; border-radius: 20px; border: 1px solid var(--border); box-shadow: 0 15px 35px rgba(0,0,0,0.03); overflow: hidden; }
    .order-summary-box { background: var(--secondary); padding: 40px; border-radius: 20px; position: sticky; top: 120px; }
    .checkout-input { width: 100%; padding: 15px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff; font-size: 15px; transition: var(--transition); border: 1px solid var(--border); margin-bottom: 20px; }
    .checkout-input:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 4px rgba(188, 143, 143, 0.1); }
    .product-thumb { width: 80px; height: 100px; object-fit: cover; border-radius: 10px; box-shadow: var(--shadow-sm); }

    /* Payment Method Styles */
    .payment-methods { display: grid; gap: 15px; margin-top: 15px; }
    .payment-method-item { border: 1px solid #e2e8f0; padding: 15px; border-radius: 12px; display: flex; align-items: center; gap: 15px; cursor: pointer; transition: var(--transition); position: relative; }
    .payment-method-item:hover { border-color: var(--primary); background: rgba(188, 143, 143, 0.02); }
    .payment-method-item input[type="radio"] { width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer; }
    .payment-method-item.active { border-color: var(--primary); background: #fff9f9; border-width: 2px; }
    .payment-method-icon { font-size: 20px; width: 35px; text-align: center; color: var(--primary); }
    .payment-method-text { flex: 1; }
    .payment-method-title { display: block; font-weight: 700; font-size: 14px; color: var(--text-main); margin-bottom: 2px; }
    .payment-method-desc { display: block; font-size: 12px; color: #888; }
</style>
@endsection

@section('content')
<div class="checkout-page">
    <div class="container">
        <div style="margin-bottom: 40px;" class="animate-fade">
            <a href="{{ route('product.show', $product->slug) }}" style="color: var(--text-secondary); text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-arrow-left-long"></i> Quay lại sản phẩm
            </a>
        </div>

        <div style="display: grid; grid-template-columns: minmax(0, 1fr) 400px; gap: 50px;" class="animate-fade">
            <!-- Checkout Form -->
            <div>
                <h1 style="font-size: 32px; margin-bottom: 40px; font-weight: 800; letter-spacing: -0.02em;">Xác nhận đặt hàng</h1>
                
                <form action="{{ route('order.store') }}" method="POST" id="checkout-form" novalidate>
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div style="margin-bottom: 40px;">
                        <h3 style="font-size: 18px; margin-bottom: 25px; border-left: 4px solid var(--primary); padding-left: 15px;">1. Thông tin giao hàng</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label style="display: block; margin-bottom: 10px; font-weight: 700; font-size: 13px; color: var(--text-main);">Họ và tên <span style="color: var(--primary);">*</span></label>
                                <input type="text" name="customer_name" id="customer_name" class="checkout-input" placeholder="Ví dụ: Nguyễn Văn A" required>
                            </div>
                            <div class="form-group">
                                <label style="display: block; margin-bottom: 10px; font-weight: 700; font-size: 13px; color: var(--text-main);">Số điện thoại <span style="color: var(--primary);">*</span></label>
                                <input type="text" name="phone" id="phone" class="checkout-input" placeholder="VD: 090xxxxxxx" required>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 10px; font-weight: 700; font-size: 13px; color: var(--text-main);">Địa chỉ giao hàng chi tiết <span style="color: var(--primary);">*</span></label>
                            <textarea name="address" id="address" class="checkout-input" style="height: 100px; resize: vertical;" placeholder="Số nhà, tên đường, Phường/Xã, Quận/Huyện, Tỉnh/Thành phố..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 10px; font-weight: 700; font-size: 13px; color: var(--text-main);">Ghi chú đơn hàng (Tùy chọn)</label>
                            <textarea name="notes" class="checkout-input" style="height: 80px; resize: vertical;" placeholder="Ghi chú về thời gian giao hàng, chỉ dẫn địa chỉ..."></textarea>
                        </div>
                    </div>

                    <div style="margin-bottom: 40px;">
                        <h3 style="font-size: 18px; margin-bottom: 25px; border-left: 4px solid var(--primary); padding-left: 15px;">3. Phương thức thanh toán</h3>
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
                                    <img src="https://sandbox.vnpayment.vn/paymentv2/Images/brands/logo-vnpay.png" alt="VNPay" style="height: 18px;">
                                </div>
                                <div class="payment-method-text">
                                    <span class="payment-method-title">Thanh toán Online (VNPay)</span>
                                    <span class="payment-method-desc">Ứng dụng ngân hàng, thẻ ATM, Visa, Master...</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <input type="hidden" name="total_amount" value="{{ $product->price }}">

                    <div style="padding: 30px; background: #fff9f9; border-radius: 16px; border: 1px dashed var(--primary); margin-bottom: 40px;">
                        <div style="display: flex; gap: 15px; align-items: flex-start;">
                            <i class="fa-solid fa-truck-fast" style="color: var(--primary); font-size: 20px; margin-top: 3px;"></i>
                            <div>
                                <h4 style="font-size: 15px; font-weight: 700; margin-bottom: 5px;">Giao hàng nhanh miễn phí</h4>
                                <p style="font-size: 14px; color: #666; margin: 0;">Bạn sẽ nhận được hàng trong vòng 2-3 ngày làm việc kể từ lúc xác nhận đơn hàng.</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn" style="width: 100%; padding: 22px; font-size: 16px; font-weight: 800; border-radius: 14px; letter-spacing: 1px; box-shadow: 0 10px 25px rgba(188, 143, 143, 0.3);">
                        <i class="fa-solid fa-lock" style="margin-right: 10px;"></i> HOÀN TẤT ĐẶT HÀNG NGAY
                    </button>
                    <p style="text-align: center; margin-top: 20px; color: #888; font-size: 13px;">
                        <i class="fa-solid fa-shield-check"></i> Thanh toán khi nhận hàng (COD)
                    </p>
                </form>
            </div>

            <!-- Sidebar Summary -->
            <div class="animate-fade" style="animation-delay: 0.2s;">
                <div class="order-summary-box">
                    <h3 style="font-size: 18px; margin-bottom: 30px; font-weight: 800;">Tóm tắt đơn hàng</h3>
                    
                    <div style="display: flex; gap: 20px; margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid rgba(188, 143, 143, 0.2);">
                        @if(!empty($product->image))
                            <img src="/{{ ltrim($product->image, '/') }}" class="product-thumb">
                        @else
                            <div style="width: 80px; height: 100px; background: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 40px;">✨</div>
                        @endif
                        <div style="flex: 1;">
                            <h4 style="font-size: 15px; margin-bottom: 8px; font-weight: 700;">{{ $product->name }}</h4>
                            <p style="font-size: 13px; color: #888; margin-bottom: 12px;">Dòng mỹ phẩm cao cấp</p>
                            <p style="font-weight: 800; color: var(--primary); font-size: 16px;">{{ number_format($product->price) }}₫</p>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span style="color: #666;">Số lượng:</span>
                            <span style="font-weight: 700;">01</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span style="color: #666;">Vận chuyển:</span>
                            <span style="font-weight: 700; color: #22c55e;">Miễn phí</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: 800; margin-top: 15px; padding-top: 15px; border-top: 2px solid rgba(188, 143, 143, 0.3);">
                            <span>Tổng tiền:</span>
                            <span style="color: var(--primary);">{{ number_format($product->price) }}₫</span>
                        </div>
                    </div>

                    <div style="margin-top: 40px; text-align: center;">
                        <p style="font-size: 12px; color: #999; line-height: 1.6;">
                            Hàng chính hãng 100%<br>
                            Đóng gói cẩn thận & Bảo mật thông tin<br>
                            Hotline tư vấn: 1900 1234
                        </p>
                    </div>
                </div>
            </div>
        </div>
</div>

<!-- Order Confirmation Modal -->
<div id="order-confirm-modal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; border: 2px solid #000000; width: 100%; max-width: 800px; padding: 35px; position: relative; animation: modalFadeIn 0.3s ease-out; box-shadow: 10px 10px 0px rgba(0,0,0,0.15); display: flex; flex-direction: column;">
        <h3 style="font-size: 22px; font-weight: 800; text-transform: uppercase; margin-bottom: 25px; border-bottom: 2px solid #000000; padding-bottom: 12px; display: flex; align-items: center; gap: 10px; color: #1f2937;">
            <i class="fa-solid fa-file-invoice" style="color: var(--primary, #ff0000);"></i> Xác nhận thông tin đơn hàng
        </h3>
        
        <p style="font-size: 14px; color: #4b5563; margin-bottom: 25px; line-height: 1.6;">Vui lòng kiểm tra kỹ các thông tin giao hàng dưới đây để tránh sai sót khi vận chuyển:</p>
        
        <div class="modal-body-layout">
            <!-- Left Column: Ordered Products -->
            <div>
                <div style="font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-box" style="color: var(--primary, #ff0000);"></i> Sản phẩm đặt mua
                </div>
                <div style="border: 1px solid #e5e7eb; padding: 12px; display: flex; gap: 12px; align-items: center; background: #fafafa;">
                    @if(!empty($product->image))
                        <img src="/{{ ltrim($product->image, '/') }}" style="width: 45px; height: 55px; object-fit: cover; border: 1px solid #e5e7eb; border-radius: 4px;" alt="{{ $product->name }}">
                    @else
                        <div style="width: 45px; height: 55px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 18px; border-radius: 4px;">✨</div>
                    @endif
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 13px; font-weight: 700; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->name }}</div>
                        <div style="font-size: 12px; color: #6b7280;">Số lượng: 01</div>
                    </div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--primary, #bc8f8f);">{{ number_format($product->price) }}₫</div>
                </div>
            </div>
            
            <!-- Right Column: Delivery Details -->
            <div>
                <div style="font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-truck" style="color: var(--primary, #ff0000);"></i> Thông tin nhận hàng
                </div>
                <div style="display: flex; flex-direction: column; gap: 14px; background: #fbfbfb; border: 1px solid #e5e7eb; padding: 15px;">
                    <div style="display: grid; grid-template-columns: 100px 1fr; gap: 10px; font-size: 14px;">
                        <span style="font-weight: 700; color: #4b5563;">Họ và tên:</span>
                        <span id="confirm-name" style="color: #1f2937; word-break: break-word;"></span>
                    </div>
                    <div style="display: grid; grid-template-columns: 100px 1fr; gap: 10px; font-size: 14px;">
                        <span style="font-weight: 700; color: #4b5563;">Số điện thoại:</span>
                        <span id="confirm-phone" style="color: var(--primary, #ff0000); font-weight: 700;"></span>
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
                        <span id="confirm-total" style="color: var(--primary, #ff0000); font-weight: 800; font-size: 17px;"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 30px;">
            <button id="modal-cancel-btn" type="button" style="padding: 14px; border: 1px solid #d1d5db; background: #ffffff; color: #374151; font-weight: 700; cursor: pointer; transition: 0.2s; border-radius: 8px;">
                Quay lại chỉnh sửa
            </button>
            <button id="modal-submit-btn" type="button" style="padding: 14px; border: none; background: #000000; color: #ffffff; font-weight: 800; text-transform: uppercase; cursor: pointer; transition: 0.2s; border-radius: 8px;">
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
        background: var(--primary, #ff0000);
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
        document.querySelectorAll('.checkout-input').forEach(el => el.style.borderColor = '');
        
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
            const totalText = document.querySelector('.order-summary-box div[style*="border-top"] span:last-child').textContent;
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
        errorEl.style.marginTop = '-12px';
        errorEl.style.marginBottom = '12px';
        errorEl.style.fontWeight = '600';
        errorEl.style.display = 'block';
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
@endsection
