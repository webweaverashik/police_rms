<?php
namespace App\Http\Controllers\Political;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Political\PoliticalParty;

class PoliticalPartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parties = PoliticalParty::withCount('reports')->latest('reports_count')->get();

        return view('political_parties.index', compact('parties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('political-parties.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'party_name' => 'required|string|max:50|unique:program_types,name',
                'party_head' => 'nullable|string|max:200',
            ],
            [
                'party_name.required' => 'ধরণের নাম প্রয়োজন',
                'party_name.unique'   => 'এই ধরণটি ইতিমধ্যে আছে',
                'party_name.max'      => 'নামটি ৫০ অক্ষরের বেশি হতে পারবে না',
            ],
        );

        $party = PoliticalParty::create([
            'name'       => $request->party_name,
            'party_head' => $request->party_head ?? null,
        ]);

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(
                [
                    'success'         => true,
                    'message'         => 'রাজনৈতিক দল সফলভাবে যোগ হয়েছে',
                    'polytical_party' => [
                        'id'   => $party->id,
                        'name' => $party->name,
                    ],
                ],
                201,
            );
        }

        // Normal redirect for non-AJAX requests
        return redirect()->route('political-parties.index')->with('success', 'রাজনৈতিক দল সফলভাবে যোগ হয়েছে');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $party = PoliticalParty::findOrFail($id);

        return view('political_parties.show', compact('party'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('political-parties.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'party_name_edit' => ['required', 'string', 'max:50', Rule::unique('political_parties', 'name')->ignore($id)],
                'party_head_edit' => 'nullable|string|max:200',
            ],
            [
                'party_name_edit.required' => 'ধরণের নাম প্রয়োজন',
                'party_name_edit.unique'   => 'এই ধরণটি ইতিমধ্যে আছে',
                'party_name_edit.max'      => 'নামটি ৫০ অক্ষরের বেশি হতে পারবে না',
            ],
        );

        $political_party = PoliticalParty::findOrFail($id);

        $political_party->update([
            'name'       => $request->party_name_edit,
            'party_head' => $request->party_head_edit,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'রাজনৈতিক দলটি সফলভাবে আপডেট করা হয়েছে',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
