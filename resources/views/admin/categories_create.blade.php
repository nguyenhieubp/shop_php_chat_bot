@extends('layouts.admin')

@section('title', 'Thêm Danh mục mới')

@section('styles')
<style>
    .form-card {
        background: var(--white);
        border-radius: 20px;
        padding: 40px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        max-width: 500px;
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
    }
    .form-input {
        width: 100%;
        padding: 14px 18px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        font-family: inherit;
        font-size: 15px;
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
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800;">Tạo Danh Mục</h1>
        <p class="admin-subtitle">Xây dựng cấu trúc phân loại sản phẩm cho cửa hàng.</p>
    </div>
    <a href="{{ route('admin.categories') }}" class="btn" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="form-card">
    <form action="{{ route('admin.category.create') }}" method="POST">
        @csrf
        
        <div class="form-group" style="margin-bottom: 30px;">
            <label class="form-label"><i class="fa-solid fa-folder-tree"></i> Tên danh mục mới</label>
            <input type="text" name="name" class="form-input" placeholder="Ví dụ: Chăm sóc da chuyên sâu..." required autofocus>
            <div style="margin-top: 12px; display: flex; gap: 8px; color: #64748b; font-size: 12px; line-height: 1.5;">
                <i class="fa-solid fa-circle-info" style="margin-top: 2px;"></i>
                <span>Slug sẽ được tự động tạo dựa trên tên bạn nhập để tối ưu SEO.</span>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border); padding-top: 30px;">
            <button type="submit" class="btn" style="width: 100%; padding: 16px; font-size: 16px; border-radius: 14px;">
                <i class="fa-solid fa-check-double" style="margin-right: 10px;"></i> Hoàn tất & Lưu danh mục
            </button>
        </div>
    </form>
</div>
@endsection
