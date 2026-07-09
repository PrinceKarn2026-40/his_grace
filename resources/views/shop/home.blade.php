@extends('layouts.shop')
@section('title', 'HisGrace Fashion — Premium Clothing in Kigali')

@push('styles')
<style>
    /* ── Hero ── */
    .hero-section {
        background: var(--dark);
        min-height: 90vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 65% 50%, rgba(192,57,43,.18) 0%, transparent 65%),
                    radial-gradient(ellipse at 10% 80%, rgba(192,57,43,.08) 0%, transparent 50%);
        pointer-events: none;
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: .5rem;
        background: rgba(192,57,43,.15); border: 1px solid rgba(192,57,43,.3);
        color: #e74c3c; border-radius: 50px; padding: .35rem 1rem;
        font-size: .75rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
        margin-bottom: 1.5rem;
    }
    .hero-title { font-family: 'Playfair Display', serif; font-size: clamp(2.6rem, 5.5vw, 4.8rem); color: #fff; line-height: 1.1; margin-bottom: 1.25rem; }
    .hero-title span { color: var(--red-light); }
    .hero-desc { color: rgba(255,255,255,.5); font-size: 1rem; max-width: 460px; margin-bottom: 2rem; line-height: 1.75; }
    .hero-cta { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2.5rem; }
    .btn-hero-primary {
        background: var(--red); color: #fff; border: none;
        padding: .85rem 2.5rem; border-radius: 50px; font-weight: 700;
        font-size: .88rem; letter-spacing: .05em; transition: .25s; text-decoration: none; display: inline-flex; align-items: center; gap: .4rem;
    }
    .btn-hero-primary:hover { background: var(--red-dark,#96281b); color: #fff; transform: translateY(-2px); box-shadow: 0 12px 32px rgba(192,57,43,.4); }
    .btn-hero-secondary {
        background: transparent; color: rgba(255,255,255,.75);
        border: 1px solid rgba(255,255,255,.2); padding: .85rem 2rem;
        border-radius: 50px; font-weight: 500; font-size: .88rem; transition: .25s; text-decoration: none;
    }
    .btn-hero-secondary:hover { border-color: rgba(255,255,255,.5); color: #fff; }
    .hero-stats { display: flex; gap: 2rem; }
    .hero-stat-num { font-family: 'Playfair Display', serif; font-size: 1.7rem; color: #fff; font-weight: 700; line-height: 1; }
    .hero-stat-label { color: rgba(255,255,255,.35); font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; margin-top: .2rem; }
    .hero-divider { width: 1px; background: rgba(255,255,255,.1); }

    /* ── Product Slider (right side of hero) ── */
    .hero-slider-wrap {
        position: relative; height: 520px; display: flex; align-items: center; justify-content: center;
    }
    .hero-slider-track {
        position: relative; width: 320px; height: 420px;
    }
    .hero-slide {
        position: absolute; inset: 0; border-radius: 20px; overflow: hidden;
        opacity: 0; transform: translateX(60px) scale(.95);
        transition: opacity .6s ease, transform .6s ease;
        box-shadow: 0 32px 80px rgba(0,0,0,.5);
    }
    .hero-slide.active {
        opacity: 1; transform: translateX(0) scale(1);
    }
    .hero-slide.prev {
        opacity: 0; transform: translateX(-60px) scale(.95);
    }
    .hero-slide img { width: 100%; height: 100%; object-fit: cover; }
    .hero-slide-placeholder {
        width: 100%; height: 100%;
        background: linear-gradient(135deg, rgba(192,57,43,.15) 0%, rgba(192,57,43,.05) 100%);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        color: rgba(255,255,255,.3);
    }
    .hero-slide-info {
        position: absolute; bottom: 0; left: 0; right: 0;
        background: linear-gradient(to top, rgba(0,0,0,.85) 0%, transparent 100%);
        padding: 1.5rem 1.25rem 1.25rem;
        border-radius: 0 0 20px 20px;
    }
    .hero-slide-cat { font-size: .68rem; text-transform: uppercase; letter-spacing: .1em; color: var(--red-light); font-weight: 700; margin-bottom: .25rem; }
    .hero-slide-name { color: #fff; font-weight: 700; font-size: .95rem; margin-bottom: .25rem; }
    .hero-slide-price { color: var(--red-light); font-weight: 700; font-size: .9rem; }

    /* Slider dots */
    .slider-dots { display: flex; gap: .5rem; justify-content: center; margin-top: 1.25rem; }
    .slider-dot { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,.2); border: none; padding: 0; cursor: pointer; transition: .3s; }
    .slider-dot.active { background: var(--red-light); width: 24px; border-radius: 4px; }

    /* Floating tags */
    .floating-tag {
        position: absolute; background: rgba(255,255,255,.07); backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,.1); border-radius: 12px;
        padding: .55rem .9rem; color: #fff; font-size: .75rem; font-weight: 600;
        display: flex; align-items: center; gap: .45rem; white-space: nowrap;
    }
    .floating-tag i { color: var(--red-light); }
    .tag-new { top: 10%; right: -20px; animation: tagFloat 3s ease-in-out infinite; }
    .tag-secure { bottom: 18%; left: -20px; animation: tagFloat 3s ease-in-out infinite .8s; }
    @keyframes tagFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }

    /* ── Sections ── */
    .section-label { color: var(--red); font-size: .72rem; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; margin-bottom: .4rem; }

    /* ── Category cards ── */
    .cat-card { background: #fff; border-radius: 16px; overflow: hidden; transition: .3s; cursor: pointer; text-decoration: none; display: block; border: 1px solid #f0f0f0; }
    .cat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 48px rgba(0,0,0,.13); border-color: rgba(192,57,43,.25); }
    .cat-card:hover .cat-img-overlay { opacity: 1; }
    .cat-card:hover .cat-img { transform: scale(1.06); }
    .cat-img-wrap { position: relative; width: 100%; aspect-ratio: 1/1; overflow: hidden; background: #f5f0f0; }
    .cat-img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
    .cat-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(192,57,43,.08), rgba(192,57,43,.03)); }
    .cat-img-placeholder i { font-size: 2.2rem; color: var(--red); opacity: .5; }
    .cat-img-overlay { position: absolute; inset: 0; background: rgba(192,57,43,.18); opacity: 0; transition: .3s; }

    /* ── Promo banner ── */
    .promo-banner { background: linear-gradient(135deg, var(--dark) 0%, #2d0a0a 50%, var(--dark) 100%); position: relative; overflow: hidden; }
    .promo-banner::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 50% 50%, rgba(192,57,43,.25) 0%, transparent 70%); }
    .promo-title { font-family: 'Playfair Display', serif; font-size: clamp(2rem, 4vw, 3.5rem); color: #fff; }
    .promo-title span { color: var(--red-light); }

    /* ── Features ── */
    .feature-icon { width: 60px; height: 60px; border-radius: 16px; background: rgba(192,57,43,.08); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
    .feature-icon i { font-size: 1.5rem; color: var(--red); }

    /* ── Live update ticker ── */
    .live-ticker {
        background: rgba(192,57,43,.06); border: 1px solid rgba(192,57,43,.12);
        border-radius: 50px; padding: .35rem 1rem; display: inline-flex; align-items: center; gap: .5rem;
        font-size: .75rem; color: rgba(255,255,255,.6); margin-bottom: 1rem;
    }
    .live-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--red-light); animation: pulse 1.5s ease-in-out infinite; }
    @keyframes pulse { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: .5; transform: scale(.8); } }
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<section class="hero-section">
    <div class="container position-relative" style="z-index:1;">
        <div class="row align-items-center g-5">

            {{-- Left: Text --}}
            <div class="col-lg-6">
                <div class="live-ticker">
                    <span class="live-dot"></span>
                    Live — {{ \App\Models\Product::count() }} products available
                </div>
                <div class="hero-badge">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--red-light);display:inline-block;"></span>
                    New Collection 2025
                </div>
                <h1 class="hero-title">
                    Dress With<br>
                    <span>Confidence</span><br>
                    & Style
                </h1>
                <p class="hero-desc">
                    Discover premium fashion crafted for the modern individual. From casual elegance to bold statements — find your perfect look at HisGrace.
                </p>
                <div class="hero-cta">
                    <a href="{{ route('shop') }}" class="btn-hero-primary">Shop Now <i class="bi bi-arrow-right"></i></a>
                    <a href="{{ route('shop', ['sort' => 'newest']) }}" class="btn-hero-secondary">New Arrivals</a>
                </div>
                <div class="hero-stats">
                    <div>
                        <div class="hero-stat-num">{{ \App\Models\Product::count() }}+</div>
                        <div class="hero-stat-label">Products</div>
                    </div>
                    <div class="hero-divider"></div>
                    <div>
                        <div class="hero-stat-num">{{ \App\Models\User::where('is_admin',false)->count() }}+</div>
                        <div class="hero-stat-label">Customers</div>
                    </div>
                    <div class="hero-divider"></div>
                    <div>
                        <div class="hero-stat-num">4.9★</div>
                        <div class="hero-stat-label">Rating</div>
                    </div>
                </div>
            </div>

            {{-- Right: Live Product Slider --}}
            <div class="col-lg-6 d-none d-lg-flex justify-content-center">
                <div class="hero-slider-wrap">
                    <div class="hero-slider-track" id="heroSlider">
                        @forelse($heroSlider as $i => $p)
                        <div class="hero-slide {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}">
                            @if($p->image)
                                <img src="{{ Storage::url($p->image) }}" alt="{{ $p->name }}">
                            @else
                                <div class="hero-slide-placeholder">
                                    <i class="bi bi-bag-heart" style="font-size:5rem;"></i>
                                    <span class="mt-2 small">{{ $p->name }}</span>
                                </div>
                            @endif
                            <div class="hero-slide-info">
                                <p class="hero-slide-cat mb-1">{{ $p->category->name ?? 'Fashion' }}</p>
                                <p class="hero-slide-name mb-1">{{ Str::limit($p->name, 30) }}</p>
                                <p class="hero-slide-price mb-0">
                                    RWF {{ number_format($p->sale_price ?? $p->price, 0) }}
                                    @if($p->sale_price)
                                        <span style="text-decoration:line-through;color:rgba(255,255,255,.4);font-size:.78rem;margin-left:.4rem;">RWF {{ number_format($p->price, 0) }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="hero-slide active">
                            <div class="hero-slide-placeholder">
                                <i class="bi bi-bag-heart" style="font-size:6rem;"></i>
                                <span class="mt-3 fw-semibold">HisGrace Fashion</span>
                                <span class="small mt-1">Premium Clothing</span>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    {{-- Dots --}}
                    @if($heroSlider->count() > 1)
                    <div class="slider-dots position-absolute" style="bottom:-40px;left:50%;transform:translateX(-50%);">
                        @foreach($heroSlider as $i => $p)
                            <button class="slider-dot {{ $i === 0 ? 'active' : '' }}" data-dot="{{ $i }}"></button>
                        @endforeach
                    </div>
                    @endif

                    {{-- Floating tags --}}
                    <div class="floating-tag tag-new"><i class="bi bi-star-fill"></i> Just Added</div>
                    <div class="floating-tag tag-secure"><i class="bi bi-shield-check"></i> Secure Pay</div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ── CATEGORIES ── --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-4">
            <p class="section-label">Browse By</p>
            <h2 class="section-title">Shop Categories</h2>
        </div>
        <div class="row g-3 justify-content-center">
            @foreach($categories as $cat)
            @php $catImg = $cat->products->first()?->image; @endphp
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="cat-card text-center">
                    <div class="cat-img-wrap">
                        @if($catImg)
                            <img src="{{ Storage::url($catImg) }}" alt="{{ $cat->name }}" class="cat-img">
                        @else
                            <div class="cat-img-placeholder"><i class="bi bi-tag"></i></div>
                        @endif
                        <div class="cat-img-overlay"></div>
                    </div>
                    <div class="p-2 pb-3">
                        <p class="fw-bold small mb-0 text-dark">{{ $cat->name }}</p>
                        <p class="text-muted mb-0" style="font-size:.72rem;">{{ $cat->products_count }} {{ Str::plural('item', $cat->products_count) }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── FEATURED PRODUCTS ── --}}
