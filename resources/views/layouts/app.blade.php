<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
@include('layouts.partials.head')
<!--end::Head-->
<!--begin::Body-->

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true"
    data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on"
    class="app-default">
    <!--begin::Theme mode setup on page load-->
    @include('layouts.partials.theme_mode')
    <!--end::Theme mode setup on page load-->

    <!--begin::Page loading(append to body)-->
    <div class="page-loader">
        <span class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </span>
    </div>
    <!--end::Page loading-->

    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
            <!--begin::Header-->
            @include('layouts.partials.header')
            <!--end::Header-->

            <!--begin::Wrapper-->
            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">

                <!--begin::Sidebar-->
                @include('layouts.partials.sidebar')
                <!--end::Sidebar-->

                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <!--begin::Content container-->
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                @if ($errors->any())
                                    <div
                                        class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10">
                                        <!--begin::Icon-->
                                        <i class="ki-duotone ki-information fs-2hx text-danger me-4 mb-5 mb-sm-0">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        <!--end::Icon-->

                                        <!--begin::Content-->
                                        <div class="d-flex flex-column pe-0 pe-sm-10">
                                            <h5 class="mb-1 text-danger">নিম্নোক্ত এররগুলো চেক করুন।</h5>
                                            @foreach ($errors->all() as $error)
                                                <li class="text-danger">{{ $error }}</li>
                                            @endforeach
                                        </div>
                                        <!--end::Content-->

                                        <!--begin::Close-->
                                        <button type="button"
                                            class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                                            data-bs-dismiss="alert">
                                            <i class="ki-outline ki-cross fs-1 text-danger"></i>
                                        </button>
                                        <!--end::Close-->
                                    </div>
                                @endif

                                @yield('content')

                                @if (auth()->user()->role->name == 'Operator')
                                    <div class="fixed-bottom bg-white shadow-lg border-top py-3 px-4"
                                        style="z-index: 1000;">
                                        <div class="d-flex justify-content-around align-items-center">

                                            @php $isHome = request()->routeIs('dashboard'); @endphp
                                            <a href="{{ route('dashboard') }}"
                                                class="d-flex flex-column align-items-center text-decoration-none">
                                                <i
                                                    class="ki-outline ki-home-2 fs-2 mb-1 {{ $isHome ? 'text-primary' : 'text-gray-500' }}"></i>
                                                <span
                                                    class="fs-6 fw-bold {{ $isHome ? 'text-primary' : 'text-gray-500' }}">হোম পেজ</span>
                                            </a>

                                            @php $isReports = request()->routeIs('reports.*'); @endphp
                                            <a href="{{ route('reports.index') }}"
                                                class="d-flex flex-column align-items-center text-decoration-none">
                                                <i
                                                    class="ki-outline ki-document fs-2 mb-1 {{ $isReports ? 'text-primary' : 'text-gray-500' }}"></i>
                                                <span
                                                    class="fs-6 fw-bold {{ $isReports ? 'text-primary' : 'text-gray-500' }}">আমার প্রতিবেদন</span>
                                            </a>

                                            @php $isProfile = request()->routeIs('profile'); @endphp
                                            <a href="{{ route('profile') }}"
                                                class="d-flex flex-column align-items-center text-decoration-none">
                                                <i
                                                    class="ki-outline ki-user fs-2 mb-1 {{ $isProfile ? 'text-primary' : 'text-gray-500' }}"></i>
                                                <span
                                                    class="fs-6 fw-bold {{ $isProfile ? 'text-primary' : 'text-gray-500' }}">আমার প্রোফাইল</span>
                                            </a>

                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!--end::Content-->

                    </div>
                    <!--end::Content wrapper-->

                    <!--begin::Footer-->
                    @include('layouts.partials.footer')
                    <!--end::Footer-->

                </div>
                <!--end:::Main-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->

    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-outline ki-arrow-up"></i>
    </div>
    <!--end::Scrolltop-->

    <!--begin::Javascript-->
    @include('layouts.partials.scripts')
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
