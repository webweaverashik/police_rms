<?php
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Administrative\Union;
use App\Models\Administrative\Upazila;
use App\Models\Administrative\Zone;
use App\Models\Political\ParliamentSeat;
use App\Models\Political\PoliticalParty;
use App\Models\Report\ProgramType;
use App\Models\Report\Report;
use App\Models\Report\ReportAssignment;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDF;
use Rakibhstu\Banglanumber\NumberToBangla;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $user = auth()->user();

        $cacheKey = 'reports.index.' . $user->role->name . '.' . $user->id;

        $reports = Cache::remember($cacheKey, now()->addHours(2), function () use ($user) {
            $query = Report::query()->with(['upazila', 'zone', 'union', 'politicalParty', 'parliamentSeat', 'programType', 'createdBy:id,name,designation_id', 'createdBy.designation:id,name', 'assignment']);

            // Operator → own reports
            if ($user->isOperator()) {
                $query->where('reports.created_by', $user->id);
            }

            // Magistrate → assigned only
            elseif ($user->hasRole('Magistrate')) {
                $query->whereExists(function ($q) use ($user) {
                    $q->select(DB::raw(1))->from('report_assignments')->whereColumn('report_assignments.report_id', 'reports.id')->where('report_assignments.user_id', $user->id);
                });
            }

            // Viewer → own zone reports
            if ($user->isViewer()) {
                $query->where('reports.zone_id', $user->zone_id);
            }

            return $query->latest('id')->get();
        });

        // Cache filter/master data (longer TTL)
        $upazilas         = Cache::remember('filters.upazilas', now()->addHours(6), fn() => Upazila::all());
        $zones            = Cache::remember('filters.zones', now()->addHours(6), fn() => Zone::all());
        $unions           = Cache::remember('filters.unions', now()->addHours(6), fn() => Union::all());
        $politicalParties = Cache::remember('filters.parties', now()->addHours(6), fn() => PoliticalParty::whereHas('seatPartyCandidates')->orderBy('name')->get());
        $parliamentSeats  = Cache::remember('filters.seats', now()->addHours(6), fn() => ParliamentSeat::all());
        $programTypes     = Cache::remember('filters.program_types', now()->addHours(6), fn() => ProgramType::all());

        $reporters = Cache::remember('filters.reporters', now()->addHours(6), function () {
            return User::with('designation:id,name')->whereHas('role', fn($q) => $q->where('name', 'Operator'))->select('id', 'name', 'designation_id')->get();
        });

        return view('reports.index', compact('reports', 'upazilas', 'zones', 'unions', 'politicalParties', 'parliamentSeats', 'programTypes', 'reporters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! auth()->user()->isOperator()) {
            return redirect()->route('reports.index')->with('warning', 'আপনার প্রতিবেদন তৈরির অনুমতি নেই');
        }

        $parliamentSeats = ParliamentSeat::all();
        $programTypes    = ProgramType::all();

        return view('reports.create', compact('parliamentSeats', 'programTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->filled('program_date')) {
                $programDate = Carbon::createFromFormat('d-m-Y', $request->program_date)->format('Y-m-d');
            }

            if ($request->filled('program_time')) {
                $programTime = Carbon::createFromFormat('h:i A', $request->program_time)->format('H:i');
            }

            Report::create([
                'parliament_seat_id'       => $request->parliament_seat_id,
                'upazila_id'               => $request->upazila_id,
                'union_id'                 => $request->union_id,
                'zone_id'                  => $request->zone_id,

                'political_party_id'       => $request->political_party_id,
                'candidate_name'           => $request->candidate_name ?? null,

                'program_special_guest'    => $request->filled('program_special_guest') ? $request->program_special_guest : null,
                'program_chair'            => $request->filled('program_chair') ? $request->program_chair : null,
                'location_name'            => $request->filled('location_name') ? $request->location_name : null,

                'program_date'             => $programDate ?? null,
                'program_time'             => $programTime ?? null,

                'tentative_attendee_count' => $request->filled('tentative_attendee_count') ? $request->tentative_attendee_count : null,

                'program_type_id'          => $request->program_type_id,
                'program_status'           => $request->program_status,
                'program_title'            => $request->program_title ?? null,
                'program_description'      => $request->program_description ?? null,

                'created_by'               => auth()->id(),
            ]);

            DB::commit();

            Cache::flush(); // acceptable for small system

            return response()->json([
                'success'  => true,
                'message'  => 'প্রতিবেদন সফলভাবে সংরক্ষণ করা হয়েছে',
                'redirect' => route('reports.index'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            // ✅ production-safe error message
            return response()->json(
                [
                    'success' => false,
                    'message' => config('app.debug') ? $e->getMessage() : 'প্রতিবেদন সংরক্ষণ করা যায়নি',
                ],
                500,
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $report = Report::findOrFail($id);

        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (! auth()->user()->isSuperAdmin()) {
            return redirect()->route('reports.index')->with('warning', 'আপনার প্রতিবেদন সংশোধনের অনুমতি নেই');
        }

        $report = Report::findOrFail($id);

        // ✅ Only upazilas under this parliament seat
        $upazilas = Upazila::where('parliament_seat_id', $report->parliament_seat_id)->orderBy('name')->get();

        // ✅ Only zones under this upazila
        $zones = Zone::where('upazila_id', $report->upazila_id)->orderBy('name')->get();

        // ✅ Only unions under this upazila
        $unions = Union::where('upazila_id', $report->upazila_id)->orderBy('name')->get();

        // ✅ Political parties limited to this parliament seat
        $politicalParties = PoliticalParty::whereHas('seatPartyCandidates', function ($q) use ($report) {
            $q->where('parliament_seat_id', $report->parliament_seat_id);
        })
            ->orderBy('name')
            ->get();

        $parliamentSeats = ParliamentSeat::orderBy('name')->get();
        $programTypes    = ProgramType::orderBy('name')->get();

        return view('reports.edit', compact('report', 'upazilas', 'zones', 'unions', 'politicalParties', 'parliamentSeats', 'programTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $report = Report::findOrFail($id);

            // Normalize date
            if ($request->filled('program_date')) {
                $programDate = Carbon::createFromFormat('d-m-Y', $request->program_date)->format('Y-m-d');
            } else {
                $programDate = $report->program_date; // 🔒 keep old
            }

            // Normalize time
            if ($request->filled('program_time')) {
                $programTime = Carbon::createFromFormat('h:i A', $request->program_time)->format('H:i:s');
            } else {
                $programTime = $report->program_time; // 🔒 keep old
            }

            $report->update([
                // 🔒 Administrative fields: update ONLY if present
                'parliament_seat_id'       => $request->has('parliament_seat_id') ? $request->parliament_seat_id : $report->parliament_seat_id,

                'upazila_id'               => $request->has('upazila_id') ? $request->upazila_id : $report->upazila_id,

                'union_id'                 => $request->has('union_id') ? $request->union_id : $report->union_id,

                'zone_id'                  => $request->has('zone_id') ? $request->zone_id : $report->zone_id,

                // Editable fields
                'political_party_id'       => $request->political_party_id,
                'candidate_name'           => $request->candidate_name ?? null,

                'program_special_guest'    => $request->filled('program_special_guest') ? $request->program_special_guest : null,

                'program_chair'            => $request->filled('program_chair') ? $request->program_chair : null,

                'location_name'            => $request->filled('location_name') ? $request->location_name : null,

                'program_date'             => $programDate,
                'program_time'             => $programTime,

                'tentative_attendee_count' => $request->filled('tentative_attendee_count') ? $request->tentative_attendee_count : null,

                'program_type_id'          => $request->program_type_id,
                'program_status'           => $request->program_status,
                'program_title'            => $request->program_title ?? null,
                'program_description'      => $request->program_description ?? null,
            ]);

            DB::commit();

            Cache::flush();

            return response()->json([
                'success'  => true,
                'message'  => 'প্রতিবেদন সফলভাবে আপডেট হয়েছে',
                'redirect' => route('reports.index'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(
                [
                    'success' => false,
                    'message' => config('app.debug') ? $e->getMessage() : 'প্রতিবেদন আপডেট করা যায়নি',
                ],
                500,
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $report->delete();
        $report->update(['deleted_by' => auth()->user()->id]);

        return response()->json(['success' => true]);
    }

    /**
     * Download the pdf report
     */
    public function download(Report $report)
    {
        $numto = new NumberToBangla();

        $date = Carbon::parse($report->created_at);

        // Date part
        $bnDate = $numto->bnNum($date->format('d')) . '/' . $numto->bnNum($date->format('m')) . '/' . $numto->bnNum($date->format('Y'));

        // Time part (12-hour)
        $bnHour     = $numto->bnNum($date->format('h'));
        $bnMinute   = $numto->bnNum($date->format('i'));
        $bnMeridiem = $date->format('A') === 'AM' ? 'পূর্বাহ্ণ' : 'অপরাহ্ণ';

        // Final output
        $reportDateTime = $bnDate . ', ' . $bnHour . ':' . $bnMinute . ' ' . $bnMeridiem;

        return PDF::loadView('reports.pdf', compact('report', 'reportDateTime'))->stream($report->program_title . ' - ' . $report->candidate_name . '.pdf');
    }

    /**
     * Magistrate assignment to report
     */
    public function getMagistrates(Report $report)
    {
        $magistrates = User::whereHas('role', function ($q) {
            $q->where('name', 'Magistrate');
        })
            ->with('role')
            ->get()
            ->map(function ($user) {
                return [
                    'id'          => $user->id,
                    'name'        => $user->name,
                    'designation' => $user->designation->name, // Magistrate
                    'avatar'      => asset('assets/img/dummy.png'),
                ];
            });

        $assigned = ReportAssignment::where('report_id', $report->id)->pluck('user_id');

        return response()->json([
            'magistrates' => $magistrates,
            'assigned'    => $assigned,
        ]);
    }

    public function assignMagistrates(Request $request, Report $report)
    {
        $request->validate([
            'user_ids'   => 'array',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Remove old assignments
        ReportAssignment::where('report_id', $report->id)->delete();

        // Insert new
        foreach ($request->user_ids as $userId) {
            ReportAssignment::create([
                'report_id'   => $report->id,
                'user_id'     => $userId,
                'assigned_by' => auth()->id(),
            ]);
        }

        Cache::flush();

        return response()->json([
            'success' => true,
            'message' => 'ম্যাজিস্ট্রেট সফলভাবে নির্ধারণ করা হয়েছে।',
        ]);
    }
}
