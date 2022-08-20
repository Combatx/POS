<x-modal size="modal-lg" id="modal-cek_penjualan" aria-labelledby="modal-detail">
    <x-slot name="title">Masukan ID Faktur</x-slot>
    <div class="form-group">
        <label for="id_faktur">ID Faktur</label>
        <input type="text" class="form-control" id="id_faktur">
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="cekpenjualan()">Simpan</button>
    </x-slot>
</x-modal>
