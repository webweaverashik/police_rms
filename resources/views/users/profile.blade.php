@extends('layouts.app')

@push('page-css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        /* ================= READONLY SECTION (LIGHT MODE) ================= */
        .readonly-section {
            border: 1px dashed #cfd3e1;
            border-radius: 8px;
            padding: 16px;
            background-color: #f9fafb;
            transition: all .2s ease;
            cursor: help;
        }

        .readonly-section:hover {
            background-color: #f1f4f8;
            border-color: #b5b9cc;
        }

        .readonly-value {
            font-size: 1.25rem;
            color: #3f4254;
            font-weight: 500;
        }

        /* ================= DARK MODE SUPPORT ================= */
        [data-bs-theme="dark"] .readonly-section {
            background-color: #1e1e2d;
            border-color: #323248;
        }

        [data-bs-theme="dark"] .readonly-section:hover {
            background-color: #2b2b40;
            border-color: #474761;
        }

        [data-bs-theme="dark"] .readonly-value {
            color: #e1e1ef;
        }

        [data-bs-theme="dark"] .readonly-section .form-label {
            color: #a1a5b7 !important;
        }
    </style>
@endpush


@section('title', 'আমার প্রোফাইল')

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">

        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            আমার প্রোফাইল
        </h1>

        <span class="h-20px border-gray-300 border-start mx-4"></span>

        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="#" class="text-muted text-hover-primary">ইউজার ম্যানেজমেন্ট</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">প্রোফাইল</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="row g-7">

        <!-- ================= LEFT: PROFILE INFO ================= -->
        <div class="col-lg-8">
            <form id="kt_create_user_form" class="form" novalidate data-update-url="{{ route('profile.update') }}">
                @csrf

                <div class="card card-flush py-4 mb-7">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>ব্যক্তিগত তথ্য</h2>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="row g-6">

                            <!-- Inside the personal info card-body -->

                            <!-- Name -->
                            <div class="col-md-6 fv-row">
                                <label class="form-label fs-4 required">ইউজারের নাম</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}"
                                    class="form-control form-control-solid fs-4" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 fv-row">
                                <label class="form-label fs-4 required">ইমেইল</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}"
                                    class="form-control form-control-solid fs-4" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Mobile -->
                            <div class="col-md-6 fv-row">
                                <label class="form-label fs-4 required">মোবাইল</label>
                                <input type="text" name="mobile_no" value="{{ auth()->user()->mobile_no }}"
                                    class="form-control form-control-solid fs-4" required maxlength="11">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- BP Number (optional) -->
                            <div class="col-md-6 fv-row">
                                <label class="form-label fs-4">বিপি নাম্বার <span
                                        class="text-muted fst-italic">(ঐচ্ছিক)</span></label>
                                <input type="text" name="bp_number" value="{{ auth()->user()->bp_number }}"
                                    class="form-control form-control-solid fs-4">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- ================= READ ONLY SECTION ================= -->
                            <div class="col-12">
                                <div class="readonly-section" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="এই তথ্যগুলো পরিবর্তন করা যাবে না।">
                                    <div class="row g-6">
                                        @php
                                            $roleBn = [
                                                'SuperAdmin' => 'সুপার এডমিন',
                                                'Admin' => 'এডমিন',
                                                'Viewer' => 'পর্যবেক্ষক',
                                                'Magistrate' => 'ম্যাজিস্ট্রেট',
                                                'Operator' => 'অপারেটর',
                                            ];
                                        @endphp

                                        <div class="col-md-4">
                                            <label class="form-label fs-4 text-muted">রোল</label>
                                            <div class="readonly-value">
                                                {{ $roleBn[auth()->user()->role->name] ?? auth()->user()->role->name }}
                                            </div>
                                        </div>



                                        <div class="col-md-4">
                                            <label class="form-label fs-4 text-muted">পদবী</label>
                                            <div class="readonly-value">
                                                {{ auth()->user()->designation->name }}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fs-4 text-muted">থানা</label>
                                            <div class="readonly-value">
                                                {{ auth()->user()->zone->name ?? '-' }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- ================= END READ ONLY SECTION ================= -->

                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-3">
                        <button type="submit" class="btn btn-primary w-150px">
                            <span class="indicator-label">আপডেট করুন</span>
                            <span class="indicator-progress">
                                অপেক্ষা করুন...
                                <span class="spinner-border spinner-border-sm ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ================= RIGHT: PASSWORD RESET ================= -->
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
                            <input type="password" name="password_new" class="form-control mb-3 mb-lg-0"
                                placeholder="নতুন পাসওয়ার্ডটি লিখুন" required id="userPasswordNew" autocomplete="off" />
                            <span class="input-group-text toggle-password" data-target="userPasswordNew"
                                style="cursor: pointer;">
                                <i class="ki-outline ki-eye
                                fs-3"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="fv-row mb-6">
                        <label class="required fw-semibold fs-6 mb-2">কনফার্ম পাসওয়ার্ড</label>
                        <div class="input-group">
                            <input type="password" name="password_confirm" class="form-control mb-3 mb-lg-0"
                                placeholder="নতুন পাসওয়ার্ডটি লিখুন" required id="userConfirmPassword"
                                autocomplete="off" />
                            <span class="input-group-text toggle-password" data-target="userConfirmPassword"
                                style="cursor: pointer;">
                                <i class="ki-outline ki-eye
                                fs-3"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Password Strength Meter -->
                    <div class="mb-6">
                        <div class="fs-6 fw-semibold text-muted mb-2">পাসওয়ার্ডের শক্তি</div>
                        <div id="password-strength-text" class="fw-bold fs-5 mb-2"></div>
                        <div class="progress h-8px">
                            <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%;">
                            </div>
                        </div>
                        <div class="text-muted fs-7 mt-2">
                            অন্তত ৮ অক্ষর, একটি বড় হাতের অক্ষর, একটি ছোট হাতের অক্ষর, একটি সংখ্যা এবং একটি বিশেষ অক্ষর
                            ব্যবহার করুন।
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-warning w-150px" id="password_update_btn"
                            data-url="{{ route('users.password.reset', $user->id) }}">
                            <span class="indicator-label">পাসওয়ার্ড আপডেট</span>
                            <span class="indicator-progress" style="display:none;">
                                অপেক্ষা করুন...
                                <span class="spinner-border spinner-border-sm ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= BOTTOM: LOGIN HISTORY ================= -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <h2 class="mb-0">My Login Activities</h2>

                        <div class="d-flex align-items-center position-relative">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" data-login-activities-table-filter="search"
                                class="form-control form-control-solid w-200px w-lg-350px ps-13"
                                placeholder="লগ অনুসন্ধান করুন" />
                        </div>
                    </div>
                </div>


                <div class="card-body py-4">
                    <!--begin::Table-->
                    <table class="table table-hover align-middle table-row-dashed fs-6 gy-5 prms-table"
                        id="kt_login_activities_table">
                        <thead>
                            <tr class="fw-bold fs-5 gs-0">
                                <th class="w-25px">#</th>
                                <th>IP Address</th>
                                <th>User Agent</th>
                                <th>Device</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold fs-5">
                            @foreach ($loginActivities as $activity)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $activity->ip_address }}</td>
                                    <td>{{ $activity->user_agent }}</td>
                                    <td>{{ $activity->device }}</td>
                                    <td>{{ $activity->created_at->diffForHumans() }} <span class="ms-1"
                                            data-bs-toggle="tooltip"
                                            title="{{ $activity->created_at->format('d-m-Y, h:i A') }}">
                                            <i class="ki-outline ki-information fs-4"></i>
                                        </span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
            </div>
        </div>

    </div>
@endsection

@push('vendor-js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('page-js')
    <script src="{{ asset('js/users/profile.js') }}"></script>
    <script>
        document.getElementById('user_profile_menu')?.classList.add('active');

        // Enable Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
    </script>
@endpush
