@extends('layouts.app')

@section('title', $product->name)

@section('styles')
<style>
    .product-detail-container {
        padding: 60px 0;
    }

    .product-detail-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: start;
    }

    .product-detail-image {
        width: 100%;
        height: 500px;
        background: #f8f8f8;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 100px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .product-info h1 {
        font-size: 42px;
        margin-bottom: 20px;
        color: var(--text);
    }

    .product-category {
        color: var(--primary);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 1px;
        margin-bottom: 10px;
        display: block;
    }

    .product-detail-price {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 30px;
    }

    .product-description {
        font-size: 16px;
        line-height: 1.8;
        color: #666;
        margin-bottom: 40px;
    }

    .action-buttons {
        display: flex;
        gap: 20px;
    }

    .btn-buy {
        flex: 1;
        padding: 18px;
        font-size: 16px;
        text-align: center;
    }

    .stock-info {
        margin-top: 20px;
        font-size: 14px;
        color: #999;
    }

    @media (max-width: 768px) {
        .product-detail-wrapper {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
    <div class="container product-detail-container">
        <div class="product-detail-wrapper animate-fade">
            <div class="product-detail-image" style="padding: 0; overflow: hidden;">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    ✨
                @endif
            </div>
            
            <div class="product-info">
                <span class="product-category">{{ $product->category->name }}</span>
                <h1>{{ $product->name }}</h1>
                <div class="product-detail-price">{{ number_format($product->price) }} VNĐ</div>
                
                <div class="product-description">
                    {!! nl2br(e($product->description)) !!}
                    @if(empty($product->description))
                        <p>Thông tin sản phẩm đang được cập nhật. Đây là dòng mỹ phẩm cao cấp giúp tôn vinh vẻ đẹp tự nhiên của bạn.</p>
                    @endif
                </div>

                <div class="action-buttons">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" class="btn btn-buy" style="background: #333; width: 100%;">Thêm vào giỏ hàng</button>
                    </form>
                    <a href="{{ route('product.checkout', $product->slug) }}" class="btn btn-buy" style="flex: 1; border: 1px solid var(--primary); background: none; color: var(--primary);">Mua ngay</a>
                </div>

                <div class="stock-info">
                    Tình trạng: {{ $product->stock > 0 ? 'Còn hàng (' . $product->stock . ')' : 'Liên hệ' }}
                </div>
                
                <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid var(--border);">
                    <p style="font-size: 14px; color: #888;">
                        🚚 Miễn phí vận chuyển cho đơn hàng từ 500k<br>
                        🛡️ Cam kết chính hãng 100%<br>
                        🔄 Đổi trả trong 7 ngày
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
