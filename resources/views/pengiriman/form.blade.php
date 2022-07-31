<x-modal size="modal-lg" data-backdrop="static" data-keyboard="false">

    <x-slot name="title">
        Tambah
    </x-slot>

    @method('put')

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="tanggal_data">Tanggal Data</label>
                <input disabled type="text" name="tanggal_data" id="tanggal_data" class="form-control">
            </div>
            <div class="form-group">
                <label for="id_pengiriman">ID Pengiriman</label>
                <input disabled type="text" name="id_pengiriman" id="id_pengiriman" class="form-control kategori">
            </div>
            <div class="form-group">
                <label for="id_faktur">ID Faktur</label>
                <input disabled type="text" name="id_faktur" id="id_faktur" class="form-control kategori">
            </div>
            <div class="form-group">
                <label for="pembeli">Pembeli</label>
                <input disabled type="text" name="pembeli" id="pembeli" class="form-control kategori">
            </div>
            <div class="form-group">
                <label for="penerima">Penerima</label>
                <input type="text" name="penerima" id="penerima" class="form-control kategori" required>
            </div>
            <div class="form-group">
                <label for="petugas_pengiriman">Petugas Pengirim Barang</label>
                <input type="text" name="petugas_pengiriman" id="petugas_pengiriman" class="form-control kategori"
                    required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option disabled selected>Silahkan Pilih Status Pengiriman</option>
                    <option disabled value="diantar">Diantar</option>
                    <option value="success">Success</option>
                </select>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="submitForm(this.form)">Simpan</button>
    </x-slot>
</x-modal>
