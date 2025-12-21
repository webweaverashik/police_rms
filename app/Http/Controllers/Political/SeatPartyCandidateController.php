<?php
namespace App\Http\Controllers\Political;

use App\Http\Controllers\Controller;
use App\Models\Political\SeatPartyCandidate;
use Illuminate\Http\Request;

class SeatPartyCandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('political-parties.index');
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
        $validated = $request->validate(
            [
                'candidate_name'     => 'required|string|max:50',
                'election_symbol'    => 'nullable|string|max:50',
                'parliament_seat_id' => 'required|integer|exists:parliament_seats,id',
                'political_party_id' => 'required|integer|exists:political_parties,id',
            ],
            [
                'candidate_name.required'     => 'প্রার্থীর নাম প্রয়োজন',
                'candidate_name.max'          => 'নামটি ৫০ অক্ষরের বেশি হতে পারবে না',
                'parliament_seat_id.required' => 'সংসদীয় আসন নির্বাচন করুন',
            ],
        );

        SeatPartyCandidate::create([
            'candidate_name'     => $validated['candidate_name'],
            'election_symbol'    => $validated['election_symbol'] ?? null,
            'parliament_seat_id' => $validated['parliament_seat_id'],
            'political_party_id' => $validated['political_party_id'],
        ]);

        return response()->json(
            [
                'success' => true,
                'message' => 'প্রার্থী সফলভাবে যোগ হয়েছে',
            ],
            201,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $candidate = SeatPartyCandidate::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                 => $candidate->id,
                'candidate_name'     => $candidate->candidate_name,
                'election_symbol'    => $candidate->election_symbol,
                'parliament_seat_id' => $candidate->parliament_seat_id,
            ],
        ]);
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
        $validated = $request->validate(
            [
                'candidate_name_edit'     => 'required|string|max:50',
                'election_symbol_edit'    => 'nullable|string|max:50',
                'parliament_seat_id_edit' => 'required|integer|exists:parliament_seats,id',
            ],
            [
                'candidate_name_edit.required'     => 'প্রার্থীর নাম প্রয়োজন',
                'candidate_name_edit.max'          => 'নামটি ৫০ অক্ষরের বেশি হতে পারবে না',
                'parliament_seat_id_edit.required' => 'সংসদীয় আসন নির্বাচন করুন',
            ],
        );

        $candidate = SeatPartyCandidate::findOrFail($id);

        // ✅ Only update allowed fields
        $candidate->update([
            'candidate_name'     => $validated['candidate_name_edit'],
            'election_symbol'    => $validated['election_symbol_edit'] ?? null,
            'parliament_seat_id' => $validated['parliament_seat_id_edit'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'প্রার্থীর তথ্য সফলভাবে আপডেট করা হয়েছে',
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
