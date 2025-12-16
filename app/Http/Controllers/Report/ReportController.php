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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->role->name == 'Operator') {
            $reports = Report::with(['upazila', 'zone', 'union', 'politicalParty', 'parliamentSeat', 'programType', 'createdBy:id,name,designation_id', 'createdBy.designation:id,name'])
                ->where('created_by', auth()->user()->id)
                ->latest('created_at')
                ->get();
        } else {
            $reports = Report::with(['upazila', 'zone', 'union', 'politicalParty', 'parliamentSeat', 'programType', 'createdBy:id,name,designation_id', 'createdBy.designation:id,name'])
                ->latest('created_at')
                ->get();
        }

        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->role->name !== 'Operator') {
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
            $programDateTime = Carbon::createFromFormat(
                'd-m-Y h:i A',
                $request->program_date_time
            )->format('Y-m-d H:i:s');

            Report::create([
                'parliament_seat_id'       => $request->parliament_seat_id,
                'upazila_id'               => $request->upazila_id,
                'union_id'                 => $request->union_id,
                'zone_id'                  => $request->zone_id,
                'political_party_id'       => $request->political_party_id,
                'candidate_name'           => $request->candidate_name,
                'program_special_guest'    => $request->program_special_guest,
                'program_chair'            => $request->program_chair,
                'program_date_time'        => $programDateTime,
                'location_name'            => $request->location_name,
                'tentative_attendee_count' => $request->tentative_attendee_count ?: null,
                'program_type_id'          => $request->program_type_id,
                'program_status'           => $request->program_status,
                'final_attendee_count'     => $request->final_attendee_count ?: null,
                'program_title'            => $request->program_title,
                'program_description'      => $request->program_description,
                'created_by'               => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'প্রতিবেদন সফলভাবে সংরক্ষণ করা হয়েছে',
                'redirect' => route('reports.index'),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
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
        if (auth()->user()->role->name !== 'Administrator') {
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
}
