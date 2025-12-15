@extends('layouts.app')


@push('page-css')
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
    <form action="{{ route('reports.store') }}" method="POST" class="form d-flex flex-column">
        @csrf

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
                                                    value="{{ $seat->id }}"
                                                    {{ old('parliament_seat_id') == $seat->id ? 'checked' : '' }}
                                                    required />
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
                    <div class="col-lg-6">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">উপজেলা</label>
                            <select name="upazila_id" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="উপজেলা বাছাই করুন" data-allow-clear="true" required>
                                <option></option>
                                @foreach ($upazilas as $upazila)
                                    <option value="{{ $upazila->id }}"
                                        {{ old('upazila_id') == $upazila->id ? 'selected' : '' }}>
                                        {{ $upazila->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Zone -->
                    <div class="col-lg-6">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">থানা / জোন</label>
                            <select name="zone_id" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="থানা / জোন বাছাই করুন" data-allow-clear="true" required>
                                <option></option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
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
                    <h2>রাজনৈতিক দলের তথ্য</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="row">

                    <!-- Political Party -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">রাজনৈতিক দলের নাম</label>
                            <select name="political_party_id" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="রাজনৈতিক দল বাছাই করুন" data-allow-clear="true" required>
                                <option></option>
                                @foreach ($politicalParties as $party)
                                    <option value="{{ $party->id }}"
                                        {{ old('political_party_id') == $party->id ? 'selected' : '' }}>
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
                            <input type="text" name="candidate_name" class="form-control form-control-solid"
                                value="{{ old('candidate_name') }}" placeholder="প্রার্থীর নাম লিখুন" required>
                        </div>
                    </div>

                    <!-- Program Special Guest -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">প্রধান অতিথি</label>
                            <input type="text" name="program_special_guest" class="form-control form-control-solid"
                                value="{{ old('program_special_guest') }}" placeholder="প্রধান অতিথির নাম লিখুন">
                        </div>
                    </div>

                    <!-- Program Chair -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">প্রোগ্রামের সভাপতি</label>
                            <input type="text" name="program_chair" class="form-control form-control-solid"
                                value="{{ old('program_chair') }}" placeholder="সভাপতির নাম লিখুন">
                        </div>
                    </div>


                    <!-- Program Type -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">প্রোগ্রামের ধরণ</label>
                            <select name="program_type_id" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="প্রোগ্রামের ধরণ বাছাই করুন" data-allow-clear="true" required>
                                <option></option>
                                @foreach ($programTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('program_type_id') == $type->id ? 'selected' : '' }}>
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
                            <input type="datetime-local" name="program_date_time" class="form-control form-control-solid"
                                value="{{ old('program_date_time') }}" required>
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

                    <!-- Tentative Attendee Count -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">সম্ভাব্য উপস্থিতি</label>
                            <input type="number" name="tentative_attendee_count" class="form-control form-control-solid"
                                value="{{ old('tentative_attendee_count') }}" placeholder="সম্ভাব্য উপস্থিতি সংখ্যা"
                                required>
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
                                $selectedStatus = old('program_status', 'upcoming');
                            @endphp

                            <div class="row g-3">
                                @foreach ($statuses as $key => $status)
                                    <div class="col">
                                        <input type="radio" class="btn-check" name="program_status"
                                            id="status_{{ $key }}" value="{{ $key }}"
                                            {{ $selectedStatus === $key ? 'checked' : '' }} required>

                                        <label for="status_{{ $key }}"
                                            class="btn btn-outline btn-outline-dashed btn-active-light-primary
                                               btn-radio-lg w-100 d-flex align-items-center">
                                            <i class="{{ $status['icon'] }} fs-2x me-3"></i>
                                            <span class="fw-bold">{{ $status['label'] }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Final Attendee Count -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">মোট উপস্থিতি</label>
                            <input type="number" name="final_attendee_count" class="form-control form-control-solid"
                                value="{{ old('final_attendee_count') }}" placeholder="মোট উপস্থিতি সংখ্যা">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-lg-12">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4">বিস্তারিত বর্ণনা</label>
                            <textarea name="description" rows="6" class="form-control form-control-solid"
                                placeholder="প্রোগ্রামের বিস্তারিত লিখুন">{{ old('description') }}</textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ===================== Actions ===================== -->
        <div class="d-flex justify-content-end">
            <button type="reset" class="btn btn-secondary me-5">রিসেট</button>
            <button type="submit" class="btn btn-primary">সাবমিট</button>
        </div>

    </form>
    <!--end::Form-->

@endsection


@push('vendor-js')
@endpush

@push('page-js')
    <script src="{{ asset('js/reports/create.js') }}"></script>

    <script>
        document.getElementById("report_info_menu").classList.add("here", "show");
        document.getElementById("add_report_link").classList.add("active");
    </script>
@endpush
