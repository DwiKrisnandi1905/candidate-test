<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    // ================= PROJECT PAGE =================

    // index page project
    public function index()
    {
        return view('project.index');
    }

    // fetch data project to datatable
    public function data()
    {
        $projects = Project::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return DataTables::of($projects)
            ->addColumn('user', fn($row) => $row->user->name ?? '-')
            ->addColumn('created_at', fn($row) => $row->created_at->format('d-m-Y H:i'))
            ->make(true);
    }

    // save data from input in modal add to database table projects
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_description' => 'nullable|string',
        ]);

        try {
            $project = Project::create([
                'id' => Str::uuid(),
                'user_id' => Auth::id(),
                'project_name' => $request->project_name,
                'project_description' => $request->project_description,
            ]);

            return response()->json(['success' => true, 'message' => 'Project created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // detail page project
    public function show($id)
    {
        $project = Project::with('details')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('project.detail', compact('project'));
    }

    // update project data
    public function update(Request $request, $id)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_description' => 'nullable|string',
        ]);

        $project = Project::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $project->update([
            'project_name' => $request->project_name,
            'project_description' => $request->project_description,
        ]);

        return redirect()->route('project.show', $id)->with('success', 'Project updated successfully.');
    }

    // delete project data
    public function delete($id)
    {
        $project = Project::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Hapus semua detail terlebih dahulu
        ProjectDetail::where('project_id', $project->id)->delete();

        // Hapus project utama
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully.']);
    }

    // ================= PROJECT DETAIL PAGE =================

    // fetch data detail project
    public function projectDetailsData(Request $request)
    {
        $details = ProjectDetail::where('project_id', $request->project_id)->get();

        return DataTables::of($details)->make(true);
    }

    // save data from input in modal add to database table project_details
    public function storeDetail(Request $request, $id)
    {
        $request->validate([
            'name_building' => 'required|string|max:255',
            'building_part_type' => 'required|in:floor,wall,beam,column',
            'material' => 'required|in:clt,glt',
            'supplier' => 'required|string|max:255',
        ]);

        $validCombinations = [
            'floor' => ['clt'],
            'wall' => ['clt'],
            'beam' => ['clt', 'glt'],
            'column' => ['glt'],
        ];

        $type = $request->building_part_type;
        $material = $request->material;

        if (!in_array($material, $validCombinations[$type])) {
            return redirect()->back()->withErrors([
                'material' => 'Material tidak valid untuk bagian bangunan ' . $type
            ])->withInput();
        }

        ProjectDetail::create([
            'id' => Str::uuid(),
            'project_id' => $id,
            'name_building' => $request->name_building,
            'building_part_type' => $type,
            'material' => $material,
            'supplier' => $request->supplier,
        ]);

        return redirect()->route('project.show', $id)->with('success', 'Detail proyek berhasil ditambahkan.');
    }

    // edit data detail
    public function editDetail($id)
    {
        $detail = ProjectDetail::findOrFail($id);
        return response()->json($detail);
    }

    // update data detail
    public function updateDetail(Request $request, $id)
    {
        $request->validate([
            'name_building' => 'required|string|max:255',
            'building_part_type' => 'required|in:floor,wall,beam,column',
            'material' => 'required|in:clt,glt',
            'supplier' => 'required|string|max:255',
        ]);

        $validCombinations = [
            'floor' => ['clt'],
            'wall' => ['clt'],
            'beam' => ['clt', 'glt'],
            'column' => ['glt'],
        ];

        $type = $request->building_part_type;
        $material = $request->material;

        if (!in_array($material, $validCombinations[$type])) {
            return response()->json(['message' => 'Invalid material for this part type'], 422);
        }

        $detail = ProjectDetail::findOrFail($id);
        $detail->update([
            'name_building' => $request->name_building,
            'building_part_type' => $type,
            'material' => $material,
            'supplier' => $request->supplier,
        ]);

        return response()->json(['message' => 'Detail updated']);
    }

    //delete data detail
    public function deleteDetail($id)
    {
        $detail = ProjectDetail::findOrFail($id);
        $detail->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
