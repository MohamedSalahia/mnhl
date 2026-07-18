<?php

namespace App\Http\Controllers\Organization;

use App\Enums\CurriculumTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\LevelRequest;
use App\Models\AssessmentScheme;
use App\Models\Curriculum;
use App\Models\Level;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class LevelController extends Controller
{

    public function index()
    {
        $projects = Project::query()
            ->where('organization_id', session('selected_organization')['id'])
            ->get();

        return view('organization.levels.index', compact('projects'));

    }// end of index

    public function data()
    {
        $levels = Level::query()
            ->with(['project', 'assessmentScheme'])
            ->whenOrganizationId(session('selected_organization')['id'])
            ->whenProjectId(request()->project_id)
            ->orderBy('order');


        return DataTables::of($levels)
            ->addColumn('record_select', 'organization.levels.data_table.record_select')
            ->addColumn('project', function (Level $level) {
                return $level->project->name;
            })
            ->addColumn('assessment_scheme', function (Level $level) {
                return $level->assessmentScheme ? $level->assessmentScheme->name : '-';
            })
            ->editColumn('created_at', function (Level $level) {
                return $level->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.levels.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $organizationId = session('selected_organization')['id'];

        $projects = Project::query()
            ->where('organization_id', $organizationId)
            ->get();

        $project = request()->project_id
            ? Project::FindOrFail(request()->project_id)
            : null;

        $assessmentSchemes = AssessmentScheme::query()
            ->whenOrganizationId($organizationId)
            ->get();

        $additionalCurricula = Curriculum::query()
            ->whenOrganizationId($organizationId)
            ->whenType(CurriculumTypeEnum::ADDITIONAL)
            ->get();

        return response()->json([
            'view' => view('organization.levels._create', compact('projects', 'project', 'assessmentSchemes', 'additionalCurricula'))->render(),
        ]);

    }// end of create

    public function store(LevelRequest $request)
    {
        $level = Level::create($request->validated());

        // Attach additional curricula
        if ($request->has('additional_curricula') && is_array($request->additional_curricula)) {
            $curriculaData = [];
            foreach ($request->additional_curricula as $curriculum) {
                if (!empty($curriculum['curriculum_id'])) {
                    $curriculaData[$curriculum['curriculum_id']] = [
                        'from_page' => $curriculum['from_page'],
                        'to_page' => $curriculum['to_page'],
                    ];
                }
            }
            $level->attachedCurricula()->sync($curriculaData);
        }

        return response()->json([
            'success_message' => __('site.added_successfully'),
        ]);

    }// end of store

    public function getReorder()
    {
        $levels = Level::query()
            ->whenOrganizationId(session('selected_organization')['id'])
            ->whenProjectId(request()->project_id)
            ->orderBy('order')
            ->get();

        return response()->json([
            'view' => view('organization.levels._reorder', compact('levels'))->render(),
        ]);

    }// end of getReorder

    public function postReorder(Request $request)
    {
        foreach ($request->ids as $index => $id) {

            Level::findOrFail($id)
                ->update(['order' => $index + 1]);

        }//end of for each

        return response()->json(['success_message' => __('site.updated_successfully')]);

    }// end of postReorder

    public function edit(Level $level)
    {
        Gate::authorize('organization-level', $level);

        $level->load(['attachedCurricula', 'assessmentScheme']);

        $organizationId = session('selected_organization')['id'];

        $projects = Project::query()
            ->where('organization_id', $organizationId)
            ->get();

        $assessmentSchemes = AssessmentScheme::query()
            ->whenOrganizationId($organizationId)
            ->get();

        $additionalCurricula = Curriculum::query()
            ->whenOrganizationId($organizationId)
            ->whenType(CurriculumTypeEnum::ADDITIONAL)
            ->get();

        return response()->json([
            'view' => view('organization.levels._edit', compact('level', 'projects', 'assessmentSchemes', 'additionalCurricula'))->render(),
        ]);

    }// end of edit

    public function update(LevelRequest $request, Level $level)
    {
        Gate::authorize('organization-level', $level);

        $level->update($request->validated());

        if ($request->has('additional_curricula') && is_array($request->additional_curricula)) {

            $curriculaData = [];

            foreach ($request->additional_curricula as $curriculum) {

                if (!empty($curriculum['curriculum_id'])) {
                    $curriculaData[$curriculum['curriculum_id']] = [
                        'from_page' => $curriculum['from_page'],
                        'to_page' => $curriculum['to_page'],
                    ];
                }
            }

            $level->attachedCurricula()->sync($curriculaData);

        } else {

            $level->attachedCurricula()->sync([]);

        }

        if (request()->redirect_to) {

            session()->flash('success', __('site.updated_successfully'));

            return response()->json([
                'redirect_to' => request()->redirect_to,
            ]);

        }

        return response()->json([
            'success_message' => __('site.updated_successfully'),
        ]);

    }// end of update

    public function destroy(Level $level)
    {
        Gate::authorize('organization-level', $level);

        $this->delete($level);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        $organizationId = session('selected_organization')['id'];

        foreach (json_decode(request()->record_ids) as $recordId) {

            $level = Level::FindOrFail($recordId);

            abort_if($level->project->organization_id !== $organizationId, 404);

            $this->delete($level);

        }//end of for each

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(Level $level)
    {
        $level->delete();

    }// end of delete

}//end of controller

