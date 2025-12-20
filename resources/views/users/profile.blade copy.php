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
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            আমার প্রোফাইল
        </h1>
        <!--end::Title-->
        <!--begin::Separator-->
        <span class="h-20px border-gray-300 border-start mx-4"></span>
        <!--end::Separator-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 ">
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="#" class="text-muted text-hover-primary">
                    ইউজার ম্যানেজমেন্ট </a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                প্রোফাইল</li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
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
        document.getElementById("user_profile_menu").classList.add("active");
    </script>
@endpush
