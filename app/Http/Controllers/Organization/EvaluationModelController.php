<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\EvaluationModelBasicInformationRequest;
use App\Models\EvaluationModel;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class EvaluationModelController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_evaluation_models', only: ['index', 'data', 'show']),
            new Middleware('permission_with_team:create_evaluation_models', only: ['create', 'store']),
            new Middleware('permission_with_team:update_evaluation_models', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_evaluation_models', only: ['destroy', 'bulkDelete']),
        ];

    }// end of middleware

    public function index()
    {
        return view('organization.evaluation_models.index');

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'];

        $evaluationModels = EvaluationModel::query()
            ->withCount('evaluationItems')
            ->whenOrganizationId($organizationId);

        return DataTables::of($evaluationModels)
            ->addColumn('record_select', 'organization.evaluation_models.data_table.record_select')
            ->editColumn('created_at', function (EvaluationModel $evaluationModel) {
                return $evaluationModel->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.evaluation_models.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function createBasicInformation()
    {
        return view('organization.evaluation_models.basic_information.create');

    }// end of createBasicInformation

    public function storeBasicInformation(EvaluationModelBasicInformationRequest $request)
    {
        $evaluationModel = EvaluationModel::create($request->validated());

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.evaluation_models.evaluation_item_information.edit', $evaluationModel->id),
        ]);

    }// end of storeBasicInformation

    public function show(EvaluationModel $evaluationModel)
    {
        Gate::authorize('organization-evaluation-model', $evaluationModel);

        $evaluationModel->load('evaluationItems');

        return view('organization.evaluation_models.show', compact('evaluationModel'));

    }// end of show

    public function editBasicInformation(EvaluationModel $evaluationModel)
    {
        Gate::authorize('organization-evaluation-model', $evaluationModel);

        return view('organization.evaluation_models.basic_information.edit', compact('evaluationModel'));

    }// end of editBasicInformation

    public function updateBasicInformation(EvaluationModelBasicInformationRequest $request, EvaluationModel $evaluationModel)
    {
        Gate::authorize('organization-evaluation-model', $evaluationModel);

        $evaluationModel->update($request->validated());

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.evaluation_models.evaluation_item_information.edit', $evaluationModel->id),
        ]);

    }// end of updateBasicInformation

    public function editEvaluationItemInformation(EvaluationModel $evaluationModel)
    {
        Gate::authorize('organization-evaluation-model', $evaluationModel);

        return view('organization.evaluation_models.evaluation_item_information.edit', compact('evaluationModel'));

    }// end of editEvaluationItemInformation

    public function destroy(EvaluationModel $evaluationModel)
    {
        Gate::authorize('organization-evaluation-model', $evaluationModel);

        $this->delete($evaluationModel);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $evaluationModel = EvaluationModel::FindOrFail($recordId);

            Gate::authorize('organization-evaluation-model', $evaluationModel);

            $this->delete($evaluationModel);

        }//end of for each

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(EvaluationModel $evaluationModel)
    {
        $evaluationModel->delete();

    }// end of delete

}//end of controller

