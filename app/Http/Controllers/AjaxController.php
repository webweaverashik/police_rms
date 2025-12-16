<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administrative\Union;

class AjaxController extends Controller
{
    public function getUnion(Request $request)
    {
        $upazila_id = $request->upazila_id;
        $unions     = Union::where('upazila_id', $upazila_id)->get();

        return response()->json($unions);
    }
}
