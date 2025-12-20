@extends('layouts.app')

@push('page-css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('title', 'প্রোগ্রামের ধরণ')

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            প্রোগ্রামের ধরণ
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
                    রাজনৈতিক তথ্য </a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                প্রোগ্রাম প্রকার </li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
@endsection

@section('content')
    <div class="container-xxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i> <input type="text"
                            data-program-types-table-filter="search" class="form-control form-control-solid w-350px ps-12"
                            placeholder="প্রোগ্রামের ধরণ খুঁজুন">
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
                    <div class="d-flex justify-content-end" data-program-types-table-filter="base">
                        <!--begin::Add Teacher-->
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_program_type">
                            <i class="ki-outline ki-plus fs-2"></i>নতুন ধরণ</a>
                        <!--end::Add Teacher-->
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
                <table class="table table-hover align-middle table-row-dashed fs-6 gy-5 prms-table"
                    id="kt_program_types_table">
                    <thead>
                        <tr class="fw-bold fs-5 gs-0">
                            <th class="w-25px">#</th>
                            <th>ধরণের নাম</th>
                            <th>বিবরণ</th>
                            <th>রিপোর্টের সংখ্যা (টি)</th>
                            <th>যুক্তকারি</th>
                            <th class="not-export">##</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold fs-5">
                        @php
                            use Rakibhstu\Banglanumber\NumberToBangla;

                            $numto = new NumberToBangla();
                        @endphp
                        @foreach ($program_types as $type)
                            <tr>
                                <td>{{ $numto->bnNum($loop->index + 1) }}</td>
                                <td class="text-gray-800">{{ $type->name }}</td>
                                <td>{{ $type->description }}</td>
                                <td>{{ $numto->bnNum($type->reports_count) }}</td>
                                <td>
                                    <!--begin::user details-->
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 mb-1">{{ $type->createdBy->name }}</span>
                                        <span class="fw-semibold">
                                            {{ $type->createdBy->designation->name }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <a href="#" title="ধরণ সংশোধন" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_edit_program_type"
                                        class="btn btn-icon text-hover-primary w-30px h-30px edit-program-type me-2"
                                        data-program-type-id={{ $type->id }}>
                                        <i class="ki-outline ki-pencil fs-2"></i>
                                    </a>
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
    </div>

    <!--begin::Modal - Add Program Type-->
    <div class="modal fade" id="kt_modal_add_program_type" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_program_type_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">নতুন প্রোগ্রামের ধরণ যুক্ত করুন</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-add-program-type-modal-action="close">
                        <i class="ki-outline ki-cross fs-1">
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body px-5 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_add_program_type_form" class="form" action="#" novalidate="novalidate">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_program_type_scroll"
                            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_modal_teacher_header"
                            data-kt-scroll-wrappers="#kt_modal_add_program_type_scroll" data-kt-scroll-offset="300px">
                            <!--begin::Name Input group-->
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-4 mb-2">ধরণের নাম</label>
                                <input type="text" name="type_name"
                                    class="form-control form-control-solid mb-3 mb-lg-0 fs-4" placeholder="ধরণের নাম লিখুন"
                                    required />
                            </div>
                            <!--end::Name Input group-->

                            <!--begin::Name Input group-->
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-4 mb-2">বিবরণ লিখুন <span
                                        class="text-muted fst-italic">(ঐচ্ছিক)</span></label>
                                <input type="text" name="type_description"
                                    class="form-control form-control-solid mb-3 mb-lg-0 fs-4"
                                    placeholder="ধরণের নাম লিখুন" />
                            </div>
                            <!--end::Name Input group-->
                        </div>
                        <!--end::Scroll-->

                        <!--begin::Actions-->
                        <div class="text-center pt-10">
                            <button type="reset" class="btn btn-light me-3"
                                data-kt-add-program-type-modal-action="cancel">ক্যান্সেল</button>
                            <button type="submit" class="btn btn-primary" data-kt-add-program-type-modal-action="submit">
                                <span class="indicator-label">সাবমিট</span>
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
    <!--end::Modal - Add Program Type-->


    <!--begin::Modal - Edit Program Type-->
    <div class="modal fade" id="kt_modal_edit_program_type" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold" id="kt_modal_edit_program_type_title">Update Program Type</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-edit-program-type-modal-action="close">
                        <i class="ki-outline ki-cross fs-1">
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body px-5 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_edit_program_type_form" class="form" action="#" novalidate="novalidate">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_edit_program_type_scroll"
                            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_modal_edit_program_type_header"
                            data-kt-scroll-wrappers="#kt_modal_edit_program_type_scroll" data-kt-scroll-offset="300px">
                            <!--begin::Name Input group-->
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-4 mb-2">ধরণের নাম</label>
                                <input type="text" name="type_name_edit"
                                    class="form-control form-control-solid mb-3 mb-lg-0 fs-4"
                                    placeholder="ধরণের নাম লিখুন" required />
                            </div>
                            <!--end::Name Input group-->

                            <!--begin::Name Input group-->
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-4 mb-2">বিবরণ লিখুন <span
                                        class="text-muted fst-italic">(ঐচ্ছিক)</span></label>
                                <input type="text" name="type_description_edit"
                                    class="form-control form-control-solid mb-3 mb-lg-0 fs-4"
                                    placeholder="ধরণের নাম লিখুন" />
                            </div>
                            <!--end::Name Input group-->
                        </div>
                        <!--end::Scroll-->

                        <!--begin::Actions-->
                        <div class="text-center pt-10">
                            <button type="reset" class="btn btn-light me-3"
                                data-edit-program-type-modal-action="cancel">ক্যান্সেল</button>
                            <button type="submit" class="btn btn-primary" data-edit-program-type-modal-action="submit">
                                <span class="indicator-label">আপডেট</span>
                                <span class="indicator-progress">Please wait...
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
    <!--end::Modal - Edit Program Type-->
@endsection


@push('vendor-js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('page-js')
    <script>
        const storeProgramTypeRoute = "{{ route('program-types.store') }}";
    </script>

    <script src="{{ asset('js/program_types/index.js') }}"></script>

    <script>
        document.getElementById("political_info_menu").classList.add("here", "show");
        document.getElementById("program_type_link").classList.add("active");
    </script>
@endpush
