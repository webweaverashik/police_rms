@extends('layouts.app')


@push('page-css')
    <style>
        .select2-container--bootstrap5 .select2-selection--single {
            font-size: 1.25rem;
            min-height: 40px;
        }

        .select2-container--bootstrap5 .select2-results__option {
            font-size: 1.25rem;
        }
    </style>
@endpush

@section('title', 'নতুন প্রতিবেদন')

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            নতুন প্রতিবেদন যুক্ত করুন
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
                নতুন প্রতিবেদন </li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
@endsection

@section('content')
    <!--begin::Form-->
    <form action="#" class="form d-flex flex-column" id="kt_create_report_form" novalidate="novalidate">
        <!-- ===================== Administrative Jurisdiction ===================== -->
        <div class="card card-flush py-4 mb-7">
            <div class="card-header">
                <div class="card-title">
                    <h2>প্রশাসনিক অধিক্ষেত্রের তথ্য</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="row">
                    <!-- Parliament Seat -->
                    <div class="col-lg-12">
                        <div class="mb-8 fv-row">
                            <label class="required fw-semibold fs-4 mb-4 d-block">
                                সংসদীয় আসন
                                <span class="ms-1" data-bs-toggle="tooltip"
                                    title="প্রোগ্রামটি যে সংসদীয় আসনের তা সিলেক্ট করুন">
                                    <i class="ki-outline ki-information fs-4"></i>
                                </span>
                            </label>

                            <div class="row row-cols-2 row-cols-xl-4 g-4">
                                @foreach ($parliamentSeats as $seat)
                                    <div class="col">
                                        <label
                                            class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6"
                                            data-kt-button="true">
                                            <!--begin::Radio-->
                                            <span
                                                class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                <input class="form-check-input" type="radio" name="parliament_seat_id"
                                                    value="{{ $seat->id }}" required />
                                            </span>
                                            <!--end::Radio-->
                                            <!--begin::Info-->
                                            <span class="ms-5">
                                                <span class="fs-4 fw-bold text-gray-800 d-block">{{ $seat->name }}</span>
                                            </span>
                                            <!--end::Info-->
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Upazila -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">উপজেলা</label>
                            <select name="upazila_id" class="form-select form-select-solid fs-4" data-control="select2"
                                data-placeholder="উপজেলা বাছাই করুন" data-allow-clear="true" data-hide-search="true"
                                required>
                                <option></option>
                                @foreach ($upazilas as $upazila)
                                    <option value="{{ $upazila->id }}">
                                        {{ $upazila->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Zone -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">ইউনিয়ন
                                <span class="ms-1" data-bs-toggle="tooltip"
                                    title="প্রথম উপজেলা সিলেক্ট করুন। এরপর সেই উপজেলার ইউনিয়ন লিস্ট দেখাবে।">
                                    <i class="ki-outline ki-information fs-4"></i>
                                </span>
                            </label>
                            <select name="union_id" class="form-select form-select-solid fs-4" data-control="select2"
                                data-placeholder="ইউনিয়ন বাছাই করুন" data-allow-clear="true" disabled required>
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <!-- Zone -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">থানা / জোন</label>
                            <select name="zone_id" class="form-select form-select-solid fs-4" data-control="select2"
                                data-placeholder="থানা / জোন বাছাই করুন" data-allow-clear="true" required>
                                <option></option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ===================== Political Information ===================== -->
        <div class="card card-flush py-4 mb-7">
            <div class="card-header">
                <div class="card-title">
                    <h2>প্রোগ্রামের প্রয়োজনীয় তথ্য</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="row">

                    <!-- Political Party -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">রাজনৈতিক দলের নাম</label>
                            <select name="political_party_id" class="form-select form-select-solid fs-4"
                                data-control="select2" data-placeholder="রাজনৈতিক দল বাছাই করুন" data-allow-clear="true"
                                required>
                                <option></option>
                                @foreach ($politicalParties as $party)
                                    <option value="{{ $party->id }}">
                                        {{ $party->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Candidate Name -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">প্রার্থীর নাম</label>
                            <input type="text" name="candidate_name" class="form-control form-control-solid fs-4"
                                placeholder="প্রার্থীর নাম লিখুন" required>
                        </div>
                    </div>

                    <!-- Program Special Guest -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">প্রধান অতিথি <span class="text-muted fst-italic">(প্রযোজ্য
                                    ক্ষেত্রে)</span></label>
                            <input type="text" name="program_special_guest" class="form-control form-control-solid fs-4"
                                placeholder="প্রধান অতিথির নাম লিখুন">
                        </div>
                    </div>

                    <!-- Program Chair -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">প্রোগ্রামের সভাপতি <span
                                    class="text-muted fst-italic">(প্রযোজ্য
                                    ক্ষেত্রে)</span></label>
                            <input type="text" name="program_chair" class="form-control form-control-solid fs-4"
                                placeholder="সভাপতির নাম লিখুন">
                        </div>
                    </div>

                    <!-- Program Type -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">প্রোগ্রামের ধরণ</label>
                            <select name="program_type_id" class="form-select form-select-solid fs-4"
                                data-control="select2" data-placeholder="প্রোগ্রামের ধরণ বাছাই করুন"
                                data-allow-clear="true" required>
                                <option></option>
                                @foreach ($programTypes as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Program Date & Time -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">তারিখ ও সময়</label>
                            <input name="program_date_time" id="program_date_time_picker"
                                placeholder="তারিখ ও সময় সেট করুন" class="form-control form-control-solid fs-4" required>
                        </div>
                    </div>
                    
                    <!-- Tentative Attendee Count -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">সম্ভাব্য উপস্থিতি (জন) <span
                                    class="text-muted fst-italic">(প্রযোজ্য ক্ষেত্রে)</span></label>
                            <input type="number" name="tentative_attendee_count"
                                class="form-control form-control-solid fs-4" placeholder="সম্ভাব্য উপস্থিতি সংখ্যা">
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4 required">প্রোগ্রামের স্থান</label>
                            <input type="text" name="location_name" class="form-control form-control-solid fs-4"
                                placeholder="প্রোগ্রামের স্থান লিখুন">
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
        
        <!-- ===================== Program Details ===================== -->
        <div class="card card-flush py-4 mb-7">
            <div class="card-header">
                <div class="card-title">
                    <h2>প্রোগ্রামের বিস্তারিত বিবরণ</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="row">
                    <!-- Location -->
                    <div class="col-lg-5">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4 required">প্রোগ্রামের বিষয়</label>
                            <input type="text" name="program_title" class="form-control form-control-solid fs-4"
                                placeholder="প্রোগ্রামের বিষয় লিখুন">
                        </div>
                    </div>
                    
                    <!-- Program Status -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">প্রোগ্রামের অবস্থা</label>

                            @php
                                $statuses = [
                                    'done' => ['label' => 'সম্পন্ন', 'icon' => 'las la-check-circle'],
                                    'ongoing' => ['label' => 'চলমান', 'icon' => 'las la-spinner'],
                                    'upcoming' => ['label' => 'আসন্ন', 'icon' => 'las la-clock'],
                                ];
                            @endphp

                            <div class="row g-3">
                                @foreach ($statuses as $key => $status)
                                    <div class="col">
                                        <input type="radio" class="btn-check" name="program_status"
                                            id="status_{{ $key }}" value="{{ $key }}" required>

                                        <label for="status_{{ $key }}"
                                            class="btn btn-outline btn-outline-dashed btn-active-light-primary
                                               btn-radio-lg w-100 d-flex align-items-center fs-4">
                                            <i class="{{ $status['icon'] }} fs-2x me-3"></i>
                                            <span class="fw-bold">{{ $status['label'] }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Final Attendee Count -->
                    <div class="col-lg-3">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">মোট উপস্থিতি (জন) <span class="text-muted fst-italic">(প্রযোজ্য
                                    ক্ষেত্রে)</span></label>
                            <input type="number" name="final_attendee_count"
                                class="form-control form-control-solid fs-4" placeholder="মোট উপস্থিতি সংখ্যা">
                        </div>
                    </div>

                    <!-- Program Description -->
                    <div class="col-lg-12">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4 required">বিস্তারিত বর্ণনা</label>
                            <textarea name="program_description" rows="6" class="form-control form-control-solid fs-4"
                                placeholder="প্রোগ্রামের বিস্তারিত লিখুন" required></textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ===================== Actions ===================== -->
        <div class="d-flex justify-content-start">
            <button type="reset" id="kt_create_report_form_reset" class="btn btn-secondary me-3 w-100px">রিসেট</button>

            <button type="submit" id="kt_create_report_form_submit" class="btn btn-primary w-100px">
                <span class="indicator-label">সাবমিট</span>
                <span class="indicator-progress">অপেক্ষা করুন...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>

    </form>
    <!--end::Form-->

@endsection


@push('vendor-js')
@endpush

@push('page-js')
    <script>
        const storeReportRoute = "{{ route('reports.store') }}";
        const fetchUnionRoute = "{{ route('ajax.union', ':upazila_id') }}";
    </script>

    <script src="{{ asset('js/reports/create.js') }}"></script>

    <script>
        document.getElementById("report_info_menu").classList.add("active");
    </script>
@endpush
