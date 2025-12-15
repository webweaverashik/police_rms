<?php
namespace App\Http\Controllers;

use App\Models\ParliamentSeat;
use App\Models\PoliticalParty;
use App\Models\ProgramType;
use App\Models\Report;
use App\Models\Upazila;
use App\Models\Zone;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->role->name == 'Operator') {
            $reports = Report::with(['upazila', 'zone', 'politicalParty', 'parliamentSeat', 'programType', 'createdBy:id,name,designation_id', 'createdBy.designation:id,name'])
                ->where('created_by', auth()->user()->id)
                ->get();
        } else {
            $reports = Report::with(['upazila', 'zone', 'politicalParty', 'parliamentSeat', 'programType', 'createdBy:id,name,designation_id', 'createdBy.designation:id,name'])->get();
        }

        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
