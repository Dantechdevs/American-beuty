@extends('layouts.admin')
@section('title', 'Add Supplier')

@push('styles')
<style>
.pf-field { display:flex; flex-direction:column; gap:.35rem; }
.pf-label { font-size:.72rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; }
.pf-input, .pf-textarea {
    padding:.62rem .9rem; border:1.5px solid var(--border); border-radius:var(--r-sm);
    font-size:.87rem; font-family:inherit; outline:none; background:#fff;
    color:var(--text); transition:border-color .18s, box-shadow .18s; width:100%;
}
.pf-input:focus, .pf-textarea:focus { border-color:var(--purple); box-shadow:0 0 0 3px rgba(124,58,237,.08); }
.pf-input.is-error { border-color:var(--tango); }
.pf-error-msg { font-size:.72rem; color:var(--tango); display:flex; align-items:center; gap:.3rem; }
.pf-textarea { resize:vertical; min-height:80px; }
</style>
@endpush

@section('content')

<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-plus-circle" style="color:var(--purple)"></i> Add Supplier
        </h1>
        <p class="page-sub">Add a new product supplier</p>
    </div>
    <a href="{{ route('admin.supplier.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div style="max-width:640px">
    <div style="background:#fff;border:1.5px solid var(--border);border-radius:var(--r);box-shadow:var(--shadow);overflow:hidden">

        <div style="padding:.9rem 1.25rem;border-bottom:1.5px solid var(--border);background:linear-gradient(120deg,#fff 55%,var(--purple-soft) 100%)">
            <h3 style="font-family:'Playfair Display',serif;font-size:.95rem;font-weight:700;margin:0;display:flex;align-items:center;gap:.5rem">
                <i class="fas fa-building" style="color:var(--purple)"></i> Supplier Details
            </h3>
        </div>

        <form method="POST" action="{{ route('admin.supplier.store') }}" style="padding:1.25rem">
            @csrf
            @include('admin.suppliers._form')
            <div style="display:flex;gap:.75rem;margin-top:1.25rem">
                <button type="submit"
                        style="padding:.75rem 1.5rem;background:linear-gradient(135deg,#7C3AED,#F72585);color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:700;cursor:pointer;font-family:inherit;box-shadow:0 4px 14px rgba(124,58,237,.3);transition:all .2s;display:flex;align-items:center;gap:.45rem">
                    <i class="fas fa-floppy-disk"></i> Save Supplier
                </button>
                <a href="{{ route('admin.supplier.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>

    </div>
</div>

@endsection