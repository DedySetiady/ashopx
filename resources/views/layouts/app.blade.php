<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AshopX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>


    <style>
    .horizontal-list .list-group-item {
        height: 100%;
    }

    .navbar-custom {
        background-color: #F4C2C2;
    }

    .text-bg-custom {
        background-color: #F4C2C2;
    }

    .text-bg-custom:hover {
        color: #000000af !important;
    }

    .text-dark {
        color: #000000af !important;
    }

    .text-dark:hover {
        color: #000000 !important;
    }

    .active {
        color: #000000 !important;
    }

    .btn-primary {
        background-color: #f4c2c2bb !important;
        border-color: #f4c2c2bb !important;
        font-weight: 600;
    }

    .btn-primary:hover {
        background-color: #F4C2C2 !important;
        color: #000000 !important;
    }

    html,
    body {
        height: 100%;
    }

    body {
        display: flex;
        flex-direction: column;
    }

    main {
        flex: 1;
    }

    .footer-contact {
        font-size: 1.1rem;
    }

    .footer-icon {
        font-size: 1.5rem;
        margin-right: 10px;
    }

    .responsive-img {
        max-width: 100%;
        height: auto;
    }
    </style>
</head>

<body style="background-color: whitesmoke">
    <main>
        {{-- NAVBAR --}}
        <nav class="navbar navbar-expand-lg navbar-custom navbar-dark sticky-top shadow">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <form action="{{ route('dashboard') }}" method="GET" class="d-flex text-end" role="search">
                    <input type="text" name="keyword" class="form-control d-md-none" placeholder="Cari nama barang"
                        value="{{request()->keyword}}">
                    <button class="btn btn-primary d-md-none" type="submit" id="button-addon2">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav gap-2 fw-semibold">
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ Request::is('/') ? 'active' : '' }}" aria-current="page"
                                href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ Request::is('about') ? ' active' : '' }}"
                                href="/about">About</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav gap-2 fw-semibold ms-auto">
                        @auth
                        <form action="{{ route('dashboard') }}" method="GET" class="d-flex d-none d-md-flex text-end"
                            role="search">
                            <input type="text" name="keyword" class="form-control" placeholder="Cari nama barang"
                                value="{{request()->keyword}}">
                            <button class="btn btn-primary" type="submit" id="button-addon2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bxs-user fs-5"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('order.index') }}">Order</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                                </li>
                            </ul>
                        </li>

                        @else
                        <form action="{{ route('dashboard') }}" method="GET" class="d-flex d-none d-md-flex text-end"
                            role="search">
                            <input type=" text" name="keyword" class="form-control" placeholder="Cari nama barang"
                                value="{{request()->keyword}}">
                            <button class="btn btn-primary" type="submit" id="button-addon2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>

                        @endauth
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}"><i class="bx bxs-cart fs-5"></i></a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
        {{-- NAVBAR --}}

        {{ $slot }}

        {{-- FOOTER --}}
        <footer class="footer mt-5 py-3" style="background-color: #F4C2C2">
            <div class="container sticky-bottom">
                <div class="row py-5 px-5">
                    <div class="col-md-3">
                        <h4 class="fw-bold pb-2">Jam Buka</h4>
                        <p class="footer-contact"><i class="fas fa-clock footer-icon"></i>Setiap Hari
                        </p>
                        <p class="footer-contact"><i class="fas fa-clock footer-icon"></i>07.00 AM - 09.00
                            PM
                        <p>
                        <p class="footer-contact"><i class="fas fa-clock footer-icon"></i>Magrib Tutup
                        </p>
                    </div>
                    <div class="col-md-3">
                        <h4 class="fw-bold pb-2">Layanan Pelanggan</h4>
                        <p class="footer-contact"><i class="fas fa-phone footer-icon"></i>+1 (800) 123-4567</p>
                        <p class="footer-contact"><i class="fas fa-envelope footer-icon"></i>support@ashopx.com</p>
                    </div>
                    <div class="col-md-3 pb-2">
                        <h4 class="fw-bold">Lokasi</h4>
                        <p class="footer-contact"><i class="fas fa-map-marker-alt footer-icon"></i>Jalan Zafri Zam-Zam
                        </p>
                    </div>
                    <div class="col-md-3 pb-2">
                        <a
                            href="https://www.google.co.id/maps/place/a.shopx_/@-3.3087138,114.5708716,17z/data=!3m1!4b1!4m6!3m5!1s0x2de4238403c79271:0x3b41064fc6b8c8a6!8m2!3d-3.3087192!4d114.5734465!16s%2Fg%2F11h2mqnscy?entry=ttu">
                            <img src="{{ asset('maps.png') }}" alt=""
                                class="responsive-img img-fluid rounded shadow"></a>
                    </div>
                </div>
                <div class="px-5 text-center">
                    <span class="text-dark" style="font-size:13px" ;>© 2024 AshopX</span>
                </div>
            </div>
        </footer>

    </main>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>