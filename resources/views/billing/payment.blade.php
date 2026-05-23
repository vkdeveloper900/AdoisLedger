@extends('layouts.app')
@section('title', $mode === 'made' ? 'Pay Vendor' : 'Receive Payment')

@section('content')
<div class="d-flex align-items-center mb-6">
    <a href="{{ url()->previous() }}" class="btn btn-icon btn-text-secondary rounded-pill me-3">
        <i class="icon-base ti tabler-arrow-left"></i>
    </a>
    <div>
        <h4 class="mb-0">{{ $mode === 'made' ? 'Pay Vendor' : 'Receive Payment' }}</h4>
        <small class="text-body-secondary">
            {{ $mode === 'made' ? 'Record payment made to vendor/farmer' : 'Record payment received from customer' }}
        </small>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger mb-6">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-{{ $mode === 'made' ? 'warning' : 'success' }}">
                            <i class="icon-base ti {{ $mode === 'made' ? 'tabler-arrow-up' : 'tabler-arrow-down' }} icon-sm"></i>
                        </span>
                    </span>
                    <span class="fw-semibold">{{ $mode === 'made' ? 'Payment Out' : 'Payment In' }}</span>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('payments.store') }}">
                    @csrf
                    <input type="hidden" name="mode" value="{{ $mode }}">

                    <div class="mb-4">
                        <label class="form-label">
                            {{ $mode === 'made' ? 'Vendor / Farmer' : 'Customer' }}
                            <span class="text-danger">*</span>
                        </label>
                        <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                            <option value="">Select {{ $mode === 'made' ? 'vendor' : 'customer' }}…</option>
                            @foreach ($parties as $p)
                                <option value="{{ $p->id }}"
                                    {{ (old('customer_id', $selectedCustomer) == $p->id) ? 'selected' : '' }}>
                                    {{ $p->name }}{{ $p->mobile ? ' · '.$p->mobile : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Amount (Rs.) <span class="text-danger">*</span></label>
                        <input type="number" name="amount"
                               class="form-control @error('amount') is-invalid @enderror"
                               min="1" value="{{ old('amount') }}" required />
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date"
                               class="form-control @error('date') is-invalid @enderror"
                               value="{{ old('date', date('Y-m-d')) }}" required />
                        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Payment Method</label>
                        <div class="d-flex gap-3">
                            @foreach (['cash' => 'Cash', 'bank' => 'Bank', 'upi' => 'UPI'] as $val => $label)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                           name="payment_method" value="{{ $val }}" id="pm_{{ $val }}"
                                           {{ old('payment_method', 'cash') === $val ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pm_{{ $val }}">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"
                                  placeholder="Optional…">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-{{ $mode === 'made' ? 'warning' : 'success' }} w-100">
                        <i class="icon-base ti tabler-check me-1"></i>
                        {{ $mode === 'made' ? 'Record Vendor Payment' : 'Record Payment' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection