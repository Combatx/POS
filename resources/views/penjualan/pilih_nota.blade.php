<x-modal size="modal-lg" id="modal-pilih_nota" aria-labelledby="modal-detail">
    <x-slot name="title">Silahkan Pilih Jenis Nota</x-slot>
    <button type="button" class="btn btn-warning btn-flat"
        onclick="notaKecil('{{ route('transaksi.nota_kecil') }}', 'Nota PDF')">Nota
        Kecil</button>

    <button type="button" class="btn btn-info btn-flat ml-3"
        onclick="notaBesar('{{ route('transaksi.nota_besar') }}', 'Nota Kecil')">Nota
        Besar</button>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    </x-slot>
</x-modal>
