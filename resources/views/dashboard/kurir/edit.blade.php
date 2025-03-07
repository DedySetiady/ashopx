<x-dashboard-layout>
    <h1 class="mt-4">Edit Kurir</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Kurir</li>
    </ol>
    <div class="row mb-5">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <form action="{{ route('courier.update', $courier) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="col-12 mb-3">
                                <label for="name" class="form-label">Nama Kurir</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Nama Kurir" name="name" id="name" value="{{ $courier->name }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender"
                                    class="form-control @error('gender') is-invalid @enderror">
                                    <option>-- Pilih Gender --</option>
                                    <option value="male" @selected($courier->gender == 'male')>Laki-laki</option>
                                    <option value="female" @selected($courier->gender == 'female')>Perempuan</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="motor" class="form-label">Nama Kendaraan</label>
                                <input type="text" class="form-control @error('motor') is-invalid @enderror"
                                    placeholder="Nama Kendaraan" name="motor" id="motor"
                                    value="{{ $courier->motor }}">
                                @error('motor')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="no_motor" class="form-label">Nomor Kendaraan</label>
                                <input type="text" class="form-control @error('no_motor') is-invalid @enderror"
                                    placeholder="Nomor Kendaraan" name="no_motor" id="no_motor"
                                    value="{{ $courier->no_motor }}">
                                @error('no_motor')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                    placeholder="Telepon" name="telepon" id="telepon" value="{{ $courier->telepon }}">
                                @error('telepon')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="photo" class="form-label">Gambar</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                    placeholder="Gambar" name="photo" id="photo" onchange="previewFile(this)">
                                @error('photo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-6 mb-3" id="frameOri">
                                <img src="/storage/photos/{{ $courier->photo }}" class="img-fluid" width="200">
                            </div>
                            <div class="col-6 mb-3 d-none" id="frame">
                                <img src="" class="img-fluid" width="200" id="previewImage">
                            </div>
                            <div class="col-6 mb-3">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
