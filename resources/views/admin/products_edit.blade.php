@extends('layouts.admin')

@section('title', 'Chỉnh sửa Sản phẩm')

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
        transition: var(--transition);
    }
    .preview-placeholder {
        color: #94a3b8;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .preview-label {
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
        gap: 25px;
        padding: 20px;
        background: #f8fafc;
        border-radius: 14px;
        margin-top: 10px;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        user-select: none;
    }
    .checkbox-item input {
        width: 20px;
        height: 20px;
        accent-color: var(--primary);
        cursor: pointer;
    }
    .checkbox-item span {
        font-weight: 600;
        font-size: 14px;
        color: #475569;
    }
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 40px;">
    <div>
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800;">Chỉnh sửa Sản phẩm</h1>
        <p class="admin-subtitle">Đang chỉnh sửa: <strong>{{ $product->name }}</strong></p>
    </div>
    <a href="{{ route('admin.products') }}" class="btn" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
</div>

<form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-container">
        <!-- Left: Form Data -->
        <div class="form-section">
            <div class="form-group" style="margin-bottom: 25px;">
                <label class="form-label"><i class="fa-solid fa-tag"></i> Tên sản phẩm</label>
                <input type="text" name="name" class="form-input" value="{{ $product->name }}" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-layer-group"></i> Danh mục</label>
                    <select name="category_id" class="form-input" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-coins"></i> Giá bán (đ)</label>
                    <input type="number" name="price" class="form-input" value="{{ $product->price }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-warehouse"></i> Tồn kho</label>
                    <input type="number" name="stock" class="form-input" value="{{ $product->stock ?? 0 }}">
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-image"></i> Thay đổi ảnh</label>
                    <input type="file" name="image" id="productImageInput" class="form-input" accept="image/*">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label class="form-label"><i class="fa-solid fa-align-left"></i> Mô tả sản phẩm</label>
                <textarea name="description" class="form-input" rows="8" style="resize: none;">{{ $product->description }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-gears"></i> Cấu hình hiển thị</label>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="is_active" {{ $product->is_active ? 'checked' : '' }}>
                        <span>Đang kinh doanh</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
                        <span>Sản phẩm nổi bật</span>
                    </label>
                </div>
            </div>

            <div style="margin-top: 40px; border-top: 1px solid var(--border); padding-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn" style="padding: 14px 40px; font-size: 15px;">
                    <i class="fa-solid fa-save" style="margin-right: 8px;"></i> Cập nhật ngay
                </button>
            </div>
        </div>

        <!-- Right: Image Preview -->
        <div class="image-preview-card">
            <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 20px; color: var(--text-main);">Xem trước hình ảnh</h3>
            
            <div class="preview-box" id="imagePreviewContainer">
                @if($product->image)
                    <span class="preview-label">ẢNH HIỆN TẠI</span>
                    <img src="{{ asset($product->image) }}" id="productImagePreview">
                @else
                    <div class="preview-placeholder">
                        <i class="fa-solid fa-image-polaroid" style="font-size: 48px;"></i>
                        <p style="font-size: 13px; font-weight: 600;">Chưa có ảnh</p>
                    </div>
                @endif
            </div>

            <div style="background: #f1f5f9; padding: 15px; border-radius: 12px; text-align: left;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <i class="fa-solid fa-circle-info" style="color: var(--primary);"></i>
                    <span style="font-size: 12px; font-weight: 700; color: #475569;">Gợi ý upload</span>
                </div>
                <ul style="list-style: none; font-size: 11px; color: #64748b; line-height: 1.8;">
                    <li>• Kích thước khuyến nghị: 800x800px</li>
                    <li>• Định dạng: JPG, PNG hoặc WebP</li>
                    <li>• Dung lượng tối đa: 2MB</li>
                </ul>
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
        const container = document.getElementById('imagePreviewContainer');
        const label = container.querySelector('.preview-label');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                if (preview) {
                    preview.src = event.target.result;
                    if (label) label.innerText = 'ẢNH MỚI CHỌN';
                } else {
                    // Create img if placeholder was showing
                    container.innerHTML = `
                        <span class="preview-label">ẢNH MỚI CHỌN</span>
                        <img src="${event.target.result}" id="productImagePreview">
                    `;
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
