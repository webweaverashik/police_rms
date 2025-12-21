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
                        data-all-reports-table-filter="search" class="form-control form-control-solid w-250px w-lg-450px ps-12"
                        placeholder="প্রতিবেদনে খুঁজুন যেমন: প্রার্থীর নাম">
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
                <div class="d-flex justify-content-end" data-all-reports-table-toolbar="base">
                    <!--begin::Filter-->
                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-end">
                        <i class="ki-outline ki-filter fs-2"></i>ফিল্টার</button>
                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-450px" data-kt-menu="true">
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
                            <div class="row">
                                <div class="col-6 mb-5">
                                    <label class="form-label fs-6 fw-semibold">সংসদীয় আসন:</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                        data-placeholder="সিলেক্ট করুন" data-allow-clear="true"
                                        data-all-reports-table-filter="status" data-hide-search="true">
                                        <option></option>
                                        @foreach ($parliamentSeats as $seat)
                                            <option value="{{ $seat->id }}_{{ $seat->name }}">{{ $seat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 mb-5">
                                    <label class="form-label fs-6 fw-semibold">উপজেলা:</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                        data-placeholder="সিলেক্ট করুন" data-allow-clear="true"
                                        data-all-reports-table-filter="status" data-hide-search="true">
                                        <option></option>
                                        @foreach ($upazilas as $upazila)
                                            <option value="{{ $upazila->id }}_{{ $upazila->name }}">{{ $upazila->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 mb-5">
                                    <label class="form-label fs-6 fw-semibold">থানা:</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                        data-placeholder="সিলেক্ট করুন" data-allow-clear="true"
                                        data-all-reports-table-filter="status" data-hide-search="true">
                                        <option></option>
                                        @foreach ($zones as $zone)
                                            <option value="{{ $zone->id }}_{{ $zone->name }}">{{ $zone->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 mb-5">
                                    <label class="form-label fs-6 fw-semibold">ইউনিয়ন:</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                        data-placeholder="সিলেক্ট করুন" data-allow-clear="true"
                                        data-all-reports-table-filter="status" data-hide-search="false">
                                        <option></option>
                                        @foreach ($unions as $union)
                                            <option value="{{ $union->id }}_{{ $union->name }}">{{ $union->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 mb-5">
                                    <label class="form-label fs-6 fw-semibold">রাজনৈতিক দল:</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                        data-placeholder="সিলেক্ট করুন" data-allow-clear="true"
                                        data-all-reports-table-filter="status" data-hide-search="false">
                                        <option></option>
                                        @foreach ($politicalParties as $party)
                                            <option value="{{ $party->id }}_{{ $party->name }}">{{ $party->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 mb-5">
                                    <label class="form-label fs-6 fw-semibold">প্রোগ্রামের ধরণ:</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                        data-placeholder="সিলেক্ট করুন" data-allow-clear="true"
                                        data-all-reports-table-filter="status" data-hide-search="false">
                                        <option></option>
                                        @foreach ($programTypes as $type)
                                            <option value="{{ $type->id }}_{{ $type->name }}">{{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if (!auth()->user()->isOperator())
                                    <div class="col-12 mb-5">
                                        <label class="form-label fs-6 fw-semibold">প্রতিবেদন তৈরিকারি:</label>
                                        <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                            data-placeholder="সিলেক্ট করুন" data-allow-clear="true"
                                            data-all-reports-table-filter="status" data-hide-search="false">
                                            <option></option>
                                            @foreach ($reporters as $user)
                                                <option
                                                    value="{{ $user->id }}_{{ $user->name }}_{{ $user->designation->name }}">
                                                    {{ $user->name }}, {{ $user->designation->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>

                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end mt-5">
                                <button type="reset"
                                    class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                    data-kt-menu-dismiss="true" data-all-reports-table-filter="reset">রিসেট</button>
                                <button type="submit" class="btn btn-primary fw-semibold px-6"
                                    data-kt-menu-dismiss="true" data-all-reports-table-filter="filter">এপ্লাই</button>
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
                        <th class="d-none">সংসদীয় আসন (filter)</th>
                        <th>উপজেলা</th>
                        <th class="d-none">উপজেলা (filter)</th>
                        <th>ইউনিয়ন</th>
                        <th class="d-none">ইউনিয়ন (filter)</th>
                        <th>থানা</th>
                        <th class="d-none">থানা (filter)</th>
                        <th>দলের নাম</th>
                        <th class="d-none">দলের নাম (filter)</th>
                        <th>প্রার্থীর নাম</th>
                        <th>প্রধান অতিথি</th>
                        <th>সভাপতি</th>
                        <th>প্রোগ্রামের ধরণ</th>
                        <th class="d-none">প্রোগ্রামের ধরণ (filter)</th>
                        <th>স্থান</th>
                        <th>তারিখ</th>
                        <th>সময়</th>
                        <th>সম্ভাব্য উপস্থিতি</th>
                        <th>প্রোগ্রামের অবস্থা</th>
                        <th class="@if (auth()->user()->role->name == 'Operator') d-none @endif">প্রতিবেদক</th>
                        <th class="d-none">প্রতিবেদক (filter)</th>
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
                            <td>{{ $numto->bnNum($loop->iteration) }}</td>

                            <td>{{ $report->parliamentSeat->name }}</td>
                            <td class="d-none">{{ $report->parliamentSeat->id }}_{{ $report->parliamentSeat->name }}</td>

                            <td>{{ $report->upazila->name }}</td>
                            <td class="d-none">{{ $report->upazila->id }}_{{ $report->upazila->name }}</td>

                            <td>{{ $report->union->name }}</td>
                            <td class="d-none">{{ $report->union->id }}_{{ $report->union->name }}</td>

                            <td>{{ $report->zone->name }}</td>
                            <td class="d-none">{{ $report->zone->id }}_{{ $report->zone->name }}</td>

                            <td>{{ $report->politicalParty->name }}</td>
                            <td class="d-none">{{ $report->politicalParty->id }}_{{ $report->politicalParty->name }}</td>

                            {{-- Candidate (NEW LOGIC) --}}
                            <td>{{ $report->candidate_name ?? '-' }}</td>

                            <td>{{ $report->program_special_guest ?? '-' }}</td>
                            <td>{{ $report->program_chair ?? '-' }}</td>
                            <td>{{ $report->programType?->name ?? '-' }}</td>
                            <td class="d-none">{{ $report->programType->id }}_{{ $report->programType->name }}</td>

                            <td>{{ $report->location_name ?? '-' }}</td>

                            {{-- Date --}}
                            <td>
                                @if ($report->program_date)
                                    {{ $numto->bnNum(\Carbon\Carbon::parse($report->program_date)->format('d')) }}-{{ $numto->bnNum(\Carbon\Carbon::parse($report->program_date)->format('m')) }}-{{ $numto->bnNum(\Carbon\Carbon::parse($report->program_date)->format('Y')) }}
                                @else
                                    -
                                @endif
                            </td>


                            {{-- Time --}}
                            <td>
                                @if ($report->program_time)
                                    {{ $numto->bnNum(\Carbon\Carbon::parse($report->program_time)->format('h')) }}:{{ $numto->bnNum(\Carbon\Carbon::parse($report->program_time)->format('i')) }}
                                    {{ \Carbon\Carbon::parse($report->program_time)->format('A') =='AM' ? 'পূর্বাহ্ণ' : 'অপরাহ্ণ' }}
                                @else
                                    -
                                @endif
                            </td>



                            <td>
                                {{ $report->tentative_attendee_count ? $numto->bnNum($report->tentative_attendee_count) . ' জন' : '-' }}
                            </td>

                            {{-- Status --}}
                            <td>
                                @php
                                    $statusMap = [
                                        'done' => ['label' => 'সম্পন্ন', 'class' => 'badge-success'],
                                        'ongoing' => ['label' => 'চলমান', 'class' => 'badge-danger'],
                                        'upcoming' => ['label' => 'আসন্ন', 'class' => 'badge-info'],
                                    ];
                                    $status = $statusMap[$report->program_status];
                                @endphp
                                <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                            </td>

                            {{-- Reporter (hidden for Operator) --}}
                            <td class="@if (auth()->user()->role->name == 'Operator') d-none @endif">
                                {{ $report->createdBy->name }}, {{ $report->createdBy->designation->name }}
                            </td>

                            <td class="d-none">
                                {{ $report->createdBy->name }},
                                {{ $report->createdBy->designation->name }}
                                {{ $report->created_by }}_{{ $report->createdBy->name }}_{{ $report->createdBy->designation->name }}
                            </td>

                            {{-- Actions --}}
                            <td>
                                @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                    {{-- Full action menu --}}
                                    <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">একশন
                                        <i class="ki-outline ki-down fs-5 m-0"></i></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4"
                                        data-kt-menu="true">

                                        <div class="menu-item px-3">
                                            <a href="{{ route('reports.show', $report->id) }}"
                                                class="menu-link text-hover-primary px-3"><i
                                                    class="ki-outline ki-eye fs-3 me-2"></i> প্রতিবেদন দেখুন</a>
                                        </div>

                                        <div class="menu-item px-3">
                                            <a href="{{ route('reports.download', $report->id) }}"
                                                class="menu-link text-hover-primary px-3" target="_blank"><i
                                                    class="bi bi-download fs-3 me-2"></i> ডাউনলোড করুন</a>
                                        </div>

                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 text-hover-warning assign-report"
                                                data-report-id="{{ $report->id }}"><i
                                                    class="ki-outline ki-user-tick fs-3 me-2"></i>
                                                ম্যাজিস্ট্রেটকে প্রেরণ</a>
                                        </div>

                                        @if (auth()->user()->isSuperAdmin())
                                            <div class="menu-item px-3">
                                                <a href="{{ route('reports.edit', $report->id) }}"
                                                    class="menu-link text-hover-primary px-3"><i
                                                        class="ki-outline ki-pencil fs-3 me-2"></i>
                                                    প্রতিবেদন সংশোধন</a>
                                            </div>

                                            {{-- <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 text-hover-danger delete-report"
                                                    data-report-id="{{ $report->id }}"><i
                                                        class="ki-outline ki-trash fs-3 me-2"></i>
                                                    প্রতিবেদন মুছুন</a>
                                            </div> --}}
                                        @endif
                                    </div>
                                    <!--end::Menu-->
                                @else
                                    <a href="{{ route('reports.show', $report->id) }}" target="_blank"
                                        class="btn btn-icon text-hover-primary">
                                        <i class="ki-outline ki-eye fs-2"></i>
                                    </a>

                                    <a href="{{ route('reports.download', $report->id) }}"
                                        class="btn btn-icon text-hover-primary px-3" target="_blank"><i
                                            class="bi bi-download fs-2 me-2"></i></a>
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

    <div class="modal fade" id="assignMagistrateModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">ম্যাজিস্ট্রেট নির্বাচন</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input id="magistrateTagify" class="form-control">
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button class="btn btn-primary" id="saveMagistrateAssignment">সংরক্ষণ</button>
                </div>

            </div>
        </div>
    </div>

@endsection


@push('vendor-js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('page-js')
    <script>
        const reportDeleteRoute = "{{ route('reports.destroy', ':id') }}";
        const reportMagistratesRoute = "{{ route('reports.magistrates', ':id') }}";
        const reportAssignRoute = "{{ route('reports.assignMagistrates', ':id') }}";
    </script>

    <script src="{{ asset('js/reports/index.js') }}"></script>

    <script>
        document.getElementById("report_info_menu").classList.add("active");
    </script>
@endpush
