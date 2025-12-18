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
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
            $query = Report::query()
                ->with(['upazila', 'zone', 'union', 'politicalParty', 'parliamentSeat', 'programType', 'createdBy:id,name,designation_id', 'createdBy.designation:id,name', 'assignment'])
                ->latest('reports.created_at');

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

            return $query->latest('reports.id')->get();
        });

        /*
    |--------------------------------------------------------------------------
    | Cache filter/master data (longer TTL)
    |--------------------------------------------------------------------------
    */
        $upazilas         = Cache::remember('filters.upazilas', now()->addHours(6), fn() => Upazila::all());
        $zones            = Cache::remember('filters.zones', now()->addHours(6), fn() => Zone::all());
        $unions           = Cache::remember('filters.unions', now()->addHours(6), fn() => Union::all());
        $politicalParties = Cache::remember('filters.parties', now()->addHours(6), fn() => PoliticalParty::all());
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

        $upazilas         = Upazila::all();
        $zones            = Zone::all();
        $politicalParties = PoliticalParty::all();
        $parliamentSeats  = ParliamentSeat::all();
        $programTypes     = ProgramType::all();

        return view('reports.create', compact('upazilas', 'zones', 'politicalParties', 'parliamentSeats', 'programTypes'));
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
                $programTime = Carbon::createFromFormat('h:i A', $request->program_time)->format('H:i:s');
            }

            Report::create([
                'parliament_seat_id'       => $request->parliament_seat_id,
                'upazila_id'               => $request->upazila_id,
                'union_id'                 => $request->union_id,
                'zone_id'                  => $request->zone_id,

                'political_party_id'       => $request->political_party_id,
                'candidate_name'           => $request->candidate_name,

                'program_special_guest'    => $request->filled('program_special_guest') ? $request->program_chair : null,
                'program_chair'            => $request->filled('program_chair') ? $request->program_chair : null,
                'location_name'            => $request->filled('location_name') ? $request->program_chair : null,

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

        $upazilas         = Upazila::all();
        $unions           = Union::all();
        $zones            = Zone::all();
        $politicalParties = PoliticalParty::all();
        $parliamentSeats  = ParliamentSeat::all();
        $programTypes     = ProgramType::all();

        $report = Report::findOrFail($id);

        return view('reports.edit', compact('report', 'upazilas', 'unions', 'zones', 'politicalParties', 'parliamentSeats', 'programTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $report = Report::findOrFail($id);

        $report->update($request->all());

        Cache::flush(); // acceptable for small system

        return response()->json([
            'success'  => true,
            'message'  => 'প্রতিবেদন সফলভাবে আপডেট হয়েছে',
            'redirect' => route('reports.index'),
        ]);
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
        $pdf = PDF::loadView('reports.pdf', compact('report'));
        return $pdf->download('report.pdf');
    }
}
