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
        // ✅ AJAX-friendly validation
        $validated = $request->validate(
            [
                'name' => 'required|string|max:50|unique:program_types,name',
            ],
            [
                'name.required' => 'ধরণের নাম প্রয়োজন',
                'name.unique'   => 'এই ধরণটি ইতিমধ্যে আছে',
                'name.max'      => 'নামটি ৫০ অক্ষরের বেশি হতে পারবে না',
            ],
        );

        // ✅ Create program type
        $programType = ProgramType::create([
            'name'        => $validated['name'],
            'description' => null,
            'created_by'  => auth()->id(),
        ]);

        // ✅ Always return JSON (because this endpoint is used via AJAX)
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
