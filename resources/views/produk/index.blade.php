@extends('layouts.app')

@section('title', 'Produk')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#">Produk</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>

                <div class="d-flex justify-content-start mb-3">
                    <div class="form-group ml-2">
                        <div class="row">
                            <label for="kategori2">Kategori</label>
                            <select name="kategori2" id="kategori2" class="custom-select ">
                                <option disabled selected>Pilih Salah Satu</option>
                                @foreach ($kategori as $key => $item)
                                    <option value="{{ $key }}"> {{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group ml-5">
                        <div class="row">
                            <label for="satuan2">Satuan</label>
                            <select name="satuan2" id="satuan2" class="custom-select">
                                <option disabled selected>Pilih Salah Satu</option>
                                @foreach ($satuan as $key => $item)
                                    <option value="{{ $key }}"> {{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row d-inline ml-5">
                        <br>
                        <button class="btn btn-primary" onclick="refresh_table()"><i class="fas fa-redo"></i> Reset</button>
                    </div>
                </div>

                <input type="hidden" name="kode" id="kodeku">
                <x-slot name="header">
                    <button onclick="addForm(`{{ route('produk.store') }}`)" class="btn btn-primary"><i
                            class="fas fa-plus-circle"></i> Tambah</button>
                </x-slot>
                <x-table>
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        {{-- <th>Diskon</th> --}}
                        <th>Stok</th>
                        <th width="10%"><i class="fas fa-cog"></i></th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    @includeIf('produk.form')
    @includeIf('produk.jumlah_barcode')

@endsection

@includeIf('includes.datatables')
@includeIf('includes.jquery-mask')
@includeIf('includes.select2')
@includeIf('includes.sweetalert2')

@push('script')
    <script>
        let modal = '#modal-form';
        let table;
        let cek_kode_update;

        $(document).ready(function() {
            $('.satuanform').select2();
            $('.kategoriform').select2();
            inputMask();
            if (!$('#kode_otomatis').is(":checked")) {
                $('#kode_barang').prop('disabled', true);
                // $('#kode_barang').prop('required', false);
                $('#cek_kode').val('otomatis');
            }

        });

        table = $('.table').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('produk.data') }}',
                data: function(d) {
                    d.kategori = $('[name=kategori2]').val();
                    d.satuan = $('[name=satuan2]').val();
                },
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false,
                },
                {
                    data: 'kode_barang'
                },
                {
                    data: 'nama_barang'
                },
                {
                    data: 'kategori'
                },
                {
                    data: 'satuan'
                },
                {
                    data: 'harga_beli'
                },
                {
                    data: 'harga_jual'
                },
                // {
                //     data: 'diskon'
                // },
                {
                    data: 'stok'
                },
                {
                    data: 'action',
                    searchable: false,
                    sortable: false,
                },
            ]
        });


        function inputMask() {
            $('#harga_beli').mask('#.##0', {
                reverse: true
            });
            $('#harga_jual').mask('#.##0', {
                reverse: true
            });
            $('#diskon').mask('#.##0', {
                reverse: true
            });
        }

        $('[name=kategori2]').on('change', function() {
            table.ajax.reload();
        });

        $('[name=satuan2]').on('change', function() {

            table.ajax.reload();
        });

        function addForm(url, title = 'Tambah') {
            cek_update(title);
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Edit') {
            cek_update(title);
            $.get(url)
                .done(response => {
                    $(modal).modal('show');
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} form`).attr('action', url);
                    $(`${modal} [name=_method]`).val('put');

                    resetForm(`${modal} form`);
                    loopForm(response.data);
                })
                .fail(errors => {
                    sweetalertku('Tidak dapat menampilkan data', 'error', 'error');
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
                    // showAlert(response.message, response.type, response.type);
                    sweetalertku(response.message, response.type, response.type);
                    table.ajax.reload();
                })
                .fail(errors => {
                    if (errors.status === 422) {
                        sweetalertku('Terjadi Kesalahan', 'error', 'error');
                        loopErrors(errors.responseJSON.errors);
                        showAlert(errors.responseJSON.errors.message, 'danger');
                        return;
                    } else {
                        sweetalertku('Terjadi Kesalahan User', 'error', 'error');
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
                            // showAlert(response.message, 'success');
                            sweetalertku(response.message, response.type, response.type);
                            table.ajax.reload();
                        })
                        .fail(errors => {
                            // showAlert('Tidak dapat menghapus data', 'danger');
                            sweetalertku('Tidak dapat menghapus data', 'error', 'error');
                            return;
                        });
                }
            });
        }

        function resetForm(selector) {
            $(selector)[0].reset();
            $('.select2class').trigger('change');
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        function loopForm(originalForm) {
            for (field in originalForm) {
                if ($(`[name=${field}]`).attr('type') != 'file') {
                    $(`[name=${field}]`).val(originalForm[field]).trigger('input');
                }
            }
            $('select').trigger('change');
        }

        function loopErrors(errors) {
            $('.invalid-feedback').remove();
            $('.is-invalid').removeClass('is-invalid');

            if (errors == undefined) {
                return;
            }

            for (error in errors) {
                $(`[name=${error}]`).addClass('is-invalid');
                if ($(`[name=${error}]`).hasClass('select2class')) {
                    $(`<span class="error invalid-feedback"> ${errors[error][0]}</span>`)
                        .insertAfter($(`[name=${error}]`).next());
                } else {
                    $(`<span class="error invalid-feedback"> ${errors[error][0]}</span>`)
                        .insertAfter($(`[name=${error}]`));
                }
            }
        }

        function refresh_table() {
            $('[name=kategori2]').prop("selectedIndex", 0);
            $('[name=satuan2]').prop("selectedIndex", 0);
            table.ajax.reload();
        }

        function cek_otomatis() {
            if ($('#kode_otomatis').is(":checked")) {
                $('#kode_barang').prop('disabled', false);
                // $('#kode_barang').prop('required', true);
                $('#cek_kode').val('manual');
            } else if (!$('#kode_otomatis').is(":checked")) {
                $('#kode_barang').prop('disabled', true);
                // $('#kode_barang').prop('required', false);
                $('#cek_kode').val('otomatis');
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

        $(".produk").on("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                submitForm(this.form);
            }
        });

        function get_barcode(kode) {
            $('#kodeku').val(kode);
            $('#jumlahku').val('');
            $('#modal-jumlah_barcode').modal('show');

        }

        function cetak_barcode() {
            if ($('#jumlahku').val() == '') {
                sweetalertku('Field Jumlah Tidak Boleh Kosong !!', 'error', 'error');
                return;
            }

            let jumlah = $('#jumlahku').val();
            let kode = $('#kodeku').val();
            location.href = "/produk/cetak-barcode/" + kode + "/" + jumlah;
        }

        function cek_update(title) {
            if (title == 'Tambah') {
                $('.cek_update_div').show();
            } else if (title == 'Edit') {
                $('.cek_update_div').hide();
                $('#kode_barang').prop('disabled', false);
            }
        }

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
