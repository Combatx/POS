<x-modal size="modal-lg" id="modal-supplier" data-backdrop="static" data-keyboard="false"
    aria-labelledby="modal-supplier">

    <x-slot name="title">Pilih Supplier</x-slot>

    <x-table class="table-supplier">
        <x-slot name="thead">
            <th width="5%">No</th>
            <th>Nama</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th><i class="fa fa-cog"></i></th>
        </x-slot>

        @foreach ($supplier as $key => $item)
            <tr>
                <td width="5%">{{ $key + 1 }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->telepon }}</td>
                <td>{{ $item->alamat }}</td>
                <td>
                    <a href="{{ route('pembelian.create', $item->id_supplier) }}"
                        class="btn btn-primary btn-xs btn-flat">
                        <i class="fa fa-check-circle">
                            Pilih
                        </i>
                    </a>
                </td>
            </tr>
        @endforeach
    </x-table>
</x-modal>
