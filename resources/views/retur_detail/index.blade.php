@extends('layouts.app')

@section('title', 'Retur')

@push('css')
    <style>
        .tampil-bayar {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }

        .tampil-terbilang {
            padding: 10px;
            background: #f0f0f0;

        }

        .table-retur tbody tr:last-child {
            display: none;
        }

        .tepi {
            margin-left: 8px;
        }

        .judul {
            font-weight: normal;
            font-size: 20px;
        }

        .titik2 {
            padding-left: 10px;
        }

        @media(max-width: 768px) {
            .tampil-bayar {
                font-size: 3em;
                height: 70px;
                padding-top: 5px;

            }
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('retur.index') }}">Retur</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>

                <h2>{{ $id_retur }}</h2>
                <x-table class="table-retur" id="table-retur">
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="15%">Jumlah</th>
                        {{-- <th>Diskon</th> --}}
                        {{-- <th width="10%">Dikirim</th> --}}
                        <th>Sub Total</th>
                        {{-- <th width="15%"><i class="fa fa-cog"></i></th> --}}
                    </x-slot>
                </x-table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>

                    <div class="col-lg-4">
                        <form action="{{ route('retur.store') }}" class="form-retur" method="post">
                            @csrf
                            <input type="hidden" name="id_retur" value="{{ $id_retur }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="total_lama" id="total_lama" value="{{ $total_lama }}">
                            <input type="hidden" name="kembalian" id="kembalian">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-3 control-label">Total</label>
                                <div class="col-lg-9">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="total_lamarp" class="col-lg-3 control-label">Total Lama</label>
                                <div class="col-lg-9">
                                    <input type="text" id="total_lamarp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kembalianrp" class="col-lg-3 control-label">Kembalian</label>
                                <div class="col-lg-9">
                                    <input type="text" id="kembalianrp" class="form-control" readonly>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <label for="diskon" class="col-lg-3 control-label">Diskon</label>
                                <div class="col-lg-9">
                                    <input type="text" name="diskon" id="diskon" class="form-control"
                                        value="{{ $retur->diskon ?? 0 }}">
                                </div>
                            </div> --}}

                            <div class="form-group row">
                                <label for="keterangan" class="col-lg-3">Keterangan</label>
                                <div class="col-lg-9">
                                    <textarea class="form-control" id="keterangan" rows="3" name="keterangan"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <x-slot name="footer">
                    <button type="button" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i
                            class="fa fa-floppy-o"> Simpan Transaksi</i></button>
                </x-slot>
            </x-card>
        </div>
    </div>

@endsection


@includeIf('includes.datatables')
@includeIf('includes.jquery-mask')
@includeIf('includes.sweetalert2')

@push('script')
    <script>
        let table, table3;
        let modalpelanggan = '#form-pelanggan';

        $(function() {
            $('body').addClass('sidebar-closed sidebar-collapse');

            $('#kode_barang').focus();

            table = $('.table-retur').DataTable({
                    processing: true,
                    autoWidth: false,
                    "order": [
                        [0, "asc"]
                    ],
                    ajax: {
                        url: '{{ route('retur_detail.data', $id_retur) }}',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            searchable: false,
                        },
                        {
                            data: 'kode_barang',
                        },
                        {
                            data: 'nama_barang',
                        },
                        {
                            data: 'harga_jual',
                        },
                        {
                            data: 'jumlah',
                        },
                        // {
                        //     data: 'dikirim',
                        // },
                        {
                            data: 'subtotal',
                            searchable: false,
                            sortable: false
                        },
                        // {
                        //     data: 'aksi',
                        //     searchable: false,
                        //     sortable: false
                        // },
                    ],
                    dom: 'Brt',
                    bSort: false,
                })
                .on('draw.dt', function() {
                    loadForm();
                });


            $(document).on('input', '.quantity', function() {
                let id = $(this).data('id');
                let jumlah = parseInt($(this).val());

                if (jumlah < 1) {
                    $(this).val(1);
                    alert('Jumlah tidak boleh kurang dari 1');
                    return;
                }
                if (jumlah > 10000) {
                    alert('Jumlah tidak boleh lebih dari 10000');
                    $(this).val(1000);
                    return;
                }

                $.post(`{{ url('/retur_detail') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'jumlah': jumlah,
                    })
                    .done(response => {
                        // $(this).on('mouseout', function() {
                        if (response.type == 'error') {
                            sweetalertku(response.message, response.type, response.type);
                        }
                        table.ajax.reload(() => loadForm());
                        // })
                    }).fail(errors => {
                        alert('Tidak dapat menyimpan data?');
                        return;
                    });
            });

            // $(document).on('input', '#diskon', function() {
            //     if ($(this).val() == "") {
            //         $(this).val().select();

            //     }

            //     loadForm($(this).cleanVal());
            // });

            $('.btn-simpan').on('click', function() {
                $('.form-retur').submit();
            });
            inputMask();
        });


        // function cektable() {
        //     x = document.getElementById("table-retur").rows.length;
        //     for (i = 1; i < x; i++) {
        //         var tr = document.getElementsByTagName("tr")[i];
        //         var tdl = tr.getElementsByTagName("td").length;


        //         var td = tr.getElementsByTagName("td")[5];
        //         if (td.innerHTML.includes("Ya") && document.getElementById("id_pelanggan").value == 1) {
        //             alert('Pilih Pelanggan Terlebih Dahulu Untuk Pengiriman!!');
        //             return false;
        //             //break;
        //         }
        //     }
        // }

        function showAlert(message, type) {
            let title = '';
            switch (type) {
                case 'success':
                    title = 'Success';
                    break;
                case 'danger':
                    title = 'Failed';
                    break;
                default:
                    break;
            }

            $(document).Toasts('create', {
                class: `bg-${type}`,
                title: title,
                body: message
            });
            setTimeout(() => {
                $('.toasts-top-right').remove();
            }, 3000);
        }



        function inputMask() {
            $('#diskon').mask('#.##0', {
                reverse: true
            });
            $('#diterima').mask('#.##0', {
                reverse: true
            });
        }

        $('#diterima').on('input', function() {
            if ($(this).val() == "") {
                $(this).val().select();
            }

            loadForm();
        }).focus(function() {
            $(this).select();
        });


        function deleteData(url) {
            if (confirm('Apakah anda yakin ingin menghapus data?')) {
                // event.preventDefault();
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload(() => loadForm());
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        }

        function loadForm() {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/retur_detail/loadform') }}/${$('.total').text()}/${$('#total_lama').val()}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp);
                    // $('#bayarrp').val('Rp. ' + response.bayarrp);
                    // $('#bayar').val(response.bayar);
                    $('#total_lamarp').val('Rp. ' + response.total_lamarp);
                    $('#kembalianrp').val('Rp. ' + response.kembalianrp);
                    $('#kembalian').val(response.kembalian);
                    $('.tampil-bayar').text('Total: Rp. ' + response.totalrp);
                    $('.tampil-terbilang').text(response.terbilang);

                    // $('#kembali').val('Rp.' + response.kembalirp);
                    if ($('#kembalian').val() != 0) {
                        $('.tampil-bayar').text('Kembalian Rp. ' + response.kembalianrp);
                        $('.tampil-terbilang').text(response.kembalian_terbilang);
                    }
                })
                .fail(errors => {
                    alert('Tidak dapat menampikan data');
                    return;
                })
        }

        function sweetalertku(message, type, title) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
                confirmButtonText: 'OK'
            })
        }
    </script>
@endpush
