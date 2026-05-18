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
                
                <form action="{{ route('order.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div style="margin-bottom: 40px;">
                        <h3 style="font-size: 18px; margin-bottom: 25px; border-left: 4px solid var(--primary); padding-left: 15px;">1. Thông tin giao hàng</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label style="display: block; margin-bottom: 10px; font-weight: 700; font-size: 13px; color: var(--text-main);">Họ và tên <span style="color: var(--primary);">*</span></label>
                                <input type="text" name="customer_name" class="checkout-input" placeholder="Ví dụ: Nguyễn Văn A" required>
                            </div>
                            <div class="form-group">
                                <label style="display: block; margin-bottom: 10px; font-weight: 700; font-size: 13px; color: var(--text-main);">Số điện thoại <span style="color: var(--primary);">*</span></label>
                                <input type="text" name="phone" class="checkout-input" placeholder="090 xxx xxxx" required>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 10px; font-weight: 700; font-size: 13px; color: var(--text-main);">Địa chỉ giao hàng chi tiết <span style="color: var(--primary);">*</span></label>
                            <textarea name="address" class="checkout-input" style="height: 100px; resize: vertical;" placeholder="Số nhà, tên đường, Phường/Xã, Quận/Huyện, Tỉnh/Thành phố..." required></textarea>
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
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" class="product-thumb">
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
</div>
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
@endsection
