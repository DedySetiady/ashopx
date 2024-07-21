<x-app-layout>
    <section class="pt-5 pb-5">
        <div class="container">
            @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            @endif
            {{-- DETAIL PRODUCT --}}
            <div class="row mb-5">
                <div class="col-md-4 mb-3">
                    {{-- GAMBAR --}}
                    <img class="img-fluid img-thumbnail" alt="100%x280" src="/storage/photos/{{ $product->photo }}">
                </div>
                <div class="col-md-8 mb-3">
                    {{-- JUDUL --}}
                    <h2 class="mb-4">{{ $product->name }}</h2>
                    {{-- HARGA --}}
                    <h3 class="mb-4">
                        IDR {{ number_format($product->price, 0, ',', '.') }}
                    </h3>
                    {{-- BODY --}}
                    <p style="text-align: justify" class="mb-4">
                        {!! $product->description !!}
                    </p>
                    {{-- ADD TO CART --}}
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        <div class="row">
                            @csrf
                            <div class="col-md-2">
                                <select name="qty" id="qty" class="form-select">
                                    @for ($i = 1; $i <= $product->stock; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                </select>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    Add to cart
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
            {{-- ANOTHER PRODUCT --}}
            @if ($products->count() > 0)
            <style>
            .card {
                height: 100%;
            }

            .card-title {
                height: 50px;
                font-size: 14px;
            }

            .card-text {
                height: 40px;
                font-size: 12px;
            }

            .card img {
                width: 100%;
                height: auto;
                display: block;
                margin: 0 auto;
            }

            .discount-label {
                position: absolute;
                top: 10px;
                right: 10px;
                background-color: red;
                color: white;
                padding: 5px 10px;
                border-radius: 5px;
                font-weight: bold;
            }

            #back-to-top {
                position: fixed;
                bottom: 20px;
                right: 20px;
                display: none;
                z-index: 999;
            }
            </style>
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-3">Another Products</h3>
                </div>
                <div class="col-12 text-end">
                    <a class="btn btn-primary mb-3 mr-1" role="button" data-bs-slide="prev"
                        data-bs-target="#carouselExampleIndicators2">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <a class="btn btn-primary mb-3" role="button" data-bs-slide="next"
                        data-bs-target="#carouselExampleIndicators2">
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <div class="col-12">
                    <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @php
                            $productChunks = $products->filter(function($product) {
                            return $product->status == 1;
                            })->chunk(9);
                            @endphp
                            @foreach ($productChunks as $productChunk)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <div class="row">
                                    @foreach ($productChunk as $product)
                                    @php
                                    $discountPercent = 0;
                                    if ($product->harga_awal && $product->harga_awal > $product->price) {
                                    $discountPercent = round((($product->harga_awal - $product->price) /
                                    $product->harga_awal) * 100);
                                    }
                                    @endphp
                                    <div class="col-md-2 mb-3">
                                        <div class="card shadow">
                                            @if($discountPercent > 0)
                                            <div class="discount-label">Disc {{ $discountPercent }}%</div>
                                            @endif
                                            <img class="img-fluid small-card" alt="ashopx"
                                                src="{{ asset('storage/photos/' . $product->photo) }}"
                                                style="max-height: 250px;">
                                            <div class="card-body">
                                                <h4 class="card-title">{{ $product->name }}</h4>
                                                <p class="card-text">
                                                    @if($product->harga_awal && $product->harga_awal > $product->price)
                                                    <del>IDR
                                                        {{ number_format($product->harga_awal, 0, ',', '.') }}</del><br>
                                                    @endif
                                                    IDR {{ number_format($product->price, 0, ',', '.') }}
                                                </p>
                                                <a href="{{ route('product.show', $product->id) }}"
                                                    class="btn btn-primary">Add to cart</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!-- Back-to-top button -->
        <a id="back-to-top" href="#" class="btn btn-info btn-lg back-to-top" role="button">
            <i class="fa-solid fa-arrow-up"></i>
        </a>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Show or hide the sticky back-to-top button
        var offset = 300;
        var duration = 500;
        $(window).scroll(function() {
            if ($(this).scrollTop() > offset) {
                $('#back-to-top').fadeIn(duration);
            } else {
                $('#back-to-top').fadeOut(duration);
            }
        });

        // Smooth scrolling when clicking back-to-top button
        $('#back-to-top').click(function(event) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, duration);
            return false;
        });
    });
    </script>
</x-app-layout>