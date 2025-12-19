<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administrative\Zone;
use App\Models\Administrative\Union;
use App\Models\Administrative\Upazila;
use App\Models\Political\PoliticalParty;
use App\Models\Political\SeatPartyCandidate;

class AjaxController extends Controller
{
    /**
     * Fetch upazilas by parliament seat
     */
    public function fetchUpazilasBySeat(Request $request)
    {
        $parliamentSeatId = $request->input('parliament_seat_id');

        if (! $parliamentSeatId) {
            return response()->json([]);
        }

        $upazilas = Upazila::where('parliament_seat_id', $parliamentSeatId)
            ->get(['id', 'name']);

        return response()->json($upazilas);
    }

    /**
     * Fetch zones by upazila
     */
    public function fetchZonesByUpazila(Request $request)
    {
        $upazilaId = $request->input('upazila_id');

        if (! $upazilaId) {
            return response()->json([]);
        }

        $zones = Zone::where('upazila_id', $upazilaId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($zones);
    }

    /**
     * Fetch unions by upazila
     */
    public function getUnion(Request $request)
    {
        $upazila_id = $request->upazila_id;
        $unions     = Union::where('upazila_id', $upazila_id)->get();

        return response()->json($unions);
    }

    /**
     * Fetch political parties for a parliament seat
     */
    public function getSeatParties(Request $request)
    {
        $request->validate([
            'parliament_seat_id' => 'required|integer',
        ]);

        $partyIds = SeatPartyCandidate::where('parliament_seat_id', $request->parliament_seat_id)->pluck('political_party_id')->unique();

        $parties = PoliticalParty::whereIn('id', $partyIds)->select('id', 'name')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'parties' => $parties,
        ]);
    }

    /**
     * Fetch candidate name by seat & party
     */
    public function getSeatPartyCandidate(Request $request)
    {
        $request->validate([
            'parliament_seat_id' => 'required|integer',
            'political_party_id' => 'required|integer',
        ]);

        $candidate = SeatPartyCandidate::where('parliament_seat_id', $request->parliament_seat_id)->where('political_party_id', $request->political_party_id)->first();

        return response()->json([
            'success'        => true,
            'candidate_name' => $candidate?->candidate_name,
        ]);
    }
}
