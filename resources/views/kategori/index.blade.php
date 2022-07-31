@extends('layouts.app')

@section('title', 'Kategori')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#">Kategori</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <button onclick="addForm(`{{ route('kategori.store') }}`)" class="btn btn-primary"><i
                            class="fas fa-plus-circle"></i> Tambah</button>
                </x-slot>
                <x-table>
                    <x-slot name="thead">
                        <th width="10%">No</th>
                        <th>Nama Kategori</th>
                        <th>Kode Kategori</th>
                        <th width="20%"><i class="fas fa-cog"></i></th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    @includeIf('kategori.form')

@endsection

@includeIf('includes.datatables')
@includeIf('includes.sweetalert2')

@push('script')
    <script>
        let modal = '#modal-form';
        let table;

        table = $('.table').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('kategori.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false,
                },
                {
                    data: 'nama'
                },
                {
                    data: 'kode_kategori'
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
                    sweetalertku(response.message, 'success');
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
                            //showAlert(response.message, 'success');
                            sweetalertku(response.message, 'success');
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

        $(".kategori").on("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                submitForm(this.form);
            }
        });

        function sweetalertku(message, type) {
            Swal.fire({
                title: 'Berhasil',
                text: message,
                icon: type,
                confirmButtonText: 'OK'
            })
        }
    </script>
@endpush
