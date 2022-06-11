<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <title>Login</title>
</head>

<body>


    <div class="container">
        <div class="row content rounded">
            <div class="col-md-6 mb-3 mt-3">
                <img src="{{ asset('img/login.svg') }}" alt="" class="img-fluid">
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="text-center">
                        <img width="100px" src="{{ asset('img/user.png') }}" alt="">
                        <h3 class="sigin-text mb-3">Welcome</h3>
                    </div>

                    @if (session()->has('loginError'))
                        <div class="alert alert-danger alert-dismissble fade show" role="alert">
                            {{ session('loginError') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('login.auth') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="name" id="username"
                                class="form-control mb-2 @error('name') is-invalid @enderror" required
                                value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password"
                                class="form-control mb-2 @error('password') is-invalid @enderror" required
                                value="{{ old('password') }}">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class=" text-center">

                            <input type="submit" name="login" value="Login" class="btn button mt-2">
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

</body>

</html>
