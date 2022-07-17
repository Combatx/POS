<form method="POST" action="{{ route('profil.update', Auth::user()->id) }}" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="row tepi">
        <div class="col-lg-6">
            <div class="row">
                <h5>Your Logo Setting</h5>
            </div>
            <div class="row">
                <div class="col-md-3">
                    @if (Auth::user()->foto == '/img/user1.png')
                        <img class="circle img-preview" src="{{ asset(Auth::user()->foto) }}" alt="">
                    @else
                        <img class="circle img-preview" src="{{ asset('storage/' . Auth::user()->foto) }}"
                            alt="">
                    @endif
                    <span class='label label-info' id="upload-file-info"></span>
                    @error('foto')
                        <div class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="col-md-9">
                    <br>
                    <label class="btn btn-primary" for="foto">
                        <input class="btn btn-primary rounded-lg @error('foto') is-invalid @enderror" id="foto"
                            name="foto" type="file" style="display:none" onchange="previewname()">Upload Logo
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
                    <label for="name" style="font-size: 13px;">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        id="name" placeholder="Name" value="{{ old('name', Auth::user()->name) }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" style="font-size: 13px;">email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" placeholder="email" value="{{ old('email', Auth::user()->email) }}">
                    @error('email')
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
                    <label for="telepon" style="font-size: 13px;">Telepon</label>
                    <input type="number" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
                        id="telepon" placeholder="Telepon" value="{{ old('telepon', Auth::user()->telepon) }}">
                    @error('telepon')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="Role" style="font-size: 13px;">Role</label>
                    @if (Auth::user()->role_id == 1)
                        <input type="text" name="Role" class="form-control" id="Role" value="Admin"
                            disabled>
                    @elseif(Auth::user()->role_id == 2)
                        <input type="text" name="Role" class="form-control" id="Role" value="Gudang"
                            disabled>
                    @elseif(Auth::user()->role_id == 3)
                        <input type="text" name="Role" class="form-control" id="Role" value="Kasir"
                            disabled>
                    @endif

                </div>

                <input type="hidden" name="cekubah" id="cekubah" name="cekubah" value="default">
                <input type="hidden" name="deleteimg" id="deleteimg" name="deleteimg" value="default">
                <input type="hidden" name="oldImage" id="oldImage" name="oldImage"
                    value="{{ Auth::user()->foto }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div style="width: 85%" class="tepi">
                <div class="form-group">
                    <label for="alamat" style="font-size: 13px;">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class=" mt-5 tepi d-flex justify-content-start">
        <button class="btn btn-primary">Update Profil</button>
    </div>

</form>
