<form method="POST" action="{{ route('reset.update', Auth::user()->id) }}" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="row tepi">
        <div class="col-lg-12">
            <h2 style="font-weight: 600">Password</h2>
            <h5 style="font-weight: 500">Masukan password akunmu saat ini untuk mengganti password yang baru!</h5>
        </div>
    </div>
    </div>
    <div class="garis"></div>

    <div class="row" style="margin-bottom: 30px;">
        <div class="col-lg-6">
            <div style="width: 70%" class="tepi">
                <p class="font-weight-bold">Password Lama</p>
            </div>

        </div>
        <div class="col-lg-6">
            <div class="form-group" style="width: 85%">
                <input type="password" name="password_lama"
                    class="form-control @error('password_lama') is-invalid @enderror" id="password_lama"
                    placeholder="Password Lama" value="{{ old('password_lama') }}">
                @error('password_lama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>


    <div class="row" style="margin-top: 30px; margin-bottom: 30px;">
        <div class="col-lg-6">
            <div style="width: 70%" class="tepi">
                <p class="font-weight-bold">Password Baru</p>
            </div>

        </div>
        <div class="col-lg-6">
            <div class="form-group" style="width: 85%">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    id="password" placeholder="Password Baru" value="{{ old('password') }}">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 30px;">
        <div class="col-lg-6">
            <div style="width: 70%" class="tepi">
                <p class="font-weight-bold">Konfirmasi Password</p>
            </div>

        </div>
        <div class="col-lg-6">
            <div class="form-group" style="width: 85%">
                <input type="password" name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation"
                    placeholder="Konfirmasi Password" value="{{ old('password_confirmation') }}">
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
    <div class=" mt-5 tepi d-flex justify-content-start">
        <button class="btn btn-primary">Update Password</button>
    </div>

</form>
