<form method="POST" action="{{ route('setting.update', 1) }}" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="row tepi">
        <div class="col-lg-6">
            <div class="row">
                <h5>Your Logo Setting</h5>
            </div>
            <div class="row">
                <div class="col-md-3">
                    @if ($setting->path_logo == '/img/logo_cart.png')
                        <img class="circle img-preview" src="{{ asset($setting->path_logo) }}" alt="">
                    @else
                        <img class="circle img-preview" src="{{ asset('storage/' . $setting->path_logo) }}"
                            alt="">
                    @endif
                    <span class='label label-info' id="upload-file-info"></span>
                    @error('path_logo')
                        <div class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="col-md-9">
                    <br>
                    <label class="btn btn-primary" for="path_logo">
                        <input class="btn btn-primary rounded-lg @error('path_logo') is-invalid @enderror"
                            id="path_logo" name="path_logo" type="file" style="display:none"
                            onchange="previewname()">Upload Logo
                    </label>

                    <button type="button" class="btn tblremove rounded-lg ml-4" onclick="deleteimage()">Remove
                        Logo</button>
                </div>
            </div>
        </div>
    </div>
    <div class="garis"></div>

    <div class="row">
        <div class="col-lg-6">
            <div style="width: 70%" class="tepi">
                <div class="form-group">
                    <label for="nama_app" style="font-size: 13px;">Nama Aplikasi</label>
                    <input type="text" name="nama_app" class="form-control @error('nama_app') is-invalid @enderror"
                        id="nama_app" placeholder="Nama Aplikasi" value="{{ old('nama_app', $setting->nama_app) }}">
                    @error('nama_app')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nama_perusahaan" style="font-size: 13px;">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan"
                        class="form-control @error('nama_perusahaan') is-invalid @enderror" id="nama_perusahaan"
                        placeholder="Nama Perusahaan" value="{{ old('nama_perusahaan', $setting->nama_perusahaan) }}">
                    @error('nama_perusahaan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="telepon" style="font-size: 13px;">Telepon</label>
                    <input type="number" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
                        id="telepon" placeholder="Telepon" value="{{ old('telepon', $setting->telepon) }}">
                    @error('telepon')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

        </div>
        <div class="col-lg-6">
            <div style="width: 70%" class="tepi">
                <div class="form-group">
                    <label for="tipe_nota">Tipe Nota</label>
                    <select id="tipe_nota" name="tipe_nota"
                        class="form-control @error('tipe_nota') is-invalid @enderror">
                        <option selected disabled>Pilih Tipe Nota</option>
                        <option value="0">Besar</option>
                        <option value="1">Kecil</option>
                    </select>
                </div>
                @error('tipe_nota')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                <div class="form-group">
                    <label for="alamat" style="font-size: 13px;">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="4" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $setting->alamat) }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <input type="hidden" name="cekubah" id="cekubah" name="cekubah" value="default">
                <input type="hidden" name="deleteimg" id="deleteimg" name="deleteimg" value="default">
                <input type="hidden" name="oldImage" id="oldImage" name="oldImage" value="{{ $setting->path_logo }}">
            </div>
        </div>
        <div class=" mt-5 tepi d-flex justify-content-start">
            <button class="btn btn-primary">Update Profil</button>
        </div>
    </div>
</form>
