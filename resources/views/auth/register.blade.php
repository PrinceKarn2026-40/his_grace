<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — HisGrace Fashion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --red: #c0392b; --red-dark: #96281b; --red-light: #e74c3c; }
        body { font-family: 'Inter', sans-serif; background: #0f0f0f; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; }
        .auth-bg { position: fixed; inset: 0; z-index: 0; background: linear-gradient(135deg, #0f0f0f 0%, #1a0a0a 50%, #0f0f0f 100%); }
        .auth-bg::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse at 80% 30%, rgba(192,57,43,.15) 0%, transparent 60%),
                        radial-gradient(ellipse at 20% 80%, rgba(192,57,43,.1) 0%, transparent 50%);
        }
        .auth-wrap { position: relative; z-index: 1; width: 100%; max-width: 500px; }
        .auth-card {
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
            border-radius: 20px; padding: 2.5rem; backdrop-filter: blur(20px);
            box-shadow: 0 32px 80px rgba(0,0,0,.6), 0 0 0 1px rgba(192,57,43,.1);
        }
        .brand { font-family: 'Playfair Display', serif; font-size: 2rem; color: #fff; text-align: center; margin-bottom: .25rem; }
        .brand span { color: var(--red-light); }
        .brand-sub { text-align: center; color: rgba(255,255,255,.4); font-size: .8rem; letter-spacing: .15em; text-transform: uppercase; margin-bottom: 2rem; }
        .form-label { color: rgba(255,255,255,.7); font-size: .82rem; font-weight: 500; margin-bottom: .4rem; }
        .form-control {
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
            color: #fff; border-radius: 10px; padding: .7rem 1rem; font-size: .9rem; transition: .2s;
        }
        .form-control:focus { background: rgba(255,255,255,.09); border-color: var(--red); box-shadow: 0 0 0 3px rgba(192,57,43,.2); color: #fff; }
        .form-control::placeholder { color: rgba(255,255,255,.25); }
        .form-control.is-invalid { border-color: #e74c3c; }
        .invalid-feedback { color: #e74c3c; font-size: .78rem; }
        .btn-google {
            background: #fff; color: #3c4043; border: 1px solid rgba(255,255,255,.15);
            border-radius: 10px; padding: .72rem; font-weight: 500; font-size: .88rem;
            transition: .2s; width: 100%; display: flex; align-items: center; justify-content: center; gap: .6rem;
            cursor: pointer; text-decoration: none;
        }
        .btn-google:hover { background: rgba(255,255,255,.95); box-shadow: 0 4px 16px rgba(0,0,0,.3); }
        .btn-google svg { width: 18px; height: 18px; flex-shrink: 0; }
        .btn-red {
            background: linear-gradient(135deg, var(--red) 0%, var(--red-light) 100%);
            color: #fff; border: none; border-radius: 10px; padding: .75rem;
            font-weight: 600; font-size: .9rem; letter-spacing: .05em; transition: .2s; width: 100%;
        }
        .btn-red:hover { background: linear-gradient(135deg, var(--red-dark) 0%, var(--red) 100%); color: #fff; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(192,57,43,.4); }
        .divider { display: flex; align-items: center; gap: 1rem; margin: 1.5rem 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.1); }
        .divider span { color: rgba(255,255,255,.3); font-size: .78rem; }
        .auth-link { color: var(--red-light); text-decoration: none; font-size: .85rem; }
        .auth-link:hover { color: #fff; }
        .check-label { color: rgba(255,255,255,.6); font-size: .83rem; }
        .form-check-input:checked { background-color: var(--red); border-color: var(--red); }
        .back-link { display: flex; align-items: center; gap: .5rem; color: rgba(255,255,255,.4); font-size: .8rem; text-decoration: none; margin-bottom: 1.5rem; transition: .2s; }
        .back-link:hover { color: rgba(255,255,255,.8); }
        .floating-shapes { position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden; }
        .shape { position: absolute; border-radius: 50%; opacity: .04; background: var(--red); animation: float 8s ease-in-out infinite; }
        .shape:nth-child(1) { width: 300px; height: 300px; top: -100px; left: -100px; animation-delay: 0s; }
        .shape:nth-child(2) { width: 200px; height: 200px; bottom: -50px; right: -50px; animation-delay: 3s; }
        @keyframes float { 0%,100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-20px) scale(1.05); } }
        .perks { display: flex; gap: 1.5rem; justify-content: center; margin-bottom: 1.5rem; }
        .perk { text-align: center; color: rgba(255,255,255,.35); font-size: .72rem; }
        .perk i { display: block; font-size: 1.1rem; color: var(--red-light); margin-bottom: .25rem; opacity: .7; }
    </style>
</head>
<body>
<div class="auth-bg"></div>
<div class="floating-shapes"><div class="shape"></div><div class="shape"></div></div>

<div class="auth-wrap">
    <a href="{{ route('home') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> Back to Store
    </a>
    <div class="auth-card">
        <div class="brand">His<span>Grace</span></div>
        <p class="brand-sub">Create Your Account</p>

        <div class="perks">
            <div class="perk"><i class="bi bi-bag-heart"></i>Exclusive Deals</div>
            <div class="perk"><i class="bi bi-truck"></i>Fast Delivery</div>
            <div class="perk"><i class="bi bi-shield-check"></i>Secure Shopping</div>
        </div>

        @if ($errors->any())
            <div class="alert py-2 mb-3" style="background:rgba(231,76,60,.15);border:1px solid rgba(231,76,60,.3);color:#e74c3c;border-radius:10px;font-size:.83rem;">
                @foreach ($errors->all() as $error)
                    <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="you@example.com" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-6">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                <label class="check-label form-check-label" for="terms">
                    I agree to the
                    <a href="{{ route('terms.show') }}" target="_blank" class="auth-link">Terms of Service</a>
                    and
                    <a href="{{ route('policy.show') }}" target="_blank" class="auth-link">Privacy Policy</a>
                </label>
            </div>
            @endif

            <button type="submit" class="btn-red">Create Account</button>
        </form>

        <div class="divider"><span>or continue with</span></div>

        <a href="{{ route('auth.google') }}" class="btn-google mb-4">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Sign up with Google
        </a>

        <p class="text-center mb-0" style="color:rgba(255,255,255,.5);font-size:.85rem;">
            Already have an account?
            <a href="{{ route('login') }}" class="auth-link fw-semibold ms-1">Sign in</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
