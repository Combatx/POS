<x-modal size="modal-lg" data-backdrop="static" data-keyboard="false">

    <x-slot name="title">
        Tambah
    </x-slot>

    @method('post')

    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="form-group">
                <label for="kode_barang">Kode Barang</label>
                <input type="text" name="kode_barang" id="kode_barang" class="form-control" required>
            </div> --}}


            <div class="form-group">
                <label for="kode_barang">Kode Barang</label>
                <input type="text" name="kode_barang" id="kode_barang" class="form-control">
            </div>
            <div class="cek_update_div">
                <input type="hidden" name="cek_kode" id="cek_kode">

                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="kode_otomatis" name="kode_otomatis"
                        onclick="cek_otomatis()">
                    <label class="custom-control-label" for="kode_otomatis">Kode Barang Otomatis</label>
                </div>
            </div>


            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select name="id_kategori" id="kategori" class="custom-select select2class kategoriform"
                    onclick="">
                    <option disabled selected>Pilih Salah Satu</option>
                    @foreach ($kategori as $key => $item)
                        <option value="{{ $key }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="satuan">Satuan</label>
                <select name="id_satuan" id="satuan" class="custom-select select2class satuanform">
                    <option disabled selected>Pilih Salah Satu</option>
                    @foreach ($satuan as $key => $item)
                        <option value="{{ $key }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="harga_beli">Harga Beli</label>
                <input type="text" name="harga_beli" id="harga_beli" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="harga_jual">Harga Jual</label>
                <input type="text" name="harga_jual" id="harga_jual" class="form-control produk" required>
            </div>
            {{-- <div class="form-group">
                <label for="diskon">Diskon</label>
                <input type="diskon" name="diskon" id="diskon" class="form-control produk" value="0">
            </div> --}}
        </div>

    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="submitForm(this.form)">Simpan</button>
    </x-slot>
</x-modal>
