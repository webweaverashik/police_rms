<?php
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Report\ProgramType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgramTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $program_types = ProgramType::with(['createdBy:id,name,designation_id', 'createdBy.designation:id,name'])
            ->withCount('reports')
            ->oldest('name')
            ->get();

        return view('program_types.index', compact('program_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('program-types.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'type_name'        => 'required|string|max:50|unique:program_types,name',
                'type_description' => 'nullable|string|max:200',
            ],
            [
                'type_name.required' => 'ধরণের নাম প্রয়োজন',
                'type_name.unique'   => 'এই ধরণটি ইতিমধ্যে আছে',
                'type_name.max'      => 'নামটি ৫০ অক্ষরের বেশি হতে পারবে না',
            ],
        );

        $programType = ProgramType::create([
            'name'        => $request->type_name,
            'description' => $request->type_description ?? null,
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
        $program_type = ProgramType::find($id);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $program_type->id,
                'name'        => $program_type->name,
                'description' => $program_type->description,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('program-types.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'type_name_edit'        => ['required', 'string', 'max:50', Rule::unique('program_types', 'name')->ignore($id)],
                'type_description_edit' => 'nullable|string|max:200',
            ],
            [
                'type_name_edit.required' => 'ধরণের নাম প্রয়োজন',
                'type_name_edit.unique'   => 'এই ধরণটি ইতিমধ্যে আছে',
                'type_name_edit.max'      => 'নামটি ৫০ অক্ষরের বেশি হতে পারবে না',
            ],
        );

        $program_type = ProgramType::findOrFail($id);

        $program_type->update([
            'name'        => $request->type_name_edit,
            'description' => $request->type_description_edit,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'প্রোগ্রামের ধরণ সফলভাবে আপডেট করা হয়েছে',
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
