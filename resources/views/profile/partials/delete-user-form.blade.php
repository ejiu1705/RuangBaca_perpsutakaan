<div class="rb-card-heading"><div><span class="eyebrow">TINDAKAN PERMANEN</span><h2>Hapus Akun</h2><p>Penghapusan akun bersifat permanen. Pastikan Anda memahami dampaknya sebelum melanjutkan.</p></div></div>
<button type="button" class="button danger" x-data="{}" x-on:click="$dispatch('open-modal', 'confirm-user-deletion')">Hapus Akun</button>
<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" class="rb-modal-content">
        @csrf @method('delete')
        <div class="rb-modal-heading"><span class="eyebrow">KONFIRMASI KEAMANAN</span><h2>Hapus Akun?</h2><p>Akun dan data yang terkait akan dihapus secara permanen. Masukkan kata sandi untuk mengonfirmasi tindakan ini.</p></div>
        <label class="rb-form-field" for="delete-account-password"><span>Kata Sandi</span><input id="delete-account-password" name="password" type="password" autocomplete="current-password" required><small>{{ $errors->userDeletion->first('password') }}</small></label>
        <div class="rb-form-actions rb-form-actions-end"><button type="button" class="button secondary" x-on:click="$dispatch('close')">Batal</button><button type="submit" class="button danger">Konfirmasi Penghapusan</button></div>
    </form>
</x-modal>
