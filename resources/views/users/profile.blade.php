@extends('layouts.app')


@push('page-css')
    <style>
        /* Optional: Smooth spinning animation for the icon */
        .animate-spin-slow {
            animation: spin 4s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('title', 'আমার প্রোফাইল')

@section('header-title')

@endsection

@section('content')
    <div class="d-flex flex-column flex-center min-h-500px pb-20">

        <i class="ki-outline ki-setting-4 fs-5x text-warning mb-5 animate-spin-slow"></i>

        <h1 class="text-center fs-2x fw-bolder text-gray-800">
            এই পেজের ডিজাইন কাজ চলমান
        </h1>

        <p class="text-gray-500 fs-4 fw-semibold text-center mt-2">
            আমরা শীঘ্রই ফিরে আসছি। অনুগ্রহ করে অপেক্ষা করুন।
        </p>

        <a href="{{ route('dashboard') }}" class="btn btn-lg fw-semibold btn-light-primary mt-10">
            হোমে ফিরে যান
        </a>

    </div>
@endsection


@push('vendor-js')
@endpush

@push('page-js')
    <script>
        document.getElementById("user_info_menu").classList.add("here", "show");
        document.getElementById("user_profile_menu").classList.add("active");
    </script>
@endpush
