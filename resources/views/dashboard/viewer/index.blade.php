@extends('layouts.app')

@push('page-css')
    <style>
        .stat-card {
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.625rem;
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--bs-gray-900);
        }

        .stat-label {
            color: var(--bs-gray-500);
            font-size: 0.925rem;
            font-weight: 500;
        }

        .ranking-badge {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .ranking-1 {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: white;
        }

        .ranking-2 {
            background: linear-gradient(135deg, #C0C0C0, #A0A0A0);
            color: white;
        }

        .ranking-3 {
            background: linear-gradient(135deg, #CD7F32, #8B4513);
            color: white;
        }

        .chart-container {
            min-height: 300px;
        }

        .scroll-y {
            max-height: 400px;
            overflow-y: auto;
        }

        .scroll-y::-webkit-scrollbar {
            width: 6px;
        }

        .scroll-y::-webkit-scrollbar-track {
            background: var(--bs-gray-100);
            border-radius: 3px;
        }

        .scroll-y::-webkit-scrollbar-thumb {
            background: var(--bs-gray-300);
            border-radius: 3px;
        }

        .progress.h-8px {
            height: 8px;
        }

        .progress.h-6px {
            height: 6px;
        }

        .month-nav-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .month-nav-btn:hover {
            background-color: var(--bs-gray-200);
        }

        .bullet-vertical {
            width: 4px;
            border-radius: 2px;
        }

        .zone-header-badge {
            background: linear-gradient(135deg, #3E97FF 0%, #7239EA 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
@endpush

@section('title', 'ড্যাশবোর্ড - ' . ($zoneName ?? 'আমার জোন'))

@section('header-title')
    <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
            ড্যাশবোর্ড
        </h1>
        <span class="h-20px border-gray-300 border-start mx-4"></span>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">হোম</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">অভারভিউ</li>
        </ul>
    </div>
@endsection

@section('content')
    <!-- Zone Info Banner -->
    <div class="card bg-light-primary mb-5 mb-xl-8">
        <div class="card-body py-4">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-4">
                    <span class="symbol-label bg-primary">
                        <i class="ki-outline ki-geolocation fs-1 text-white"></i>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <h4 class="fw-bold text-gray-800 mb-1">{{ $zoneName ?? 'আমার জোন' }}</h4>
                    <span class="text-muted fs-7">আপনার জোনের সকল রিপোর্ট ও কার্যক্রমের সারসংক্ষেপ</span>
                </div>
                <div class="zone-header-badge d-none d-md-block">
                    <i class="ki-outline ki-eye fs-4 me-2"></i>
                    ভিউয়ার ড্যাশবোর্ড
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <!-- Total Reports -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-light-primary text-primary me-4">
                            <i class="ki-outline ki-file-added fs-1"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="totalReports">0</div>
                            <div class="stat-label">মোট রিপোর্ট</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Programs -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-light-success text-success me-4">
                            <i class="ki-outline ki-check-circle fs-1"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="completedPrograms">0</div>
                            <div class="stat-label">সম্পন্ন কার্যক্রম</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Programs -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-light-warning text-warning me-4">
                            <i class="ki-outline ki-time fs-1"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="pendingPrograms">0</div>
                            <div class="stat-label">অপেক্ষমাণ কার্যক্রম</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Attendees -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-light-info text-info me-4">
                            <i class="ki-outline ki-people fs-1"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="totalAttendees">0</div>
                            <div class="stat-label">মোট উপস্থিতি</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <!-- Reports Trend Chart (Monthly with Navigation) -->
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">মাসিক রিপোর্ট ট্রেন্ড</span>
                        <span class="text-muted fw-semibold fs-7">দিন অনুযায়ী রিপোর্টের সংখ্যা</span>
                    </h3>
                    <div class="card-toolbar">
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-icon btn-light me-2 month-nav-btn"
                                id="prevMonthBtn">
                                <i class="ki-outline ki-left fs-2"></i>
                            </button>
                            <span class="fw-bold text-gray-800 fs-5 mx-2" id="currentMonthLabel">লোড হচ্ছে...</span>
                            <button type="button" class="btn btn-sm btn-icon btn-light ms-2 month-nav-btn"
                                id="nextMonthBtn">
                                <i class="ki-outline ki-right fs-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="reportsChart" class="chart-container"></div>
                </div>
            </div>
        </div>

        <!-- Program Type with Tabs (Chart & Table) -->
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">কার্যক্রমের ধরন</span>
                        <span class="text-muted fw-semibold fs-7">ধরন অনুযায়ী বিভাজন</span>
                    </h3>
                    <div class="card-toolbar">
                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#programTypeChartTab" role="tab">
                                    <i class="ki-outline ki-chart-pie-3 fs-4 me-1"></i>
                                    চার্ট
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#programTypeTableTab" role="tab">
                                    <i class="ki-outline ki-row-horizontal fs-4 me-1"></i>
                                    তালিকা
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Chart Tab -->
                        <div class="tab-pane fade show active" id="programTypeChartTab" role="tabpanel">
                            <div id="programTypeChart" class="chart-container"></div>
                        </div>
                        <!-- Table Tab -->
                        <div class="tab-pane fade" id="programTypeTableTab" role="tabpanel">
                            <div class="table-responsive scroll-y" style="max-height: 300px;">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="ps-4">ধরন</th>
                                            <th class="text-center">সংখ্যা</th>
                                            <th class="text-end pe-4">%</th>
                                        </tr>
                                    </thead>
                                    <tbody id="programTypeTableBody">
                                        <!-- Dynamic content from JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Union & User Tables -->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <!-- Union wise Report Count -->
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">ইউনিয়ন অনুযায়ী রিপোর্ট</span>
                        <span class="text-muted fw-semibold fs-7">এই জোনের ইউনিয়ন ভিত্তিক রিপোর্ট</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive scroll-y">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-150px ps-4">ইউনিয়ন</th>
                                    <th class="min-w-80px text-center">রিপোর্ট</th>
                                    <th class="min-w-60px text-end pe-4">শতাংশ</th>
                                </tr>
                            </thead>
                            <tbody id="unionTableBody">
                                <!-- Dynamic content from JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Users by Reports -->
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">সর্বাধিক রিপোর্টকারী</span>
                        <span class="text-muted fw-semibold fs-7">এই জোনের শীর্ষ ব্যবহারকারী</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive scroll-y">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="w-25px ps-4">র‍্যাংক</th>
                                    <th class="min-w-150px">ব্যবহারকারী</th>
                                    <th class="min-w-100px text-center">পদবী</th>
                                    <th class="min-w-60px text-center">রিপোর্ট</th>
                                    <th class="min-w-100px text-end pe-4">সর্বশেষ</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                                <!-- Dynamic content from JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Political Party & Candidate Tables -->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <!-- Political Party Reports -->
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">রাজনৈতিক দল অনুযায়ী রিপোর্ট</span>
                        <span class="text-muted fw-semibold fs-7">দল ভিত্তিক কার্যক্রম বিশ্লেষণ</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div id="politicalPartyChart" class="chart-container"></div>
                </div>
            </div>
        </div>

        <!-- Candidate Reports -->
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">প্রার্থী অনুযায়ী কার্যক্রম</span>
                        <span class="text-muted fw-semibold fs-7">প্রার্থীদের কার্যক্রম সংখ্যা</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive scroll-y">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-150px ps-4">প্রার্থী</th>
                                    <th class="min-w-120px">রাজনৈতিক দল</th>
                                    <th class="min-w-80px text-center">কার্যক্রম</th>
                                    <th class="min-w-100px text-end pe-4">অংশগ্রহণকারী</th>
                                </tr>
                            </thead>
                            <tbody id="candidateTableBody">
                                <!-- Dynamic content from JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="row g-5 g-xl-8">
        <div class="col-xl-12">
            <div class="card h-100">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">সাম্প্রতিক রিপোর্ট</span>
                        <span class="text-muted fw-semibold fs-7">এই জোনের সর্বশেষ জমাকৃত রিপোর্ট</span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-primary">
                            সব দেখুন
                            <i class="ki-outline ki-arrow-right fs-4 ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body py-3">
                    <div class="scroll-y" id="recentReports" style="max-height: 500px;">
                        <!-- Dynamic content from JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('vendor-js')
    <script src="{{ asset('assets/plugins/custom/apexcharts/apexcharts.bundle.js') }}"></script>
@endpush

@php
    $defaultMonthlyReports = [
        'month' => '',
        'year' => '',
        'monthName' => '',
        'categories' => [],
        'data' => [],
    ];
@endphp

@push('page-js')
    {{-- Pass server data to JavaScript --}}
    <script>
        var dashboardServerData = {
            stats: {
                totalReports: {{ $stats['totalReports'] ?? 0 }},
                completedPrograms: {{ $stats['completedPrograms'] ?? 0 }},
                pendingPrograms: {{ $stats['pendingPrograms'] ?? 0 }},
                totalAttendees: {{ $stats['totalAttendees'] ?? 0 }}
            },
            unions: @json($unions ?? []),
            users: @json($topUsers ?? []),
            programTypes: @json($programTypes ?? []),
            politicalParties: @json($politicalParties ?? []),
            candidates: @json($candidates ?? []),
            recentReports: @json($recentReports ?? []),
            monthlyReports: @json($monthlyReports ?? $defaultMonthlyReports),
            monthlyReportsUrl: "{{ route('dashboard.monthly-reports') }}",
            zoneId: {{ $zoneId ?? 'null' }}
        };
    </script>

    {{-- Dashboard JavaScript --}}
    <script src="{{ asset('js/dashboard/viewer.js') }}"></script>

    <script>
        // Mark dashboard link as active in sidebar
        var dashboardLink = document.getElementById("dashboard_link");
        if (dashboardLink) {
            dashboardLink.classList.add("active");
        }
    </script>
@endpush
