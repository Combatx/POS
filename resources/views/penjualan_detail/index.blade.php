@extends('layouts.app')

@section('title', 'Transaksi')

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

        .table-penjualan tbody tr:last-child {
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
    <li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Transaksi</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>

                <div class="row tepi">
                    <div class="col-lg-6">
                        <div class="row">
                            <i class="fas fa-id-card fa-2x">
                            </i>
                            <span class="pl-4 judul">Kasir</span>
                            <div style="padding-left:80px;"> : </div>
                            <div class="titik2"> {{ auth()->user()->name }}</div>
                        </div>
                        <br>
                        <div class="row">
                            <i class="fas fa-users fa-2x"></i>
                            <span class="pl-4 judul">Pelanggan</span>
                            <div style="padding-left:30px;"> :</div>
                            <p class=" namapelanggan titik2">Umum</p>
                            <button class="btn btn-primary ml-4" onclick="caripelanggan()"><i class="fas fa-search"></i>
                                Cari Pelanggan</button>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <i class="fas fa-shopping-basket fa-2x"></i>
                            <span class="pl-4 judul">No Faktur</span>
                            <div style="padding-left:80px;"> : </div>
                            <div class="titik2">{{ $id_penjualan }}</div>
                        </div>
                        {{-- <div class="row">
                            <i class="fas fa-clock fa-2x"></i>
                            <span class="pl-4 judul">Waktu</span>
                            <div style="padding-left:80px;"> : </div>
                            <div class="titik2">{{ $id_penjualan }}</div>
                        </div> --}}
                    </div>
                </div>
                <br>

                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_barang" class="col-lg-2">Kode Produk</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                                <input type="text" class="form-control" name="kode_barang" id="kode_barang"
                                    oninput="cekkodebarang()">
                                <input type="hidden" name="cekkondisi" id="cekkondisi">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i
                                            class="fas fa-search"></i></button>
                                </span>
                            </div>
                        </div>

                    </div>
                </form>

                <x-table class="table-penjualan" id="table-penjualan">
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="15%">Jumlah</th>
                        {{-- <th>Diskon</th> --}}
                        <th width="10%">Dikirim</th>
                        <th>Sub Total</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </x-slot>
                </x-table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>

                    <div class="col-lg-4">
                        <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                            @csrf
                            <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">
                            <input type="hidden" name="id_pelanggan" id="id_pelanggan" value="1">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-3 control-label">Total</label>
                                <div class="col-lg-9">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="diskon" class="col-lg-3 control-label">Diskon</label>
                                <div class="col-lg-9">
                                    <input type="text" name="diskon" id="diskon" class="form-control"
                                        value="{{ $penjualan->diskon ?? 0 }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bayarrp" class="col-lg-3 control-label">Bayar</label>
                                <div class="col-lg-9">
                                    <input type="text" id="bayarrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diterima" class="col-lg-3 control-label">Diterima</label>
                                <div class="col-lg-9">
                                    <input type="text" id="diterima" class="form-control" name="diterima"
                                        value="{{ $penjualan->diterima ?? 0 }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kembali" class="col-lg-3 control-label">Kembalian</label>
                                <div class="col-lg-9">
                                    <input type="text" id="kembali" name="kembali" class="form-control"
                                        value="0" readonly>
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

    @includeIf('penjualan_detail.produk')
    @includeIf('penjualan_detail.pelanggan')
    @includeIf('penjualan_detail.form_pelanggan')
@endsection


@includeIf('includes.datatables')
@includeIf('includes.jquery-mask')

@push('script')
    <script>
        let table, table2, table3;
        let modalpelanggan = '#form-pelanggan';

        $(function() {
            $('body').addClass('sidebar-closed sidebar-collapse');

            $('#kode_barang').focus();

            table = $('.table-penjualan').DataTable({
                    processing: true,
                    autoWidth: false,
                    "order": [
                        [0, "asc"]
                    ],
                    ajax: {
                        url: '{{ route('transaksi.data', $id_penjualan) }}',
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
                        {
                            data: 'dikirim',
                        },
                        {
                            data: 'subtotal',
                            searchable: false,
                            sortable: false
                        },
                        {
                            data: 'aksi',
                            searchable: false,
                            sortable: false
                        },
                    ],
                    dom: 'Brt',
                    bSort: false,
                })
                .on('draw.dt', function() {
                    loadForm($('#diskon').cleanVal());
                    setTimeout(() => {
                        $('#diterima').trigger('input');
                    }, 300);
                });


            table2 = $('.table-produk').DataTable();

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

                $.post(`{{ url('/transaksi') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'jumlah': jumlah,
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            table.ajax.reload(() => loadForm($('#diskon').cleanVal(), $(
                                '#diterima').cleanVal()));
                        })
                    }).fail(errors => {
                        alert('Tidak dapat menyimpan data?');
                        return;
                    });
            });

            $(document).on('input', '#diskon', function() {
                if ($(this).val() == "") {
                    $(this).val().select();

                }

                loadForm($(this).cleanVal());
            });

            $('.btn-simpan').on('click', function() {
                if (parseInt($('#diterima').cleanVal()) < $('#bayar').val()) {
                    alert('Pembayaran kurang');
                    return;
                }

                if (cektable() == false) {
                    return;
                } else {
                    $('.form-penjualan').submit();
                }
            });
            inputMask();
        });


        function cektable() {
            x = document.getElementById("table-penjualan").rows.length;
            for (i = 1; i < x; i++) {
                var tr = document.getElementsByTagName("tr")[i];
                var tdl = tr.getElementsByTagName("td").length;


                var td = tr.getElementsByTagName("td")[5];
                if (td.innerHTML.includes("Ya") && document.getElementById("id_pelanggan").value == 1) {
                    alert('Pilih Pelanggan Terlebih Dahulu Untuk Pengiriman!!');
                    return false;
                    //break;
                }
            }
        }

        table3 = $('.table-pelanggan').DataTable({
            processing: true,
            autoWidth: false,
            "order": [
                [0, "asc"]
            ],
            ajax: {
                url: '{{ route('transaksi.pelanggan') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                },
                {
                    data: 'nama',
                },
                {
                    data: 'telepon',
                },
                {
                    data: 'alamat',
                },
                {
                    data: 'action',
                    searchable: false,
                    sortable: false
                },
            ],
        });


        function caripelanggan() {
            $('#modal-pelanggan').modal('show');
        }

        function getpelanggan(nama, kode) {
            $('.namapelanggan').text(nama);
            $('#id_pelanggan').val(kode);
            $('#modal-pelanggan').modal('hide');

        }

        function refreshpelanggan() {
            table3.ajax.reload();
        }

        function addForm(url, title = 'Tambah') {

            $('#modal-pelanggan').modal('hide');
            $(modalpelanggan).modal('show');
            $(`${modalpelanggan} .modal-title`).text(title);
            $(`${modalpelanggan} form`).attr('action', url);
            $(`${modalpelanggan} [name=_method]`).val('post');
            resetForm(`${modalpelanggan} form`);
        }

        function submitForm(originalForm) {
            $.post({
                    url: $(originalForm).attr('action'),
                    data: new FormData(originalForm),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                })
                .done(response => {
                    $(modalpelanggan).modal('hide');
                    table3.ajax.reload();
                    $('#modal-pelanggan').modal('show');
                    showAlert(response.message, 'success');
                    table.ajax.reload();
                })
                .fail(errors => {
                    if (errors.status === 422) {
                        loopErrors(errors.responseJSON.errors);
                        showAlert(errors.responseJSON.errors.message, 'danger');
                        return;
                    }
                });
        }

        function deletePelanggan(url) {
            if (confirm('Yakin data akan di hapus?')) {
                $.post(url, {
                        '_method': 'delete'
                    })
                    .done(response => {
                        showAlert(response.message, 'success');
                        table.ajax.reload();
                    })
                    .fail(errors => {
                        showAlert('Tidak dapat menghapus data', 'danger');
                        return;
                    });
            }
        }

        function resetForm(selector) {
            $(selector)[0].reset();
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        function loopErrors(errors) {
            $('.invalid-feedback').remove();
            $('.is-invalid').removeClass('is-invalid');
            if (errors == undefined) {
                return;
            }

            for (error in errors) {
                $(`[name=${error}]`).addClass('is-invalid');

                $(`<span class="error invalid-feedback"> ${errors[error][0]}</span>`)
                    .insertAfter($(`[name=${error}]`));

            }
        }

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

        function batalpelanggan() {
            $(modalpelanggan).modal('hide');
            $('#modal-pelanggan').modal('show');
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

            loadForm($('#diskon').cleanVal(), $(this).cleanVal());
        }).focus(function() {
            $(this).select();
        });

        function tampilProduk(url) {
            $('#modal-produk').modal('show');
        }



        function hideProduk() {
            $('#modal-produk').modal('hide');
        }

        function pilihProduk(id, kode) {
            $('#id_produk').val(id);
            $('#kode_produk').val(kode);
            $('#cekkondisi').val('pilihbarang');
            hideProduk();
            tambahProduk();
        }

        function cekkodebarang() {
            $('#id_produk').val(null);
            $('#cekkondisi').val('cekkode');
            if ($('#kode_barang').val() == '') {
                return;
            } else {
                tambahProduk();
            }
        }

        function tambahProduk() {
            $.post('{{ route('transaksi.store') }}', $('.form-produk').serialize())
                .done(response => {
                    $('#kode_barang').focus();
                    $('#kode_barang').val('');
                    table.ajax.reload(() => loadForm($('#diskon').cleanVal()));
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        }

        // function tampilMember(url) {
        //     $('#modal-member').modal('show');
        // }

        // function pilihMember(id, kode) {
        //     $('#id_member').val(id);
        //     $('#kode_member').val(kode);
        //     $('#diskon').val('{{ $diskon }}');
        //     loadForm($('#diskon').val());
        //     $('.btn-member').on('click', function() {
        //         $('#diterima').val(0).focus().select();
        //     });

        //     hideMember();
        // }

        // function hideMember() {
        //     $('#modal-member').modal('hide');
        // }

        function deleteData(url) {
            if (confirm('Apakah anda yakin ingin menghapus data?')) {
                // event.preventDefault();
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload(() => loadForm($('#diskon').cleanVal()));
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        }

        function loadForm(diskon = 0, diterima = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/transaksi/loadform') }}/${diskon}/${$('.total').text()}/${diterima}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp);
                    $('#bayarrp').val('Rp. ' + response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('Bayar: Rp. ' + response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang);

                    $('#kembali').val('Rp.' + response.kembalirp);
                    if ($('#diterima').cleanVal() != 0) {
                        $('.tampil-bayar').text('Kembali Rp. ' + response.kembalirp);
                        $('.tampil-terbilang').text(response.kembali_terbilang);
                    }
                })
                .fail(errors => {
                    alert('Tidak dapat menampikan data');
                    return;
                })
        }

        function pengiriman(go, url) {
            if ($('.dikirim-' + parseInt(go)).is(":checked")) {

                $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        method: 'POST',
                        data: {
                            dikirim: 'ya'
                        },
                    }).done(response => {
                        $('.dikirim-' + parseInt(go)).prop("checked", false);
                        showAlert(response.message, 'success');
                        table.ajax.reload();
                    })
                    .fail(errors => {
                        if (errors.dikirim === 422) {
                            loopErrors(errors.responseJSON.errors);
                            showAlert(errors.responseJSON.errors.message, 'danger');
                            return;
                        }
                    });
            } else if (!$('.dikirim-' + parseInt(go)).is(":checked")) {

                $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        method: 'POST',
                        data: {
                            dikirim: 'tidak'
                        },
                    }).done(response => {
                        $('.dikirim-' + parseInt(go)).prop("checked");
                        showAlert(response.message, 'success');
                        table.ajax.reload();
                    })
                    .fail(errors => {
                        if (errors.dikirim === 422) {
                            loopErrors(errors.responseJSON.errors);
                            showAlert(errors.responseJSON.errors.message, 'danger');
                            return;
                        }
                    });
            }
        }
    </script>
@endpush
