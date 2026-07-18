<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\EvaluationItemRequest;
use App\Models\EvaluationItem;
use App\Models\EvaluationModel;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class EvaluationItemController extends Controller
{

    public function index()
    {
        $organizationId = session('selected_organization')['id'];

        $evaluationModels = EvaluationModel::query()
            ->where('organization_id', $organizationId)
            ->get();

        return view('organization.evaluation_items.index', compact('evaluationModels'));

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'];

        $evaluationItems = EvaluationItem::query()
            ->with('evaluationModel')
            ->whenOrganizationId($organizationId)
            ->whenEvaluationModelId(request()->evaluation_model_id);


        return DataTables::of($evaluationItems)
            ->addColumn('record_select', 'organization.evaluation_items.data_table.record_select')
            ->addColumn('evaluation_model', function (EvaluationItem $evaluationItem) {
                return $evaluationItem->evaluationModel->name;
            })
            ->addColumn('color_preview', 'organization.evaluation_items.data_table.color_preview')
            ->addColumn('pass', 'organization.evaluation_items.data_table.pass')
            ->editColumn('created_at', function (EvaluationItem $evaluationItem) {
                return $evaluationItem->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.evaluation_items.data_table.actions')
            ->rawColumns(['record_select', 'color_preview', 'pass', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $organizationId = session('selected_organization')['id'];

        $evaluationModels = EvaluationModel::query()
            ->where('organization_id', $organizationId)
            ->get();

        $evaluationModel = request()->evaluation_model_id
            ? EvaluationModel::FindOrFail(request()->evaluation_model_id)
            : null;

        return response()->json([
            'view' => view('organization.evaluation_items._create', compact('evaluationModels', 'evaluationModel'))->render(),
        ]);

    }// end of create

    public function store(EvaluationItemRequest $request)
    {
        EvaluationItem::create($request->validated());

        return response()->json([
            'success_message' => __('site.added_successfully'),
        ]);

    }// end of store

    public function getReorder()
    {
        $evaluationItems = EvaluationItem::query()
            ->whenOrganizationId(session('selected_organization')['id'])
            ->whenEvaluationModelId(request()->evaluation_model_id)
            ->orderBy('order')
            ->get();

        return response()->json([
            'view' => view('organization.evaluation_items._reorder', compact('evaluationItems'))->render(),
        ]);

    }// end of getReorder

    public function postReorder(Request $request)
    {
        foreach ($request->ids as $index => $id) {

            EvaluationItem::findOrFail($id)
                ->update(['order' => $index + 1]);

        }//end of for each

        return response()->json(['success_message' => __('site.updated_successfully')]);

    }// end of postReorder

    public function edit(EvaluationItem $evaluationItem)
    {
        Gate::authorize('organization-evaluation-item', $evaluationItem);

        $organizationId = session('selected_organization')['id'];

        $evaluationModels = EvaluationModel::query()
            ->where('organization_id', $organizationId)
            ->get();

        return response()->json([
            'view' => view('organization.evaluation_items._edit', compact('evaluationItem', 'evaluationModels'))->render(),
        ]);

    }// end of edit

    public function update(EvaluationItemRequest $request, EvaluationItem $evaluationItem)
    {
        Gate::authorize('organization-evaluation-item', $evaluationItem);

        $evaluationItem->update($request->validated());


        return response()->json([
            'success_message' => __('site.updated_successfully'),
        ]);

    }// end of update

    public function destroy(EvaluationItem $evaluationItem)
    {
        Gate::authorize('organization-evaluation-item', $evaluationItem);

        $this->delete($evaluationItem);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        $organizationId = session('selected_organization')['id'];

        foreach (json_decode(request()->record_ids) as $recordId) {

            $evaluationItem = EvaluationItem::FindOrFail($recordId);

            abort_if($evaluationItem->evaluationModel->organization_id !== $organizationId, 404);

            $this->delete($evaluationItem);

        }//end of for each

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(EvaluationItem $evaluationItem)
    {
        $evaluationItem->delete();

    }// end of delete

}//end of controller

