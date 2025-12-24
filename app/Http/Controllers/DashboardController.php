<?php
namespace App\Http\Controllers;

use App\Models\Administrative\Union;
use App\Models\Administrative\Zone;
use App\Models\Political\PoliticalParty;
use App\Models\Report\ProgramType;
use App\Models\Report\Report;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**w
     * Bengali month names
     */
    private $bengaliMonths = [
        1  => 'জানুয়ারি',
        2  => 'ফেব্রুয়ারি',
        3  => 'মার্চ',
        4  => 'এপ্রিল',
        5  => 'মে',
        6  => 'জুন',
        7  => 'জুলাই',
        8  => 'আগস্ট',
        9  => 'সেপ্টেম্বর',
        10 => 'অক্টোবর',
        11 => 'নভেম্বর',
        12 => 'ডিসেম্বর',
    ];

    /**
     * Display the dashboard based on user role
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isOperator()) {
            return view('dashboard.operator.index');
        }

        if ($user->isMagistrate()) {
            return redirect()->route('reports.index');
        }

        if ($user->isViewer()) {
            return $this->viewerDashboard($user);
        }

        // Admin dashboard
        $data = [
            'stats'            => $this->getStats(),
            'zones'            => $this->getZoneWiseReports(),
            'unions'           => $this->getUnionWiseReports(),
            'topUsers'         => $this->getTopUsersByReports(),
            'programTypes'     => $this->getProgramTypesDistribution(),
            'politicalParties' => $this->getPoliticalPartyReports(),
            'candidates'       => $this->getCandidateReports(),
            'recentReports'    => $this->getRecentReports(),
            'monthlyReports'   => $this->getMonthlyReportsData(0),
        ];

        return view('dashboard.admin.index', $data);
    }

    /**
     * Viewer Dashboard - filtered by user's zone_id
     */
    private function viewerDashboard($user)
    {
        $zoneId = $user->zone_id;
        $zone   = Zone::find($zoneId);

        $data = [
            'zoneId'           => $zoneId,
            'zoneName'         => $zone->name ?? 'আমার জোন',
            'stats'            => $this->getStats($zoneId),
            'unions'           => $this->getUnionWiseReports($zoneId),
            'topUsers'         => $this->getTopUsersByReports(10, $zoneId),
            'programTypes'     => $this->getProgramTypesDistribution($zoneId),
            'politicalParties' => $this->getPoliticalPartyReports($zoneId),
            'candidates'       => $this->getCandidateReports(10, $zoneId),
            'recentReports'    => $this->getRecentReports(10, $zoneId),
            'monthlyReports'   => $this->getMonthlyReportsData(0, $zoneId),
        ];

        return view('dashboard.viewer.index', $data);
    }

    /**
     * Get overall statistics
     * @param int|null $zoneId - Filter by zone if provided
     */
    private function getStats(?int $zoneId = null): array
    {
        $query = Report::query();

        if ($zoneId) {
            $query->where('zone_id', $zoneId);
        }

        return [
            'totalReports'      => (clone $query)->count(),
            'completedPrograms' => (clone $query)->where('program_status', 'done')->count(),
            'pendingPrograms'   => (clone $query)->where('program_status', 'ongoing')->count(),
            'totalAttendees'    => (int) (clone $query)->sum('tentative_attendee_count'),
        ];
    }

    /**
     * Get zone-wise report counts
     */
    private function getZoneWiseReports(): array
    {
        $zones = Zone::withCount('reports')->orderByDesc('reports_count')->get();

        $totalReports = $zones->sum('reports_count');

        return $zones
            ->map(function ($zone) use ($totalReports) {
                return [
                    'id'         => $zone->id,
                    'name'       => $zone->name,
                    'reports'    => $zone->reports_count,
                    'percentage' => $totalReports > 0 ? round(($zone->reports_count / $totalReports) * 100, 1) : 0,
                ];
            })
            ->toArray();
    }

    /**
     * Get union-wise report counts
     * @param int|null $zoneId - Filter by zone if provided
     */
    private function getUnionWiseReports(?int $zoneId = null): array
    {
        $query = Union::withCount([
            'reports' => function ($query) use ($zoneId) {
                if ($zoneId) {
                    $query->where('zone_id', $zoneId);
                }
            },
        ])
            ->with('upazila:id,name')
            ->orderByDesc('reports_count')
            ->limit(20);

        // If zone is specified, only get unions that have reports in that zone
        if ($zoneId) {
            $query->whereHas('reports', function ($q) use ($zoneId) {
                $q->where('zone_id', $zoneId);
            });
        }

        $unions = $query->get();

        $totalReports = $unions->sum('reports_count');

        return $unions
            ->map(function ($union) use ($totalReports) {
                return [
                    'id'         => $union->id,
                    'name'       => $union->name,
                    'upazila'    => $union->upazila->name ?? null,
                    'reports'    => $union->reports_count,
                    'percentage' => $totalReports > 0 ? round(($union->reports_count / $totalReports) * 100, 1) : 0,
                ];
            })
            ->toArray();
    }

    /**
     * Get top users by report count
     * @param int $limit - Number of users to return
     * @param int|null $zoneId - Filter by zone if provided
     */
    private function getTopUsersByReports(int $limit = 10, ?int $zoneId = null): array
    {
        $query = User::withCount([
            'reports' => function ($query) use ($zoneId) {
                if ($zoneId) {
                    $query->where('zone_id', $zoneId);
                }
            },
        ])
            ->with('designation:id,name')
            ->whereHas('reports', function ($query) use ($zoneId) {
                if ($zoneId) {
                    $query->where('zone_id', $zoneId);
                }
            })
            ->orderByDesc('reports_count')
            ->limit($limit);

        // Filter users by zone
        if ($zoneId) {
            $query->where('zone_id', $zoneId);
        }

        return $query
            ->get()
            ->map(function ($user) use ($zoneId) {
                $lastReportQuery = $user->reports();
                if ($zoneId) {
                    $lastReportQuery->where('zone_id', $zoneId);
                }
                $lastReport = $lastReportQuery->latest()->first();

                return [
                    'id'          => $user->id,
                    'name'        => $user->name,
                    'designation' => $user->designation->name ?? 'N/A',
                    'reports'     => $user->reports_count,
                    'lastReport'  => $lastReport ? $this->formatTimeAgo($lastReport->created_at) : 'N/A',
                ];
            })
            ->toArray();
    }

    /**
     * Get program types distribution
     * @param int|null $zoneId - Filter by zone if provided
     */
    private function getProgramTypesDistribution(?int $zoneId = null): array
    {
        return ProgramType::withCount([
            'reports' => function ($query) use ($zoneId) {
                if ($zoneId) {
                    $query->where('zone_id', $zoneId);
                }
            },
        ])
            ->orderByDesc('reports_count')
            ->get()
            ->map(function ($type) {
                return [
                    'name'  => $type->name,
                    'count' => $type->reports_count,
                ];
            })
            ->toArray();
    }

    /**
     * Get political party reports with colors (only parties with reports > 0)
     * @param int|null $zoneId - Filter by zone if provided
     */
    private function getPoliticalPartyReports(?int $zoneId = null): array
    {
        $colors = ['#3E97FF', '#50CD89', '#F6C000', '#7239EA', '#F1416C', '#181C32', '#009EF7', '#FFC700'];

        return PoliticalParty::withCount([
            'reports' => function ($query) use ($zoneId) {
                if ($zoneId) {
                    $query->where('zone_id', $zoneId);
                }
            },
        ])
            ->having('reports_count', '>', 0)
            ->orderByDesc('reports_count')
            ->get()
            ->map(function ($party, $index) use ($colors) {
                return [
                    'name'  => $party->name,
                    'count' => $party->reports_count,
                    'color' => $colors[$index % count($colors)],
                ];
            })
            ->toArray();
    }

    /**
     * Get candidate reports with program count and attendees
     * @param int $limit - Number of candidates to return
     * @param int|null $zoneId - Filter by zone if provided
     */
    private function getCandidateReports(int $limit = 10, ?int $zoneId = null): array
    {
        $query = Report::select('candidate_name', 'political_party_id')->selectRaw('COUNT(*) as programs')->selectRaw('COALESCE(SUM(tentative_attendee_count), 0) as attendees')->with('politicalParty:id,name')->whereNotNull('candidate_name')->where('candidate_name', '!=', '');

        if ($zoneId) {
            $query->where('zone_id', $zoneId);
        }

        return $query
            ->groupBy('candidate_name', 'political_party_id')
            ->orderByDesc('programs')
            ->limit($limit)
            ->get()
            ->map(function ($candidate) {
                return [
                    'name'      => $candidate->candidate_name,
                    'party'     => $candidate->politicalParty->name ?? 'স্বতন্ত্র',
                    'programs'  => (int) $candidate->programs,
                    'attendees' => (int) $candidate->attendees,
                ];
            })
            ->toArray();
    }

    /**
     * Get recent reports
     * @param int $limit - Number of reports to return
     * @param int|null $zoneId - Filter by zone if provided
     */
    private function getRecentReports(int $limit = 10, ?int $zoneId = null): array
    {
        $query = Report::with(['zone:id,name', 'union:id,name', 'programType:id,name'])
            ->latest()
            ->limit($limit);

        if ($zoneId) {
            $query->where('zone_id', $zoneId);
        }

        return $query
            ->get()
            ->map(function ($report) {
                return [
                    'id'     => $report->id,
                    'title'  => $report->program_title ?: $report->location_name ?: 'Untitled',
                    'zone'   => $report->zone->name ?? 'N/A',
                    'union'  => $report->union->name ?? 'N/A',
                    'type'   => $report->programType->name ?? 'N/A',
                    'status' => $this->translateStatus($report->program_status),
                    'time'   => $this->formatTimeAgo($report->created_at),
                ];
            })
            ->toArray();
    }

    /**
     * Get monthly reports data for a specific month
     * @param int $offset - Month offset (0 = current, -1 = previous, 1 = next)
     * @param int|null $zoneId - Filter by zone if provided
     */
    private function getMonthlyReportsData(int $offset = 0, ?int $zoneId = null): array
    {
        $targetDate  = Carbon::now()->addMonths($offset);
        $year        = $targetDate->year;
        $month       = $targetDate->month;
        $daysInMonth = $targetDate->daysInMonth;

        $categories = [];
        $data       = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $categories[] = $this->convertToBengaliNumber($day);

            $query = Report::whereYear('created_at', $year)->whereMonth('created_at', $month)->whereDay('created_at', $day);

            if ($zoneId) {
                $query->where('zone_id', $zoneId);
            }

            $data[] = $query->count();
        }

        return [
            'month'      => $month,
            'year'       => $year,
            'monthName'  => $this->bengaliMonths[$month],
            'categories' => $categories,
            'data'       => $data,
        ];
    }

    /**
     * API endpoint for monthly reports navigation
     */
    public function getMonthlyReports(Request $request)
    {
        $offset = (int) $request->get('offset', 0);
        $zoneId = $request->get('zone_id');

                                             // Limit offset to prevent going too far back or forward
        $offset = max(-24, min(0, $offset)); // Max 24 months back, no future

        // If user is a viewer, enforce zone filtering
        $user = auth()->user();
        if ($user->isViewer()) {
            $zoneId = $user->zone_id;
        }

        return response()->json($this->getMonthlyReportsData($offset, $zoneId ? (int) $zoneId : null));
    }

    /**
     * Convert number to Bengali digits
     */
    private function convertToBengaliNumber($number): string
    {
        $bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($englishDigits, $bengaliDigits, (string) $number);
    }

    /**
     * Translate status to Bengali
     */
    private function translateStatus(?string $status): string
    {
        return match ($status) {
            'done', 'completed'      => 'সম্পন্ন',
            'ongoing', 'in_progress' => 'চলমান',
            'upcoming' => 'আসন্ন',
            'pending'  => 'অপেক্ষমাণ',
            default    => 'অন্যান্য',
        };
    }

    /**
     * Format time ago in Bengali
     */
    private function formatTimeAgo(Carbon $date): string
    {
        $diff = $date->diffForHumans();

        // Simple Bengali translation replacements
        $replacements = [
            'seconds' => 'সেকেন্ড',
            'second'  => 'সেকেন্ড',
            'minutes' => 'মিনিট',
            'minute'  => 'মিনিট',
            'hours'   => 'ঘণ্টা',
            'hour'    => 'ঘণ্টা',
            'day'     => 'দিন',
            'days'    => 'দিন',
            'week'    => 'সপ্তাহ',
            'weeks'   => 'সপ্তাহ',
            'month'   => 'মাস',
            'months'  => 'মাস',
            'year'    => 'বছর',
            'years'   => 'বছর',
            'ago'     => 'আগে',
        ];

        foreach ($replacements as $en => $bn) {
            $diff = str_replace($en, $bn, $diff);
        }

        return $diff;
    }

    /**
     * API endpoint for refreshing dashboard data via AJAX
     */
    public function refreshData(Request $request)
    {
        $user   = auth()->user();
        $zoneId = null;

        // If viewer, filter by their zone
        if ($user->isViewer()) {
            $zoneId = $user->zone_id;
        }

        $data = [
            'stats'            => $this->getStats($zoneId),
            'unions'           => $this->getUnionWiseReports($zoneId),
            'users'            => $this->getTopUsersByReports(10, $zoneId),
            'programTypes'     => $this->getProgramTypesDistribution($zoneId),
            'politicalParties' => $this->getPoliticalPartyReports($zoneId),
            'candidates'       => $this->getCandidateReports(10, $zoneId),
            'recentReports'    => $this->getRecentReports(10, $zoneId),
            'monthlyReports'   => $this->getMonthlyReportsData(0, $zoneId),
        ];

        // Include zones only for admin
        if (! $user->isViewer()) {
            $data['zones'] = $this->getZoneWiseReports();
        }

        return response()->json($data);
    }
}
