@extends('layouts.app')

@section('title', 'Cửa hàng Mỹ phẩm Cao cấp')

@section('styles')
<style>
    .hero-slider {
        position: relative;
        height: 280px;
        overflow: hidden;
        background: #f8fafc;
        margin-bottom: 50px;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        visibility: hidden;
        transition: opacity 1s ease-in-out, visibility 1s;
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
        background: linear-gradient(90deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.2) 60%, transparent 100%);
    }

    .slide-content {
        position: relative;
        z-index: 10;
        max-width: 600px;
        padding-left: 10%;
    }

    .slide-title {
        font-size: 40px;
        color: var(--primary);
        margin-bottom: 15px;
        line-height: 1.1;
        transform: translateY(30px);
        opacity: 0;
        transition: all 0.8s ease 0.3s;
    }

    .slide-subtitle {
        font-size: 16px;
        color: #64748b;
        margin-bottom: 20px;
        transform: translateY(30px);
        opacity: 0;
        transition: all 0.8s ease 0.5s;
    }

    .slide.active .slide-title,
    .slide.active .slide-subtitle {
        transform: translateY(0);
        opacity: 1;
    }

    .slider-dots {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 12px;
        z-index: 20;
    }

    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(188, 143, 143, 0.3);
        cursor: pointer;
        transition: var(--transition);
    }

    .dot.active {
        background: var(--primary);
        width: 30px;
        border-radius: 5px;
    }

    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.7);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 21;
        transition: var(--transition);
        color: var(--primary);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .slider-nav:hover {
        background: var(--primary);
        color: white;
    }

    .slider-prev { left: 20px; }
    .slider-next { right: 20px; }

    .section-title {
        text-align: center;
        margin-bottom: 50px;
        font-size: 36px;
        font-weight: 700;
        color: var(--primary);
    }

    /* Enhanced Product Section */
    .filter-toolbar {
        display: flex;
        flex-direction: column;
        gap: 25px;
        margin-bottom: 50px;
    }

    .filter-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 12px 20px 12px 45px;
        border: 1px solid var(--border);
        border-radius: 30px;
        background: #f8fafc;
        outline: none;
        transition: all 0.3s ease;
        font-size: 15px;
    }

    .search-box input:focus {
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(188, 143, 143, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-btn {
        position: absolute;
        right: 6px;
        top: 6px;
        bottom: 6px;
        padding: 0 20px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-btn:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }

    .category-filter {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .cat-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 30px;
        background: #f1f5f9;
        color: #64748b;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .cat-btn.active {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 12px rgba(188, 143, 143, 0.3);
    }

    .price-range-group {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f1f5f9;
        padding: 5px 15px;
        border-radius: 30px;
    }

    .price-input {
        width: 100px;
        border: none;
        background: transparent;
        padding: 8px;
        font-size: 14px;
        font-weight: 600;
        outline: none;
        color: var(--primary);
    }

    .price-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    .sort-select {
        padding: 10px 20px;
        border: 1px solid var(--border);
        border-radius: 30px;
        background: white;
        color: var(--text);
        cursor: pointer;
        outline: none;
        font-size: 14px;
        transition: var(--transition);
    }

    .sort-select:focus {
        border-color: var(--primary);
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 35px;
        margin-bottom: 60px;
    }

    .product-card {
        border: none;
        background: white;
        padding: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .product-image-container {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: #f8fafc;
    }

    .product-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .product-card:hover .product-image-container img {
        transform: scale(1.1);
    }

    .product-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--primary);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 5;
    }

    .product-info {
        padding: 15px;
        text-align: left;
    }

    .product-name {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #1e293b;
        height: 44px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-height: 1.4;
    }

    .product-price {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 15px;
    }

    .product-actions {
        display: flex;
        gap: 10px;
    }

    .btn-cart {
        padding: 10px 15px;
        background: #334155;
        border-radius: 8px;
        color: white;
        border: none;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-cart:hover {
        background: #1e293b;
    }

    .btn-detail {
        flex: 1;
        padding: 10px;
        text-align: center;
        text-decoration: none;
        background: var(--secondary);
        color: var(--primary);
        font-weight: 600;
        font-size: 13px;
        border-radius: 8px;
        transition: var(--transition);
    }

    .btn-detail:hover {
        background: #f1e4e4;
    }
</style>
@endsection

@section('content')
    <section class="hero-slider animate-fade">
        @forelse($sliders as $slider)
            <div class="slide {{ $loop->first ? 'active' : '' }}" style="background-image: url('{{ asset($slider->image) }}');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h1 class="slide-title">{{ $slider->title }}</h1>
                    <p class="slide-subtitle">{{ $slider->subtitle }}</p>
                    @if($slider->link)
                        <a href="{{ $slider->link }}" class="btn" style="padding: 15px 40px; font-size: 16px; border-radius: 30px;">Khám phá ngay</a>
                    @else
                        <a href="#products" class="btn" style="padding: 15px 40px; font-size: 16px; border-radius: 30px;">Mua ngay</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="slide active" style="background-color: var(--secondary); display: flex; justify-content: center; text-align: center;">
                <div class="container" style="max-width: 800px;">
                    <h1 style="font-size: 48px; color: var(--primary); margin-bottom: 20px;">Vẻ đẹp hoàn mỹ của bạn</h1>
                    <p style="font-size: 18px; color: #64748b; margin-bottom: 30px;">Chào mừng bạn đến với Cosmetic Store - Nơi thăng hoa nhan sắc Việt.</p>
                    <a href="#products" class="btn" style="padding: 15px 40px; font-size: 16px; border-radius: 30px;">Bắt đầu mua sắm</a>
                </div>
            </div>
        @endforelse

        @if(count($sliders) > 1)
            <button class="slider-nav slider-prev" onclick="prevSlide()" aria-label="Previous slide">
                <i class="fa-solid fa-angle-left"></i>
            </button>
            <button class="slider-nav slider-next" onclick="nextSlide()" aria-label="Next slide">
                <i class="fa-solid fa-angle-right"></i>
            </button>

            <div class="slider-dots">
                @foreach($sliders as $index => $slider)
                    <div class="dot {{ $loop->first ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></div>
                @endforeach
            </div>
        @endif
    </section>

    <div class="container" id="products">
        <h2 class="section-title animate-fade">Khám phá sản phẩm</h2>

        <div class="filter-toolbar animate-fade">
            <div class="filter-row">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="product-search" placeholder="Bạn đang tìm sản phẩm gì?">
                    <button id="search-btn" class="search-btn">
                        <span>Tìm kiếm</span>
                    </button>
                </div>
                <div class="category-filter">
                    <button class="cat-btn active" data-category="all">Tất cả</button>
                    @foreach($categories as $category)
                        <button class="cat-btn" data-category="{{ $category->id }}">{{ $category->name }}</button>
                    @endforeach
                </div>
            </div>

            <div class="filter-row">
                <div class="price-range-group">
                    <input type="number" id="min-price" placeholder="Giá từ" class="price-input">
                    <span style="color: #94a3b8;">-</span>
                    <input type="number" id="max-price" placeholder="Đến" class="price-input">
                </div>
                <div class="sort-filter">
                    <select id="price-sort" class="sort-select">
                        <option value="default">Sắp xếp: Mặc định</option>
                        <option value="low-high">Giá: Thấp đến Cao</option>
                        <option value="high-low">Giá: Cao đến Thấp</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="product-grid" id="productGrid">
            @foreach($featuredProducts->take(10) as $product)
                <div class="product-card animate-fade" data-product-category="{{ $product->category_id }}" data-price="{{ $product->price }}">
                    @if($loop->index < 3)
                        <div class="product-badge">New</div>
                    @endif
                    <div class="product-image-container">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 40px; color: #cbd5e1;">✨</div>
                        @endif
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <p class="product-price">{{ number_format($product->price) }} VNĐ</p>
                        
                        <div class="product-actions">
                            <a href="{{ route('product.show', $product->slug) }}" class="btn-detail">Chi tiết</a>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" style="flex: 0 0 auto;">
                                @csrf
                                <button type="submit" class="btn-cart" title="Thêm vào giỏ hàng">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Stats Section -->
    <section id="stats" style="background: var(--primary); color: white; padding: 60px 0; margin-top: 50px;">
        <div class="container animate-fade">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); text-align: center;">
                <div>
                    <h2 style="font-size: 36px; margin-bottom: 10px;">99%</h2>
                    <p style="font-size: 14px; text-transform: uppercase;">Khách hàng hài lòng</p>
                </div>
                <div>
                    <h2 style="font-size: 36px; margin-bottom: 10px;">5000+</h2>
                    <p style="font-size: 14px; text-transform: uppercase;">Đơn hàng thành công</p>
                </div>
                <div>
                    <h2 style="font-size: 36px; margin-bottom: 10px;">24/7</h2>
                    <p style="font-size: 14px; text-transform: uppercase;">Hỗ trợ tận tình</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <div class="container" id="blog" style="margin-top: 80px;">
        <h2 class="section-title animate-fade">Tin tức & Làm đẹp</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            @foreach($latestPosts as $post)
            <div class="product-card animate-fade" style="text-align: left; padding: 0; overflow: hidden;">
                <div style="height: 200px; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 40px; color: #999; overflow: hidden;">
                    @if($post->image)
                        <img src="{{ asset($post->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        📰
                    @endif
                </div>
                <div style="padding: 20px;">
                    <h3 style="font-size: 18px; margin-bottom: 10px; color: var(--primary);">{{ $post->title }}</h3>
                    <p style="font-size: 14px; color: #666; margin-bottom: 15px;">
                        {{ Str::limit(strip_tags($post->content), 100) }}
                    </p>
                    <a href="{{ route('blog.show', $post->slug) }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 14px;">Xem chi tiết →</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category Filter with Sorting
        const catButtons = document.querySelectorAll('.cat-btn');
        const productGrid = document.getElementById('productGrid');
        const sortSelect = document.getElementById('price-sort');
        const searchInput = document.getElementById('product-search');
        const minPriceInput = document.getElementById('min-price');
        const maxPriceInput = document.getElementById('max-price');
        let productCards = Array.from(document.querySelectorAll('.product-card[data-product-category]'));

        function applyFilters() {
            const selectedCategory = document.querySelector('.cat-btn.active').getAttribute('data-category');
            const sortValue = sortSelect.value;
            const searchText = searchInput.value.toLowerCase();
            const minPrice = parseInt(minPriceInput.value) || 0;
            const maxPrice = parseInt(maxPriceInput.value) || Infinity;

            // Filter logic
            productCards.forEach(card => {
                const category = card.getAttribute('data-product-category');
                const price = parseInt(card.getAttribute('data-price'));
                const name = card.querySelector('.product-name').textContent.toLowerCase();

                const matchesCategory = (selectedCategory === 'all' || category === selectedCategory);
                const matchesSearch = name.includes(searchText);
                const matchesPrice = (price >= minPrice && price <= maxPrice);

                if (matchesCategory && matchesSearch && matchesPrice) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Sorting logic
            let visibleCards = productCards.filter(card => card.style.display !== 'none');
            
            if (sortValue !== 'default') {
                visibleCards.sort((a, b) => {
                    const priceA = parseInt(a.getAttribute('data-price'));
                    const priceB = parseInt(b.getAttribute('data-price'));
                    return sortValue === 'low-high' ? priceA - priceB : priceB - priceA;
                });

                // Reorder in DOM
                visibleCards.forEach(card => productGrid.appendChild(card));
            }
        }

        catButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                catButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                applyFilters();
            });
        });

        sortSelect.addEventListener('change', fetchProducts);
        minPriceInput.addEventListener('change', fetchProducts);
        maxPriceInput.addEventListener('change', fetchProducts);

        const searchBtn = document.getElementById('search-btn');
        searchBtn.addEventListener('click', fetchProducts);
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                fetchProducts();
            }
        });

        async function fetchProducts() {
            const selectedCategory = document.querySelector('.cat-btn.active').getAttribute('data-category');
            const sortValue = sortSelect.value;
            const searchText = searchInput.value;
            const minPrice = minPriceInput.value;
            const maxPrice = maxPriceInput.value;

            // Show loading state
            productGrid.style.opacity = '0.5';
            searchBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

            try {
                const url = new URL('{{ route('api.products.search') }}', window.location.origin);
                url.searchParams.append('query', searchText);
                url.searchParams.append('category_id', selectedCategory);
                url.searchParams.append('min_price', minPrice);
                url.searchParams.append('max_price', maxPrice);
                url.searchParams.append('sort', sortValue);

                const response = await fetch(url);
                const products = await response.json();

                renderProducts(products);
            } catch (error) {
                console.error('Error fetching products:', error);
            } finally {
                productGrid.style.opacity = '1';
                searchBtn.innerHTML = '<span>Tìm kiếm</span>';
            }
        }

        function renderProducts(products) {
            productGrid.innerHTML = '';

            if (products.length === 0) {
                productGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 60px; color: #64748b; font-size: 18px;">Không tìm thấy sản phẩm nào phù hợp.</div>';
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            products.forEach((product, index) => {
                const card = document.createElement('div');
                card.className = 'product-card animate-fade';
                card.setAttribute('data-product-category', product.category_id);
                card.setAttribute('data-price', product.price);

                const badge = index < 3 ? '<div class="product-badge">New</div>' : '';
                const imageHtml = product.image 
                    ? `<img src="/${product.image}" alt="${product.name}" loading="lazy">`
                    : `<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 40px; color: #cbd5e1;">✨</div>`;

                card.innerHTML = `
                    ${badge}
                    <div class="product-image-container">
                        ${imageHtml}
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">${product.name}</h3>
                        <p class="product-price">${new Intl.NumberFormat().format(product.price)} VNĐ</p>
                        
                        <div class="product-actions">
                            <a href="/product/${product.slug}" class="btn-detail">Chi tiết</a>
                            <form action="/cart/add/${product.id}" method="POST" style="flex: 0 0 auto;">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <button type="submit" class="btn-cart" title="Thêm vào giỏ hàng">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                `;
                productGrid.appendChild(card);
            });
        }

        // Hero Slider Logic
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        let currentSlide = 0;
        let slideInterval;

        if (slides.length > 1) {
            function showSlide(index) {
                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));
                
                slides[index].classList.add('active');
                dots[index].classList.add('active');
                currentSlide = index;
            }

            window.goToSlide = function(index) {
                showSlide(index);
                resetInterval();
            }

            window.nextSlide = function() {
                let nextIdx = (currentSlide + 1) % slides.length;
                showSlide(nextIdx);
                resetInterval();
            }

            window.prevSlide = function() {
                let prevIdx = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(prevIdx);
                resetInterval();
            }

            function autoNextSlide() {
                let nextIdx = (currentSlide + 1) % slides.length;
                showSlide(nextIdx);
            }

            function resetInterval() {
                clearInterval(slideInterval);
                slideInterval = setInterval(autoNextSlide, 5000);
            }

            slideInterval = setInterval(autoNextSlide, 5000);
        }
    });
</script>
@endsection
