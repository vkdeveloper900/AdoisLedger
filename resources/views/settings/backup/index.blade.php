@extends('layouts.app')

@section('title', 'Backup & Restore')

@section('content')

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-6">
    <div>
        <h4 class="mb-1">Backup & Restore</h4>
        <p class="text-body-secondary mb-0">Create, download, and restore database backups</p>
    </div>
    <form method="POST" action="{{ route('settings.backup.store') }}">
        @csrf
        <button type="submit" class="btn btn-primary"
                onclick="return confirm('Create a new backup now?')">
            <i class="ti tabler-database-export me-1"></i> Create Backup Now
        </button>
    </form>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible mb-4">
        <i class="ti tabler-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible mb-4">
        <i class="ti tabler-alert-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">

    {{-- Left: Backup List --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0">Available Backups</h5>
                    <small class="text-body-secondary">Stored in <code>storage/app/backups/</code></small>
                </div>
                <span class="badge bg-label-primary rounded-pill">{{ count($backups) }} file(s)</span>
            </div>

            @if(count($backups) === 0)
                <div class="card-body text-center py-6">
                    <div class="avatar avatar-lg mb-3 mx-auto">
                        <span class="avatar-initial rounded bg-label-secondary">
                            <i class="ti tabler-database-off icon-28px"></i>
                        </span>
                    </div>
                    <h6 class="mb-1">No backups yet</h6>
                    <p class="text-body-secondary mb-4">Click "Create Backup Now" to generate your first backup.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Filename</th>
                                <th>Size</th>
                                <th>Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $backup)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar avatar-sm rounded bg-label-primary">
                                            <i class="ti tabler-file-zip icon-16px"></i>
                                        </span>
                                        <div>
                                            <span class="fw-medium small d-block">{{ $backup['filename'] }}</span>
                                            <small class="text-body-secondary">ZIP Archive</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">{{ $backup['size_human'] }}</span>
                                </td>
                                <td class="text-body-secondary small">
                                    {{ $backup['created_at']->format('d M Y') }}<br>
                                    <span class="text-body-secondary" style="font-size:11px">{{ $backup['created_at']->format('h:i A') }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        {{-- Download --}}
                                        <a href="{{ route('settings.backup.download', $backup['filename']) }}"
                                           class="btn btn-icon btn-sm btn-text-primary rounded-pill"
                                           data-bs-toggle="tooltip" title="Download">
                                            <i class="ti tabler-download icon-md"></i>
                                        </a>
                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('settings.backup.destroy', $backup['filename']) }}"
                                              onsubmit="return confirm('Delete this backup permanently?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-icon btn-sm btn-text-danger rounded-pill"
                                                    data-bs-toggle="tooltip" title="Delete">
                                                <i class="ti tabler-trash icon-md"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Right: Info + Restore --}}
    <div class="col-lg-4">

        {{-- What's included --}}
        <div class="card mb-4">
            <div class="card-header border-bottom">
                <h6 class="mb-0"><i class="ti tabler-info-circle me-2 text-primary"></i>What's Included</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-label-success p-1_5"><i class="ti tabler-database icon-14px"></i></span>
                        <div>
                            <span class="fw-medium small d-block">Full Database</span>
                            <small class="text-body-secondary">All tables, transactions, ledger, users</small>
                        </div>
                    </li>
                    <li class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-label-info p-1_5"><i class="ti tabler-photo icon-14px"></i></span>
                        <div>
                            <span class="fw-medium small d-block">Uploaded Files</span>
                            <small class="text-body-secondary">Avatars & profile images</small>
                        </div>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <span class="badge bg-label-warning p-1_5"><i class="ti tabler-file-info icon-14px"></i></span>
                        <div>
                            <span class="fw-medium small d-block">Backup Metadata</span>
                            <small class="text-body-secondary">Date, DB driver, version</small>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Restore --}}
        <div class="card border border-warning">
            <div class="card-header border-bottom bg-label-warning">
                <h6 class="mb-0"><i class="ti tabler-restore me-2 text-warning"></i>Restore from Backup</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning border-0 p-2 mb-3">
                    <i class="ti tabler-alert-triangle me-1"></i>
                    <small><strong>Warning:</strong> Restore will <strong>overwrite all current data</strong>. This cannot be undone.</small>
                </div>
                <form method="POST" action="{{ route('settings.backup.restore') }}"
                      enctype="multipart/form-data"
                      onsubmit="return confirm('⚠️ RESTORE WILL OVERWRITE ALL CURRENT DATA.\n\nAre you absolutely sure you want to continue?')">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Upload Backup ZIP</label>
                        <input type="file" name="backup_file" class="form-control form-control-sm @error('backup_file') is-invalid @enderror"
                               accept=".zip" required>
                        @error('backup_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-body-secondary d-block mt-1">Max size: 100 MB. Must be a valid AdoisLedger backup ZIP.</small>
                    </div>
                    <button type="submit" class="btn btn-warning btn-sm w-100">
                        <i class="ti tabler-restore me-1"></i> Restore Now
                    </button>
                </form>
            </div>
        </div>

        {{-- Tips --}}
        <div class="card mt-4 border-0 bg-label-primary">
            <div class="card-body">
                <h6 class="mb-3"><i class="ti tabler-bulb me-2"></i>Backup Tips</h6>
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2"><i class="ti tabler-check text-success me-1"></i>Create a backup before any major changes.</li>
                    <li class="mb-2"><i class="ti tabler-check text-success me-1"></i>Download & store backups on a USB drive or cloud storage.</li>
                    <li class="mb-2"><i class="ti tabler-check text-success me-1"></i>Keep at least 3 recent backups.</li>
                    <li><i class="ti tabler-check text-success me-1"></i>Test restore on a test machine first.</li>
                </ul>
            </div>
        </div>

    </div>
</div>

@endsection
