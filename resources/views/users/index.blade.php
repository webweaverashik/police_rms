@push('page-css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush


@extends('layouts.app')

@section('title', 'ব্যবহারকারি')

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            সকল ইউজার তালিকা
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
                    সেটিংস </a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                ব্যবহারকারি </li>
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
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5">
                    </i>
                    <input type="text" data-kt-user-table-filter="search"
                        class="form-control form-control-solid w-250px ps-13" placeholder="ব্যবহারকারি অনুসন্ধান করুন" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-all-user-table-filter="base">
                    <!--begin::Filter-->
                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-end">
                        <i class="ki-outline ki-filter fs-2"></i>ফিল্টার</button>
                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-350px" data-kt-menu="true">
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-gray-900 fw-bold">ফিল্টার অপশন</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Separator-->
                        <div class="separator border-gray-200"></div>
                        <!--end::Separator-->
                        <!--begin::Content-->
                        <div class="px-7 py-5" data-all-user-table-filter="form">
                            <div class="mb-5">
                                <label class="form-label fs-6 fw-semibold">রোল:</label>
                                @php
                                    $roleBn = [
                                        'SuperAdmin' => 'সুপার এডমিন',
                                        'Admin' => 'এডমিন',
                                        'Viewer' => 'পর্যবেক্ষক',
                                        'Magistrate' => 'ম্যাজিস্ট্রেট',
                                        'Operator' => 'তৈরিকারি',
                                    ];
                                @endphp

                                <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                    data-placeholder="সিলেক্ট করুন" data-allow-clear="true"
                                    data-all-user-table-filter="status" data-hide-search="true">
                                    <option></option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}_{{ $role->name }}">
                                            {{ $roleBn[$role->name] ?? $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end mt-5">
                                <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                    data-kt-menu-dismiss="true" data-all-user-table-filter="reset">রিসেট</button>
                                <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true"
                                    data-all-user-table-filter="filter">এপ্লাই</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Menu 1-->

                    <!--begin::Add user-->
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="ki-outline ki-plus fs-2"></i>নতুন ইউজার</a>
                    <!--end::Add user-->
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <table class="table table-hover align-middle table-row-dashed fs-6 gy-5 prms-table" id="kt_table_users">
                <thead>
                    <tr class="fw-bold fs-5 gs-0">
                        <th class="w-25px">#</th>
                        <th>ইউজারের তথ্য</th>
                        <th>বিপি নম্বর</th>
                        <th>এসাইনকৃত থানা</th>
                        <th>ইমেইল</th>
                        <th>মোবাইল</th>
                        <th>রোল</th>
                        <th class="d-none">রোল (filter)</th>
                        <th>সর্বশেষ লগিন</th>
                        <th>সক্রিয়/নিষ্ক্রিয়</th>
                        <th>কার্যক্রম</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold fs-5">
                    @php
                        use Rakibhstu\Banglanumber\NumberToBangla;

                        $numto = new NumberToBangla();
                    @endphp

                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $numto->bnNum($loop->index + 1) }}</td>
                            <td>
                                <!--begin::user details-->
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 mb-1">{{ $user->name }}</span>
                                    <span class="fw-semibold">
                                        {{ $user->designation->name }}
                                        {{ $user->zones && $user->zones->isNotEmpty() && $user->isOperator() ? ', ' . $user->zones->first()->name : '' }}
                                    </span>

                                </div>
                            </td>
                            <td>{{ $user->bp_number ? $numto->bnNum($user->bp_number) : '-' }}</td>
                            <td>{{ $user->zone ? $user->zone->name : '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $numto->bnNum($user->mobile_no) }}</td>

                            @php
                                $roleBadges = [
                                    'SuperAdmin' => ['label' => 'সুপার এডমিন', 'class' => 'badge-danger'],
                                    'Admin' => ['label' => 'এডমিন', 'class' => 'badge-success'],
                                    'Viewer' => ['label' => 'পর্যবেক্ষক', 'class' => 'badge-primary'],
                                    'Magistrate' => ['label' => 'ম্যাজিস্ট্রেট', 'class' => 'badge-warning'],
                                    'Operator' => ['label' => 'তৈরিকারি', 'class' => 'badge-info'],
                                ];

                                $roleName = $user->role->name ?? null;
                            @endphp
                            <td>
                                @if ($roleName && isset($roleBadges[$roleName]))
                                    <span class="badge {{ $roleBadges[$roleName]['class'] }}">
                                        {{ $roleBadges[$roleName]['label'] }}
                                    </span>
                                @else
                                    <span class="badge badge-light">N/A</span>
                                @endif
                            </td>
                            <td class="d-none">{{ $user->role_id }}_{{ $user->role->name }}</td>

                            <td>
                                {{ $numto->bnNum($user->created_at->format('d')) }}-
                                {{ $numto->bnNum($user->created_at->format('m')) }}-
                                {{ $numto->bnNum($user->created_at->format('Y')) }},
                                {{ $numto->bnNum($user->created_at->format('h')) }}:
                                {{ $numto->bnNum($user->created_at->format('i')) }}
                                {{ $user->created_at->format('A') }}
                            </td>
                            <td>
                                @if ($user->id != auth()->user()->id)
                                    @if ($user->is_active == 0)
                                        <div
                                            class="form-check form-switch form-check-solid form-check-success d-flex justify-content-center">
                                            <input class="form-check-input toggle-active" type="checkbox"
                                                value="{{ $user->id }}">
                                        </div>
                                    @elseif ($user->is_active == 1)
                                        <div
                                            class="form-check form-switch form-check-solid form-check-success d-flex justify-content-center">
                                            <input class="form-check-input toggle-active" type="checkbox"
                                                value="{{ $user->id }}" checked />
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($user->id == auth()->user()->id)
                                    <a href="{{ route('profile') }}" title="আমার প্রোফাইল" data-bs-toggle="tooltip"
                                        class="btn btn-icon text-hover-success w-30px h-30px me-3">
                                        <i class="ki-outline ki-eye fs-2"></i>
                                    </a>
                                @elseif ($user->id != auth()->user()->id)
                                    <a href="{{ route('users.edit', $user->id) }}" title="সংশোধন"
                                        class="btn btn-icon text-hover-primary w-30px h-30px">
                                        <i class="ki-outline ki-pencil fs-2"></i>
                                    </a>

                                    <a href="#" title="পাসওয়ার্ড পরিবর্তন" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_edit_password" data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        class="btn btn-icon text-hover-primary w-30px h-30px change-password-btn">
                                        <i class="ki-outline ki-key fs-2"></i>
                                    </a>

                                    <a href="#" title="ডিলিট" data-bs-toggle="tooltip"
                                        class="btn btn-icon text-hover-danger w-30px h-30px delete-user"
                                        data-user-id="{{ $user->id }}">
                                        <i class="ki-outline ki-trash fs-2"></i>
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

    <!--begin::Modal - Edit User Password-->
    <div class="modal fade" id="kt_modal_edit_password" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-450px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_edit_password_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold" id="kt_modal_edit_password_title">পাসওয়ার্ড রিসেট</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-edit-password-modal-action="close">
                        <i class="ki-outline ki-cross fs-1">
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body px-5 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_edit_password_form" class="form" action="#" novalidate="novalidate"
                        autocomplete="off">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column scroll-y px-5" id="kt_modal_edit_password_scroll"
                            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_modal_edit_password_header"
                            data-kt-scroll-wrappers="#kt_modal_edit_password_scroll" data-kt-scroll-offset="300px">
                            <div class="row">
                                <div class="col-lg-12">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">নতুন পাসওয়ার্ড</label>
                                        <!--end::Label-->

                                        <div class="input-group">
                                            <input type="password" name="new_password" id="teacherPasswordNew"
                                                class="form-control mb-3 mb-lg-0" placeholder="Enter New Password"
                                                required autocomplete="off" />
                                            <span class="input-group-text toggle-password"
                                                data-target="teacherPasswordNew" style="cursor: pointer;"
                                                title="See Password" data-bs-toggle="tooltip">
                                                <i class="ki-outline ki-eye fs-3"></i>
                                            </span>
                                        </div>

                                        <!-- Password strength meter -->
                                        <div id="password-strength-text" class="mt-1 fw-bold small text-muted"></div>
                                        <div class="progress mt-1" style="height: 5px;">
                                            <div id="password-strength-bar" class="progress-bar" role="progressbar"
                                                style="width: 0%"></div>
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                            </div>
                        </div>
                        <!--end::Scroll-->

                        <!--begin::Actions-->
                        <div class="text-center pt-10">
                            <button type="reset" class="btn btn-light me-3"
                                data-kt-edit-password-modal-action="cancel">বাতিল</button>
                            <button type="submit" class="btn btn-success" data-kt-edit-password-modal-action="submit">
                                <span class="indicator-label">আপডেট</span>
                                <span class="indicator-progress">অপেক্ষা করুন...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Edit User Password-->
@endsection


@push('vendor-js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('page-js')
    <script>
        const routeDeleteUser = "{{ route('users.destroy', ':id') }}";
        const routeToggleActive = "{{ route('users.toggleActive', ':id') }}";
    </script>

    <script src="{{ asset('js/users/index.js') }}"></script>

    <script>
        document.getElementById("user_info_menu").classList.add("here", "show");
        document.getElementById("user_list_link").classList.add("active");
    </script>
@endpush
