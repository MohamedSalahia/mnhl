<?php

namespace App\Http\Controllers\Organization;

use App\Enums\CurriculumTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\ProjectBasicInformationRequest;
use App\Models\Curriculum;
use App\Models\EvaluationModel;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class ProjectController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_projects', only: ['index', 'data', 'show']),
            new Middleware('permission_with_team:create_projects', only: ['create', 'createBasicInformation', 'storeBasicInformation']),
            new Middleware('permission_with_team:update_projects', only: ['edit', 'editBasicInformation', 'updateBasicInformation']),
            new Middleware('permission_with_team:delete_projects', only: ['destroy', 'bulkDelete']),
        ];

    }// end of middleware

    public function index()
    {
        $organizationId = session('selected_organization')['id'];

        $evaluationModels = EvaluationModel::query()
            ->whenOrganizationId($organizationId)
            ->get();

        $curricula = Curriculum::query()
            ->whenOrganizationId($organizationId)
            ->whenType(CurriculumTypeEnum::MAIN)
            ->get();

        return view('organization.projects.index', compact('evaluationModels', 'curricula'));

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'];

        $projects = Project::query()
            ->with(['evaluationModel'])
            ->withCount('levels')
            ->whenOrganizationId($organizationId)
            ->whenEvaluationModelId(request()->evaluation_model_id)
            ->whenCurriculumId(request()->curriculum_id);

        return DataTables::of($projects)
            ->addColumn('record_select', 'organization.projects.data_table.record_select')
            ->editColumn('evaluation_model_id', function (Project $project) {
                return $project->evaluationModel->name ?? '';
            })
            ->editColumn('can_proceed_to_next_project', function (Project $project) {
                return $project->can_proceed_to_next_project ? __('site.yes') : __('site.no');
            })
            ->editColumn('created_at', function (Project $project) {
                return $project->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.projects.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $organizationId = session('selected_organization')['id'];

        $evaluationModels = EvaluationModel::query()
            ->whenOrganizationId($organizationId)
            ->get();

        $mainCurriculums = Curriculum::query()
            ->whenOrganizationId($organizationId)
            ->whenType(CurriculumTypeEnum::MAIN)
            ->get();

        return view('organization.projects.create', compact('evaluationModels', 'mainCurriculums'));

    }// end of create

    public function createBasicInformation()
    {
        $organizationId = session('selected_organization')['id'];

        $evaluationModels = EvaluationModel::query()
            ->whenOrganizationId($organizationId)
            ->get();

        $mainCurriculums = Curriculum::query()
            ->whenOrganizationId($organizationId)
            ->whenType(CurriculumTypeEnum::MAIN)
            ->get();

        return view('organization.projects.basic_information.create', compact('evaluationModels', 'mainCurriculums'));

    }// end of createBasicInformation

    public function storeBasicInformation(ProjectBasicInformationRequest $request)
    {
        $project = Project::create($request->validated());

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.projects.level_information.edit', $project->id),
        ]);

    }// end of storeBasicInformation

    public function show(Project $project)
    {
        Gate::authorize('organization-project', $project);

        $project->load(['levels', 'curriculum', 'evaluationModel']);

        return view('organization.projects.show', compact('project'));

    }// end of show

    public function edit(Project $project)
    {
        Gate::authorize('organization-project', $project);

        $organizationId = session('selected_organization')['id'];

        $evaluationModels = EvaluationModel::query()
            ->whenOrganizationId($organizationId)
            ->get();

        $mainCurriculums = Curriculum::query()
            ->whenOrganizationId($organizationId)
            ->whenType(CurriculumTypeEnum::MAIN)
            ->get();

        return view('organization.projects.edit', compact('project', 'evaluationModels', 'mainCurriculums'));

    }// end of edit

    public function editBasicInformation(Project $project)
    {
        Gate::authorize('organization-project', $project);

        $organizationId = session('selected_organization')['id'];

        $evaluationModels = EvaluationModel::query()
            ->whenOrganizationId($organizationId)
            ->get();

        $mainCurriculums = Curriculum::query()
            ->whenOrganizationId($organizationId)
            ->whenType(CurriculumTypeEnum::MAIN)
            ->get();

        return view('organization.projects.basic_information.edit', compact('project', 'evaluationModels', 'mainCurriculums'));

    }// end of editBasicInformation

    public function updateBasicInformation(ProjectBasicInformationRequest $request, Project $project)
    {
        Gate::authorize('organization-project', $project);

        $project->update($request->validated());

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.projects.level_information.edit', $project->id),
        ]);

    }// end of updateBasicInformation

    public function editLevelInformation(Project $project)
    {
        Gate::authorize('organization-project', $project);

        return view('organization.projects.level_information.edit', compact('project'));

    }// end of editLevelInformation

    public function levels(Project $project)
    {
        $levels = $project->levels()->orderBy('order')->get();

        return response()->json([
            'view' => view('organization.projects._levels', compact('levels'))->render(),
        ]);

    }// end of levels

    public function getReorder()
    {
        $projects = Project::query()
            ->whenOrganizationId(session('selected_organization')['id'])
            ->orderBy('order')
            ->get();

        return response()->json([
            'view' => view('organization.projects._reorder', compact('projects'))->render(),
        ]);

    }// end of getReorder

    public function postReorder(Request $request)
    {
        foreach ($request->ids as $index => $id) {

            Project::findOrFail($id)
                ->update(['order' => $index + 1]);

        }//end of for each

        return response()->json(['success_message' => __('site.updated_successfully')]);

    }// end of postReorder

    public function destroy(Project $project)
    {
        Gate::authorize('organization-project', $project);

        $this->delete($project);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $project = Project::FindOrFail($recordId);

            Gate::authorize('organization-project', $project);

            $this->delete($project);

        }//end of for each

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(Project $project)
    {
        $project->delete();

    }// end of delete

}//end of controller

