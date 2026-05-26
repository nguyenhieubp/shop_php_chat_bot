@extends('layouts.app')

@section('title', 'Liên hệ với chúng tôi')

@section('styles')
<style>
    .contact-hero {
        background-color: var(--surface);
        background-image: 
            radial-gradient(var(--border) 1.5px, transparent 1.5px), 
            radial-gradient(var(--border) 1.5px, transparent 1.5px);
        background-size: 24px 24px;
        background-position: 0 0, 12px 12px;
        padding: 120px 0 100px;
        text-align: center;
        border-bottom: 2px solid var(--primary);
        position: relative;
    }
    
    .contact-tag {
        display: inline-block;
        padding: 8px 16px;
        background: var(--accent, #ff0000);
        color: white;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
        margin-bottom: 24px;
        text-transform: uppercase;
    }

    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: 56px;
        font-weight: 800;
        margin-bottom: 24px;
        color: var(--text);
        text-transform: uppercase;
        letter-spacing: -0.03em;
    }

    .hero-subtitle {
        color: var(--text-muted);
        font-size: 18px;
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.8;
        font-weight: 500;
    }

    .contact-card {
        background: var(--surface);
        padding: 50px;
        border-radius: var(--radius-xl, 0px);
        border: 2px solid var(--primary);
        box-shadow: 12px 12px 0px var(--primary);
        margin-top: -60px;
        position: relative;
        z-index: 10;
        transition: var(--transition);
    }

    .form-group {
        position: relative;
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        margin-bottom: 10px;
        font-weight: 800;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text);
    }

    .form-input-custom {
        width: 100%;
        height: 55px;
        padding: 15px 20px;
        border-radius: var(--radius-md, 0px);
        background: var(--secondary);
        border: 2px solid var(--border);
        color: var(--text);
        font-family: inherit;
        font-size: 15px;
        font-weight: 600;
        transition: var(--transition);
        outline: none;
    }

    .form-input-custom::placeholder {
        color: var(--text-muted);
        opacity: 0.5;
    }

    .form-input-custom:focus {
        border-color: var(--primary);
        background: var(--surface);
        box-shadow: 4px 4px 0px var(--primary);
    }

    textarea.form-input-custom {
        height: auto;
        padding-top: 15px;
        resize: vertical;
    }

    .btn-submit-custom {
        width: 100%;
        padding: 20px;
        font-size: 16px;
        font-weight: 800;
        border-radius: var(--radius-md, 0px);
        text-transform: uppercase;
        letter-spacing: 2px;
        background: var(--primary);
        color: var(--surface);
        border: 2px solid var(--primary);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        transition: var(--transition);
    }

    .btn-submit-custom:hover {
        background: var(--accent, #ff0000);
        border-color: var(--accent, #ff0000);
        color: white;
        transform: translate(-4px, -4px);
        box-shadow: 6px 6px 0px var(--primary);
    }

    .contact-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-top: 80px;
    }

    .info-item {
        padding: 40px 30px;
        background: var(--surface);
        border-radius: var(--radius-lg, 0px);
        border: 2px solid var(--border);
        transition: var(--transition);
        text-align: center;
    }

    .info-item:hover {
        transform: translate(-6px, -6px);
        box-shadow: 8px 8px 0px var(--primary);
        border-color: var(--primary);
    }

    .info-icon {
        width: 60px;
        height: 60px;
        background: var(--secondary);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-md, 0px);
        font-size: 24px;
        margin: 0 auto 24px;
        border: 2px solid transparent;
        transition: var(--transition);
    }

    .info-item:hover .info-icon {
        background: var(--primary);
        color: var(--surface);
        border-color: var(--primary);
    }

    @media (max-width: 768px) {
        .contact-info-grid {
            grid-template-columns: 1fr;
        }
        .contact-card {
            padding: 30px 20px;
        }
        .hero-title {
            font-size: 40px;
        }
    }
</style>
@endsection

@section('content')
    <section class="contact-hero animate-fade">
        <div class="container">
            <span class="contact-tag">Get In Touch</span>
            <h1 class="hero-title">Liên hệ với chúng tôi</h1>
            <p class="hero-subtitle">
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
                        <input type="text" name="name" class="form-input-custom" placeholder="Ví dụ: Nguyễn Văn A">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Thông tin liên hệ</label>
                        <input type="text" name="contact" class="form-input-custom" placeholder="Email hoặc Số điện thoại" required>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <label class="form-label">Chủ đề cần hỗ trợ</label>
                    <input type="text" name="subject" class="form-input-custom" placeholder="Tư vấn sản phẩm, báo lỗi đơn hàng...">
                </div>

                <div class="form-group" style="margin-bottom: 35px;">
                    <label class="form-label">Lời nhắn của bạn</label>
                    <textarea name="message" class="form-input-custom" rows="6" placeholder="Chia sẻ suy nghĩ của bạn với chúng tôi..." required></textarea>
                </div>

                <button type="submit" class="btn-submit-custom">
                    <i class="fa-solid fa-paper-plane"></i> Gửi lời nhắn ngay
                </button>
            </form>
        </div>

        <div class="contact-info-grid animate-fade">
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-phone-volume"></i></div>
                <h4 style="font-size: 18px; margin-bottom: 10px; font-family: 'Playfair Display', serif;">Tổng đài hỗ trợ</h4>
                <p style="color: var(--primary); font-weight: 800; font-size: 16px;">1900 1234</p>
                <p style="color: var(--text-muted); font-size: 13px; margin-top: 5px; opacity: 0.8;">Hỗ trợ khách hàng 24/7</p>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
                <h4 style="font-size: 18px; margin-bottom: 10px; font-family: 'Playfair Display', serif;">Gửi thư điện tử</h4>
                <p style="color: var(--primary); font-weight: 800; font-size: 16px;">hello@cosmetic.vn</p>
                <p style="color: var(--text-muted); font-size: 13px; margin-top: 5px; opacity: 0.8;">Phản hồi trong 2 giờ</p>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-map-location-dot"></i></div>
                <h4 style="font-size: 18px; margin-bottom: 10px; font-family: 'Playfair Display', serif;">Văn phòng đại diện</h4>
                <p style="color: var(--primary); font-weight: 800; font-size: 16px;">Eaut</p>
                <p style="color: var(--text-muted); font-size: 13px; margin-top: 5px; opacity: 0.8;">TP. Hà Nội, Việt Nam</p>
            </div>
        </div>
    </div>
@endsection
