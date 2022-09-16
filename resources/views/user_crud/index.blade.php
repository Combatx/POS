@extends('layouts.app')

@section('title', 'Kelola User')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#"> User</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>

                <x-slot name="header">
                    <button onclick="addForm(`{{ route('user.store') }}`)" class="btn btn-primary"><i
                            class="fas fa-plus-circle"></i> Tambah</button>
                </x-slot>
                <x-table>
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Role</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th width="10%"><i class="fas fa-cog"></i></th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    @includeIf('user_crud.form')

@endsection

@includeIf('includes.datatables')
@includeIf('includes.select2')

@push('script')
    <script>
        let modal = '#modal-form';
        let table;

        $(document).ready(function() {
            $('.select2class').select2();
        });


        table = $('.table').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('user.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'telepon'
                },
                {
                    data: 'alamat'
                },
                {
                    data: 'role',
                },
                {

                    data: 'foto',
                    searchable: false,
                    sortable: false,
                },
                {
                    data: 'status',
                    searchable: false,
                    sortable: false,
                },
                {
                    data: 'action',
                    searchable: false,
                    sortable: false,
                },
            ]
        });


        function checkswitch(go, url) {
            if ($('.status-' + parseInt(go)).is(":checked")) {
                //console.log(url);
                $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        method: 'POST',
                        data: {
                            status: 'Aktif'
                        },
                    }).done(response => {
                        $('.status').prop("checked", false);
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
            } else if (!$('.status-' + parseInt(go)).is(":checked")) {

                $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        method: 'POST',
                        data: {
                            status: 'NonAktif'
                        },
                    }).done(response => {
                        $('.status').prop("checked");
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
        }

        function addForm(url, title = 'Tambah') {
            $('.senyap').show();
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Edit') {
            $('.senyap').hide();
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
