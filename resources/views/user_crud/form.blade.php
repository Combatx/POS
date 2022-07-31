<x-modal size="modal-lg" data-backdrop="static" data-keyboard="false">

    <x-slot name="title">
        Tambah
    </x-slot>

    @method('post')

    <div class="row">
        <div class="col-lg-12">

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="telepon">Telepon</label>
                <input type="number" name="telepon" id="telepon" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="senyap" for="password">Password</label>
                <input type="password" name="password" id="password" class="senyap form-control" required>
            </div>
            <div class="form-group">
                <label class="senyap" for="password_confirmation">Password Confirmation</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="senyap form-control" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" class="form-control" id="alamat" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="role_id">Role</label>
                <select name="role_id" id="role_id" class="custom-select select2class" onclick="">
                    <option disabled selected>Pilih Salah Satu</option>
                    @foreach ($role as $item)
                        <option value="{{ $item->id_roles }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="submitForm(this.form)">Simpan</button>
    </x-slot>
</x-modal>