@if($featured->count())
<section class="py-5" style="background:#f8f8f8;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <p class="section-label mb-1">Hand-Picked</p>
                <h2 class="section-title mb-0">Featured Products</h2>
            </div>
            <a href="{{ route('shop') }}" class="btn btn-sm btn-outline-red rounded-pill px-4">View All</a>
        </div>
        <div class="row g-4">
            @foreach($featured as $product)
                @include('shop.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── PROMO BANNER ── --}}
<section class="promo-banner py-5">
    <div class="container text-center py-4 position-relative" style="z-index:1;">
        <p class="section-label" style="color:rgba(255,255,255,.4);">Limited Time</p>
        <h2 class="promo-title mb-3">Up to <span>40% Off</span><br>Sale Items</h2>
        <p class="mb-4" style="color:rgba(255,255,255,.45);max-width:400px;margin:0 auto 1.5rem;">
            Don't miss out on our biggest sale of the season. Premium fashion at unbeatable prices.
        </p>
        <a href="{{ route('shop') }}" class="btn-hero-primary">
            Shop the Sale <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>

{{-- ── NEW ARRIVALS ── --}}
@if($newArrivals->count())
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <p class="section-label mb-1">Just In</p>
                <h2 class="section-title mb-0">New Arrivals</h2>
            </div>
            <a href="{{ route('shop', ['sort' => 'newest']) }}" class="btn btn-sm btn-outline-red rounded-pill px-4">View All</a>
        </div>
        <div class="row g-4">
            @foreach($newArrivals as $product)
                @include('shop.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── COMING SOON ── --}}
@if($upcoming->count())
<section class="py-5" style="background:linear-gradient(135deg,#1a1a1a,#2d2d2d);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <p class="section-label" style="color:rgba(255,255,255,.4);">Exclusive Drops</p>
                <h2 class="section-title mb-0 text-white">Coming Soon</h2>
            </div>
        </div>
        <div class="row g-4">
            @foreach($upcoming as $product)
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 rounded-3 overflow-hidden" style="background:#2a2a2a;">
                    <div class="position-relative">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" class="card-img-top" style="height:220px;object-fit:cover;filter:brightness(.65);">
                        @else
                            <div style="height:220px;background:linear-gradient(135deg,#333,#444);display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-bag-heart" style="font-size:4rem;color:var(--gold);opacity:.4;"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                            <span class="badge px-3 py-2 fw-bold" style="background:var(--gold);font-size:.78rem;letter-spacing:.06em;">COMING SOON</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;">{{ $product->category->name }}</p>
                        <h6 class="fw-semibold text-white mb-2">{{ $product->name }}</h6>
                        <p class="mb-2" style="color:var(--gold);font-size:.82rem;">
                            <i class="bi bi-calendar-event me-1"></i>{{ $product->release_date->format('d M Y') }}
                        </p>
                        <div class="d-flex gap-2 justify-content-center" data-countdown="{{ $product->release_date->format('Y-m-d') }}">
                            <div class="text-center">
                                <div class="fw-bold text-white countdown-days" style="font-size:1.2rem;">--</div>
                                <div class="text-muted" style="font-size:.65rem;">DAYS</div>
                            </div>
                            <div class="text-white fw-bold" style="font-size:1.2rem;">:</div>
                            <div class="text-center">
                                <div class="fw-bold text-white countdown-hours" style="font-size:1.2rem;">--</div>
                                <div class="text-muted" style="font-size:.65rem;">HRS</div>
                            </div>
                            <div class="text-white fw-bold" style="font-size:1.2rem;">:</div>
                            <div class="text-center">
                                <div class="fw-bold text-white countdown-mins" style="font-size:1.2rem;">--</div>
                                <div class="text-muted" style="font-size:.65rem;">MIN</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── FEATURES STRIP ── --}}
