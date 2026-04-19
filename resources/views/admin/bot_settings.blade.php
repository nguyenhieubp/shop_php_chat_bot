@extends('layouts.admin')

@section('title', 'Cấu hình Chatbot')

@section('styles')
<style>
    .settings-container {
        max-width: 900px;
    }
    .settings-group {
        background: var(--white);
        border-radius: 20px;
        padding: 30px;
        border: 1px solid var(--border);
        margin-bottom: 30px;
    }
    .form-label {
        display: block;
        font-weight: 700;
        margin-bottom: 10px;
        color: #1e293b;
        font-size: 14px;
    }
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-family: inherit;
        font-size: 14px;
        transition: var(--transition);
        background: #f8fafc;
    }
    .form-control:focus {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(188, 143, 143, 0.1);
        outline: none;
    }
    .setting-item {
        margin-bottom: 25px;
    }
    .setting-item:last-child {
        margin-bottom: 0;
    }
    .setting-key {
        font-family: monospace;
        font-size: 11px;
        color: #94a3b8;
        display: block;
        margin-top: 5px;
    }
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 35px;">
    <div>
        <h1 class="admin-title">Cấu hình Chatbot</h1>
        <p class="admin-subtitle">Tùy chỉnh nội dung văn bản và các nút bấm hiển thị trên Chatbot.</p>
    </div>
</div>

<div class="settings-container">
    <form action="{{ route('admin.bot.update') }}" method="POST">
        @csrf
        <div class="settings-group">
            <h3 style="margin-bottom: 25px; font-size: 18px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-comments" style="color: var(--primary);"></i> Nội dung hội thoại
            </h3>
            
            @foreach($settings as $setting)
            <div class="setting-item">
                <label class="form-label" for="{{ $setting->key }}">{{ $setting->label }}</label>
                <input type="text" name="settings[{{ $setting->key }}]" id="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                <span class="setting-key">Key: {{ $setting->key }}</span>
            </div>
            @endforeach
            
            <div style="margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 30px; text-align: right;">
                <button type="submit" class="btn" style="padding: 14px 40px;">
                    <i class="fa-solid fa-save"></i> Lưu cài đặt
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
