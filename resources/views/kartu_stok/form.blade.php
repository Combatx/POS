<!-- Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <form action="{{ route('laporan.index') }}" class="form-horizontal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Periode Laporan Pendapatan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="tanggal_awal">Tanggal Awal</label>
                            <div class="input-group datetimepicker" id="tanggal_awal" data-target-input="nearest">
                                <input type="text" name="tanggal_awal" class="form-control datetimepicker-input"
                                    data-target="#tanggal_awal" />
                                <div class="input-group-append" data-target="#tanggal_awal"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="tanggal_akhir">Tanggal Selesai</label>
                            <div class="input-group datetimepicker" id="tanggal_akhir" data-target-input="nearest">
                                <input type="text" name="tanggal_akhir" class="form-control datetimepicker-input"
                                    data-target="#tanggal_akhir" />
                                <div class="input-group-append" data-target="#tanggal_akhir"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary">Simpan</button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </form>
</div>
