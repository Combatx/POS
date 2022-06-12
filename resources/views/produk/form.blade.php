<x-modal size="modal-lg" data-backdrop="static" data-keyboard="false">

    <x-slot name="title">
        Tambah
    </x-slot>

    @method('post')

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="kode_barang">Kode Barang</label>
                <input type="text" name="kode_barang" id="kode_barang" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select name="id_kategori" id="satuan" class="custom-select">
                    <option disabled selected>Pilih Salah Satu</option>
                    @foreach ($kategori as $key => $item)
                        <option value="{{ $key }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="satuan">Satuan</label>
                <select name="id_satuan" id="satuan" class="custom-select">
                    <option disabled selected>Pilih Salah Satu</option>
                    @foreach ($satuan as $key => $item)
                        <option value="{{ $key }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="harga_beli">Harga Beli</label>
                <input type="number" name="harga_beli" id="harga_beli" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="harga_jual">Harga Jual</label>
                <input type="number" name="harga_jual" id="harga_jual" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="diskon">Diskon</label>
                <input type="number" name="diskon" id="diskon" class="form-control">
            </div>
        </div>

    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="submitForm(this.form)">Simpan</button>
    </x-slot>
</x-modal>
