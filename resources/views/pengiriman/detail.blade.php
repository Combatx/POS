<x-modal size="modal-xl" id="modal-detail" data-backdrop="static" data-keyboard="false" aria-labelledby="modal-detail">

    <x-slot name="title">Detail Pengiriman</x-slot>

    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <span class="tepi-detail">ID Pengiriman</span>
                <div style="padding-left:60px;"> : </div>
                <div class="titik2 id-pengiriman"> </div>
            </div>
            <div class="row">
                <span class="tepi-detail">ID Faktur</span>
                <div style="padding-left:95px;"> : </div>
                <div class="titik2 id-faktur"> </div>
            </div>
            <div class="row">
                <span class="tepi-detail">Tanggal Transaksi</span>
                <div style="padding-left:37px;"> : </div>
                <div class="titik2 tanggal-transaksi">Selasa, 24 Juni 2022</div>
            </div>
            <div class="row">
                <span class="tepi-detail">Status</span>
                <div style="padding-left:115px;"> : </div>
                <div class="titik2 status-now"></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <span class="tepi-detail">Pembeli</span>
                <div style="padding-left:132px;"> : </div>
                <div class="titik2 pembeli"></div>
            </div>
            <div class="row">
                <span class="tepi-detail">Penerima</span>
                <div style="padding-left:122px;"> : </div>
                <div class="titik2 penerima"></div>
            </div>
            <div class="row">
                <span class="tepi-detail">Petugas Pengirim</span>
                <div style="padding-left:70px;"> : </div>
                <div class="titik2 petugas_pengirim"></div>
            </div>
            <div class="row">
                <span class="tepi-detail">Petugas Update Status</span>
                <div style="padding-left:37px;"> : </div>
                <div class="titik2 petugas_update"></div>
            </div>
            <div class="row">
                <span class="tepi-detail">Tanggal Update Status</span>
                <div style="padding-left:37px;"> : </div>
                <div class="titik2 tanggal_update"></div>
            </div>
        </div>
    </div>

    <x-table class="table-detail">
        <x-slot name="thead">
            <th width="5%">No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Dikirim</th>
            <th>Subtotal</th>
        </x-slot>
    </x-table>
</x-modal>
