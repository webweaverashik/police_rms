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
                        <i class="ki-outline ki-filter fs-2"></i>Filter</button>
                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Separator-->
                        <div class="separator border-gray-200"></div>
                        <!--end::Separator-->
                        <!--begin::Content-->
                        <div class="px-7 py-5" data-all-reports-table-filter="form">
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-semibold">Payment Type:</label>
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
                            <i class="ki-outline ki-exit-up fs-2"></i>Export
                        </button>

                        <!--begin::Menu-->
                        <div id="kt_table_report_dropdown_menu"
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                            data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-row-export="copy">Copy to
                                    clipboard</a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-row-export="excel">Export as Excel</a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-row-export="csv">Export as CSV</a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-row-export="pdf">Export as PDF</a>
                            </div>
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
                    <tr class="fw-bold fs-7 gs-0">
                        <th>#</th>
                        <th>সংসদীয় আসন</th>
                        <th>উপজেলা</th>
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
                        <th class="not-export">একশন</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    {{-- @foreach ($reports as $transaction)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>
                                    {{ $transaction->paymentInvoice->invoice_number }}
                            </td>

                            <td>{{ $transaction->voucher_no }}</td>
                            <td>{{ $transaction->amount_paid }}</td>
                            <td class="d-none">
                                @if ($transaction->payment_type === 'partial')
                                    T_partial
                                @elseif ($transaction->payment_type === 'full')
                                    T_full_paid
                                @elseif ($transaction->payment_type === 'discounted')
                                    T_discounted
                                @endif
                            </td>

                            <td>
                                @if ($transaction->payment_type === 'partial')
                                    <span class="badge badge-warning rounded-pill">Partial</span>
                                @elseif ($transaction->payment_type === 'full')
                                    <span class="badge badge-success rounded-pill">Full Paid</span>
                                @elseif ($transaction->payment_type === 'discounted')
                                    <span class="badge badge-info rounded-pill">Discounted</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('students.show', $transaction->student->id) }}">
                                    {{ $transaction->student->name }}, {{ $transaction->student->student_unique_id }}
                                </a>
                            </td>

                            <td class="@if (!auth()->user()->hasRole('admin')) d-none @endif">
                                @php
                                    $branchName = $transaction->student->branch->branch_name;
                                    $badgeColor = $branchColors[$branchName] ?? 'badge-light-secondary'; // Default color
                                @endphp
                                <span class="badge {{ $badgeColor }}">{{ $branchName }}</span>
                            </td>


                            <td>
                                {{ $transaction->created_at->format('h:i:s A, d-M-Y') }}
                            </td>

                            <td>
                                {{ $transaction->createdBy->name ?? 'System' }}
                            </td>

                            <td>
                                @if ($transaction->is_approved === false)
                                    @if ($canApproveTxn)
                                        <a href="#" title="Approve Transaction"
                                            class="btn btn-icon text-hover-success w-30px h-30px approve-txn me-2"
                                            data-txn-id={{ $transaction->id }}>
                                            <i class="bi bi-check-circle fs-2"></i>
                                        </a>
                                    @endif

                                    @if ($canDeleteTxn)
                                        <a href="#" title="Delete Transaction"
                                            class="btn btn-icon text-hover-danger w-30px h-30px delete-txn"
                                            data-txn-id={{ $transaction->id }}>
                                            <i class="bi bi-trash fs-2"></i>
                                        </a>
                                    @endif

                                    @if (!$canApproveTxn)
                                        <span class="badge rounded-pill text-bg-secondary">Pending Approval</span>
                                    @endif
                                @else
                                    @if ($canDownloadPayslip)
                                        <a href="{{ route('transactions.download', $transaction->id) }}" target="_blank"
                                            data-bs-toggle="tooltip" title="Download Payslip"
                                            class="btn btn-icon text-hover-primary w-30px h-30px">
                                            <i class="bi bi-download fs-2"></i>
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach --}}
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
        document.getElementById("report_info_menu").classList.add("here", "show");
        document.getElementById("my_report_link").classList.add("active");
    </script>
@endpush
