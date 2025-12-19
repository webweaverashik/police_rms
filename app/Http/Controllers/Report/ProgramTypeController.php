<?php
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Report\ProgramType;
use Illuminate\Http\Request;

class ProgramTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name'        => 'required|string|max:255|unique:program_types,name',
                'description' => 'nullable|string|max:1000',
            ],
            [
                'name.required' => 'প্রোগ্রামের ধরণের নাম প্রয়োজন',
                'name.unique'   => 'এই নামের প্রোগ্রামের ধরণ ইতিমধ্যে আছে',
                'name.max'      => 'নাম ২৫৫ অক্ষরের বেশি হতে পারবে না',
            ],
        );

        $programType = ProgramType::create([
            'name'        => $request->name,
            'description' => $request->description ?? null,
            'created_by'  => auth()->id(),
        ]);

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(
                [
                    'success'      => true,
                    'message'      => 'প্রোগ্রামের ধরণ সফলভাবে যোগ হয়েছে',
                    'program_type' => [
                        'id'   => $programType->id,
                        'name' => $programType->name,
                    ],
                ],
                201,
            );
        }

        // Normal redirect for non-AJAX requests
        return redirect()->route('program-types.index')->with('success', 'প্রোগ্রামের ধরণ সফলভাবে যোগ হয়েছে');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
