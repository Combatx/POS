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
                        <th>Diskon</th>
                        <th>Stok</th>
                        <th width="10%"><i class="fas fa-cog"></i></th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    @includeIf('produk.form')

@endsection

@includeIf('includes.datatables')
@includeIf('includes.jquery-mask')
@includeIf('includes.select2')

@push('script')
    <script>
        let modal = '#modal-form';
        let table;

        $(document).ready(function() {
            $('.satuanform').select2();
            $('.kategoriform').select2();
            inputMask();
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
                {
                    data: 'diskon'
                },
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
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Edit') {
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
    </script>
@endpush
