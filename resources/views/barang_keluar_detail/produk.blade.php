<x-modal id="modal-produk" aria-labelledby="modal-produk">
    <x-slot name="title">Pilih Produk</x-slot>

    <x-table class="table-produk">
        <x-slot name="thead">
            <th width="5%">No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Harga</th>
            <th><i class="fa fa-cog"></i></th>
        </x-slot>

        @foreach ($produk as $key => $item)
            <tr>
                <td width="5%">{{ $key + 1 }}</td>
                <td><span class="badge badge-primary">{{ $item->kode_barang }}</span></td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->harga_beli }}</td>
                <td>
                    <a href="#" class="btn btn-primary btn-xs"
                        onclick="pilihProduk('{{ $item->id_produk }}', '{{ $item->kode_barang }}')">
                        <i class="fa fa-check-circle">
                            Pilih
                        </i>
                    </a>
                </td>
            </tr>
        @endforeach
    </x-table>
</x-modal>
