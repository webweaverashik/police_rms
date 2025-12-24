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
            $query = Report::query()->with(['upazila', 'zone', 'union', 'politicalParty', 'parliamentSeat', 'programType', 'createdBy:id,name,designation_id', 'createdBy.designation:id,name', 'assignments']);

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

            return $query->latest('updated_at')->get();
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
        // Validate the request
        $validated = $request->validate(
            [
                // Administrative Jurisdiction
                'parliament_seat_id'       => 'required|exists:parliament_seats,id',
                'upazila_id'               => 'required|exists:upazilas,id',
                'zone_id'                  => 'required|exists:zones,id',
                'union_id'                 => 'required|exists:unions,id',
                'location_name'            => 'nullable|string|max:255',

                // Political Information
                'political_party_id'       => 'required|exists:political_parties,id',
                'candidate_name'           => 'nullable|string|max:255',

                // Program Information
                'program_special_guest'    => 'nullable|string|max:255',
                'program_chair'            => 'nullable|string|max:255',
                'program_type_id'          => 'required|exists:program_types,id',
                'program_date'             => 'nullable|date_format:d-m-Y',
                'program_time'             => 'nullable|date_format:h:i A',
                'program_status'           => 'required|in:upcoming,ongoing,done',
                'tentative_risks'          => 'required|in:yes,no',

                // Conditional fields based on program_status
                'tentative_attendee_count' => 'nullable|integer|min:10',
                'actual_attendee_count'    => 'nullable|string|max:255',
                'dead_injured_count'       => 'nullable|string|max:255',

                // Program Details
                'program_title'            => 'required|string|max:1000',
                'program_description'      => 'nullable|string',
            ],
            [
                // Custom validation messages in Bengali
                'parliament_seat_id.required'  => 'সংসদীয় আসন সিলেক্ট করুন',
                'parliament_seat_id.exists'    => 'নির্বাচিত সংসদীয় আসন সঠিক নয়',
                'upazila_id.required'          => 'উপজেলা সিলেক্ট করুন',
                'upazila_id.exists'            => 'নির্বাচিত উপজেলা সঠিক নয়',
                'zone_id.required'             => 'থানা সিলেক্ট করুন',
                'zone_id.exists'               => 'নির্বাচিত থানা সঠিক নয়',
                'union_id.required'            => 'ইউনিয়ন সিলেক্ট করুন',
                'union_id.exists'              => 'নির্বাচিত ইউনিয়ন সঠিক নয়',
                'political_party_id.required'  => 'রাজনৈতিক দল সিলেক্ট করুন',
                'political_party_id.exists'    => 'নির্বাচিত রাজনৈতিক দল সঠিক নয়',
                'program_type_id.required'     => 'প্রোগ্রামের ধরণ সিলেক্ট করুন',
                'program_type_id.exists'       => 'নির্বাচিত প্রোগ্রামের ধরণ সঠিক নয়',
                'program_status.required'      => 'প্রোগ্রামের অবস্থা সিলেক্ট করুন',
                'program_status.in'            => 'প্রোগ্রামের অবস্থা সঠিক নয়',
                'tentative_risks.required'     => 'ঝুঁকির অবস্থা সিলেক্ট করুন',
                'tentative_risks.in'           => 'ঝুঁকির অবস্থা সঠিক নয়',
                'program_title.required'       => 'প্রোগ্রামের বিষয় লিখুন',
                'program_date.date_format'     => 'তারিখের ফরম্যাট সঠিক নয় (দিন-মাস-বছর)',
                'program_time.date_format'     => 'সময়ের ফরম্যাট সঠিক নয়',
                'tentative_attendee_count.min' => 'ন্যূনতম ১০ জন সংখ্যা দেওয়া যাবে',
            ],
        );

        DB::beginTransaction();

        try {
            // Parse date if provided
            $programDate = null;
            if ($request->filled('program_date')) {
                $programDate = Carbon::createFromFormat('d-m-Y', $request->program_date)->format('Y-m-d');
            }

            // Parse time if provided
            $programTime = null;
            if ($request->filled('program_time')) {
                $programTime = Carbon::createFromFormat('h:i A', $request->program_time)->format('H:i');
            }

            // Prepare data based on program_status
            $reportData = [
                'parliament_seat_id'    => $validated['parliament_seat_id'],
                'upazila_id'            => $validated['upazila_id'],
                'union_id'              => $validated['union_id'],
                'zone_id'               => $validated['zone_id'],

                'political_party_id'    => $validated['political_party_id'],
                'candidate_name'        => $validated['candidate_name'] ?? null,

                'program_special_guest' => $validated['program_special_guest'] ?? null,
                'program_chair'         => $validated['program_chair'] ?? null,
                'location_name'         => $validated['location_name'] ?? null,

                'program_date'          => $programDate,
                'program_time'          => $programTime,

                'program_type_id'       => $validated['program_type_id'],
                'program_status'        => $validated['program_status'],
                'tentative_risks'       => $validated['tentative_risks'],

                'program_title'         => $validated['program_title'],
                'program_description'   => $validated['program_description'] ?? null,

                'created_by'            => auth()->id(),
            ];

            // Set conditional fields based on program_status
            if ($validated['program_status'] === 'done') {
                // For completed programs: store actual counts, clear tentative
                $reportData['actual_attendee_count']    = $validated['actual_attendee_count'] ?? null;
                $reportData['dead_injured_count']       = $validated['dead_injured_count'] ?? null;
                $reportData['tentative_attendee_count'] = null;
            } else {
                // For ongoing/upcoming programs: store tentative, clear actual counts
                $reportData['tentative_attendee_count'] = $validated['tentative_attendee_count'] ?? null;
                $reportData['actual_attendee_count']    = null;
                $reportData['dead_injured_count']       = null;
            }

            Report::create($reportData);

            DB::commit();

            Cache::flush(); // acceptable for small system

            return response()->json([
                'success'  => true,
                'message'  => 'প্রতিবেদন সফলভাবে সংরক্ষণ করা হয়েছে',
                'redirect' => route('reports.index'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            // Log the error for debugging
            Log::error('Report creation failed: ' . $e->getMessage(), [
                'trace'   => $e->getTraceAsString(),
                'request' => $request->except(['_token']),
            ]);

            // Production-safe error message
            return response()->json(
                [
                    'success' => false,
                    'message' => config('app.debug') ? $e->getMessage() : 'প্রতিবেদন সংরক্ষণ করা যায়নি',
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
        $user   = auth()->user();

        // Operator → only own created reports
        if ($user->isOperator()) {
            if ($report->created_by !== $user->id) {
                return back()->with('warning', 'এই প্রতিবেদনটি আপনার দেখার অনুমতি নেই');
            }
        }

        // Viewer → same zone only
        if ($user->isViewer()) {
            if ($report->zone_id !== $user->zone_id) {
                return back()->with('warning', 'এই প্রতিবেদনটি আপনার দেখার অনুমতি নেই');
            }
        }

        // Magistrate → only assigned reports
        if ($user->isMagistrate()) {
            $isAssigned = $report->assignments()->where('user_id', $user->id)->exists();

            if (! $isAssigned) {
                return back()->with('warning', 'এই প্রতিবেদনটি আপনার দেখার অনুমতি নেই');
            }
        }

        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $report = Report::findOrFail($id);
        $user   = auth()->user();

        // 🔐 Authorization
        if (! $user->isSuperAdmin() && ! $user->isOperator() && $report->created_by !== $user->id) {
            return redirect()->route('reports.index')->with('warning', 'আপনার প্রতিবেদন সংশোধনের অনুমতি নেই');
        }

        // 📍 Dependent dropdown data
        $upazilas = Upazila::where('parliament_seat_id', $report->parliament_seat_id)->orderBy('name')->get();

        $zones = Zone::where('upazila_id', $report->upazila_id)->orderBy('name')->get();

        $unions = Union::where('upazila_id', $report->upazila_id)->orderBy('name')->get();

        $politicalParties = PoliticalParty::whereHas('seatPartyCandidates', fn($q) => $q->where('parliament_seat_id', $report->parliament_seat_id))->orderBy('name')->get();

        // 📚 Static lists
        $parliamentSeats = ParliamentSeat::orderBy('name')->get();
        $programTypes    = ProgramType::orderBy('name')->get();

        // 🧭 Decide view once
        $view = $report->program_status === 'done' ? 'reports.edit' : 'reports.pending-edit';

        return view($view, compact('report', 'upazilas', 'zones', 'unions', 'politicalParties', 'parliamentSeats', 'programTypes'));
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
     * Update pending report status
     */
    public function updatePending(Request $request, string $id)
    {
        // ✅ Validation (AJAX-friendly)
        $validated = $request->validate(
            [
                'program_status'        => 'required|in:done',
                'actual_attendee_count' => 'required|integer|min:10',
                'dead_injured_count'    => 'nullable|string',
                'program_description'   => 'nullable|string',
            ],
            [
                'program_status.required'        => 'প্রোগ্রামের অবস্থা নির্বাচন করুন।',
                'program_status.in'              => 'প্রোগ্রামের অবস্থা সঠিক নয়।',
                'actual_attendee_count.required' => 'মোট উপস্থিতির সংখ্যা লিখুন।',
                'actual_attendee_count.integer'  => 'উপস্থিতির সংখ্যা অবশ্যই সংখ্যা হতে হবে।',
                'actual_attendee_count.min'      => 'ন্যূনতম ১০ জন উপস্থিতি হতে হবে।',
            ],
        );

        DB::beginTransaction();

        try {
            $report = Report::findOrFail($id);

            $data = [
                'program_status'        => $validated['program_status'],
                'actual_attendee_count' => $validated['actual_attendee_count'],

                // ✅ Empty → NULL
                'dead_injured_count'    => $request->filled('dead_injured_count') ? $validated['dead_injured_count'] : null,
            ];

            // ✅ Update only if provided (otherwise keep old value)
            if ($request->filled('program_description')) {
                $data['program_description'] = $validated['program_description'];
            }

            $report->update($data);

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

        // ================= Report Created Date (Bangla) =================
        $date = Carbon::parse($report->created_at);

        $bnDate = $numto->bnNum($date->format('d')) . '/' . $numto->bnNum($date->format('m')) . '/' . $numto->bnNum($date->format('Y'));

        $bnHour     = $numto->bnNum($date->format('h'));
        $bnMinute   = $numto->bnNum($date->format('i'));
        $bnMeridiem = $date->format('A') === 'AM' ? 'পূর্বাহ্ণ' : 'অপরাহ্ণ';

        $reportDateTime = $bnDate . ', ' . $bnHour . ':' . $bnMinute . ' ' . $bnMeridiem;
        // ===============================================================

        // ================= Download Date (NOW, ENGLISH) =================
        $downloadDateTime = now()->format('d-m-Y h:i A');
        // ===============================================================

        $pdf = PDF::loadView('reports.pdf', compact('report', 'reportDateTime'));

        // ✅ mPDF footer (beyond margin_bottom)
        $pdf->getMpdf()->SetFooter(
            '<span style="font-size:12px; font-weight: normal;">
            Downloaded at: ' .
            $downloadDateTime .
            '
        </span>',
        );

        return $pdf->stream($report->program_title . ' - ' . $report->candidate_name . '.pdf');
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
