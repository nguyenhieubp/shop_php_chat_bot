@extends('layouts.app')

@section('title', 'Fashion Hub - Thời trang đa phong cách')

@section('styles')
<style>
    .hero-slider {
        position: relative;
        height: 650px;
        overflow: hidden;
        background: #000;
        margin-bottom: 80px;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        visibility: hidden;
        transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        background-size: cover;
        background-position: center;
    }

    .slide.active {
        opacity: 1;
        visibility: visible;
    }

    .slide-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 60%, transparent 100%);
    }

    [data-theme="dark"] .slide-overlay {
        background: linear-gradient(135deg, rgba(2, 6, 23, 0.95) 0%, rgba(2, 6, 23, 0.4) 60%, transparent 100%);
    }

    .slide-content {
        position: relative;
        z-index: 10;
        max-width: 800px;
        padding-left: 5%;
    }

    .badge-premium {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: #ff0000;
        color: #ffffff;
        border-radius: 0;
        font-weight: 900;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        margin-bottom: 30px;
    }

    .slide-title {
        font-size: 72px;
        color: #ffffff;
        margin-bottom: 15px;
        line-height: 1;
        font-weight: 900;
        letter-spacing: -0.05em;
        text-transform: uppercase;
    }

    .slide-subtitle {
        font-size: 18px;
        color: rgba(255,255,255,0.8);
        margin-bottom: 40px;
        max-width: 500px;
        font-weight: 500;
    }

    .slider-dots {
        position: absolute;
        bottom: 30px;
        left: 5%;
        display: flex;
        gap: 12px;
        z-index: 20;
    }

    .dot {
        width: 40px;
        height: 3px;
        background: rgba(255,255,255,0.3);
        cursor: pointer;
        transition: var(--transition);
    }

    .dot.active {
        background: #ff0000;
        width: 60px;
    }

    /* Minimalist Filter Bar */
    .filter-section {
        margin-bottom: 40px;
    }

    .filter-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--surface);
        padding: 10px 10px 10px 30px;
        border-radius: 100px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border);
    }

    @media (max-width: 991px) {
        .filter-container {
            border-radius: 20px;
            flex-direction: column;
            padding: 20px;
            gap: 20px;
        }
    }

    .search-input-group {
        display: flex;
        align-items: center;
        gap: 15px;
        flex: 1;
    }

    .search-input-group i {
        color: var(--primary);
        font-size: 18px;
    }

    .search-input-group input {
        border: none;
        background: transparent;
        color: var(--text);
        font-size: 16px;
        font-weight: 600;
        width: 100%;
        outline: none;
    }

    .category-pills {
        display: flex;
        gap: 8px;
        padding-right: 20px;
        border-right: 1px solid var(--border);
        margin-right: 20px;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .cat-pill {
        padding: 10px 24px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-muted);
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
        border: 1px solid transparent;
    }

    .cat-pill:hover {
        background: var(--bg);
        color: var(--text);
    }

    .cat-pill.active {
        background: var(--text);
        color: var(--surface);
    }

    [data-theme="dark"] .cat-pill.active {
        background: var(--primary);
    }

    /* Product Grid Modern */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 24px;
        margin-bottom: 60px;
    }

    .product-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 0;
        padding: 0;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .product-card:hover {
        border-color: #000;
        box-shadow: var(--shadow-lg);
    }

    .product-image-wrapper {
        position: relative;
        aspect-ratio: 3/4;
        overflow: hidden;
        background: #f1f1f1;
    }

    .product-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 1s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .product-card:hover .product-image-wrapper img {
        transform: scale(1.1);
    }

    .btn-quick-add {
        position: relative;
        z-index: 10;
        width: 44px;
        height: 44px;
        background: var(--surface);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text);
        font-size: 18px;
        box-shadow: var(--shadow-md);
        opacity: 0;
        transform: translateY(10px);
        transition: var(--transition);
        border: none;
        cursor: pointer;
    }

    .btn-quick-add-wrapper {
        position: absolute;
        bottom: 12px;
        right: 12px;
        z-index: 10;
    }

    .stretched-link::after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1;
        content: "";
    }

    .product-card:hover .btn-quick-add {
        opacity: 1;
        transform: translateY(0);
    }

    .btn-quick-add:hover {
        background: var(--primary);
        color: white;
    }

    .product-meta {
        text-align: left;
    }

    .product-meta {
        padding: 4px 8px 8px 8px;
    }

    .product-details-box {
        margin-top: 10px;
        padding: 0 5px;
    }

    .product-brand {
        font-size: 11px;
        font-weight: 800;
        color: #ff0000;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        margin-bottom: 5px;
    }

    .product-title {
        font-size: 14px;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--text);
        line-height: 1.4;
        text-transform: uppercase;
    }

    .product-price-tag {
        font-size: 16px;
        font-weight: 900;
        color: #000;
    }

    .fit-suggestion {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 8px;
        border-top: 1px dashed var(--border);
        padding-top: 8px;
        font-weight: 600;
    }

    /* Stats Minimalist */
    .stats-banner {
        padding: 80px 0;
        background: var(--surface);
        border-radius: var(--radius-xl);
        margin-bottom: 80px;
        border: 1px solid var(--border);
    }

    .stat-number {
        font-size: 50px;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.05em;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 14px;
        font-weight: 800;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.2em;
    }

    @media (max-width: 768px) {
        .stat-number { font-size: 48px; }
        .slide-title { font-size: 50px; }
    }
