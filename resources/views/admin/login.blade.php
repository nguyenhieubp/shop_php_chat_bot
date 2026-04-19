@extends('layouts.app')

@section('title', 'Đăng nhập Quản trị')

@section('styles')
<style>
    .login-container {
        max-width: 400px;
        margin: 100px auto;
        padding: 40px;
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        text-align: center;
    }

    .login-container h2 {
        margin-bottom: 30px;
        color: var(--primary);
    }

    .login-form .form-label {
        display: block;
        text-align: left;
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 14px;
    }

    .login-form input {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid var(--border);
        background: var(--bg);
        color: var(--text);
        border-radius: 4px;
    }
</style>
@endsection

@section('content')
<div class="login-container animate-fade">
    <h2>Admin Login</h2>
    <form action="{{ url('admin/login') }}" method="POST" class="login-form">
        @csrf
        <div class="form-group">
            <label class="form-label">Mật khẩu truy cập</label>
            <input type="password" name="password" placeholder="Nhập mật khẩu quản trị" required>
        </div>
        <button type="submit" class="btn" style="width: 100%;">Đăng nhập</button>
    </form>
    <p style="margin-top: 20px; font-size: 12px; color: #999;">Mặc định: admin123</p>
</div>
@endsection
