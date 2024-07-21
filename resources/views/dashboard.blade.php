<x-app-layout>
    <style>
    .gambar img {
        width: 100%;
        height: auto;
        object-fit: fill;
    }

    /* Ensure the carousel height is consistent across devices */
    @media (max-width: 767.98px) {
        .gambar img {
            height: auto;

            /* Adjust height for mobile view */
        }
    }

    @media (min-width: 768px) {
        .gambar img {
            height: 550px;
            /* Adjust height for larger screens */
        }
    }

    /* Style for the back-to-top button */
    #back-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: none;
        z-index: 999;
    }
    </style>
    <div class="container pt-4">
        <div class="row">
            <div class="col-md-8">
                <h1>
                    AshopX
                </h1>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col carousel-slide">
                <div id="carouselExampleCaptions" class="carousel slide shadow" data-bs-ride="carousel"
                    style=" border-radius: 15px;">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"
                            class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner" style=" border-radius: 10px;">
                        <div class="carousel-item gambar active">
                            <img src="storage/photos/1.png" class="d-block w-100 img-fluid gambar" alt="ashopx">
                            <div class="carousel-caption d-none d-md-block">

                            </div>
                        </div>
                        <div class="carousel-item gambar">
                            <img src="storage/photos/2.png" class="d-block w-100 img-fluid gambar" alt="ashopx">
                            <div class="carousel-caption d-none d-md-block">

                            </div>
                        </div>
                        <div class="carousel-item gambar">
                            <img src="storage/photos/3.png" class="d-block w-100 img-fluid gambar" alt="ashopx">
                            <div class="carousel-caption d-none d-md-block">

                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <section class="pt-5 pb-5">
        <div class="container">
            @if (session('success'))
            <div class="alert text-dark" role="alert" style="background-color: #F4C2C2">
                {{ session('success') }}
            </div>
            @endif
            {{-- BODY --}}
            <div class="row">
                <div class="col-4 mb-2">
                    <form action="{{ route('dashboard') }}" method="GET">
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option selected disabled>Kategori</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                @if ($products->count() > 0)
                {{-- DAFTAR PRODUK --}}
                <div class="col-12 text-end">
                    <a class="btn btn-primary mb-3 mr-1" role="button" data-bs-slide="prev"
                        data-bs-target="#carouselExampleIndicators2">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <a class="btn btn-primary mb-3 " role="button" data-bs-slide="next"
                        data-bs-target="#carouselExampleIndicators2">
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <div class="col-12">
                    <style>
                    .card {
                        height: 100%;
                        /* width: 250px; */
                    }

                    .card-title {
                        height: 50px;
                        /* Sesuaikan tinggi teks agar konsisten */
                        font-size: 14px;
                        /* Mengurangi ukuran font nama produk */
                    }

                    .card-text {
                        height: 40px;
                        /* Sesuaikan tinggi teks agar konsisten */
                        font-size: 12px;
                        /* Mengurangi ukuran font deskripsi produk */

                    }

                    .card img {
                        /* max-width: auto; */
                        /* Set the desired max-width for the image */
                        /* max-height: 100px; */
                        /* Set the desired max-height for the image */
                        /* display: block;
                        margin: 0 auto; */
                        /* Center the image horizontally */
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
                    </style>
                    <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @php
                            $productChunks = $products->toArray();
                            $productChunks = array_chunk($productChunks, 9);
                            @endphp
                            @foreach ($productChunks as $productChunk)
                            <div class="carousel-item {{ $loop->first ? ' active' : '' }}">
                                <div class="row">
                                    @foreach ($productChunk as $product)
                                    @php
                                    $discountPercent = 0;
                                    if ($product['harga_awal'] && $product['harga_awal'] > $product['price']) {
                                    $discountPercent = round((($product['harga_awal'] - $product['price']) /
                                    $product['harga_awal']) * 100);
                                    }
                                    @endphp
                                    <div class="col-md-2 mb-3">
                                        <div class="card shadow">
                                            @if($discountPercent > 0)
                                            <div class="discount-label">Disc {{ $discountPercent }}%</div>
                                            @endif
                                            <img class=" img-fluid small-card" alt="ashopx"
                                                src=" storage/photos/{{ $product['photo'] }}"
                                                style="max-height: 250px;">
                                            <div class=" card-body">
                                                <h4 class="card-title">{{ $product['name'] }}</h4>
                                                <p class="card-text">
                                                    @if($product['harga_awal'] && $product['harga_awal'] >
                                                    $product['price'])
                                                    <del>IDR
                                                        {{ number_format($product['harga_awal'], 0, ',', '.') }}</del><br>

                                                    @endif
                                                    IDR {{ number_format($product['price'], 0, ',', '.') }}
                                                </p>
                                                <a href="{{ route('product.show', $product['id']) }}"
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
                {{-- DAFTAR PRODUK --}}
                @else
                <div class="row justify-content-center mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <b>Belum ada barang</b>
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

        </div>
        {{-- BODY --}}
        </div>
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