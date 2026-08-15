@extends('layouts.app')

@section('title', 'Edit User - Admin')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Edit User</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $user->name }}</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="admin-btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="font-heading font-semibold text-gray-800">Informasi User</h3>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="admin-form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="admin-form-input @error('name') error @enderror" required>
                    @error('name')<p class="admin-form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="admin-form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="admin-form-input @error('email') error @enderror" required>
                    @error('email')<p class="admin-form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="admin-form-label">Password <span class="text-xs text-gray-400">(kosongkan jika tidak ingin ganti)</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="password" class="admin-form-input pr-10 @error('password') error @enderror" minlength="6">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i id="passwordIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')<p class="admin-form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="admin-form-label">Role Akses</label>
                    <div class="space-y-2 mt-1">
                        @foreach($roles as $role)
                        <label class="flex items-center gap-2 p-2 rounded-lg border cursor-pointer transition
                            {{ in_array($role->id, $userRoleIds) ? 'border-primary-300 bg-primary-50' : 'border-gray-200 hover:bg-gray-50' }}">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                {{ in_array($role->id, $userRoleIds) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">{{ $role->name }}</span>
                                <span class="block text-xs text-gray-400">{{ $role->description ?? $role->slug }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-6 border-t border-gray-100 mt-6">
                <button type="submit" class="admin-btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="admin-btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('passwordIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
