@extends('layouts.admin')

@section('title', 'Chỉnh sửa Slider')

@section('styles')
<style>
    .slider-container {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 30px;
        align-items: start;
    }
    .form-section {
        background: var(--white);
        border-radius: 20px;
        padding: 40px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }
    .preview-section {
        position: sticky;
        top: 40px;
        background: var(--white);
        border-radius: 20px;
        padding: 30px;
        border: 1px solid var(--border);
        text-align: center;
    }
    .hero-preview-box {
        width: 100%;
        height: 250px;
        background: #f1f5f9;
        border-radius: 16px;
        border: 2px dashed #cbd5e1;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin-bottom: 25px;
    }
    .hero-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .preview-label-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(15, 23, 42, 0.7);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        backdrop-filter: blur(4px);
        z-index: 5;
    }
    .form-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 12px;
        font-size: 14px;
    }
    .form-label i {
        color: var(--primary);
        font-size: 16px;
    }
    .form-input {
        width: 100%;
        padding: 14px 18px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        font-family: inherit;
        background: #fcfcfd;
        transition: var(--transition);
        margin-bottom: 25px;
    }
    .form-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(188, 143, 143, 0.1);
        background: white;
        outline: none;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 40px;">
    <div>
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800;">Chỉnh sửa Banner</h1>
        <p class="admin-subtitle">Đang chỉnh sửa Slider: <strong>#{{ $slider->id }}</strong></p>
    </div>
    <a href="{{ route('admin.sliders') }}" class="btn" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
</div>

<form action="{{ route('admin.slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="slider-container">
        <!-- Left Column: Settings -->
        <div class="form-section">
            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-heading"></i> Tiêu đề chính</label>
                <input type="text" name="title" id="titleInput" class="form-input" value="{{ $slider->title }}" placeholder="Ví dụ: Ưu đãi Mùa Hè">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-paragraph"></i> Tiêu đề phụ (Subtitle)</label>
                <input type="text" name="subtitle" id="subtitleInput" class="form-input" value="{{ $slider->subtitle }}" placeholder="Ví dụ: Giảm giá sâu cho các dòng mỹ phẩm cao cấp">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-link"></i> Liên kết (URL)</label>
                    <input type="text" name="link" class="form-input" value="{{ $slider->link }}" placeholder="https://...">
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-sort-numeric-up"></i> Vị trí hiển thị</label>
                    <input type="number" name="position" class="form-input" value="{{ $slider->position }}">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label class="form-label"><i class="fa-solid fa-image-polaroid"></i> Thay đổi hình ảnh</label>
                <div style="position: relative;">
                    <input type="file" name="image" id="sliderImageInput" class="form-input" accept="image/*">
                    <p style="font-size: 12px; color: #64748b; margin-top: -15px;">Để trống nếu giữ nguyên ảnh hiện tại.</p>
                </div>
            </div>

            <div class="form-group" style="background: #f8fafc; padding: 20px; border-radius: 14px;">
                <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                    <input type="checkbox" name="is_active" {{ $slider->is_active ? 'checked' : '' }} style="width: 22px; height: 22px; accent-color: var(--primary);">
                    <span style="font-weight: 700; font-size: 15px; color: #1e293b;">Banner này đang kích hoạt</span>
                </label>
            </div>

            <div style="margin-top: 40px; border-top: 1px solid var(--border); padding-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn" style="padding: 16px 45px; font-size: 16px; flex: 1; border-radius: 15px;">
                    <i class="fa-solid fa-save" style="margin-right: 10px;"></i> Cập nhật ngay
                </button>
            </div>
        </div>

        <!-- Right Column: Live Result -->
        <div class="preview-section">
            <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 25px; color: var(--text-main); display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i class="fa-solid fa-eye"></i> Xem trước kết quả
            </h3>
            
            <div class="hero-preview-box" id="previewContainer">
                <span class="preview-label-badge" id="previewLabel">ẢNH HIỆN TẠI</span>
                <img src="{{ asset($slider->image) }}" id="sliderPreviewImg">
                
                <!-- Mockup Overlay -->
                <div id="mockupOverlay" style="position: absolute; left: 20px; bottom: 20px; text-align: left; max-width: 80%;">
                    <h4 id="mockupTitle" style="color: var(--primary); font-size: 18px; margin-bottom: 5px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">{{ $slider->title }}</h4>
                    <p id="mockupSubtitle" style="color: #64748b; font-size: 12px;">{{ $slider->subtitle }}</p>
                </div>
            </div>

            <div style="background: #f1f5f9; padding: 20px; border-radius: 15px; text-align: left;">
                <h4 style="font-size: 13px; color: #475569; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-info"></i> Thông tin thiết kế
                </h4>
                <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                    Khi bạn thay đổi tiêu đề hoặc ảnh ở cột trái, bản xem trước ở đây sẽ thay đổi theo thời gian thực để bạn dễ dàng căn chỉnh.
                </p>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    const imageInput = document.getElementById('sliderImageInput');
    const previewImg = document.getElementById('sliderPreviewImg');
    const previewLabel = document.getElementById('previewLabel');
    const titleIn = document.getElementById('titleInput');
    const subtitleIn = document.getElementById('subtitleInput');
    const mockupTitle = document.getElementById('mockupTitle');
    const mockupSub = document.getElementById('mockupSubtitle');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target.result;
                previewLabel.innerText = 'ẢNH MỚI CHỌN';
                previewLabel.style.background = 'rgba(188, 143, 143, 0.9)';
            };
            reader.readAsDataURL(file);
        }
    });

    titleIn.addEventListener('input', (e) => {
        mockupTitle.innerText = e.target.value;
    });

    subtitleIn.addEventListener('input', (e) => {
        mockupSub.innerText = e.target.value;
    });
</script>
@endsection
