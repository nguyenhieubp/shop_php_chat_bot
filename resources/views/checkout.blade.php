@extends('layouts.app')

@section('title', 'Thanh toán đơn hàng')

@section('content')
    <div class="container" style="padding: 80px 0; max-width: 1000px;">
        <h1 style="font-size: 36px; margin-bottom: 40px; text-align: center;">Thông tin thanh toán</h1>

        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 40px;" class="animate-fade">
            <!-- Form -->
            <div class="card" style="padding: 40px; border: 1px solid var(--border); border-radius: 16px;">
                <h3 style="margin-bottom: 25px; font-weight: 700;">1. Thông tin giao hàng</h3>
                <form action="{{ route('order.store') }}" method="POST">
                    @csrf
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Họ và tên khách hàng</label>
                        <input type="text" name="customer_name" class="form-input" placeholder="Nguyễn Văn A" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Số điện thoại nhận hàng</label>
                        <input type="text" name="phone" class="form-input" placeholder="090 123 4567" required>
                    </div>
                    <!-- Assuming multi-product storeOrder needs to be updated but I will iterate on that later -->
                    <input type="hidden" name="is_cart_order" value="1">
                    @foreach($cart as $id => $item)
                        <input type="hidden" name="cart_items[]" value="{{ $id }}">
                    @endforeach

                    <div style="margin-top: 30px; padding-top: 25px; border-top: 1px solid var(--border);">
                        <p style="font-size: 13px; color: #777; margin-bottom: 15px;">Bằng cách đặt hàng, bạn xác nhận đồng ý với Điều khoản và Chính sách của Cosmetic Store.</p>
                        <button type="submit" class="btn" style="width: 100%; padding: 18px; font-size: 16px;">HOÀN TẤT ĐẶT HÀNG</button>
                    </div>
                </form>
            </div>

            <!-- Summary -->
            <div>
                <div style="background: var(--card-bg); padding: 30px; border-radius: 12px; border: 1px solid var(--border);">
                    <h3 style="margin-bottom: 20px; font-size: 18px;">Đơn hàng của bạn</h3>
                    <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px;">
                        @foreach($cart as $item)
                            <div style="display: flex; gap: 15px; margin-bottom: 15px; align-items: center;">
                                <img src="{{ asset($item['image'] ?? 'placeholder.png') }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                <div style="flex: 1;">
                                    <div style="font-size: 14px; font-weight: 600;">{{ $item['name'] }}</div>
                                    <div style="font-size: 12px; color: #777;">x{{ $item['quantity'] }}</div>
                                </div>
                                <div style="font-weight: 600; font-size: 14px;">{{ number_format($item['price'] * $item['quantity']) }}đ</div>
                            </div>
                        @endforeach
                    </div>
                    <div style="border-top: 1px solid var(--border); padding-top: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Tạm tính</span>
                            <span>{{ number_format($total) }}đ</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: 700; color: var(--primary); font-size: 18px;">
                            <span>Tổng thanh toán</span>
                            <span>{{ number_format($total) }}đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
