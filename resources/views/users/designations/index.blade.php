@push('page-css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush


@extends('layouts.app')

@section('title', 'সকল পদবী')

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            সকল পদবী
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
                পদবী </li>
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
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-all-user-table-filter="base">
                    <!--begin::Add user-->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_designation" class="btn btn-primary">
                        <i class="ki-outline ki-plus fs-2"></i>নতুন পদবী</a>
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
                        <th>কার্যক্রম</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold fs-5">
                    @php
                        use Rakibhstu\Banglanumber\NumberToBangla;

                        $numto = new NumberToBangla();
                    @endphp

                    @foreach ($designations as $user)
                        <tr>
                            <td>{{ $numto->bnNum($loop->index + 1) }}</td>
                            <td>
                                <!--begin::user details-->
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 mb-1">{{ $user->name }}</span>
                                    <span class="fw-semibold">
                                        {{ $user->name }}
                                        {{ $user->zones && $user->zones->isNotEmpty() && $user->isOperator() ? ', ' . $user->zones->first()->name : '' }}
                                    </span>

                                </div>
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
@endsection


@push('vendor-js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('page-js')
    <script>
        const routeDeleteDesignation = "{{ route('designations.destroy', ':id') }}";
    </script>

    <script src="{{ asset('js/users/designations/index.js') }}"></script>

    <script>
        document.getElementById("user_info_menu").classList.add("here", "show");
        document.getElementById("designation_link").classList.add("active");
    </script>
@endpush
