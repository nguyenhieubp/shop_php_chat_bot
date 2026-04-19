@extends('layouts.app')

@section('title', 'Tin tức & Làm đẹp')

@section('styles')
<style>
    .blog-header {
        padding: 80px 0;
        text-align: center;
        background: var(--secondary);
        margin-bottom: 50px;
    }

    .blog-header h1 {
        font-size: 42px;
        color: var(--primary);
        margin-bottom: 15px;
    }

    .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 40px;
        margin-bottom: 60px;
    }

    .post-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
    }

    .post-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .post-thumb {
        height: 220px;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 50px;
        color: #ccc;
    }

    .post-content {
        padding: 25px;
        flex-grow: 1;
    }

    .post-date {
        font-size: 12px;
        color: #888;
        text-transform: uppercase;
        margin-bottom: 10px;
        display: block;
    }

    .post-title {
        font-size: 20px;
        margin-bottom: 15px;
        color: var(--text);
        line-height: 1.4;
    }

    .post-excerpt {
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .read-more {
        color: var(--primary);
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>
@endsection

@section('content')
    <section class="blog-header animate-fade">
        <div class="container">
            <h1>Tin tức & Bí quyết Làm đẹp</h1>
            <p>Chia sẻ kiến thức chăm sóc da và phong cách sống hiện đại.</p>
        </div>
    </section>

    <div class="container">
        <div class="blog-grid">
            @foreach($posts as $post)
            <div class="post-card animate-fade">
                <div class="post-thumb">📰</div>
                <div class="post-content">
                    <span class="post-date">{{ $post->created_at->format('d M, Y') }}</span>
                    <h2 class="post-title">{{ $post->title }}</h2>
                    <p class="post-excerpt">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="read-more">Đọc tiếp →</a>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top: 30px; display: flex; justify-content: center;">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