</style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-slider animate-fade">
        @forelse($sliders as $slider)
            <div class="slide {{ $loop->first ? 'active' : '' }}" style="background-image: url('{{ asset($slider->image) }}');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <div class="badge-premium">
                        <i class="fa-solid fa-crown"></i> Bộ sưu tập cao cấp
                    </div>
                    <h1 class="slide-title">{{ $slider->title }}</h1>
                    <p class="slide-subtitle">{{ $slider->subtitle }}</p>
                    <a href="#products" class="btn">
                        Khám phá ngay <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="slide active">
                <div class="slide-content">
                    <div class="badge-premium">Khám phá vẻ đẹp</div>
                    <h1 class="slide-title">Define your <br> own style.</h1>
                    <p class="slide-subtitle">Bộ sưu tập thời trang street-style dẫn đầu xu hướng 2024.</p>
                    <a href="#products" class="btn">Bắt đầu mua sắm</a>
                </div>
            </div>
        @endforelse

        @if(count($sliders) > 1)
            <div class="slider-dots">
                @foreach($sliders as $index => $slider)
                    <div class="dot {{ $loop->first ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Main Content -->
    <div class="container">
        <!-- Filter Section -->
        <div class="filter-section" id="products">
            <div class="filter-container">
                <div class="search-input-group">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" id="product-search" placeholder="Bạn đang tìm sản phẩm gì?">
                </div>
                
                <div class="category-pills">
                    <div class="cat-pill active" data-category="all">Tất cả</div>
                    @foreach($categories as $category)
                        <div class="cat-pill" data-category="{{ $category->id }}">{{ $category->name }}</div>
                    @endforeach
                </div>

                <div style="padding-right: 20px;">
                    <select id="price-sort" class="sort-select" style="font-family: inherit; font-size: 13px; font-weight: 800; border: none; background: transparent; cursor: pointer; outline: none; text-transform: uppercase; letter-spacing: 0.05em;">
                        <option value="default">Sắp xếp</option>
                        <option value="low-high">Giá: Thấp đến Cao</option>
                        <option value="high-low">Giá: Cao đến Thấp</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="product-grid" id="productGrid">
            @foreach($featuredProducts as $product)
                <div class="product-card" data-category="{{ $product->category_id }}" data-price="{{ $product->price }}">
                    <div class="product-image-wrapper">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 40px; color: var(--border);">✨</div>
                        @endif
                        
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="btn-quick-add-wrapper">
                            @csrf
                            <button type="submit" class="btn-quick-add" title="Thêm vào giỏ">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </form>
                    </div>
                    
                    <div class="product-meta">
                        <div class="product-details-box">
                            <div class="product-brand">{{ $product->category->name ?? 'YaMe Collection' }}</div>
                            <h3 class="product-title">
                                <a href="{{ route('product.show', $product->slug) }}" class="stretched-link" style="text-decoration: none; color: inherit;">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <div class="product-price-tag">{{ number_format($product->price) }} đ</div>
                            @if($product->min_height)
                                <div class="fit-suggestion">
                                    <i class="fa-solid fa-ruler-vertical"></i> Gợi ý: {{ $product->min_height }}-{{ $product->max_height }}cm | {{ $product->min_weight }}-{{ $product->max_weight }}kg
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Stats Section -->
        <section class="stats-banner">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; text-align: center;">
                <div>
                    <div class="stat-number">99%</div>
                    <div class="stat-label">Hài lòng</div>
                </div>
                <div>
                    <div class="stat-number">12k</div>
                    <div class="stat-label">Khách hàng</div>
                </div>
                <div>
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Hỗ trợ tận tâm</div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const catPills = document.querySelectorAll('.cat-pill');
        const productGrid = document.getElementById('productGrid');
        const sortSelect = document.getElementById('price-sort');
        const searchInput = document.getElementById('product-search');

        // Filter Logic
        async function fetchProducts() {
            const activeCat = document.querySelector('.cat-pill.active').getAttribute('data-category');
            const sortVal = sortSelect.value;
            const query = searchInput.value;

            productGrid.style.opacity = '0.4';

            try {
                const url = new URL('{{ route('api.products.search') }}', window.location.origin);
                url.searchParams.append('query', query);
                url.searchParams.append('category_id', activeCat);
                url.searchParams.append('sort', sortVal);

                const res = await fetch(url);
                const products = await res.json();
                renderProducts(products);
            } catch (e) {
                console.error(e);
            } finally {
                productGrid.style.opacity = '1';
            }
        }

        function renderProducts(products) {
            productGrid.innerHTML = '';
            if (products.length === 0) {
                productGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 100px; font-size: 20px; font-weight: 700; color: var(--text-muted);">Không tìm thấy sản phẩm phù hợp.</div>';
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            products.forEach(p => {
                const card = document.createElement('div');
                card.className = 'product-card';
                card.innerHTML = `
                    <div class="product-image-wrapper">
                        ${p.image ? `<img src="/${p.image}" alt="${p.name}">` : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:40px;color:var(--border);">✨</div>`}
                        <form action="/cart/add/${p.id}" method="POST" class="btn-quick-add-wrapper">
                            <input type="hidden" name="_token" value="${token}">
                            <button type="submit" class="btn-quick-add" title="Thêm vào giỏ"><i class="fa-solid fa-plus"></i></button>
                        </form>
                    </div>
                    <div class="product-meta">
                        <div class="product-details-box">
                            <div class="product-brand">${p.category?.name || 'YaMe Style'}</div>
                            <h3 class="product-title"><a href="/product/${p.slug}" class="stretched-link" style="text-decoration:none;color:inherit;">${p.name}</a></h3>
                            <div class="product-price-tag">${new Intl.NumberFormat().format(p.price)} đ</div>
                            ${p.min_height ? `
                                <div class="fit-suggestion">
                                    <i class="fa-solid fa-ruler-vertical"></i> Gợi ý: ${p.min_height}-${p.max_height}cm | ${p.min_weight}-${p.max_weight}kg
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                productGrid.appendChild(card);
            });
        }

        catPills.forEach(p => p.addEventListener('click', function() {
            catPills.forEach(x => x.classList.remove('active'));
            this.classList.add('active');
            fetchProducts();
        }));

        sortSelect.addEventListener('change', fetchProducts);
        searchInput.addEventListener('input', debounce(fetchProducts, 500));

        function debounce(f, w) {
            let t;
            return (...a) => { clearTimeout(t); t = setTimeout(() => f(...a), w); };
        }

        // Slider Logic
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        let idx = 0;

        if (slides.length > 1) {
            function show(n) {
                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));
                slides[n].classList.add('active');
                dots[n].classList.add('active');
                idx = n;
            }

            window.goToSlide = n => show(n);
            setInterval(() => show((idx + 1) % slides.length), 6000);
        }
    });
</script>
@endsection
