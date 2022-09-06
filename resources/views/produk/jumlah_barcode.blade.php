<x-modal size="modal-lg" id="modal-jumlah_barcode" aria-labelledby="modal-detail">
    <x-slot name="title">Masukan Jumlah Barcode Yang Ingin Di Cetak !!</x-slot>
    <div class="form-group">
        <label for="jumlah">Jumlah</label>
        <input type="number" class="form-control" id="jumlahku">
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="cetak_barcode()">Cetak</button>
    </x-slot>
</x-modal>
