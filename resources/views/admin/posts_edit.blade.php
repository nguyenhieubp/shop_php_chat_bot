@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Bài Viết')

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
        aspect-ratio: 16/9;
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
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 40px;">
    <div>
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800;">Chỉnh Sửa Nội Dung</h1>
        <p class="admin-subtitle">Cập nhật và hoàn thiện bài viết của bạn.</p>
    </div>
    <a href="{{ route('admin.posts') }}" class="btn" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
</div>

<form action="{{ route('admin.post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-container">
        <!-- Left: Content -->
        <div class="form-section">
            <div class="form-group" style="margin-bottom: 25px;">
                <label class="form-label"><i class="fa-solid fa-heading"></i> Tiêu đề bài viết</label>
                <input type="text" name="title" class="form-input" style="font-size: 18px; font-weight: 800;" value="{{ $post->title }}" required>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label class="form-label"><i class="fa-solid fa-pen-nib"></i> Nội dung chi tiết</label>
                <textarea name="content" class="form-input" rows="20" style="resize: none; line-height: 1.6;">{{ $post->content }}</textarea>
            </div>

            <div style="margin-top: 40px; border-top: 1px solid var(--border); padding-top: 30px;">
                <button type="submit" class="btn" style="padding: 16px 50px; font-size: 16px; border-radius: 15px;">
                    <i class="fa-solid fa-cloud-arrow-up" style="margin-right: 10px;"></i> Cập Nhật Bài Viết
                </button>
            </div>
        </div>

        <!-- Right: Multimedia & Settings -->
        <div class="image-preview-card">
            <div class="form-group" style="margin-bottom: 30px;">
                <label class="form-label"><i class="fa-solid fa-image"></i> Ảnh bìa bài viết</label>
                <div class="preview-box" id="imagePreviewContainer">
                    <div class="preview-placeholder" id="uploadPlaceholder" style="{{ $post->image ? 'display: none;' : '' }}">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 40px; opacity: 0.2; margin-bottom: 10px;"></i>
                        <p style="font-size: 12px; font-weight: 600;">Chưa có ảnh bìa</p>
                    </div>
                    <img src="{{ $post->image ? asset($post->image) : '' }}" id="postImagePreview" style="{{ $post->image ? 'display: block;' : 'display: none;' }}">
                </div>
                <input type="file" name="image" id="postImageInput" class="form-input" accept="image/*">
            </div>

            <div style="margin-top: 30px; background: #fffbeb; border: 1px solid #fef3c7; padding: 20px; border-radius: 15px; text-align: left;">
                <h4 style="font-size: 13px; color: #92400e; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-lightbulb"></i> Lưu ý chỉnh sửa
                </h4>
                <p style="font-size: 11px; color: #b45309; line-height: 1.6;">
                    Khi bạn cập nhật bài viết, nội dung mới sẽ được hiển thị ngay lập tức tới khách hàng. Hãy kiểm tra kỹ lỗi chính tả.
                </p>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.getElementById('postImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('postImagePreview');
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
