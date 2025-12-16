@extends('layouts.app')


@push('page-css')
@endpush

@section('title', 'ড্যাশবোর্ড')

@section('header-title')

@endsection

@section('content')
    @php
        use Rakibhstu\Banglanumber\NumberToBangla;

        $numto = new NumberToBangla();
    @endphp


    <div class="row g-5 g-xl-8 pb-20">
        <div class="col-6">
            <a href="{{ route('reports.create') }}" class="card bg-primary hover-elevate-up shadow-sm h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <i class="ki-outline ki-plus-square fs-3x text-white mb-4"></i>
                    <span class="text-white fs-2x d-block">নতুন প্রতিবেদন</span>
                </div>
            </a>
        </div>

        <div class="col-6">
            <a href="{{ route('reports.index') }}" class="card bg-success hover-elevate-up shadow-sm h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <i class="ki-outline ki-file-added fs-3x text-white mb-4"></i>
                    <span class="text-white fs-2x d-block">আমার প্রতিবেদন</span>
                </div>
            </a>
        </div>
    </div>
    <p class="text-center fs-4x">আপনি মোট &nbsp;<span class="text-info fw-bold fs-5x">{{ $numto->bnNum(auth()->user()->reports()->count()) }}</span>&nbsp; টি প্রতিবেদন দাখিল করেছেন</p>
@endsection


@push('vendor-js')
@endpush

@push('page-js')
    <script>
        document.getElementById("dashboard_link").classList.add("active");
    </script>
@endpush
