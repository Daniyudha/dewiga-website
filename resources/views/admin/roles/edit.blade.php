@extends('layouts.app')

@section('title', 'Edit Role - Admin')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Edit Role</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $role->name }} ({{ $role->slug }})</p>
    </div>
    <a href="{{ route('admin.roles.index') }}" class="admin-btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.roles.update', $role) }}">
    @csrf @method('PUT')
    <div class="gap-6">
        {{-- Role Info --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="font-heading font-semibold text-gray-800">Informasi Role</h3>
                </div>
                <div class="admin-card-body space-y-4">
                    <div>
                        <label class="admin-form-label">Nama Role</label>
                        <input type="text" name="name" value="{{ old('name', $role->name) }}" class="admin-form-input @error('name') error @enderror" required>
                        @error('name')<p class="admin-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="admin-form-label">Slug</label>
                        <input type="text" value="{{ $role->slug }}" class="admin-form-input bg-gray-100" readonly disabled>
                        <p class="text-xs text-gray-400 mt-1">Slug tidak dapat diubah</p>
                    </div>
                    <div>
                        <label class="admin-form-label">Deskripsi</label>
                        <textarea name="description" rows="3" class="admin-form-input">{{ old('description', $role->description) }}</textarea>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3 text-sm text-blue-700">
                        <strong>{{ $role->users_count ?? $role->users()->count() }}</strong> pengguna memiliki role ini
                    </div>
                </div>
            </div>

        {{-- Permissions --}}
        <div class="mt-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="font-heading font-semibold text-gray-800">Permission</h3>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="selectAll(true)" class="text-xs text-blue-600 hover:underline">Pilih Semua</button>
                        <span class="text-gray-300">|</span>
                        <button type="button" onclick="selectAll(false)" class="text-xs text-gray-500 hover:underline">Hapus Semua</button>
                    </div>
                </div>
                <div class="admin-card-body">
                    @foreach($permissions as $group => $groupPermissions)
                    <div class="mb-6 last:mb-0">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-1 h-5 rounded-full bg-primary-500"></div>
                            <h4 class="font-medium text-sm text-gray-700">{{ $group }}</h4>
                            <span class="text-xs text-gray-400">({{ $groupPermissions->count() }})</span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach($groupPermissions as $perm)
                            <label class="flex items-start gap-2 p-2 rounded-lg border cursor-pointer transition
                                {{ in_array($perm->id, $rolePermissionIds) ? 'border-primary-300 bg-primary-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                    {{ in_array($perm->id, $rolePermissionIds) ? 'checked' : '' }}
                                    class="mt-0.5 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">{{ $perm->name }}</span>
                                    <span class="block text-xs text-gray-400">{{ $perm->slug }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="admin-card-footer flex justify-end gap-3">
                    <a href="{{ route('admin.roles.index') }}" class="admin-btn-secondary">Batal</a>
                    <button type="submit" class="admin-btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function selectAll(checked) {
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = checked);
    // Trigger label style update
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
        cb.closest('label').className = cb.checked 
            ? 'flex items-start gap-2 p-2 rounded-lg border cursor-pointer border-primary-300 bg-primary-50'
            : 'flex items-start gap-2 p-2 rounded-lg border cursor-pointer border-gray-200 hover:border-gray-300 hover:bg-gray-50';
    });
}
document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
    cb.addEventListener('change', function() {
        this.closest('label').className = this.checked 
            ? 'flex items-start gap-2 p-2 rounded-lg border cursor-pointer border-primary-300 bg-primary-50'
            : 'flex items-start gap-2 p-2 rounded-lg border cursor-pointer border-gray-200 hover:border-gray-300 hover:bg-gray-50';
    });
});
</script>
@endpush