@extends('layouts.app')

@push('page-css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('title', 'রাজনৈতিক দল')

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            রাজনৈতিক দলের তালিকা
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
                দল </li>
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
                        data-political-parties-table-filter="search" class="form-control form-control-solid w-350px ps-12"
                        placeholder="রাজনৈতিক দলের যে কোনো তথ্য খুঁজুন">
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
                <div class="d-flex justify-content-end" data-political-parties-table-filter="base">
                    <!--begin::Add Teacher-->
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_add_political_party">
                        <i class="ki-outline ki-plus fs-2"></i>নতুন দল</a>
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
                id="kt_political_parties_table">
                <thead>
                    <tr class="fw-bold fs-5 gs-0">
                        <th class="w-25px">#</th>
                        <th>ধরণের নাম</th>
                        <th>দলীয় প্রধান</th>
                        <th>রিপোর্টের সংখ্যা (টি)</th>
                        <th class="not-export">##</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold fs-5">
                    @php
                        use Rakibhstu\Banglanumber\NumberToBangla;

                        $numto = new NumberToBangla();
                    @endphp
                    @foreach ($parties as $party)
                        <tr>
                            <td>{{ $numto->bnNum($loop->index + 1) }}</td>
                            <td>
                                <a href="{{ route('political-parties.show', $party->id) }}"
                                    class="text-gray-800 text-hover-primary">
                                    {{ $party->name }}
                                </a>
                            </td>

                            <td>{{ $party->party_head ?? '-' }}</td>
                            <td>{{ $numto->bnNum($party->reports_count) }}</td>
                            <td>
                                <a href="#" title="দলের তথ্য সংশোধন" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_edit_political_party"
                                    class="btn btn-icon text-hover-primary w-30px h-30px me-2"
                                    data-party-id={{ $party->id }}>
                                    <i class="ki-outline ki-pencil fs-2"></i>
                                </a>

                                @if ($party->reports_count == 0)
                                    <a href="#"
                                        class="btn btn-icon w-30px h-30px text-hover-danger delete-political-party"
                                        title="দলটি মুছে ফেলুন" data-party-id="{{ $party->id }}"><i
                                            class="ki-outline ki-trash fs-2"></i>
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

    <!--begin::Modal - Add Political Party-->
    <div class="modal fade" id="kt_modal_add_political_party" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_political_party_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">নতুন রাজনৈতিক দল যুক্ত করুন</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary"
                        data-kt-add-political-party-modal-action="close">
                        <i class="ki-outline ki-cross fs-1">
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body px-5 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_add_political_party_form" class="form" action="#" novalidate="novalidate">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_political_party_scroll"
                            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_modal_teacher_header"
                            data-kt-scroll-wrappers="#kt_modal_add_political_party_scroll" data-kt-scroll-offset="300px">
                            <!--begin::Name Input group-->
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-4 mb-2">রাজনৈতিক দলের নাম</label>
                                <input type="text" name="party_name"
                                    class="form-control form-control-solid mb-3 mb-lg-0 fs-4" placeholder="দলের নাম লিখুন"
                                    required />
                            </div>
                            <!--end::Name Input group-->

                            <!--begin::Name Input group-->
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-4 mb-2">দলীয় প্রধান <span
                                        class="text-muted fst-italic">(ঐচ্ছিক)</span></label>
                                <input type="text" name="party_head"
                                    class="form-control form-control-solid mb-3 mb-lg-0 fs-4"
                                    placeholder="দলের প্রধানের নাম লিখুন" />
                            </div>
                            <!--end::Name Input group-->
                        </div>
                        <!--end::Scroll-->

                        <!--begin::Actions-->
                        <div class="text-center pt-10">
                            <button type="reset" class="btn btn-light me-3"
                                data-kt-add-political-party-modal-action="cancel">ক্যান্সেল</button>
                            <button type="submit" class="btn btn-primary"
                                data-kt-add-political-party-modal-action="submit">
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
    <!--end::Modal - Add Political Party-->

    <!--begin::Modal - Edit Political Party-->
    <div class="modal fade" id="kt_modal_edit_political_party" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold" id="kt_modal_edit_political_party_title">Update Political Party</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" kt_modal_edit_political_party="close">
                        <i class="ki-outline ki-cross fs-1">
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body px-5 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_edit_political_party_form" class="form" action="#" novalidate="novalidate">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_edit_political_party_scroll"
                            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_modal_edit_political_party_header"
                            data-kt-scroll-wrappers="#kt_modal_edit_political_party_scroll" data-kt-scroll-offset="300px">
                            <!--begin::Name Input group-->
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-4 mb-2">রাজনৈতিক দলের নাম</label>
                                <input type="text" name="party_name_edit"
                                    class="form-control form-control-solid mb-3 mb-lg-0 fs-4" placeholder="দলের নাম লিখুন"
                                    required />
                            </div>
                            <!--end::Name Input group-->

                            <!--begin::Name Input group-->
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-4 mb-2">দলীয় প্রধান <span
                                        class="text-muted fst-italic">(ঐচ্ছিক)</span></label>
                                <input type="text" name="party_head_edit"
                                    class="form-control form-control-solid mb-3 mb-lg-0 fs-4"
                                    placeholder="দলের প্রধানের নাম লিখুন" />
                            </div>
                            <!--end::Name Input group-->
                        </div>
                        <!--end::Scroll-->

                        <!--begin::Actions-->
                        <div class="text-center pt-10">
                            <button type="reset" class="btn btn-light me-3"
                                kt_modal_edit_political_party="cancel">ক্যান্সেল</button>
                            <button type="submit" class="btn btn-primary" kt_modal_edit_political_party="submit">
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
    <!--end::Modal - Edit Political Party-->
@endsection


@push('vendor-js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('page-js')
    <script>
        const storePoliticalPartyRoute = "{{ route('political-parties.store') }}";
        const partyDeleteRoute = "{{ route('political-parties.destroy', ':id') }}";
    </script>

    <script src="{{ asset('js/political_parties/index.js') }}"></script>

    <script>
        document.getElementById("political_info_menu").classList.add("here", "show");
        document.getElementById("political_party_link").classList.add("active");
    </script>
@endpush
