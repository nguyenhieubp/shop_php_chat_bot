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
        
        @php
            $groupedSettings = $settings->groupBy('group');
            $groupLabels = [
                'general' => ['label' => 'Cài đặt chung', 'icon' => 'fa-robot'],
                'menu' => ['label' => 'Menu & Điều hướng', 'icon' => 'fa-compass'],
                'consultation' => ['label' => 'Tư vấn chọn Size', 'icon' => 'fa-ruler-combined'],
                'order' => ['label' => 'Quy trình Đặt hàng', 'icon' => 'fa-shopping-cart'],
                'tracking' => ['label' => 'Tra cứu đơn hàng', 'icon' => 'fa-truck-fast'],
                'feedback' => ['label' => 'Góp ý & Phản hồi', 'icon' => 'fa-envelope-open-text'],
            ];
        @endphp

        @foreach($groupLabels as $groupKey => $info)
            @if(isset($groupedSettings[$groupKey]))
                <div class="settings-group">
                    <h3 style="margin-bottom: 25px; font-size: 18px; display: flex; align-items: center; gap: 12px; color: var(--primary); border-bottom: 2px solid #f1f5f9; padding-bottom: 15px;">
                        <i class="fa-solid {{ $info['icon'] }}"></i> {{ $info['label'] }}
                    </h3>
                    
                    <div class="row" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 20px;">
                        @foreach($groupedSettings[$groupKey] as $setting)
                        <div class="setting-item" style="background: #fdfdfd; padding: 15px; border-radius: 12px; border: 1px solid #f1f5f9;">
                            <label class="form-label" for="{{ $setting->key }}">{{ $setting->label }}</label>
                            <input type="text" name="settings[{{ $setting->key }}]" id="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                            <span class="setting-key">Key: {{ $setting->key }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        {{-- Handle any groups not explicitly defined in groupLabels --}}
        @foreach($groupedSettings as $groupKey => $items)
            @if(!isset($groupLabels[$groupKey]))
                <div class="settings-group">
                    <h3 style="margin-bottom: 25px; font-size: 18px; display: flex; align-items: center; gap: 12px; color: var(--primary); border-bottom: 2px solid #f1f5f9; padding-bottom: 15px;">
                        <i class="fa-solid fa-gears"></i> Nhóm khác ({{ $groupKey }})
                    </h3>
                    <div class="row" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 20px;">
                        @foreach($items as $setting)
                        <div class="setting-item" style="background: #fdfdfd; padding: 15px; border-radius: 12px; border: 1px solid #f1f5f9;">
                            <label class="form-label" for="{{ $setting->key }}">{{ $setting->label }}</label>
                            <input type="text" name="settings[{{ $setting->key }}]" id="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                            <span class="setting-key">Key: {{ $setting->key }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
        
        <div style="position: sticky; bottom: 30px; margin-top: 40px; text-align: right; z-index: 10;">
            <button type="submit" class="btn" style="padding: 14px 50px; box-shadow: 0 10px 25px -5px rgba(188, 143, 143, 0.4); border-radius: 30px;">
                <i class="fa-solid fa-save"></i> Lưu cài đặt Chatbot
            </button>
        </div>
    </form>
</div>
@endsection
