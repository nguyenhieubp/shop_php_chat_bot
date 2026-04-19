@extends('layouts.app')

@section('title', $post->title)

@section('styles')
<style>
    .post-header {
        padding: 100px 0 60px;
        text-align: center;
        background: var(--bg);
    }

    .post-header .container {
        max-width: 800px;
    }

    .post-meta {
        font-size: 14px;
        color: var(--primary);
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 20px;
        display: block;
    }

    .post-header h1 {
        font-size: 48px;
        line-height: 1.2;
        margin-bottom: 30px;
    }

    .post-hero-img {
        width: 100%;
        height: 450px;
        background: #f8f8f8;
        border-radius: 12px;
        margin-bottom: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 80px;
    }

    .post-body {
        max-width: 800px;
        margin: 0 auto;
        font-size: 18px;
        line-height: 1.8;
        color: #444;
    }

    .post-body p {
        margin-bottom: 25px;
    }

    .back-to-blog {
        display: inline-block;
        margin-top: 50px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
    <article class="animate-fade">
        <header class="post-header">
            <div class="container">
                <span class="post-meta">{{ $post->created_at->format('d F, Y') }}</span>
                <h1>{{ $post->title }}</h1>
            </div>
        </header>

        <div class="container">
            <div class="post-cover animate-fade" style="overflow: hidden; display: flex; align-items: center; justify-content: center; font-size: 80px; color: #ccc;">
                @if($post->image)
                    <img src="{{ asset($post->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    📰
                @endif
            </div>
            
            <div class="post-body">
                {!! nl2br(e($post->content)) !!}
                
                <div style="border-top: 1px solid var(--border); margin-top: 60px; padding-top: 30px;">
                    <a href="{{ route('blog.index') }}" class="back-to-blog">← Quay lại danh sách tin tức</a>
                </div>
            </div>
        </div>
    </article>
@endsection
