@extends('layouts.app')

@section('title')
    Laporan Lainya
@endsection


@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#">Laporan Lainya</a></li>
@endsection

@section('content')
    <!-- Main row -->
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                </x-slot>

                <div class="row">
                    <div class="col-lg-6">
                        <x-card>
                            <x-slot name="header">
                                <h3>Laporan Pembelian</h3>
                            </x-slot>
                            <h3 class="mb-3">Masukan Periode Terlebih Dahulu</h3>
                            <form action="{{ route('laporan.pembelian') }}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label for="tanggal_awal" class="col-lg-2 control-label">Tanggal Awal</label>
                                    <div class="col-lg-8">
                                        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tanggal_akhir" class="col-lg-2 control-label">Tanggal Akhir</label>
                                    <div class="col-lg-8">
                                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-warning">Cetak</button>
                                </div>
                            </form>
                        </x-card>
                    </div>
                    <div class="col-lg-6">
                        <x-card>
                            <x-slot name="header">
                                <h3>Laporan Penjualan</h3>
                            </x-slot>
                            <h3 class="mb-3">Masukan Periode Terlebih Dahulu</h3>
                            <form action="{{ route('laporan.penjualan') }}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label for="tanggal_awal" class="col-lg-2 control-label">Tanggal Awal</label>
                                    <div class="col-lg-8">
                                        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tanggal_akhir" class="col-lg-2 control-label">Tanggal Akhir</label>
                                    <div class="col-lg-8">
                                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-info">Cetak</button>
                                </div>
                            </form>
                        </x-card>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    @includeIf('includes.datetimepicker')
@endsection
