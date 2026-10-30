@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Import Homestay</h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.homestays.index') }}">Homestay</a></li>
        <li class="breadcrumb-item active">Import</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-import me-1"></i>
            Import Data Homestay
        </div>
        <div class="card-body">
            <p class="text-muted small">Format file yang didukung: .xlsx, .xls, .csv</p>
            <p class="text-muted small">Template unduhan: <a href="{{ route('admin.homestay.template') }}" download="homestay_template.xlsx">Download Template</a></p>
            
            {{ $message ? '<div class="alert alert-' . ($alertType ?? 'success') . ' alert-dismissible fade show mt-3" role="alert">
                <strong>' . ($message ?? '') . '</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>' : ''}}
            
            <form action="{{ route('admin.homestay.import') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pilih File Excel</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fas fa-upload me-1"></i> Import
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Format File yang Diharapkan
        </div>
        <div class="card-body">
            <p>The Excel file should contain headers matching the homestay fields:</p>
            <ul class="mb-0">
                <li><strong>name_id</strong> - Nama Homestay (Bahasa Indonesia)</li>
                <li><strong>name_en</strong> - Homestay Name (English)</li>
                <li><strong>address_id</strong> - Alamat (Bahasa Indonesia)</li>
                <li><strong>address_en</strong> - Address (English)</li>
                <li><strong>latitude</strong> - Latitude (between -90 and 90)</li>
                <li><strong>longitude</strong> - Longitude (between -180 and 180)</li>
                <li><strong>capacity</strong> - Kapasitas</li>
                <li><strong>is_active</strong> - Aktif (1/true atau 0/false)</li>
                <li><strong>order</strong> - Urutan</li>
            </ul>
        </div>
    </div>
</div>
@endsection