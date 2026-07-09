@extends('layouts.shop')
@section('title', 'Wishlist - HisGrace')

@section('content')
<div class="container py-5">
    <h2 class="brand-font mb-4">My Wishlist</h2>

    @if($items->count())
    <div class="row g-4">
        @foreach($items as $item)
            @if($item->product)
                @include('shop.partials.product-card', ['product' => $item->product])
            @endif
        @endforeach
    </div>
    @else
    <div class="text-center py-5 text-muted">
        <i class="bi bi-heart" style="font-size:3rem;"></i>
        <h5 class="mt-3">Your wishlist is empty</h5>
        <a href="{{ route('shop') }}" class="btn btn-gold mt-2">Browse Products</a>
    </div>
    @endif
</div>
@endsection
