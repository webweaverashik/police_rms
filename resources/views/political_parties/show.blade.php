@extends('layouts.app')

@push('page-css')
@endpush

@section('title', $party->name )

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            {{ $party->name }}
        </h1>
        <!--end::Title-->
        <!--begin::Separator-->
        <span class="h-20px border-gray-300 border-start mx-4"></span>
        <!--end::Separator-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 ">
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('political-parties.index') }}" class="text-muted text-hover-primary">
                    রাজনৈতিক তথ্য </a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                দলের বিস্তারিত </li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
@endsection

@section('content')

@endsection


@push('vendor-js')
@endpush

@push('page-js')
    <script>
        document.getElementById("political_info_menu").classList.add("here", "show");
        document.getElementById("political_party_link").classList.add("active");
    </script>
@endpush
