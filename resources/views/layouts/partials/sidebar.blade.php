<div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="{{ route('dashboard') }}">
            <img alt="Logo" src="{{ asset('assets/img/logo-dark.png') }}" class="h-50px app-sidebar-logo-default" />
            <img alt="Logo" src="{{ asset('assets/img/icon.png') }}" class="h-20px app-sidebar-logo-minimize" />
        </a>
        <!--end::Logo image-->

        <!--begin::Sidebar toggle-->
        <!--begin::Minimized sidebar setup:
            if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
                1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
                2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
                3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
                4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
            }
        -->
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate "
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-outline ki-black-left-line fs-3 rotate-180"></i>
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->

    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <!--begin::Scroll wrapper-->
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
                data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                data-kt-scroll-save-state="true">
                <!--begin::Menu-->
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
                    data-kt-menu="true" data-kt-menu-expand="false">

                    <!--begin:Dashboard Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('dashboard') }}" id="dashboard_link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-chart-pie-4 fs-2"></i>
                            </span>
                            <span class="menu-title">ড্যাশবোর্ড</span>
                        </a>
                        <!--end:Dashboard Menu link-->
                    </div>
                    <!--end:Dashboard Menu item-->

                    <!--begin:Report Info Menu item-->
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion" id="report_info_menu">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-filter-tablet fs-1"></i>
                            </span>
                            <span class="menu-title">রিপোর্ট</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->

                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <a class="menu-link" id="add_report_link" href="{{ route('reports.create') }}"><span
                                        class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                        class="menu-title">নতুন
                                        রিপোর্ট</span></a>
                            </div>
                            <!--end:Menu item-->


                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <a class="menu-link" id="my_report_link" href="{{ route('reports.index') }}"><span
                                        class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                        class="menu-title">
                                        @if (auth()->user()->role->name == 'Operator')
                                            আমার রিপোর্ট
                                        @else
                                            সকল রিপোর্ট
                                        @endif
                                    </span></a>
                            </div>
                            <!--end:Menu item-->

                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end: Report Info Menu item-->


                    <!--begin:Analytics Info Menu item-->
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion" id="analytics_info_menu">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-chart-simple-3 fs-1"></i>
                            </span>
                            <span class="menu-title">বিশ্লেষণ ও পরিসংখ্যান</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->

                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <a class="menu-link" id="status_report_link" href="{{ route('reports.create') }}"><span
                                        class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                        class="menu-title">অবস্থা অনুযায়ী রিপোর্ট</span></a>
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <a class="menu-link" id="zone_report_link" href="{{ route('reports.index') }}"><span
                                        class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                        class="menu-title">জোনভিত্তিক সারসংক্ষেপ</span></a>
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <a class="menu-link" id="program_type_report_link"
                                    href="{{ route('reports.index') }}"><span class="menu-bullet"><span
                                            class="bullet bullet-dot"></span></span><span class="menu-title">কর্মসূচি
                                        অনুযায়ী বিশ্লেষণ</span></a>
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end: Analytics Info Menu item-->


                    <!--begin:Location Info Menu item-->
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion" id="location_info_menu">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-map fs-1"></i>
                            </span>
                            <span class="menu-title">অধিক্ষেত্র</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->

                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <a class="menu-link" id="zone_link" href="{{ route('reports.create') }}"><span
                                        class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                        class="menu-title">জোন</span></a>
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <a class="menu-link" id="upazila_link" href="{{ route('reports.index') }}"><span
                                        class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                        class="menu-title">উপজেলা</span></a>
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <a class="menu-link" id="parliament_link" href="{{ route('reports.index') }}"><span
                                        class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                        class="menu-title">সংসদীয় আসন</span></a>
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end: Location Info Menu item-->


                    <!--begin:Political Info Menu item-->
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion" id="political_info_menu">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-information fs-1"></i>
                            </span>
                            <span class="menu-title">রাজনৈতিক তথ্য</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->

                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link--><a class="menu-link" id="political_party_link"
                                    href="{{ route('reports.create') }}"><span class="menu-bullet"><span
                                            class="bullet bullet-dot"></span></span><span class="menu-title">রাজনৈতিক
                                        দল</span></a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <a class="menu-link" id="program_type_link"
                                    href="{{ route('reports.index') }}"><span class="menu-bullet"><span
                                            class="bullet bullet-dot"></span></span><span class="menu-title">কর্মসূচির
                                        ধরন</span></a>
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end: Political Info Menu item-->

                    {{-- @if (auth()->user()->role->name == 'Administrator') --}}
                        <!--begin:User Info Menu item-->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion" id="user_info_menu">
                            <!--begin:Menu link-->
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="ki-outline ki-user-edit fs-1"></i>
                                </span>
                                <span class="menu-title">ইউজার ম্যানেজমেন্ট</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <!--end:Menu link-->

                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <!--begin:Menu link--><a class="menu-link" id="user_list_link"
                                        href="{{ route('users.index') }}"><span class="menu-bullet"><span
                                                class="bullet bullet-dot"></span></span><span
                                            class="menu-title">ইউজার</span></a>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->

                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <a class="menu-link" id="designation_link"
                                        href="{{ route('designations.index') }}"><span class="menu-bullet"><span
                                                class="bullet bullet-dot"></span></span><span class="menu-title">সকল
                                            পদবী</span></a>
                                </div>
                                <!--end:Menu item-->
                            </div>
                            <!--end:Menu sub-->
                        </div>
                        <!--end: User Info Menu item-->
                    {{-- @endif --}}
                </div>
                <!--end::Menu wrapper-->
            </div>
            <!--end::sidebar menu-->

            <!--begin::Footer-->
            <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="btn btn-flex flex-center btn-custom btn-danger overflow-hidden text-nowrap px-0 h-40px w-100"
                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" title="সাইন আউট করুন">
                    <span class="btn-label">
                        সাইন আউট
                    </span>
                    <i class="ki-outline ki-document btn-icon fs-2 m-0"></i>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
            <!--end::Footer-->
        </div>