<section class="py-5" style="background:#f8f8f8;">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="feature-icon"><i class="bi bi-truck"></i></div>
                <h6 class="fw-bold mb-1">Free Shipping</h6>
                <p class="text-muted small mb-0">On orders over RWF 50,000</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-icon"><i class="bi bi-arrow-return-left"></i></div>
                <h6 class="fw-bold mb-1">Easy Returns</h6>
                <p class="text-muted small mb-0">30-day return policy</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                <h6 class="fw-bold mb-1">Secure Payment</h6>
                <p class="text-muted small mb-0">100% secure via MoMo</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-icon"><i class="bi bi-headset"></i></div>
                <h6 class="fw-bold mb-1">24/7 Support</h6>
                <p class="text-muted small mb-0">Always here to help</p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.slider-dot');
    if (slides.length <= 1) return;

    let current = 0;
    let timer;

    function goTo(index) {
        slides[current].classList.remove('active');
        slides[current].classList.add('prev');
        dots[current]?.classList.remove('active');

        setTimeout(() => slides[current < slides.length ? current : 0].classList.remove('prev'), 600);

        current = index;
        slides[current].classList.add('active');
        dots[current]?.classList.add('active');
    }

    function next() {
        goTo((current + 1) % slides.length);
    }

    function startTimer() {
        timer = setInterval(next, 3500);
    }

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            clearInterval(timer);
            goTo(i);
            startTimer();
        });
    });

    startTimer();
})();

// Countdown timers
document.querySelectorAll('[data-countdown]').forEach(function(el) {
    function tick() {
        const release = new Date(el.dataset.countdown + 'T00:00:00');
        const diff = release - new Date();
        if (diff <= 0) { el.innerHTML = '<span class="text-success small">Available Now!</span>'; return; }
        const days  = Math.floor(diff / 86400000);
        const hours = Math.floor((diff % 86400000) / 3600000);
        const mins  = Math.floor((diff % 3600000) / 60000);
        el.querySelector('.countdown-days').textContent  = String(days).padStart(2,'0');
        el.querySelector('.countdown-hours').textContent = String(hours).padStart(2,'0');
        el.querySelector('.countdown-mins').textContent  = String(mins).padStart(2,'0');
    }
    tick();
    setInterval(tick, 60000);
});
</script>
@endpush
