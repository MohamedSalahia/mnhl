<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\CurriculumRequest;
use App\Models\Branch;
use App\Models\Curriculum;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class CurriculumController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_curricula', only: ['index', 'data']),
            new Middleware('permission_with_team:create_curricula', only: ['create', 'store']),
            new Middleware('permission_with_team:update_curricula', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_curricula', only: ['destroy', 'bulkDelete']),
        ];

    }// end of middleware

    public function index()
    {
        return view('organization.curricula.index');

    }// end of index

    public function data()
    {
        $curricula = Curriculum::query()
            ->withCount('projects')
            ->whenOrganizationId(session('selected_organization')['id'])
            ->whenBranchId(session('selected_branch')['id'])
            ->whenType(request()->curriculum_type);

        return DataTables::of($curricula)
            ->addColumn('record_select', 'organization.curricula.data_table.record_select')
            ->addColumn('book_name', function (Curriculum $curriculum) {
                return $curriculum->book_name ?? '-';
            })
            ->addColumn('book_number_of_pages', function (Curriculum $curriculum) {
                return $curriculum->book_number_of_pages ?? '-';
            })
            ->addColumn('curriculum_type', function (Curriculum $curriculum) {
                return view('organization.curricula.data_table.curriculum_type', compact('curriculum'));
            })
            ->editColumn('created_at', function (Curriculum $curriculum) {
                return $curriculum->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.curricula.data_table.actions')
            ->rawColumns(['record_select', 'curriculum_type', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $organizationId = session('selected_organization')['id'];

        $branches = Branch::query()
            ->where('organization_id', $organizationId)
            ->with(['translations'])
            ->get();

        $branch = request()->branch_id
            ? Branch::FindOrFail(request()->branch_id)
            : null;

        return view('organization.curricula.create', compact('branches', 'branch'));

    }// end of create

    public function store(CurriculumRequest $request)
    {
        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('book_file')) {
            $file = $request->file('book_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads', $fileName, 'public');
            $data['book_file'] = $fileName;
        }

        Curriculum::create($data);

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.curricula.index'),
        ]);

    }// end of store

    public function edit(Curriculum $curriculum)
    {
        Gate::authorize('organization-curriculum', $curriculum);

        $organizationId = session('selected_organization')['id'];

        $branches = Branch::query()
            ->where('organization_id', $organizationId)
            ->with(['translations'])
            ->get();

        return view('organization.curricula.edit', compact('curriculum', 'branches'));

    }// end of edit

    public function update(CurriculumRequest $request, Curriculum $curriculum)
    {
        Gate::authorize('organization-curriculum', $curriculum);

        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('book_file')) {
            // Delete old file if exists
            if ($curriculum->book_file && Storage::disk('public')->exists('uploads/' . $curriculum->book_file)) {
                Storage::disk('public')->delete('uploads/' . $curriculum->book_file);
            }

            $file = $request->file('book_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads', $fileName, 'public');
            $data['book_file'] = $fileName;
        } else {
            // Keep existing file if no new file uploaded
            unset($data['book_file']);
        }

        $curriculum->update($data);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.curricula.index'),
        ]);

    }// end of update

    public function projects(Curriculum $curriculum)
    {
        $projects = $curriculum->projects()
            ->get();

        return view('organization.curricula._projects', compact('curriculum', 'projects'));

    }// end of projects

    public function destroy(Curriculum $curriculum)
    {
        Gate::authorize('organization-curriculum', $curriculum);

        $this->delete($curriculum);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        $organizationId = session('selected_organization')['id'];

        foreach (json_decode(request()->record_ids) as $recordId) {

            $curriculum = Curriculum::FindOrFail($recordId);

            abort_if($curriculum->organization_id !== $organizationId, 404);

            $this->delete($curriculum);

        }//end of for each

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(Curriculum $curriculum)
    {
        // Delete file if exists
        if ($curriculum->book_file && Storage::disk('public')->exists('uploads/' . $curriculum->book_file)) {
            Storage::disk('public')->delete('uploads/' . $curriculum->book_file);
        }

        $curriculum->delete();

    }// end of delete

}//end of controller

