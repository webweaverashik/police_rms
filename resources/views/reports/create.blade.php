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

        /* Mini Popup for Program Type */
        .program-type-popup {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin-top: 8px;
            background: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            z-index: 1050;
            display: none;
        }

        .program-type-popup.show {
            display: block;
            animation: popupFadeIn 0.15s ease-out;
        }

        .program-type-popup::before {
            content: '';
            position: absolute;
            top: -6px;
            right: 18px;
            width: 12px;
            height: 12px;
            background: var(--bs-body-bg);
            border-left: 1px solid var(--bs-border-color);
            border-top: 1px solid var(--bs-border-color);
            transform: rotate(45deg);
        }

        @keyframes popupFadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
                    <div class="col-lg-3">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">
                                সংসদীয় আসন
                                <span class="ms-1" data-bs-toggle="tooltip"
                                    title="প্রোগ্রামটি যে সংসদীয় আসনের তা সিলেক্ট করুন">
                                    <i class="ki-outline ki-information fs-4"></i>
                                </span>
                            </label>

                            <select name="parliament_seat_id" class="form-select form-select-solid fs-4"
                                data-control="select2" data-placeholder="আসন বাছাই করুন" data-hide-search="true" required>
                                <option></option>
                                @foreach ($parliamentSeats as $seat)
                                    <option value="{{ $seat->id }}">
                                        {{ $seat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Upazila -->
                    <div class="col-lg-3">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">উপজেলা
                                <span class="ms-1" data-bs-toggle="tooltip"
                                    title="প্রথমে সংসদীয় আসন সিলেক্ট করুন। এরপর সেই আসনের উপজেলার লিস্ট দেখাবে।">
                                    <i class="ki-outline ki-information fs-4"></i>
                                </span>
                            </label>
                            <select name="upazila_id" class="form-select form-select-solid fs-4" data-control="select2"
                                data-placeholder="উপজেলা বাছাই করুন" data-hide-search="true" required disabled>
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <!-- Zone -->
                    <div class="col-lg-3">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">থানা
                                <span class="ms-1" data-bs-toggle="tooltip"
                                    title="প্রথমে উপজেলা সিলেক্ট করুন। এরপর সেই উপজেলার থানার লিস্ট দেখাবে।">
                                    <i class="ki-outline ki-information fs-4"></i>
                                </span>
                            </label>
                            <select name="zone_id" class="form-select form-select-solid fs-4" data-control="select2"
                                data-placeholder="থানা বাছাই করুন" data-hide-search="true" required disabled>
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <!-- Union -->
                    <div class="col-lg-3">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">ইউনিয়ন / পৌরসভা
                                <span class="ms-1" data-bs-toggle="tooltip"
                                    title="প্রথমে উপজেলা সিলেক্ট করুন। এরপর সেই উপজেলার ইউনিয়ন লিস্ট দেখাবে।">
                                    <i class="ki-outline ki-information fs-4"></i>
                                </span>
                            </label>
                            <select name="union_id" class="form-select form-select-solid fs-4" data-control="select2"
                                data-placeholder="ইউনিয়ন বাছাই করুন" data-hide-search="true" disabled required>
                                <option></option>
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
                    <h2>প্রোগ্রামের প্রয়োজনীয় তথ্য</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="row">

                    <!-- Political Party -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">রাজনৈতিক দলের নাম <span class="ms-1"
                                    data-bs-toggle="tooltip"
                                    title="প্রথমে সংসদীয় আসন সিলেক্ট করুন তাহলে সেই আসনের রাজনৈতিক দলের লিস্ট দেখাবে">
                                    <i class="ki-outline ki-information fs-4"></i>
                                </span></label>
                            <select name="political_party_id" class="form-select form-select-solid fs-4"
                                data-control="select2" data-placeholder="রাজনৈতিক দল বাছাই করুন" required disabled>
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <!-- Candidate Name -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">প্রার্থীর নাম <span class="ms-1" data-bs-toggle="tooltip"
                                    title="সংসদীয় আসন ও রাজনৈতিক দল উভয় সিলেক্ট করলে প্রার্থীর নাম অটো চলে আসবে বা আপনি চাইলে নাও দিতে পারেন।">
                                    <i class="ki-outline ki-information fs-4"></i>
                                </span></label>
                            <select name="candidate_name" class="form-select form-select-solid fs-4" data-control="select2"
                                data-placeholder="প্রার্থী বাছাই করুন" data-allow-clear="true" data-hide-search="true"
                                disabled>
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <!-- Program Special Guest -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">প্রধান অতিথি <span class="text-muted fst-italic">(প্রযোজ্য
                                    ক্ষেত্রে)</span></label>
                            <input type="text" name="program_special_guest"
                                class="form-control form-control-solid fs-4" placeholder="প্রধান অতিথির নাম লিখুন">
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
                            <label class="required form-label fs-4">প্রোগ্রামের ধরণ
                                <span class="ms-1" data-bs-toggle="tooltip"
                                    title="প্রোগ্রামের ধরণ সিলেক্ট করুন। আর যদি লিস্টে ঐ সম্পর্কিত ধরণ না পান তাহলে + আইকনে ক্লিক করে নতুন তৈরি করুন।">
                                    <i class="ki-outline ki-information fs-4"></i>
                                </span>
                            </label>

                            <div class="d-flex gap-2 position-relative" id="programTypeWrapper">
                                <select name="program_type_id" class="form-select form-select-solid fs-4 flex-grow-1"
                                    data-control="select2" data-placeholder="প্রোগ্রামের ধরণ বাছাই করুন" required>
                                    <option></option>
                                    @foreach ($programTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>

                                <!-- Add Button -->
                                <button type="button" class="btn btn-light-primary btn-icon" id="toggleProgramTypePopup"
                                    title="নতুন যোগ করুন">
                                    <i class="ki-outline ki-plus fs-2"></i>
                                </button>

                                <!-- Mini Popup -->
                                <div class="program-type-popup" id="programTypePopup">
                                    <div class="d-flex gap-2 align-items-start">
                                        <div class="flex-grow-1">
                                            <input type="text" id="newProgramTypeName"
                                                class="form-control form-control-sm fs-5"
                                                placeholder="নতুন প্রোগ্রামের ধরণের নাম...">
                                            <div class="invalid-feedback" id="programTypeError"></div>
                                        </div>
                                        <button type="button" class="btn btn-icon btn-sm btn-success"
                                            id="saveProgramTypeBtn">
                                            <i class="ki-outline ki-check fs-4"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-sm btn-light-danger"
                                            id="cancelProgramTypeBtn">
                                            <i class="ki-outline ki-cross fs-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program Date -->
                    <div class="col-6 col-lg-2">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">তারিখ <span class="text-muted fst-italic">(প্রযোজ্য
                                    ক্ষেত্রে)</span></label>
                            <div class="flatpickr-wrapper position-relative" id="program_date_wrapper">
                                <input name="program_date" data-input placeholder="তারিখ সিলেক্ট করুন"
                                    class="form-control form-control-solid fs-4 pe-10">
                                <a class="flatpickr-clear position-absolute end-0 top-50 translate-middle-y me-3 d-none"
                                    data-clear title="মুছে ফেলুন" style="cursor: pointer;">
                                    <i class="ki-outline ki-cross fs-2 text-gray-500 text-hover-danger"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Program Time -->
                    <div class="col-6 col-lg-2">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">সময় <span class="text-muted fst-italic">(প্রযোজ্য
                                    ক্ষেত্রে)</span></label>
                            <div class="flatpickr-wrapper position-relative" id="program_time_wrapper">
                                <input name="program_time" data-input placeholder="সময় সেট করুন"
                                    class="form-control form-control-solid fs-4 pe-10">
                                <a class="flatpickr-clear position-absolute end-0 top-50 translate-middle-y me-3 d-none"
                                    data-clear title="মুছে ফেলুন" style="cursor: pointer;">
                                    <i class="ki-outline ki-cross fs-2 text-gray-500 text-hover-danger"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4 ">প্রোগ্রামের স্থান <span
                                    class="text-muted fst-italic">(প্রযোজ্য ক্ষেত্রে)</span></label>
                            <input type="text" name="location_name" class="form-control form-control-solid fs-4"
                                placeholder="প্রোগ্রামের স্থান লিখুন">
                        </div>
                    </div>

                    <!-- Program Status -->
                    <div class="col-lg-4 fv-row">
                        <div class="mb-8">
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
                                    <div class="col-4">
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

                    <!-- Tentative Risks -->
                    <div class="col-lg-2 fv-row">
                        <div class="mb-8">
                            <label class="required form-label fs-4">কোনো ঝুঁকি রয়েছে কি?</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="tentative_risks"
                                        id="tentative_risks_no" value="no" required>

                                    <label for="tentative_risks_no"
                                        class="btn btn-outline btn-outline-dashed border-hover-success btn-active-success btn-radio-lg w-100 d-flex align-items-center fs-4">
                                        <i class="las la-check-circle fs-2x me-3"></i>
                                        <span class="fw-bold">না</span>
                                    </label>
                                </div>

                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="tentative_risks"
                                        id="tentative_risks_yes" value="yes" required>

                                    <label for="tentative_risks_yes"
                                        class="btn btn-outline btn-outline-dashed border-hover-success border-hover-danger btn-active-danger btn-radio-lg w-100 d-flex align-items-center fs-4">
                                        <i class="las la-exclamation-triangle fs-2x me-3"></i>
                                        <span class="fw-bold">হ্যাঁ</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tentative Attendee Count (shown for ongoing/upcoming) -->
                    <div class="col-lg-2" id="tentative_attendee_count_wrapper" style="display: none;">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">সম্ভাব্য উপস্থিতি (জন) <span
                                    class="text-muted fst-italic">(প্রযোজ্য ক্ষেত্রে)</span></label>
                            <input type="number" name="tentative_attendee_count"
                                class="form-control form-control-solid fs-4" placeholder="সম্ভাব্য উপস্থিতি সংখ্যা">
                        </div>
                    </div>

                    <!-- Actual Attendee Count (shown for done) -->
                    <div class="col-lg-2" id="actual_attendee_count_wrapper" style="display: none;">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">মোট উপস্থিতি (জন) <span
                                    class="text-muted fst-italic">(প্রযোজ্য ক্ষেত্রে)</span></label>
                            <input type="number" name="actual_attendee_count"
                                class="form-control form-control-solid fs-4" placeholder="মোট উপস্থিতি সংখ্যা">
                        </div>
                    </div>

                    <!-- Dead/Injured Count (shown for done) -->
                    <div class="col-lg-2" id="dead_injured_count_wrapper" style="display: none;">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">হতাহতের সংখ্যা কত<span
                                    class="text-muted fst-italic">(প্রযোজ্য ক্ষেত্রে)</span></label>
                            <input type="text" name="dead_injured_count"
                                class="form-control form-control-solid fs-4" placeholder="প্রোগ্রামে যদি হতাহত থাকে লিখুন">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ===================== Program Details ===================== -->
        <div class="card card-flush py-4 mb-7">
            <div class="card-header">
                <div class="card-title">
                    <h2>প্রোগ্রামের বিবরণ</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="row">
                    <!-- Location -->
                    <div class="col-lg-12">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4 required">প্রোগ্রামের বিষয়</label>
                            <input type="text" name="program_title" class="form-control form-control-solid fs-4"
                                placeholder="প্রোগ্রামের বিষয় লিখুন" required>
                        </div>
                    </div>

                    <!-- Program Description -->
                    <div class="col-lg-12">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">বিস্তারিত বর্ণনা <span class="text-muted fst-italic">(প্রযোজ্য
                                    ক্ষেত্রে)</span></label>
                            <textarea name="program_description" rows="10" class="form-control form-control-solid fs-4"
                                placeholder="প্রোগ্রামের বিস্তারিত লিখুন"></textarea>
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
        const storeProgramTypeRoute = "{{ route('program-types.store') }}";

        // AJAX routes
        const fetchUpazilasBySeatRoute = "{{ route('ajax.fetch.upazilas.by.seat') }}";
        const fetchZonesByUpazilaRoute = "{{ route('ajax.fetch.zones.by.upazila') }}";
        const fetchUnionRoute = "{{ route('ajax.union', ':upazila_id') }}";
        const fetchSeatPartiesRoute = "{{ route('ajax.seat.parties') }}";
        const fetchCandidateRoute = "{{ route('ajax.seat.party.candidate') }}";
    </script>

    <script src="{{ asset('js/reports/create.js') }}"></script>

    <script>
        document.getElementById("report_info_menu").classList.add("active");
    </script>
@endpush