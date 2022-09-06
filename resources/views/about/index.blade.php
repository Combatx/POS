@extends('layouts.app')

@section('title', 'About')

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
            border: 2px solid black;
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
    <li class="breadcrumb-item"><a href="#">About</a></li>
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
                <a href="{{ route('profil.index') }}" class="nonaktif">Profil Setting</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('reset.index') }}" class="nonaktif">Ubah Password</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('about.index') }}" class="aktif">About Setting</a>
            </li>
        </ul>

        <div class="garis"></div>

        {{-- content --}}
        <div>
            <h2>fsdfsdfsdf</h2>
        </div>
    </div>
@endsection
