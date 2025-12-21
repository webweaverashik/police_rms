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

@section('title', 'নতুন ইউজার')

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            নতুন ইউজার
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
                নতুন ইউজার </li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
@endsection

@section('content')
    <!--begin::Form-->
    <form action="#" class="form d-flex flex-column" id="kt_create_user_form" novalidate="novalidate">
        <!-- ===================== Personal Info ===================== -->
        <div class="card card-flush py-4 mb-7">
            <div class="card-header">
                <div class="card-title">
                    <h2>ব্যক্তিগত তথ্য</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="row">
                    <!-- User name -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4 required">ইউজারের নাম</label>
                            <input type="text" name="name" class="form-control form-control-solid fs-4"
                                placeholder="ইউজারের নাম লিখুন">
                        </div>
                    </div>

                    <!-- BP Number -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4 required">বিপি / আইডি নং</label>
                            <input type="number" name="bp_number" class="form-control form-control-solid fs-4"
                                placeholder="ইউজারের বিপি বা আইডি নাম্বার লিখুন" required>
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">রোল
                                <span class="ms-1" data-bs-toggle="tooltip" title="ব্যবহারকারির রোল নির্বাচন করুন">
                                    <i class="ki-outline ki-information fs-4"></i>
                                </span>
                            </label>

                            @php
                                $roleBn = [
                                    'SuperAdmin' => 'সুপার এডমিন',
                                    'Admin' => 'এডমিন',
                                    'Viewer' => 'পর্যবেক্ষক',
                                    'Magistrate' => 'ম্যাজিস্ট্রেট',
                                    'Operator' => 'তৈরিকারি',
                                ];
                            @endphp

                            <select name="role_id" class="form-select form-select-solid fs-4" data-control="select2"
                                data-placeholder="রোল বাছাই করুন" data-hide-search="true" required>
                                <option></option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">
                                        {{ $roleBn[$role->name] ?? $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Designations -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">পদবী
                            </label>
                            <select name="designation_id" class="form-select form-select-solid fs-4" data-control="select2"
                                data-placeholder="পদবী বাছাই করুন" data-hide-search="true" required>
                                <option></option>
                                @foreach ($designations as $designation)
                                    <option value="{{ $designation->id }}">
                                        {{ $designation->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Zone Assignment -->
                    <div class="col-lg-4 d-none">
                        <div class="mb-8 fv-row">
                            <label class="required form-label fs-4">থানা এসাইন করুন
                                <span class="ms-1" data-bs-toggle="tooltip" title="রিপোর্ট দেখাতে থানা নির্বাচন করুন।">
                                    <i class="ki-outline ki-information fs-4"></i>
                                </span>
                            </label>

                            <select name="zone_id" class="form-select form-select-solid fs-4" data-control="select2"
                                data-placeholder="থানা বাছাই করুন" data-hide-search="true" required disabled>
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

        <!-- ===================== Login Information ===================== -->
        <div class="card card-flush py-4 mb-7">
            <div class="card-header">
                <div class="card-title">
                    <h2>সাইন ইন সম্পর্কিত তথ্য</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="row">
                    <!-- Email -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4 required">ইমেইল এড্রেস</label>
                            <input type="email" name="email" class="form-control form-control-solid fs-4"
                                placeholder="ইমেইল এড্রেস লিখুন" required>
                        </div>
                    </div>

                    <!-- Mobile -->
                    <div class="col-lg-4">
                        <div class="mb-8 fv-row">
                            <label class="form-label fs-4 required">মোবাইল নং</label>
                            <input type="number" name="mobile_no" class="form-control form-control-solid fs-4"
                                placeholder="মোবাইল নাম্বার লিখুন" required>
                        </div>
                    </div>


                </div>
            </div>
        </div>


        <!-- ===================== Actions ===================== -->
        <div class="d-flex justify-content-start">
            <button type="reset" id="kt_create_user_form_reset" class="btn btn-secondary me-3 w-100px">রিসেট</button>

            <button type="submit" id="kt_create_user_form_submit" class="btn btn-primary w-150px">
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
        const storeUserRoute = "{{ route('users.store') }}";
    </script>

    <script src="{{ asset('js/users/create.js') }}"></script>

    <script>
        document.getElementById("user_info_menu").classList.add("here", "show");
        document.getElementById("user_list_link").classList.add("active");
    </script>
@endpush
