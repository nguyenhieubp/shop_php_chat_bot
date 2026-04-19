@extends('layouts.admin')

@section('title', 'Thêm Sản phẩm mới')

@section('styles')
<style>
    .form-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        align-items: start;
    }
    .form-section {
        background: var(--white);
        border-radius: 20px;
        padding: 35px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }
    .image-preview-card {
        background: var(--white);
        border-radius: 20px;
        padding: 25px;
        border: 1px solid var(--border);
        text-align: center;
        position: sticky;
        top: 40px;
    }
    .preview-box {
        width: 100%;
        aspect-ratio: 1;
        background: #f8fafc;
        border-radius: 16px;
        border: 2px dashed var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        overflow: hidden;
        position: relative;
    }
    .preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .preview-placeholder {
        color: #94a3b8;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }
    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        font-weight: 700;
        color: #475569;
        font-size: 14px;
    }
    .form-label i {
        color: var(--primary);
        font-size: 16px;
    }
    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-family: inherit;
        font-size: 14px;
        transition: var(--transition);
        background: #fcfcfd;
    }
    .form-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(188, 143, 143, 0.1);
        background: white;
        outline: none;
    }
    .checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 15px;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid var(--border);
        cursor: pointer;
        transition: var(--transition);
    }
    .checkbox-item:hover {
        border-color: var(--primary);
    }
    .checkbox-item input {
        width: 20px;
        height: 20px;
        accent-color: var(--primary);
        cursor: pointer;
    }
    .checkbox-item span {
        font-weight: 700;
        font-size: 13px;
        color: #475569;
    }
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 40px;">
    <div>
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800;">Thêm Sản Phẩm Mới</h1>
        <p class="admin-subtitle">Bổ sung dòng mỹ phẩm tuyệt vời vào bộ sưu tập của bạn.</p>
    </div>
    <a href="{{ route('admin.products') }}" class="btn" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
</div>

<form action="{{ route('admin.product.create') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-container">
        <!-- Left: Basic Info -->
        <div class="form-section">
            <div class="form-group" style="margin-bottom: 25px;">
                <label class="form-label"><i class="fa-solid fa-tag"></i> Tên sản phẩm</label>
                <input type="text" name="name" class="form-input" placeholder="Ví dụ: Kem dưỡng Ohui Prime Advancer" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-layer-group"></i> Danh mục</label>
                    <select name="category_id" class="form-input" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-coins"></i> Giá bán (đ)</label>
                    <input type="number" name="price" class="form-input" placeholder="0" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label class="form-label"><i class="fa-solid fa-align-left"></i> Mô tả chi tiết</label>
                <textarea name="description" class="form-input" rows="12" style="resize: none;" placeholder="Mô tả về công dụng, thành phần, cách sử dụng..."></textarea>
            </div>

            <div style="margin-top: 40px; border-top: 1px solid var(--border); padding-top: 30px;">
                <button type="submit" class="btn" style="padding: 16px 50px; font-size: 16px; border-radius: 15px;">
                    <i class="fa-solid fa-plus-circle" style="margin-right: 10px;"></i> Lưu & Đăng Sản Phẩm
                </button>
            </div>
        </div>

        <!-- Right: Multimedia & Settings -->
        <div class="image-preview-card">
            <div class="form-group" style="margin-bottom: 30px;">
                <label class="form-label"><i class="fa-solid fa-image"></i> Hình ảnh sản phẩm</label>
                <div class="preview-box" id="imagePreviewContainer">
                    <div class="preview-placeholder" id="uploadPlaceholder">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 48px; opacity: 0.2; margin-bottom: 10px;"></i>
                        <p style="font-size: 13px; font-weight: 600;">Chưa có ảnh</p>
                    </div>
                    <img src="" id="productImagePreview" style="display: none;">
                </div>
                <input type="file" name="image" id="productImageInput" class="form-input" accept="image/*" required>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-sliders"></i> Thiết lập hiển thị</label>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <span>Sản phẩm nổi bật</span>
                        <input type="checkbox" name="is_featured" value="1" checked>
                    </label>
                    <label class="checkbox-item">
                        <span>Đang kinh doanh</span>
                        <input type="checkbox" name="is_active" value="1" checked>
                    </label>
                </div>
            </div>

            <div style="margin-top: 30px; background: #fffbeb; border: 1px solid #fef3c7; padding: 20px; border-radius: 15px; text-align: left;">
                <h4 style="font-size: 13px; color: #92400e; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-lightbulb"></i> Lưu ý nhỏ
                </h4>
                <p style="font-size: 11px; color: #b45309; line-height: 1.6;">
                    Chọn ảnh có phông nền sạch (trắng hoặc xám) để làm nổi bật sản phẩm nhất trên website.
                </p>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.getElementById('productImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('productImagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
