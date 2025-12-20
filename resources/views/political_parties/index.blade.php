@extends('layouts.app')

@push('page-css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('title', 'রাজনৈতিক দল')

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            রাজনৈতিক দলের তালিকা
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
                দল </li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
@endsection

@section('content')
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i> <input type="text"
                        data-political-parties-table-filter="search" class="form-control form-control-solid w-350px ps-12"
                        placeholder="রাজনৈতিক দলের যে কোনো তথ্য খুঁজুন">
                </div>
                <!--end::Search-->

                <!--begin::Export hidden buttons-->
                <div id="kt_hidden_export_buttons" class="d-none"></div>
                <!--end::Export buttons-->

            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-political-parties-table-filter="base">
                    <!--begin::Add Teacher-->
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_add_program_type">
                        <i class="ki-outline ki-plus fs-2"></i>নতুন দল</a>
                    <!--end::Add Teacher-->
                    <!--end::Filter-->
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <table class="table table-hover align-middle table-row-dashed fs-6 gy-5 prms-table"
                id="kt_political_parties_table">
                <thead>
                    <tr class="fw-bold fs-5 gs-0">
                        <th class="w-25px">#</th>
                        <th>ধরণের নাম</th>
                        <th>দলীয় প্রধান</th>
                        <th>রিপোর্টের সংখ্যা (টি)</th>
                        <th class="not-export">##</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold fs-5">
                    @php
                        use Rakibhstu\Banglanumber\NumberToBangla;

                        $numto = new NumberToBangla();
                    @endphp
                    @foreach ($parties as $party)
                        <tr>
                            <td>{{ $numto->bnNum($loop->index + 1) }}</td>
                            <td class="text-gray-800">{{ $party->name }}</td>
                            <td>{{ $party->party_head }}</td>
                            <td>{{ $numto->bnNum($party->reports_count) }}</td>
                            <td>
                                <a href="#" title="দলের তথ্য সংশোধন" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_edit_program_type"
                                    class="btn btn-icon text-hover-primary w-30px h-30px me-2"
                                    data-party-id={{ $party->id }}>
                                    <i class="ki-outline ki-pencil fs-2"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
@endsection


@push('vendor-js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('page-js')
    <script>
        const storePoliticalPartyRoute = "{{ route('political-parties.store') }}";
    </script>

    <script src="{{ asset('js/political_parties/index.js') }}"></script>

    <script>
        document.getElementById("political_info_menu").classList.add("here", "show");
        document.getElementById("political_party_link").classList.add("active");
    </script>
@endpush
