@extends('layouts.app')

@push('page-css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('title', $party->name)

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            {{ $party->name }}
        </h1>
        <!--end::Title-->
        <!--begin::Separator-->
        <span class="h-20px border-gray-300 border-start mx-4"></span>
        <!--end::Separator-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 ">
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('political-parties.index') }}" class="text-muted text-hover-primary">
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
                দলের বিস্তারিত </li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
@endsection

@section('content')
    @php
        use Rakibhstu\Banglanumber\NumberToBangla;

        $numto = new NumberToBangla();

        // Badge Colors assignments
        $badgeColors = ['primary', 'success', 'warning', 'info'];

        // Map seat name => color
        $seatColorMap = [];

        $colorIndex = 0;
    @endphp

    <!--begin::Layout-->
    <div class="d-flex flex-column flex-xl-row">
        <!--begin::Sidebar-->
        <div class="flex-column flex-lg-row-auto w-100 w-lg-350px w-xl-400px mb-10">
            <!--begin::Card-->
            <div class="card card-flush mb-0" data-kt-sticky="true" data-kt-sticky-name="student-summary"
                data-kt-sticky-offset="{default: false, lg: 0}" data-kt-sticky-width="{lg: '350px', xl: '400px'}"
                data-kt-sticky-left="auto" data-kt-sticky-top="100px" data-kt-sticky-animation="false"
                data-kt-sticky-zindex="95">
                <!--begin::Card header-->
                <div class="card-header ">
                    <!--begin::Card title-->
                    <div class="card-title">
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0 fs-6 mt-n10">
                    <!--begin::রাজনৈতিক দলের তথ্য-->
                    <div class="mb-7">
                        <!--begin::Title-->
                        <h5 class="mb-4 fs-4">রাজনৈতিক দলের তথ্য
                        </h5>
                        <!--end::Title-->
                        <!--begin::Details-->
                        <div class="mb-0">
                            <!--begin::Details-->
                            <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                                <!--begin::Row-->
                                <tr class="">
                                    <td class="text-gray-600 fs-4">দলের নাম:</td>
                                    <td class="text-gray-800 fs-4">{{ $party->name }}
                                    </td>
                                </tr>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-600 fs-4">দলীয় প্রধান:</td>
                                    <td class="fs-4">{{ $party->party_head }}</td>
                                </tr>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-600 fs-4">মোট প্রতিবেদন:</td>
                                    <td class="fs-4">{{ $numto->bnNum($party->reports->count()) }} টি</td>
                                </tr>
                                <!--end::Row-->
                            </table>
                            <!--end::Details-->
                        </div>
                        <!--end::Details-->
                    </div>
                    <!--end::রাজনৈতিক দলের তথ্য-->

                    <!--begin::Seperator-->
                    <div class="separator separator-dashed mb-7"></div>
                    <!--end::Seperator-->

                    <!--begin::প্রতিবেদন দাখিল সংক্রান্ত তথ্য-->
                    <div class="mb-0">
                        <!--begin::Title-->
                        <h5 class="mb-4 fs-4">অন্যান্য তথ্য</h5>
                        <!--end::Title-->
                        <!--begin::Details-->
                        <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                            <tr>
                                <td class="text-gray-600 fs-4">তৈরিকারি:</td>
                                <td class="text-gray-800 fs-4">{{ $party->createdBy->name }},
                                    {{ $party->createdBy->designation->name }}</td>
                            </tr>
                            <!--begin::Row-->
                            <tr class="">
                                <td class="text-gray-600 fs-4">এন্ট্রির সময়:</td>
                                <td class="text-gray-800 fs-4">
                                    {{ $numto->bnNum($party->created_at->format('d')) }}-
                                    {{ $numto->bnNum($party->created_at->format('m')) }}-
                                    {{ $numto->bnNum($party->created_at->format('Y')) }},
                                    {{ $numto->bnNum($party->created_at->format('h')) }}:{{ $numto->bnNum($party->created_at->format('i')) }}
                                    {{ $party->created_at->format('A') == 'AM' ? 'পূর্বাহ্ণ' : 'অপরাহ্ণ' }}
                                </td>
                            </tr>
                            <!--end::Row-->

                            <!--begin::Row-->
                            <tr class="">
                                <td class="text-gray-600 fs-4">সর্বশেষ হালনাগাদ:</td>
                                <td class="text-gray-800 fs-4">
                                    {{ $numto->bnNum($party->updated_at->format('d')) }}-
                                    {{ $numto->bnNum($party->updated_at->format('m')) }}-
                                    {{ $numto->bnNum($party->updated_at->format('Y')) }},
                                    {{ $numto->bnNum($party->updated_at->format('h')) }}:{{ $numto->bnNum($party->updated_at->format('i')) }}
                                    {{ $party->updated_at->format('A') == 'AM' ? 'পূর্বাহ্ণ' : 'অপরাহ্ণ' }}
                                </td>
                            </tr>
                            <!--end::Row-->
                        </table>
                        <!--end::Details-->
                    </div>
                    <!--end::প্রতিবেদন দাখিল সংক্রান্ত তথ্য-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Sidebar-->

        <!--begin::Content-->
        <div class="flex-lg-row-fluid ms-lg-10">
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title p-4 w-100 d-flex align-items-center justify-content-between">
                        <h3 class="fw-semibold m-0">
                            পটুয়াখালী জেলায় সব আসনে
                            <span class="fst-italic fw-bold">{{ $party->name }}</span>
                            এর মনোনীত প্রার্থীরা
                        </h3>

                        <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_party_candidate">
                            <i class="ki-outline ki-plus fs-2"></i>
                            নতুন প্রার্থী
                        </a>
                    </div>
                </div>

                <!--begin::Card header-->
                <!--begin::Card body-->
                <div class="card-body p-9">
                    <table class="table table-hover align-middle table-row-dashed fs-6 gy-5 prms-table"
                        id="kt_party_candidates_table">
                        <thead>
                            <tr class="fw-bold fs-5 gs-0">
                                <th class="w-50px">#</th>
                                <th>প্রার্থীর নাম</th>
                                <th>নির্বাচনী প্রতীক</th>
                                <th>সংসদীয় আসন</th>
                                <th>রিপোর্ট সংখ্যা (টি)</th>
                                <th class="not-export">##</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold fs-5">
                            @foreach ($party->seatPartyCandidates as $candidate)
                                <tr>
                                    <td>{{ $numto->bnNum($loop->index + 1) }}</td>
                                    <td class="text-gray-800">{{ $candidate->candidate_name }}</td>
                                    <td>{{ $candidate->election_symbol ?? '-' }}</td>
                                    <td>
                                        @php
                                            $seatName = $candidate->seat->name;
                                            
                                            if (!isset($seatColorMap[$seatName])) {
                                                $seatColorMap[$seatName] =
                                                $badgeColors[$colorIndex % count($badgeColors)];
                                                $colorIndex++;
                                            }
                                            @endphp
                                        <span class="badge badge-{{ $seatColorMap[$seatName] }}" data-bs-toggle="tooltip"
                                        title="{{ $candidate->seat->description }}">
                                        {{ $seatName }}
                                    </span>
                                </td>
                                <td>{{ $numto->bnNum($candidate->reports()->count()) }}</td>
                                    <td>
                                        <a href="#" title="প্রার্থীর তথ্য সংশোধন" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_edit_party_candidate"
                                            class="btn btn-icon text-hover-primary w-30px h-30px me-2"
                                            data-candidate-id={{ $candidate->id }}>
                                            <i class="ki-outline ki-pencil fs-2"></i>
                                        </a>

                                            {{-- <a href="#"
                                                class="btn btn-icon w-30px h-30px text-hover-danger delete-political-party"
                                                title="দলটি মুছে ফেলুন" data-party-id="{{ $candidate->id }}"><i
                                                    class="ki-outline ki-trash fs-2"></i>
                                            </a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Personal Info-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Layout-->


    <!--begin::Modal - Add Party Candidate-->
    <div class="modal fade" id="kt_modal_add_party_candidate" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_party_candidate_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">{{ $party->name }} দলের নতুন প্রার্থী যুক্ত করুন</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary"
                        data-kt-add-party-candidate-modal-action="close">
                        <i class="ki-outline ki-cross fs-1">
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body px-5 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_add_party_candidate_form" class="form" action="#" novalidate="novalidate">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_party_candidate_scroll"
                            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_modal_teacher_header"
                            data-kt-scroll-wrappers="#kt_modal_add_party_candidate_scroll" data-kt-scroll-offset="300px">
                            <input type="hidden" name="political_party_id" value="{{ $party->id }}">
                            <!--begin::Name Input group-->
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-4 mb-2">প্রার্থীর নাম</label>
                                <input type="text" name="candidate_name"
                                    class="form-control form-control-solid mb-3 mb-lg-0 fs-4"
                                    placeholder="প্রার্থীর নাম লিখুন" required />
                            </div>
                            <!--end::Name Input group-->

                            <div class="mb-7 fv-row">
                                <label class="required form-label fs-4">
                                    সংসদীয় আসন
                                    <span class="ms-1" data-bs-toggle="tooltip"
                                        title="প্রার্থী যে আসনে প্রতিদ্বন্দ্বিতা করবেন সেটি সিলেক্ট করুন">
                                        <i class="ki-outline ki-information fs-4"></i>
                                    </span>
                                </label>

                                <select name="parliament_seat_id" class="form-select form-select-solid fs-4"
                                    data-control="select2" data-placeholder="আসন বাছাই করুন" data-hide-search="true"
                                    required>
                                    <option></option>
                                    @foreach ($seats as $seat)
                                        <option value="{{ $seat->id }}">
                                            {{ $seat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!--begin::Name Input group-->
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-4 mb-2">নির্বাচনী প্রতীক <span
                                        class="text-muted fst-italic">(ঐচ্ছিক)</span></label>
                                <input type="text" name="election_symbol"
                                    class="form-control form-control-solid mb-3 mb-lg-0 fs-4"
                                    placeholder="প্রার্থীর নির্বাচনী প্রতীক লিখুন" />
                            </div>
                            <!--end::Name Input group-->
                        </div>
                        <!--end::Scroll-->

                        <!--begin::Actions-->
                        <div class="text-center pt-10">
                            <button type="reset" class="btn btn-light me-3"
                                data-kt-add-party-candidate-modal-action="cancel">ক্যান্সেল</button>
                            <button type="submit" class="btn btn-primary"
                                data-kt-add-party-candidate-modal-action="submit">
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
    <!--end::Modal - Add Party Candidate-->

    <!--begin::Modal - Edit Party Candidate-->
    <div class="modal fade" id="kt_modal_edit_party_candidate" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold" id="kt_modal_edit_party_candidate_title">Update Party Candidate</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary"
                        data-kt-edit-party-candidate-modal-action="close">
                        <i class="ki-outline ki-cross fs-1">
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body px-5 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_edit_party_candidate_form" class="form" action="#" novalidate="novalidate">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_edit_party_candidate_scroll"
                            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_modal_edit_party_candidate_header"
                            data-kt-scroll-wrappers="#kt_modal_edit_party_candidate_scroll" data-kt-scroll-offset="300px">
                            <!--begin::Name Input group-->
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-4 mb-2">প্রার্থীর নাম</label>
                                <input type="text" name="candidate_name_edit"
                                    class="form-control form-control-solid mb-3 mb-lg-0 fs-4"
                                    placeholder="প্রার্থীর নাম লিখুন" required />
                            </div>
                            <!--end::Name Input group-->

                            <div class="mb-7 fv-row">
                                <label class="required form-label fs-4">
                                    সংসদীয় আসন
                                    <span class="ms-1" data-bs-toggle="tooltip"
                                        title="প্রার্থী যে আসনে প্রতিদ্বন্দ্বিতা করবেন সেটি সিলেক্ট করুন">
                                        <i class="ki-outline ki-information fs-4"></i>
                                    </span>
                                </label>

                                <select name="parliament_seat_id_edit" class="form-select form-select-solid fs-4"
                                    data-control="select2" data-placeholder="আসন বাছাই করুন" data-hide-search="true"
                                    required>
                                    <option></option>
                                    @foreach ($seats as $seat)
                                        <option value="{{ $seat->id }}">
                                            {{ $seat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <!--begin::Name Input group-->
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-4 mb-2">নির্বাচনী প্রতীক <span
                                        class="text-muted fst-italic">(ঐচ্ছিক)</span></label>
                                <input type="text" name="election_symbol_edit"
                                    class="form-control form-control-solid mb-3 mb-lg-0 fs-4"
                                    placeholder="প্রার্থীর নির্বাচনী প্রতীক লিখুন" />
                            </div>
                            <!--end::Name Input group-->
                        </div>
                        <!--end::Scroll-->

                        <!--begin::Actions-->
                        <div class="text-center pt-10">
                            <button type="reset" class="btn btn-light me-3"
                                data-kt-edit-party-candidate-modal-action="cancel">ক্যান্সেল</button>
                            <button type="submit" class="btn btn-primary"
                                data-kt-edit-party-candidate-modal-action="submit">
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
    <!--end::Modal - Edit Party Candidate-->
@endsection


@push('vendor-js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('page-js')
    <script>
        const storePartyCandidateRoute = "{{ route('party-candidates.store') }}";
        const partyCandidateDeleteRoute = "{{ route('party-candidates.destroy', ':id') }}";
    </script>

    <script src="{{ asset('js/political_parties/show.js') }}"></script>
    <script>
        document.getElementById("political_info_menu").classList.add("here", "show");
        document.getElementById("political_party_link").classList.add("active");
    </script>
@endpush
