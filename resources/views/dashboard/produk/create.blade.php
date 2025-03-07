<x-dashboard-layout>
    <h1 class="mt-4">Tambah Produk</h1>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.2/tinymce.min.js"></script>
    <script>
    tinymce.init({
        selector: 'textarea#description'
    });
    </script>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Produk</li>
    </ol>
    <div class="row mb-5">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 mb-3">
                            <label for="name" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Nama Produk" name="name" id="name" value="{{ old('name') }}">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="category_id"
                                class="form-control @error('category_id') is-invalid @enderror">
                                <option selected disabled>-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id'))>
                                    {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="brand" class="form-label">Nama Brand</label>
                            <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                placeholder="Nama Brand" name="brand" id="brand" value="{{ old('brand') }}">
                            @error('brand')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="harga_awal" class="form-label">Harga Awal</label>
                            <input type="number" class="form-control @error('harga_awal') is-invalid @enderror"
                                placeholder="Harga Awal" name="harga_awal" id="harga_awal"
                                value="{{ old('harga_awal') }}">

                            @error('harga_awal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="price" class="form-label">Harga Jual</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                placeholder="Harga" name="price" id="price" value="{{ old('price') }}">
                            @error('price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="expired" class="form-label">Expired</label>
                            <input type="date" class="form-control @error('expired') is-invalid @enderror"
                                placeholder="Expired" name="expired" id="expired" value="{{ old('expired') }}">
                            @error('expired')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                placeholder="Stock" name="stock" id="stock" value="{{ old('stock') }}">
                            @error('stock')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Keterangan</label>
                            <textarea name="description" id="description"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Keterangan">{{ old('description') }}</textarea>
                            @error('description')
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
                        <div class="col-6 mb-3 d-none" id="frame">
                            <img src="" class="img-fluid" width="200" id="previewImage">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option selected disabled>-- Pilih Status --</option>
                                <option value="1" @selected(old('status'))>Tampilkan</option>
                                <option value="0" @selected(old('status'))>Tidak Tampilkan</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
    tinymce.init({
        selector: '#description',
        height: 300,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | \
                      alignleft aligncenter alignright alignjustify | \
                      bullist numlist outdent indent | removeformat | help'
    });
    </script> -->
</x-dashboard-layout>