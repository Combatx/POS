<x-modal size="modal-lg" id="modal-pelanggan" data-backdrop="static" data-keyboard="false"
    aria-labelledby="modal-pelanggan">

    <x-slot name="title">Pilih Pelanggan</x-slot>

    <div class="row">
        <div class="col-lg-6">
            <div class="d-flex justify-content-start">
                <button type="button" onclick="addForm(`{{ route('pelanggan.store') }}`)"
                    class="btn refresh-pelanggan text-white" style="background-color: blue;"><i
                        class="fas fa-plus-circle"></i> Tambah
                </button>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="d-flex justify-content-end">
                <button type="button" onclick="refreshpelanggan()" class="btn refresh-pelanggan text-white"
                    style="background-color: yellowgreen"><i class="fas fa-sync"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    <br>
    <br>
    <x-table class="table-pelanggan">
        <x-slot name="thead">
            <th width="5%">No</th>
            <th>Nama</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th><i class="fa fa-cog"></i></th>
        </x-slot>

        @foreach ($pelanggan as $key => $item)
            <tr>
                <td width="5%">{{ $key + 1 }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->telepon }}</td>
                <td>{{ $item->alamat }}</td>
                <td>
                    <a href="{{ route('pembelian.create', $item->id_pelanggan) }}" class="btn btn-primary btn-xs">
                        <i class="fa fa-check-circle">
                            Pilih
                        </i>
                    </a>
                </td>
            </tr>
        @endforeach
    </x-table>
</x-modal>
