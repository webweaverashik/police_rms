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

@section('title', 'আমার প্রোফাইল')

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            আমার প্রোফাইল
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
                    ইউজার ম্যানেজমেন্ট </a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                প্রোফাইল</li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
@endsection

@section('content')
    <div class="row g-7">

        <!-- ================= LEFT: USER INFORMATION ================= -->
        <div class="col-lg-8">
            <form id="kt_create_user_form" class="form" novalidate>
                <div class="card card-flush py-4 mb-7">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>ব্যক্তিগত তথ্য</h2>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="row g-6">

                            <div class="col-md-6 col-lg-4">
                                <label class="form-label fs-4 required">ইউজারের নাম</label>
                                <input type="text" name="name" class="form-control form-control-solid fs-4">
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label class="form-label fs-4">বিপি নাম্বার</label>
                                <input type="number" name="bp_number" class="form-control form-control-solid fs-4">
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label class="form-label fs-4 required">রোল</label>
                                <select name="role_id" class="form-select form-select-solid fs-4" data-control="select2"
                                    required>
                                    <option></option>
                                </select>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label class="form-label fs-4 required">পদবী</label>
                                <select name="designation_id" class="form-select form-select-solid fs-4"
                                    data-control="select2" required>
                                    <option></option>
                                </select>
                            </div>

                            <div class="col-md-6 col-lg-4 d-none" id="zone-wrapper">
                                <label class="form-label fs-4 required">থানা</label>
                                <select name="zone_id" class="form-select form-select-solid fs-4" data-control="select2">
                                    <option></option>
                                </select>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label class="form-label fs-4 required">ইমেইল</label>
                                <input type="email" name="email" class="form-control form-control-solid fs-4">
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label class="form-label fs-4 required">মোবাইল</label>
                                <input type="number" name="mobile_no" class="form-control form-control-solid fs-4">
                            </div>

                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary w-100px">রিসেট</button>
                        <button type="submit" class="btn btn-primary w-100px">সাবমিট</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ================= RIGHT: PASSWORD + METER ================= -->
        <div class="col-lg-4">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>পাসওয়ার্ড সেট করুন</h2>
                    </div>
                </div>

                <div class="card-body pt-0">

                    <!-- New Password -->
                    <div class="fv-row mb-6">
                        <label class="required fw-semibold fs-6 mb-2">নতুন পাসওয়ার্ড</label>

                        <div class="input-group">
                            <input type="password" id="password_new" class="form-control" autocomplete="off" />

                            <span class="input-group-text toggle-password" data-target="password_new" style="cursor:pointer"
                                title="See Password">
                                <i class="ki-outline ki-eye fs-3"></i>
                            </span>
                        </div>

                        <!-- Strength meter -->
                        <div id="password-strength-text" class="mt-2 fw-bold small text-muted"></div>
                        <div class="progress mt-1" style="height: 5px;">
                            <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%">
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="fv-row mb-6">
                        <label class="required fw-semibold fs-6 mb-2">কনফার্ম পাসওয়ার্ড</label>
                        <input type="password" id="password_confirm" class="form-control" autocomplete="off">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-warning w-150px">
                            পাসওয়ার্ড আপডেট
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection



@push('vendor-js')
@endpush

@push('page-js')
    <script src="{{ asset('js/users/profile.js') }}"></script>

    <script>
        document.getElementById("user_profile_menu").classList.add("active");
    </script>
@endpush
