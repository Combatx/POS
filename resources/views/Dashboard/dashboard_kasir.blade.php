@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
@endsection


@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <h2 class="mb-3" style="font-weight: bold">Selaman Datang Di Toko Usaha Baru Pemangkat</h2>
                <p>Silahkan Mulai Transaksi</p>
                <a href="{{ route('transaksi.baru') }}" class="btn btn-primary"><i class="fas fa-cash-register"></i> Transaksi
                    Baru</a>

                <p
                    style="margin-top: 50px; font-style: italic; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif">
                    Kebahagiaan adalah produk sampingan dari upaya untuk
                    membuat
                    orang lain
                    bahagia." - Gretta Palmer</p>
            </div>
        </div>
    </div>
@endsection
