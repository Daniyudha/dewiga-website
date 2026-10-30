@extends('layouts.app')

@section('title', 'Homestays - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Homestays') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data homestay untuk peta lokasi.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" class="admin-btn-secondary" onclick="openImportModal()">
                <i class="fas fa-file-import"></i>
                {{ __('Import') }}
            </button>
            <a href="{{ route('admin.homestays.create') }}" class="admin-btn-primary me-2">
                <i class="fas fa-plus"></i>
                {{ __('Add New') }}
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="w-10">#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Address') }}</th>
                        <th class="!text-center">{{ __('Coordinates') }}</th>
                        <th class="!text-center">{{ __('Capacity') }}</th>
                        <th class="!text-center">{{ __('Active') }}</th>
                        <th class="!text-center w-24">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($homestays as $homestay)
                    <tr>
                        <td class="font-medium text-gray-900">{{ $loop->iteration }}</td>
                        <td class="max-w-[220px] truncate font-medium">{{ $homestay->name_id }}</td>
                        <td class="max-w-[220px] truncate text-sm text-gray-600">{{ $homestay->address_id }}</td>
                        <td class="!text-center text-sm text-gray-600">
                            @if($homestay->latitude && $homestay->longitude)
                                {{ number_format($homestay->latitude, 6) }}, {{ number_format($homestay->longitude, 6) }}
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>
                        <td class="!text-center">{{ $homestay->capacity }}</td>
                        <td class="!text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold {{ $homestay->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $homestay->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </td>
                        <td class="!text-center flex items-center justify-center gap-2">
                            <a href="{{ route('admin.homestays.edit', $homestay) }}" class="admin-btn-sm admin-btn-outline"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.homestays.destroy', $homestay) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                @csrf @method('delete')
                                <button type="submit" class="admin-btn-sm admin-btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">{{ __('No data found.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    

    <div class="mt-4">{{ $homestays->links() }}</div>

{{-- Import Modal --}}
    <div id="importModal" class="modal-overlay" onclick="if(event.target===this) closeImportModal()">
        <div class="modal-box">
            <div class="px-6 py-4 bg-primary-50 border-b border-primary-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-import"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-heading font-semibold text-gray-900">Import Data Homestay</h3>
                    <p class="text-xs text-gray-500">Upload file Excel (.xlsx, .xls, .csv)</p>
                </div>
                <button type="button" onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 text-lg leading-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="px-6 py-5">
                <div class="mb-4">
                    <a href="{{ route('admin.homestay.template') }}" download="homestay_template.xlsx" class="inline-flex items-center gap-2 text-sm text-primary-600 hover:text-primary-800 font-medium">
                        <i class="fas fa-download"></i> Download Template Excel
                    </a>
                    <p class="text-xs text-gray-400 mt-1">Format: .xlsx | .xls | .csv</p>
                </div>

                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary-400 hover:bg-primary-50/30 transition cursor-pointer" id="dropzoneImport">
                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                    <p class="text-sm text-gray-600 font-medium">Drop file here or click to browse</p>
                    <p class="text-xs text-gray-400 mt-1">Supported: .xlsx, .xls, .csv</p>
                    <input type="file" id="importFile" name="file" accept=".xlsx,.xls,.csv" class="hidden">
                </div>

                <form id="importForm" action="{{ route('admin.homestay.import') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <input type="file" name="file" id="formImportFile" accept=".xlsx,.xls,.csv" class="hidden">
                    <button type="submit" class="admin-btn-primary w-full">
                        <i class="fas fa-upload me-1"></i> Import Data
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openImportModal() {
            document.getElementById('importModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeImportModal() {
            document.getElementById('importModal').classList.remove('open');
            document.body.style.overflow = '';
            document.getElementById('importFile').value = '';
            document.getElementById('formImportFile').value = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImportModal();
            }
        });

        const dropzoneImport = document.getElementById('dropzoneImport');
        const importFile = document.getElementById('importFile');
        const formImportFile = document.getElementById('formImportFile');

        function submitImport(fileList) {
            if (fileList.length > 0) {
                const dt = new DataTransfer();
                dt.items.add(fileList[0]);
                formImportFile.files = dt.files;
                document.getElementById('importForm').submit();
            }
        }

        dropzoneImport.addEventListener('click', () => importFile.click());

        dropzoneImport.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzoneImport.classList.add('border-primary-500', 'bg-primary-50/50');
        });

        dropzoneImport.addEventListener('dragleave', () => {
            dropzoneImport.classList.remove('border-primary-500', 'bg-primary-50/50');
        });

        dropzoneImport.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzoneImport.classList.remove('border-primary-500', 'bg-primary-50/50');
            submitImport(e.dataTransfer.files);
        });

        importFile.addEventListener('change', () => {
            submitImport(importFile.files);
        });
    </script>
 @endsection
