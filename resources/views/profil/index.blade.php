@extends('layouts.app')

@section('title', 'Profil')

@push('css')
    <style>
        .card {
            border-radius: 30px;
            padding: 20px;
            /* box-shadow: 0; */

        }

        .aktif {
            margin-left: 25px;
            margin-right: 25px;
            padding: 8px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 50px;
            margin-left: 30px;
            margin-right: 30px;

            background-color: #007BFF;
            color: white;
            /* border: 2px solid #007BFF; */
        }

        .nonaktif {
            margin-left: 25px;
            margin-right: 25px;
            padding: 8px 50px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 10px;
            margin-left: 30px;
            margin-right: 30px;

            background-color: white;
            color: black;
            /* border: 2px solid #AAAAAA; */
        }


        .garis {
            margin-top: 30px;
            margin-bottom: 50px;
            border-top: 1px solid #aaaaaa;
        }

        .tepi {
            margin-left: 45px;
        }

        .circle {
            width: 81px;
            height: 81px;
            object-fit: cover;
            border-radius: 50%;
            /* border: 2px solid black; */
        }

        .tblremove {
            background-color: #E0E0E0;
        }

        .labelcustom {
            font-weight: 100;
        }
    </style>
@endpush
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#">Profil</a></li>
@endsection

@section('content')

    <div class="card">
        <ul class="nav" id="myTab" role="tablist">
            @if (auth()->user()->hasRole('admin'))
                <li class="nav-item" role="presentation">
                    <a href="{{ route('setting.index') }}" class="nonaktif">Application Setting</a>
                </li>
            @endif
            <li class="nav-item" role="presentation">
                <a href="{{ route('profil.index') }}" class="aktif">Profil Setting</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('reset.index') }}" class="nonaktif">Ubah Password</a>
            </li>
            {{-- <li class="nav-item" role="presentation">
                <a href="{{ route('about.index') }}" class="nonaktif">About Setting</a>
            </li> --}}
        </ul>

        <div class="garis"></div>

        {{-- content --}}
        <div>
            @includeIf('profil.form')
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {

            @if (session()->has('success'))
                showAlert('Profil Berhasil Di Edit !!', 'success');
            @endif
        });


        function previewname() {
            var test = document.getElementById("foto").files[0].name;
            // console.log(test);
            $('#upload-file-info').text(test);
            const image = document.querySelector('#foto');
            const imgPreview = document.querySelector('.img-preview');

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
            document.getElementById("cekubah").value = 'berubah';
            document.getElementById("deleteimg").value = 'berubah';
        }

        function deleteimage() {
            // imgPreview.style.display = 'block';
            document.querySelector('.img-preview').src = "{{ url('img/user1.png') }}";
            document.getElementById("deleteimg").value = 'delete';
        }


        function showAlert(message, type) {
            let title = '';
            switch (type) {
                case 'success':
                    title = 'Success';
                    break;
                case 'danger':
                    title = 'Failed';
                    break;
                default:
                    break;
            }

            $(document).Toasts('create', {
                class: `bg-${type}`,
                title: title,
                body: message
            });
            setTimeout(() => {
                $('.toasts-top-right').remove();
            }, 3000);
        }

        $(".satuan").on("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                submitForm(this.form);
            }
        });
    </script>
@endpush
