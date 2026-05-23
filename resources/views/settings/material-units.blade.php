@extends('layouts.app')
@section('title', 'Materials & Units')

@section('content')
<div class="d-flex align-items-center mb-4">
    <h5 class="mb-0">Materials & Units</h5>
    <small class="text-body-secondary ms-2">(Construction Business)</small>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible mb-4 py-2" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">

    {{-- Materials --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header py-2 d-flex align-items-center justify-content-between">
                <span class="fw-semibold">Construction Materials</span>
                <span class="badge bg-label-primary rounded-pill">{{ $materials->count() }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th style="width:50px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($materials as $i => $m)
                            <tr>
                                <td class="text-body-secondary small">{{ $i + 1 }}</td>
                                <td>{{ $m->name }}</td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('settings.materials.destroy', $m) }}"
                                          onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-text-danger rounded-pill p-0">
                                            <i class="icon-base ti tabler-trash icon-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-body-secondary py-4 small">No materials yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <form method="POST" action="{{ route('settings.materials.store') }}" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror"
                           placeholder="e.g. Reti, Cement…" required />
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <button type="submit" class="btn btn-sm btn-primary text-nowrap">
                        <i class="icon-base ti tabler-plus me-1"></i> Add
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Units --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header py-2 d-flex align-items-center justify-content-between">
                <span class="fw-semibold">Units</span>
                <span class="badge bg-label-info rounded-pill">{{ $units->count() }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th style="width:50px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($units as $i => $u)
                            <tr>
                                <td class="text-body-secondary small">{{ $i + 1 }}</td>
                                <td>{{ $u->name }}</td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('settings.units.destroy', $u) }}"
                                          onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-text-danger rounded-pill p-0">
                                            <i class="icon-base ti tabler-trash icon-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-body-secondary py-4 small">No units yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <form method="POST" action="{{ route('settings.units.store') }}" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror"
                           placeholder="e.g. kg, trip, hour…" required />
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <button type="submit" class="btn btn-sm btn-primary text-nowrap">
                        <i class="icon-base ti tabler-plus me-1"></i> Add
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
