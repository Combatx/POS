@extends('layouts.app')

@section('title', 'Pengiriman')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#">Pengiriman</a></li>
@endsection

@push('css')
    <style>
        .tepi-detail {
            margin-left: 10px;
        }

        .titik2 {
            margin-left: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    {{-- <button onclick="addForm(`{{ route('kategori.store') }}`)" class="btn btn-primary"><i
                            class="fas fa-plus-circle"></i> Tambah</button> --}}
                </x-slot>
                <input type="hidden" id="kodekirim">
                <x-table class="table-pengiriman">
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th>Tanggal Data</th>
                        <th>Id Pengiriman</th>
                        <th>Id Faktur</th>
                        <th>Status</th>
                        <th>Penerima</th>
                        <th>Pengirim</th>
                        <th>Tanggal Status</th>
                        <th>Petugas Status</th>
                        <th width="10%"><i class="fas fa-cog"></i></th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    @includeIf('pengiriman.form')
    @includeIf('pengiriman.detail')
    @includeIf('pengiriman.cek_nama')

@endsection

@includeIf('includes.datatables')
@includeIf('includes.sweetalert2')

@push('script')
    <script>
        let modal = '#modal-form';
        let modaldetail = '#modal-detail';
        let table;
        let table_detail;

        table = $('.table-pengiriman').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('pengiriman.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false,
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'id_pengiriman',
                },
                {
                    data: 'id_penjualan'
                },
                {
                    data: 'status'
                },
                {
                    data: 'penerima',
                    "defaultContent": "Kosong"
                },
                {
                    data: 'petugas_pengiriman',
                    "defaultContent": "Kosong"
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'petugas_status',
                    "defaultContent": "Kosong"
                },
                {
                    data: 'action',
                    searchable: false,
                    sortable: false,
                },
            ]
        });

        function addForm(url, title = 'Tambah') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Edit') {
            $.get(url)
                .done(response => {
                    console.log(response);
                    $(modal).modal('show');
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} form`).attr('action', url);
                    $(`${modal} [name=_method]`).val('put');

                    resetForm(`${modal} form`);
                    loopForm(response.data);
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
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
                    $(modal).modal('hide');
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

        function deleteData(url) {
            Swal.fire({
                title: 'Delete',
                text: "Apakah Kamu Ingin Menghapus Data Ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
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
            });
        }

        function resetForm(selector) {
            $(selector)[0].reset();
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        function loopForm(originalForm) {
            for (field in originalForm) {
                if ($(`[name=${field}]`).attr('type') != 'file') {
                    $(`[name=${field}]`).val(originalForm[field]);
                }
            }
            //$('select').trigger('change');
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

        table_detail = $('.table-detail').DataTable({
            processing: true,
            autoWidth: false,
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false,
                },
                {
                    data: 'kode_barang'
                },
                {
                    data: 'nama_barang',
                },
                {
                    data: 'harga'
                },
                {
                    data: 'jumlah'
                },
                {
                    data: 'dikirim',
                },
                {
                    data: 'subtotal',
                },
            ]
        });

        function detail(bio, detail) {
            console.log(bio, detail);
            $(modaldetail).modal('show');
            $('.status-ku').remove();
            getbio(bio);
            table_detail.ajax.url(detail);
            table_detail.ajax.reload();
        }

        function getbio(url) {
            $.get(url)
                .done(response => {
                    $('.id-pengiriman').text(response.pengiriman['kode_pengiriman'])
                    $('.id-faktur').text(response.pengiriman['kode_faktur'])
                    $('.tanggal-transaksi').text(response.pengiriman['tanggal_transaksi'])
                    $('.status-now').append(response.pengiriman['status'])
                    $('.pembeli').text(response.pengiriman['pembeli'])
                    $('.penerima').text(response.pengiriman['penerima'])
                    $('.petugas_update').text(response.pengiriman['petugas_update'])
                    $('.petugas_pengirim').text(response.pengiriman['petugas_pengirim'])
                    $('.tanggal_update').text(response.pengiriman['tanggal_update'])
                })
                .fail(errors => {
                    sweetalertku('Tidak dapat menampikan data', 'error', 'error');
                    return;
                });

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

        function ceknama(kode) {
            $.get(`{{ url('/pengiriman/ceknama') }}/${kode} `)
                .done(response => {
                    if (response.cek == 'ada') {
                        location.href = response.hasil;
                    } else if (response.cek == 'tidak') {
                        $('#kodekirim').val(kode);
                        $('#modal-cek_nama').modal('show');
                    }
                })
                .fail(errors => {
                    sweetalertku('Terjadi Kesalahan', 'error', 'error');
                })
        }

        function simpan_pengirim() {
            let kode = $('#kodekirim').val();
            if ($('#nama_petugas').val() == '') {
                sweetalertku('Field Nama Tidak Boleh Kosong !!', 'error', 'error');
                return;
            }

            $.post(`{{ url('/pengiriman/simpankirim') }}/${kode}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'petugas': $('#nama_petugas').val(),
                })
                .done(response => {
                    // $(this).on('mouseout', function() {
                    if (response.cek == 'ada') {
                        location.href = response.hasil;
                    } else if (response.cek == 'tidak') {
                        $('#kodekirim').val(kode);
                        $('#modal-cek_nama').modal('show');
                    }
                }).fail(errors => {
                    sweetalertku('Terjadi Kesalahan', 'error', 'error');
                    return;
                });
        }

        $(".kategori").on("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                submitForm(this.form);
            }
        });

        function sweetalertku(message, title, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
                confirmButtonText: 'OK'
            })
        }
    </script>
@endpush
