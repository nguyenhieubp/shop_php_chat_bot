@extends('layouts.app')

@section('title', 'Giỏ hàng của bạn')

@section('styles')
<style>
    .cart-container { padding: 80px 0; min-height: 70vh; }
    .cart-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
    .cart-table th { text-align: left; padding: 15px; border-bottom: 2px solid var(--border); font-family: 'Playfair Display', serif; }
    .cart-table td { padding: 15px; border-bottom: 1px solid var(--border); vertical-align: middle; }
    .cart-img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; background: #eee; }
    
    /* Quantity Picker Styling */
    .qty-picker { 
        display: flex; 
        align-items: center; 
        border: 1px solid var(--border); 
        border-radius: 10px; 
        width: fit-content; 
        background: #fff;
        overflow: hidden;
    }
    .qty-btn { 
        background: #f8fafc; 
        border: none; 
        width: 36px; 
        height: 36px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        cursor: pointer; 
        transition: var(--transition);
        font-weight: 700;
        color: #64748b;
    }
    .qty-btn:hover { background: var(--secondary); color: var(--primary); }
    .qty-input-custom { 
        width: 45px; 
        text-align: center; 
        border: none; 
        font-weight: 700; 
        font-size: 14px; 
        outline: none;
        background: transparent;
    }

    .remove-btn { color: #ef4444; background: none; border: none; cursor: pointer; font-size: 14px; font-weight: 600; transition: var(--transition); }
    .remove-btn:hover { color: #b91c1c; }
    
    .summary-card { background: var(--card-bg); padding: 30px; border-radius: 12px; border: 1px solid var(--border); position: sticky; top: 120px; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; }
    .summary-total { font-size: 20px; font-weight: 700; border-top: 1px solid var(--border); padding-top: 15px; margin-top: 15px; color: var(--primary); }
</style>
@endsection

@section('content')
    <div class="container cart-container">
        <h1 style="font-size: 36px; margin-bottom: 40px; text-align: center;" class="animate-fade">Giỏ hàng của bạn</h1>

        @if(session('cart') && count(session('cart')) > 0)
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;" class="animate-fade">
                <!-- Cart Items -->
                <div>
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('cart') as $id => $details)
                                <tr>
                                    <td style="display: flex; align-items: center; gap: 15px;">
                                        <img src="{{ asset($details['image'] ?? 'placeholder.png') }}" class="cart-img" onerror="this.src='https://via.placeholder.com/80?text=Beauty'">
                                        <div>
                                            <div style="font-weight: 600; font-size: 15px;">{{ $details['name'] }}</div>
                                        </div>
                                    </td>
                                    <td style="font-size: 14px; color: #64748b;">{{ number_format($details['price']) }}đ</td>
                                    <td>
                                        <div class="qty-picker">
                                            <button type="button" class="qty-btn" onclick="changeQty({{ $id }}, -1)">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input type="text" id="qty-input-{{ $id }}" value="{{ $details['quantity'] }}" class="qty-input-custom" readonly>
                                            <button type="button" class="qty-btn" onclick="changeQty({{ $id }}, 1)">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td id="subtotal-{{ $id }}" style="font-weight: 700; color: var(--text-main);">{{ number_format($details['price'] * $details['quantity']) }}đ</td>
                                    <td style="text-align: right;">
                                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="remove-btn" title="Xóa khỏi giỏ hàng">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('home') }}" style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-arrow-left"></i> Tiếp tục mua sắm
                    </a>
                </div>

                <!-- Summary -->
                <div>
                    <div class="summary-card">
                        <h3 style="margin-bottom: 25px; font-weight: 800; letter-spacing: -0.02em;">Tóm tắt đơn hàng</h3>
                        <div class="summary-row">
                            <span style="color: #64748b;">Tạm tính</span>
                            <span id="summary-subtotal" style="font-weight: 600;">{{ number_format($total) }}đ</span>
                        </div>
                        <div class="summary-row">
                            <span style="color: #64748b;">Phí vận chuyển</span>
                            <span style="color: #22c55e; font-weight: 700;">Miễn phí</span>
                        </div>
                        <div class="summary-total">
                            <span style="color: var(--text-main);">Tổng cộng</span>
                            <span id="summary-total">{{ number_format($total) }}đ</span>
                        </div>
                        
                        <div style="margin-top: 35px;">
                            <a href="{{ route('order.checkout') }}" class="btn" style="width: 100%; text-align: center; padding: 18px; font-size: 16px; font-weight: 800; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(188, 143, 143, 0.3);">
                                TIẾN HÀNH THANH TOÁN
                            </a>
                        </div>
                        
                        <div style="margin-top: 25px; text-align: center; color: #94a3b8; font-size: 12px; font-weight: 500;">
                             <i class="fa-solid fa-shield-halved" style="margin-right: 5px;"></i> Cam kết chính hãng & Miễn phí đổi trả 30 ngày
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 100px 20px;" class="animate-fade">
                <div style="width: 100px; height: 100px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                    <i class="fa-solid fa-cart-shopping" style="font-size: 40px; color: #cbd5e1;"></i>
                </div>
                <h2 style="font-weight: 800; margin-bottom: 12px; letter-spacing: -0.02em;">Giỏ hàng của bạn đang trống</h2>
                <p style="color: #64748b; margin-bottom: 35px; max-width: 450px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                    Có vẻ như bạn chưa chọn được sản phẩm làm đẹp ưng ý. Khám phá ngay các bộ sưu tập mới nhất của chúng tôi nhé!
                </p>
                <a href="{{ route('home') }}" class="btn" style="padding: 15px 40px; font-weight: 700; border-radius: 12px;">TIẾP TỤC MUA SẮM</a>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
    function changeQty(id, delta) {
        const input = document.getElementById('qty-input-' + id);
        let newQty = parseInt(input.value) + delta;
        if (newQty >= 1) {
            updateCart(id, newQty);
        }
    }

    function updateCart(id, quantity) {
        const input = document.getElementById('qty-input-' + id);
        const picker = input.closest('.qty-picker');
        picker.style.opacity = '0.5';
        picker.style.pointerEvents = 'none';
        
        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id, quantity: parseInt(quantity) })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                // Update input value in DOM
                input.value = quantity;

                // Update item subtotal
                document.getElementById(`subtotal-${id}`).innerText = data.item_subtotal;
                
                // Update summary totals
                document.getElementById('summary-subtotal').innerText = data.total;
                document.getElementById('summary-total').innerText = data.total;
                
                // Update cart badge in header
                const badge = document.querySelector('.cart-badge');
                if(badge) {
                    badge.innerText = data.cart_count;
                }
                
                // Reset picker state
                picker.style.opacity = '1';
                picker.style.pointerEvents = 'auto';
            }
        })
        .catch(error => {
            console.error('Error updating cart:', error);
            window.location.reload();
        });
    }
</script>
@endsection
