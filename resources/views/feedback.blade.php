@extends('layouts.app')

@section('title', 'Liên hệ với chúng tôi')

@section('styles')
<style>
    .contact-hero {
        background: linear-gradient(rgba(188, 143, 143, 0.05), rgba(230, 230, 250, 0.05));
        padding: 100px 0 60px;
        text-align: center;
    }
    .contact-card {
        background: white;
        padding: 40px;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.03);
        border: 1px solid var(--border);
        margin-top: -80px;
        position: relative;
        z-index: 10;
    }
    .form-label {
        display: block;
        margin-bottom: 10px;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text);
    }
    .contact-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-top: 60px;
    }
    .info-item {
        padding: 30px;
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        transition: var(--transition);
    }
    .info-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: var(--primary);
    }
    .info-icon {
        width: 50px;
        height: 50px;
        background: var(--secondary);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 20px;
        margin: 0 auto 20px;
    }
</style>
@endsection

@section('content')
    <section class="contact-hero animate-fade">
        <div class="container">
            <h1 style="font-size: 48px; margin-bottom: 20px; color: var(--primary);">Liên hệ với chúng tôi</h1>
            <p style="color: #666; font-size: 18px; max-width: 700px; margin: 0 auto;">
                Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn 24/7. 
                Hãy để lại lời nhắn, Cosmetic Store sẽ phản hồi bạn trong thời gian sớm nhất.
            </p>
        </div>
    </section>

    <div class="container" style="margin-bottom: 100px;">
        <div class="animate-fade contact-card" style="max-width: 900px; margin-left: auto; margin-right: auto;">
            <form action="{{ route('feedback.store') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 25px;">
                    <div class="form-group">
                        <label class="form-label">Họ và tên của bạn</label>
                        <input type="text" name="name" class="form-input" placeholder="Ví dụ: Nguyễn Văn A" style="height: 55px; border-radius: 12px; background: #fafafa; border: 1px solid #eee;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Thông tin liên hệ</label>
                        <input type="text" name="contact" class="form-input" placeholder="Email hoặc Số điện thoại" style="height: 55px; border-radius: 12px; background: #fafafa; border: 1px solid #eee;" required>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <label class="form-label">Chủ đề cần hỗ trợ</label>
                    <input type="text" name="subject" class="form-input" placeholder="Tư vấn sản phẩm, báo lỗi đơn hàng..." style="height: 55px; border-radius: 12px; background: #fafafa; border: 1px solid #eee;">
                </div>

                <div class="form-group" style="margin-bottom: 35px;">
                    <label class="form-label">Lời nhắn của bạn</label>
                    <textarea name="message" class="form-input" rows="6" placeholder="Chia sẻ suy nghĩ của bạn với chúng tôi..." style="border-radius: 12px; background: #fafafa; border: 1px solid #eee; padding-top: 15px;" required></textarea>
                </div>

                <button type="submit" class="btn" style="width: 100%; padding: 20px; font-size: 16px; font-weight: 700; border-radius: 12px; text-transform: uppercase; letter-spacing: 2px; box-shadow: 0 10px 20px rgba(188, 143, 143, 0.2);">
                    <i class="fa-solid fa-paper-plane" style="margin-right: 10px;"></i> Gửi lời nhắn ngay
                </button>
            </form>
        </div>

        <div class="contact-info-grid animate-fade">
            <div class="info-item text-center">
                <div class="info-icon"><i class="fa-solid fa-phone-volume"></i></div>
                <h4 style="font-size: 18px; margin-bottom: 10px; font-family: 'Playfair Display', serif;">Tổng đài hỗ trợ</h4>
                <p style="color: var(--primary); font-weight: 700; font-size: 16px;">1900 1234</p>
                <p style="color: #888; font-size: 13px; margin-top: 5px;">Hỗ trợ khách hàng 24/7</p>
            </div>
            <div class="info-item text-center">
                <div class="info-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
                <h4 style="font-size: 18px; margin-bottom: 10px; font-family: 'Playfair Display', serif;">Gửi thư điện tử</h4>
                <p style="color: var(--primary); font-weight: 700; font-size: 16px;">hello@cosmetic.vn</p>
                <p style="color: #888; font-size: 13px; margin-top: 5px;">Phản hồi trong 2 giờ</p>
            </div>
            <div class="info-item text-center">
                <div class="info-icon"><i class="fa-solid fa-map-location-dot"></i></div>
                <h4 style="font-size: 18px; margin-bottom: 10px; font-family: 'Playfair Display', serif;">Văn phòng đại diện</h4>
                <p style="color: var(--primary); font-weight: 700; font-size: 16px;">Eaut</p>
                <p style="color: #888; font-size: 13px; margin-top: 5px;">TP. Hà Nội, Việt Nam</p>
            </div>
        </div>
    </div>
@endsection
