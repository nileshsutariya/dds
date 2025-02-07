<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function create()
    {
        return view('admin.area.area');
    }

    public function store(Request $request)
    {
        $request->validate([
            'area_name' => 'required',
            'code' => 'required',
        ]);

        $area = new Area();

        $area->area_name = $request->area_name;
        $area->code = $request->code;

        $area->save();

        return redirect()->route('area.index');
    }

    public function index(Request $request)
    {
        $areas = Area::paginate(100);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.area.area_table', compact('areas'))->render(),
                'pagination' => (string) $areas->links()
            ]);
        }
    
        return view('admin.area.area_table', compact('areas'));
    }

    public function edit(string $id)
    {
        $area = Area::findorfail($id);
        return view('admin.area.area' , compact('area'));
    }

    public function update(Request $request , string $id)
    {
        $request->validate([
            'area_name' => 'required',
            'code' => 'required',
        ]);

        $area = Area::findorfail($id);

        $area->area_name = $request->area_name;
        $area->code = $request->code;

        $area->save();

        return redirect()->route('area.index');
    }
}
