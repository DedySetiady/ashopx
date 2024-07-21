<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5">
            <div class="card shadow" style="background-color: #fff4f4;">
                <form class="card-body" method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- LOGO --}}
                    <div class="text-center">
                        <img src="{{ asset('logo.jpg') }}"
                            class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3" width="200px"
                            alt="profile">
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            aria-describedby="emailHelp" placeholder="Email" name="email" value="{{ old('email') }}">
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" placeholder="Password" name="password">
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- Lupa Password --}}
                    <div class="form-text text-end text-dark mb-3">
                        <a href="{{ route('password.request') }}" class="text-dark fw-bold"> Lupa Password?
                        </a>
                    </div>

                    {{-- LOGIN BUTTON --}}
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-dark">Login</button>
                    </div>

                    {{-- REGISTER --}}
                    <div class="form-text text-center mb-5 text-dark">Belum punya akun? <a
                            href="{{ route('register') }}" class="text-dark fw-bold"> Daftar</a>
                    </div>


                </form>
            </div>
        </div>
    </div>
</x-guest-layout>