<x-modal size="modal-lg" id="modal-cek_nama" aria-labelledby="modal-detail">
    <x-slot name="title">Masukan Nama Petugas Pengiriman</x-slot>
    <div class="form-group">
        <label for="id_faktur">Nama</label>
        <input type="text" class="form-control" id="nama_petugas">
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="simpan_pengirim()">Simpan</button>
    </x-slot>
</x-modal>
