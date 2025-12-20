@extends('layouts.app')


@push('page-css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('title', 'প্রতিবেদন বিস্তারিত')

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            প্রতিবেদন
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
                    রিপোর্ট </a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                প্রতিবেদনের বিস্তারিত </li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
@endsection

@section('content')
    @php
        use Rakibhstu\Banglanumber\NumberToBangla;

        $numto = new NumberToBangla();
    @endphp

    <!--begin::Layout-->
    <div class="d-flex flex-column flex-xl-row">
        <!--begin::Sidebar-->
        <div class="flex-column flex-lg-row-auto w-100 w-lg-350px w-xl-450px mb-10">
            <!--begin::Card-->
            @php
                $statusBorder = match ($report->status) {
                    'upcoming' => 'border border-dashed border-info',
                    'ongoing' => 'border border-dashed border-warning',
                    default => '',
                };
            @endphp

            <div class="card card-flush mb-0 {{ $statusBorder }}" data-kt-sticky="true"
                data-kt-sticky-name="student-summary" data-kt-sticky-offset="{default: false, lg: 0}"
                data-kt-sticky-width="{lg: '350px', xl: '450px'}" data-kt-sticky-left="auto" data-kt-sticky-top="100px"
                data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
                <!--begin::Card header-->
                <div class="card-header ">
                    <!--begin::Card title-->
                    <div class="card-title">
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0 fs-6 mt-n10">
                    <!--begin::প্রশাসনিক অধিক্ষেত্রের তথ্য-->
                    <div class="mb-7">
                        <!--begin::Title-->
                        <h5 class="mb-4 fs-4">প্রশাসনিক অধিক্ষেত্রের তথ্য
                        </h5>
                        <!--end::Title-->
                        <!--begin::Details-->
                        <div class="mb-0">
                            <!--begin::Details-->
                            <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                                <!--begin::Row-->
                                <tr class="">
                                    <td class="text-gray-600 fs-4">সংসদীয় আসন:</td>
                                    <td class="text-gray-800 fs-4">{{ $report->parliamentSeat->name }}
                                        <span class="ms-1" data-bs-toggle="tooltip"
                                            title="{{ $report->parliamentSeat->description }}">
                                            <i class="ki-outline ki-information-5 text-gray-600 fs-6"></i>
                                        </span>
                                    </td>
                                </tr>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-600 fs-4">উপজেলা:</td>
                                    <td class="fs-4">{{ $report->upazila->name }}</td>
                                </tr>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <tr class="">
                                    <td class="text-gray-600 fs-4">ইউনিয়ন:</td>
                                    <td class="fs-4">{{ $report->union->name }}</td>
                                </tr>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <tr class="">
                                    <td class="text-gray-600 fs-4">থানা:</td>
                                    <td class="fs-4">{{ $report->zone->name }}</td>
                                </tr>
                                <!--end::Row-->

                            </table>
                            <!--end::Details-->
                        </div>
                        <!--end::Details-->
                    </div>
                    <!--end::প্রশাসনিক অধিক্ষেত্রের তথ্য-->

                    <!--begin::Seperator-->
                    <div class="separator separator-dashed mb-7"></div>
                    <!--end::Seperator-->

                    <!--begin::রাজনৈতিক দলের তথ্য-->
                    <div class="mb-7">
                        <!--begin::Title-->
                        <h5 class="mb-4 fs-4">রাজনৈতিক দলের তথ্য
                        </h5>
                        <!--end::Title-->
                        <!--begin::Details-->
                        <div class="mb-0">
                            <!--begin::Details-->
                            <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                                <!--begin::Row-->
                                <tr class="">
                                    <td class="text-gray-600 fs-4">দলের নাম:</td>
                                    <td class="text-gray-800 fs-4">{{ $report->politicalParty->name }}
                                        <span class="ms-1" data-bs-toggle="tooltip"
                                            title="দলীয় প্রধান: {{ $report->politicalParty->party_head }} @if ($report->politicalParty->local_address) , স্থানীয় কার্যালয়: {{ $report->politicalParty->local_address }} @endif">
                                            <i class="ki-outline ki-information-5 text-gray-600 fs-6"></i>
                                        </span>
                                    </td>
                                </tr>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-600 fs-4">প্রার্থীর নাম:</td>
                                    <td class="fs-4">{{ $report->candidate_name }}</td>
                                </tr>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <tr class="">
                                    <td class="text-gray-600 fs-4">প্রধান অতিথি:</td>
                                    <td class="fs-4">{{ $report->program_special_guest ?? '-' }}</td>
                                </tr>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <tr class="">
                                    <td class="text-gray-600 fs-4">সভাপতি:</td>
                                    <td class="fs-4">{{ $report->program_chair ?? '-' }}</td>
                                </tr>
                                <!--end::Row-->

                            </table>
                            <!--end::Details-->
                        </div>
                        <!--end::Details-->
                    </div>
                    <!--end::রাজনৈতিক দলের তথ্য-->

                    <!--begin::Seperator-->
                    <div class="separator separator-dashed mb-7"></div>
                    <!--end::Seperator-->

                    <!--begin::প্রতিবেদন দাখিল সংক্রান্ত তথ্য-->
                    <div class="mb-0">
                        <!--begin::Title-->
                        <h5 class="mb-4 fs-4">প্রতিবেদন দাখিল সংক্রান্ত তথ্য</h5>
                        <!--end::Title-->
                        <!--begin::Details-->
                        <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                            <tr>
                                <td class="text-gray-600 fs-4">প্রতিবেদন তৈরিকারি:</td>
                                <td class="text-gray-800 fs-4">{{ $report->createdBy->name }}, {{ $report->createdBy->designation->name }}</td>
                            </tr>
                            <!--begin::Row-->
                            <tr class="">
                                <td class="text-gray-600 fs-4">দাখিলের সময়:</td>
                                <td class="text-gray-800 fs-4">
                                    {{ $numto->bnNum($report->created_at->format('d')) }}-
                                    {{ $numto->bnNum($report->created_at->format('m')) }}-
                                    {{ $numto->bnNum($report->created_at->format('Y')) }},
                                    {{ $numto->bnNum($report->created_at->format('h')) }}:{{ $numto->bnNum($report->created_at->format('i')) }}
                                    {{ $report->created_at->format('A') =='AM' ? 'পূর্বাহ্ণ' : 'অপরাহ্ণ' }}
                                </td>
                            </tr>
                            <!--end::Row-->

                            <!--begin::Row-->
                            <tr class="">
                                <td class="text-gray-600 fs-4">সর্বশেষ হালনাগাদ:</td>
                                <td class="text-gray-800 fs-4">
                                    {{ $numto->bnNum($report->updated_at->format('d')) }}-
                                    {{ $numto->bnNum($report->updated_at->format('m')) }}-
                                    {{ $numto->bnNum($report->updated_at->format('Y')) }},
                                    {{ $numto->bnNum($report->updated_at->format('h')) }}:{{ $numto->bnNum($report->updated_at->format('i')) }}
                                    {{ $report->updated_at->format('A') =='AM' ? 'পূর্বাহ্ণ' : 'অপরাহ্ণ' }}
                                </td>
                            </tr>
                            <!--end::Row-->
                        </table>
                        <!--end::Details-->
                    </div>
                    <!--end::প্রতিবেদন দাখিল সংক্রান্ত তথ্য-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Sidebar-->

        <!--begin::Content-->
        <div class="flex-lg-row-fluid ms-lg-10">
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title p-4">
                        <h3 class="fw-semibold m-0">{{ $report->program_title }}</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--begin::Card header-->
                <!--begin::Card body-->
                <div class="card-body p-9">
                    <!--begin::Row-->
                    <div class="row mb-5">
                        <label class="col-lg-2 fw-semibold text-muted fs-4">বিস্তারিত:</label>

                        <div class="col-lg-10">
                            <p class="fw-semibold fs-4 text-gray-800 mb-0">
                                {!! nl2br(e($report->program_description)) !!}
                            </p>
                        </div>
                    </div>

                    <!--end::Row-->

                    <!--begin::Input group-->
                    <div class="row mb-5">
                        <!--begin::Label-->
                        <label class="col-6 col-lg-2 fw-semibold text-muted fs-4">প্রোগ্রামের ধরণ:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6 col-lg-10">
                            <span class="fw-semibold fs-4 text-gray-800">{{ $report->programType->name }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-5">
                        <!--begin::Label-->
                        <label class="col-6 col-lg-2 fw-semibold text-muted fs-4">প্রোগ্রামের অবস্থা:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6 col-lg-10">
                            @php
                                $statusMap = [
                                    'done' => ['label' => 'সম্পন্ন', 'class' => 'badge-success'],
                                    'ongoing' => ['label' => 'চলমান', 'class' => 'badge-danger'],
                                    'upcoming' => ['label' => 'আসন্ন', 'class' => 'badge-info'],
                                ];
                                $status = $statusMap[$report->program_status] ?? null;
                            @endphp

                            <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-5">
                        <!--begin::Label-->
                        <label class="col-6 col-lg-2 fw-semibold text-muted fs-4">প্রোগ্রামের তারিখ:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6 col-lg-10">
                            <span class="fw-semibold fs-4 text-gray-800">
                                @if ($report->program_date)
                                    {{ $numto->bnNum(\Carbon\Carbon::parse($report->program_date)->format('d')) }}-{{ $numto->bnNum(\Carbon\Carbon::parse($report->program_date)->format('m')) }}-{{ $numto->bnNum(\Carbon\Carbon::parse($report->program_date)->format('Y')) }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-5">
                        <!--begin::Label-->
                        <label class="col-6 col-lg-2 fw-semibold text-muted fs-4">প্রোগ্রামের সময়:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6 col-lg-10">
                            <span class="fw-semibold fs-4 text-gray-800">
                                @if ($report->program_time)
                                    {{ $numto->bnNum(\Carbon\Carbon::parse($report->program_time)->format('h')) }}:{{ $numto->bnNum(\Carbon\Carbon::parse($report->program_time)->format('i')) }} {{ \Carbon\Carbon::parse($report->program_time)->format('A') =='AM' ? 'পূর্বাহ্ণ' : 'অপরাহ্ণ' }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-5">
                        <!--begin::Label-->
                        <label class="col-6 col-lg-2 fw-semibold text-muted fs-4">প্রোগ্রামের স্থান:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6 col-lg-10">
                            <span class="fw-semibold fs-4 text-gray-800">{{ $report->location_name ?? '-' }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-5">
                        <!--begin::Label-->
                        <label class="col-6 col-lg-2 fw-semibold text-muted fs-4">সম্ভাব্য উপস্থিতি:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-6 col-lg-10">
                            <span
                                class="fw-semibold fs-4 text-gray-800">{{ $report->tentative_attendee_count ? $numto->bnNum($report->tentative_attendee_count) . ' জন' : '-' }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Personal Info-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Layout-->
@endsection


@push('vendor-js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('page-js')
    <script src="{{ asset('js/reports/show.js') }}"></script>
    <script>
        document.getElementById("report_info_menu").classList.add("active");
    </script>
@endpush
