@extends('layouts.app')

@push('page-css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('title', 'সকল প্রতিবেদন')

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            @if (auth()->user()->role->name == 'Operator')
                আমার সকল প্রতিবেদন
            @else
                সকল প্রতিবেদন
            @endif
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
                    প্রতিবেদন </a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                প্রতিবেদন তালিকা </li>
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
                        data-all-reports-table-filter="search" class="form-control form-control-solid w-350px ps-12"
                        placeholder="প্রতিবেদনে খুঁজুন">
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
                <div class="d-flex justify-content-end" data-transaction-table-toolbar="base">
                    <!--begin::Filter-->
                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-end">
                        <i class="ki-outline ki-filter fs-2"></i>ফিল্টার</button>
                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-gray-900 fw-bold">ফিল্টার অপশন</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Separator-->
                        <div class="separator border-gray-200"></div>
                        <!--end::Separator-->
                        <!--begin::Content-->
                        <div class="px-7 py-5" data-all-reports-table-filter="form">
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-semibold">উপজেলা:</label>
                                <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                    data-placeholder="Select option" data-allow-clear="true" data-hide-search="true">
                                    <option></option>
                                    <option value="T_partial">Partial</option>
                                    <option value="T_full_paid">Full Paid</option>
                                    <option value="T_discounted">Discounted</option>
                                </select>
                            </div>
                            <!--end::Input group-->

                            @if (auth()->user()->hasRole('admin'))
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <label class="form-label fs-6 fw-semibold">Branch:</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                        data-placeholder="Select option" data-allow-clear="true" data-hide-search="true">
                                        <option></option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ ucfirst($branch->branch_name) }}">
                                                {{ ucfirst($branch->branch_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input group-->
                            @endif

                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                    data-kt-menu-dismiss="true" data-all-reports-table-filter="reset">Reset</button>
                                <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true"
                                    data-all-reports-table-filter="filter">Apply</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Menu 1-->

                    <!--begin::Export dropdown-->
                    <div class="dropdown">
                        <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                            <i class="ki-outline ki-exit-up fs-2"></i>এক্সপোর্ট
                        </button>

                        <!--begin::Menu-->
                        <div id="kt_table_report_dropdown_menu"
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                            data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-row-export="copy">ক্লিপবোর্ডে কপি করুন</a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-row-export="excel">Excel ফাইল ডাউনলোড</a>
                            </div>
                            {{-- <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-row-export="csv">CSV ফাইল ডাউনলোড</a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-row-export="pdf">PDF ফাইল ডাউনলোড</a>
                            </div> --}}
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--end::Export dropdown-->

                    @if (auth()->user()->role->name == 'Operator')
                        <!--begin::Add subscription-->
                        <a href="{{ route('reports.create') }}" class="btn btn-primary">
                            <i class="ki-outline ki-plus fs-2"></i>নতুন রিপোর্ট</a>
                        <!--end::Add subscription-->
                    @endif

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
            <table class="table table-hover align-middle table-row-dashed fs-6 gy-5 prms-table" id="kt_all_reports_table">
                <thead>
                    <tr class="fw-bold fs-6 gs-0">
                        <th>#</th>
                        <th>সংসদীয় আসন</th>
                        <th>উপজেলা</th>
                        <th>ইউনিয়ন</th>
                        <th>থানা/জোন</th>
                        <th>দলের নাম</th>
                        <th>প্রার্থীর নাম</th>
                        <th>প্রধান অতিথি</th>
                        <th>সভাপতি</th>
                        <th>প্রোগ্রামের ধরণ</th>
                        <th>তারিখ ও সময়</th>
                        <th>সম্ভাব্য উপস্থিতি</th>
                        <th>প্রোগ্রামের অবস্থা</th>
                        <th>মোট উপস্থিতি</th>
                        <th class="@if (auth()->user()->role->name == 'Operator') d-none @endif">প্রতিবেদক</th>
                        <th class="not-export w-100px">##</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 fw-semibold">
                    @php
                        use Rakibhstu\Banglanumber\NumberToBangla;

                        $numto = new NumberToBangla();
                    @endphp

                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ $numto->bnNum($loop->index + 1) }}</td>
                            <td>{{ $report->parliamentSeat->name }}</td>
                            <td>{{ $report->upazila->name }}</td>
                            <td>{{ $report->union->name }}</td>
                            <td>{{ $report->zone->name }}</td>
                            <td>{{ $report->politicalParty->name }}</td>
                            <td>{{ $report->candidate_name }}</td>
                            <td>{{ $report->program_special_guest ? $report->program_special_guest : '-' }}</td>
                            <td>{{ $report->program_chair ? $report->program_chair : '-' }}</td>
                            <td>{{ $report->programType->name }}</td>
                            <td>
                                {{ $numto->bnNum($report->program_date_time->format('d')) }}-
                                {{ $numto->bnNum($report->program_date_time->format('m')) }}-
                                {{ $numto->bnNum($report->program_date_time->format('Y')) }},
                                {{ $numto->bnNum($report->program_date_time->format('h')) }}:
                                {{ $numto->bnNum($report->program_date_time->format('i')) }}
                                {{ $report->program_date_time->format('A') }}
                            </td>

                            <td>
                                {{ $report->tentative_attendee_count ? $numto->bnNum($report->tentative_attendee_count) : '-' }}
                            </td>
                            <td>
                                @php
                                    $statusMap = [
                                        'done' => ['label' => 'সম্পন্ন', 'class' => 'badge-success'],
                                        'ongoing' => ['label' => 'চলমান', 'class' => 'badge-danger'],
                                        'upcoming' => ['label' => 'আসন্ন', 'class' => 'badge-info'],
                                    ];
                                    $status = $statusMap[$report->program_status] ?? null;
                                @endphp

                                <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                            </td>

                            <td>
                                {{ $report->final_attendee_count ? $numto->bnNum($report->final_attendee_count) : '-' }}
                            </td>
                            <td class="@if (auth()->user()->role->name == 'Operator') d-none @endif">{{ $report->createdBy->name }},
                                {{ $report->createdBy->designation->name }}</td>
                            <td>
                                @if (auth()->user()->role->name !== 'Operator')
                                    <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">একশন
                                        <i class="ki-outline ki-down fs-5 m-0"></i></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4"
                                        data-kt-menu="true">

                                        <div class="menu-item px-3">
                                            <a href="{{ route('reports.show', $report->id) }}" title="প্রতিবেদনটি দেখুন"
                                                class="menu-link text-hover-primary px-3" target="_blank"><i
                                                    class="ki-outline ki-eye fs-3 me-2"></i> দেখুন</a>
                                        </div>

                                        <div class="menu-item px-3">
                                            <a href="#" title="প্রতিবেদনটি সংশোধন করুন"
                                                class="menu-link text-hover-primary px-3"><i
                                                    class="ki-outline ki-pencil fs-3 me-2"></i>
                                                সংশোধন</a>
                                        </div>

                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 text-hover-danger delete-report"
                                                title="প্রতিবেদনটি মুছে ফেলুন" data-report-id="{{ $report->id }}"><i
                                                    class="ki-outline ki-trash fs-3 me-2"></i>
                                                মুছুন</a>
                                        </div>
                                    </div>
                                    <!--end::Menu-->
                                @else
                                    <a href="{{ route('reports.show', $report->id) }}" title="প্রতিবেদনটি দেখুন" target="_blank"
                                        class="btn btn-icon text-hover-primary w-30px h-30px edit-teacher me-2">
                                        <i class="ki-outline ki-eye fs-2"></i>
                                    </a>
                                @endif
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
    <script src="{{ asset('js/reports/index.js') }}"></script>

    <script>
        document.getElementById("report_info_menu").classList.add("active");
    </script>
@endpush
