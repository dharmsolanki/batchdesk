@extends('layouts.app')
@section('title', 'New Supplier — BatchDesk')
@section('content')

    <h1 class="font-bold text-2xl tracking-tight mb-4">New Supplier</h1>

    <form method="POST" action="{{ route('suppliers.store') }}" class="card p-5 space-y-3">
        @csrf
        <div class="grid grid-cols-2 gap-3">
            <div class="col-span-2">
                <label class="label">Supplier name *</label>
                <input name="name" required class="field" value="{{ old('name') }}">
            </div>
            <div>
                <label class="label">Phone</label>
                <input name="phone" class="field" value="{{ old('phone') }}">
            </div>
            <div>
                <label class="label">Email</label>
                <input name="email" type="email" class="field" value="{{ old('email') }}">
            </div>
            <div>
                <label class="label">GSTIN</label>
                <input name="gst_number" class="field" value="{{ old('gst_number') }}">
            </div>
            <div>
                <label class="label">City</label>
                <input name="city" class="field" value="{{ old('city') }}">
            </div>
            <div class="col-span-2">
                <label class="label">Address</label>
                <input name="address" class="field" value="{{ old('address') }}">
            </div>
            <div class="col-span-2">
                <label class="label">Payment terms</label>
                <input name="payment_terms" class="field" placeholder="e.g. 30 days credit / Advance"
                    value="{{ old('payment_terms') }}">
            </div>
            <div class="col-span-2">
                <label class="label">Notes</label>
                <textarea name="notes" rows="2" class="field">{{ old('notes') }}</textarea>
            </div>
        </div>
        <button class="btn-primary w-full py-2.5">Add Supplier</button>
    </form>
@endsection
