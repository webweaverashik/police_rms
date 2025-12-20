@push('page-css')
@endpush


@extends('layouts.app')

@section('title', 'সকল পদবী')

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            সকল পদবী
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
                    সেটিংস </a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                পদবী </li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <!--begin::Row-->
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-5 g-xl-9">
                @php
                    use Rakibhstu\Banglanumber\NumberToBangla;

                    $numto = new NumberToBangla();
                @endphp

                @foreach ($designations as $designation)
                    <!--begin::Col-->
                    <div class="col">
                        <!--begin::Card-->
                        <div class="card card-flush h-md-100 border-hover-primary">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>{{ $designation->name }} &nbsp;</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-1">
                                <table class="table fs-6 fw-semibold gs-0 gy-1 gx-0">
                                    <!--begin::Row-->
                                    <tr class="">
                                        <td class="text-gray-600">এই পদে মোট ইউজার:</td>
                                        <td class="text-gray-800 text-center">
                                            {{ $numto->bnNum(count($designation->users)) }} জন
                                        </td>
                                    </tr>
                                    <!--end::Row-->
                                </table>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                @endforeach

            </div>
            <!--end::Row-->
        </div>
    </div>
@endsection


@push('vendor-js')
@endpush

@push('page-js')
    <script>
        const routeDeleteDesignation = "{{ route('designations.destroy', ':id') }}";
    </script>

    <script src="{{ asset('js/users/designations/index.js') }}"></script>

    <script>
        document.getElementById("user_info_menu").classList.add("here", "show");
        document.getElementById("designation_link").classList.add("active");
    </script>
@endpush
