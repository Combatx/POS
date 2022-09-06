@extends('layouts.app')

@section('title', 'Penjualan')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card>
                <div class="alert alert-success alert-dismissible">
                    <i class="fa fa-check icon"></i>
                    Data Transaksi telah selesai.
                </div>
                <x-slot name="footer">
                    <button class="btn btn-success" onclick="pilihnota()">Cetak Nota</button>
                    <a href="{{ route('transaksi.baru') }}" class="btn btn-primary btn-flat">Transaksi Baru</a>
                </x-slot>
            </x-card>
        </div>
    </div>

    @includeIf('penjualan.pilih_nota')
@endsection

@push('script')
    <script>
        function pilihnota() {
            $('#modal-pilih_nota').modal('show');
        }

        function notaKecil(url, title) {
            popupCenter(url, title, 825, 700);
        }

        function notaBesar(url, title) {
            popupCenter(url, title, 1100, 875);
        }

        function popupCenter(url, title, w, h) {
            const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
            const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

            const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document
                .documentElement.clientWidth : screen.width;
            const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document
                .documentElement.clientHeight : screen.height;

            const systemZoom = width / window.screen.availWidth;
            const left = (width - w) / 2 / systemZoom + dualScreenLeft
            const top = (height - h) / 2 / systemZoom + dualScreenTop
            const newWindow = window.open(url, title,
                `
                scrollbars=yes,
                width=${w / systemZoom}, 
                height=${h / systemZoom}, 
                top=${top}, 
                left=${left}
                `
            )

            if (window.focus) newWindow.focus();
        }
    </script>
@endpush
