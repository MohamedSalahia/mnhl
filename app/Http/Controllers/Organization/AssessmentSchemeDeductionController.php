<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\AssessmentSchemeDeductionRequest;
use App\Models\AssessmentScheme;
use App\Models\AssessmentSchemeDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class AssessmentSchemeDeductionController extends Controller
{

    public function index()
    {
        $organizationId = session('selected_organization')['id'];

        $assessmentSchemes = AssessmentScheme::query()
            ->where('organization_id', $organizationId)
            ->get();

        return view('organization.assessment_scheme_deductions.index', compact('assessmentSchemes'));

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'];

        $deductions = AssessmentSchemeDeduction::query()
            ->with('assessmentScheme')
            ->whenOrganizationId($organizationId)
            ->whenAssessmentSchemeId(request()->assessment_scheme_id);


        return DataTables::of($deductions)
            ->addColumn('record_select', 'organization.assessment_scheme_deductions.data_table.record_select')
            ->addColumn('assessment_scheme', function (AssessmentSchemeDeduction $deduction) {
                return $deduction->assessmentScheme->name;
            })
            ->addColumn('color_preview', 'organization.assessment_scheme_deductions.data_table.color_preview')
            ->editColumn('created_at', function (AssessmentSchemeDeduction $deduction) {
                return $deduction->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.assessment_scheme_deductions.data_table.actions')
            ->rawColumns(['record_select', 'color_preview', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $organizationId = session('selected_organization')['id'];

        $assessmentSchemes = AssessmentScheme::query()
            ->where('organization_id', $organizationId)
            ->get();

        $assessmentScheme = request()->assessment_scheme_id
            ? AssessmentScheme::FindOrFail(request()->assessment_scheme_id)
            : null;

        return response()->json([
            'view' => view('organization.assessment_scheme_deductions._create', compact('assessmentSchemes', 'assessmentScheme'))->render(),
        ]);

    }// end of create

    public function store(AssessmentSchemeDeductionRequest $request)
    {
        AssessmentSchemeDeduction::create($request->validated());

        return response()->json([
            'success_message' => __('site.added_successfully'),
        ]);

    }// end of store

    public function getReorder()
    {
        $deductions = AssessmentSchemeDeduction::query()
            ->whenOrganizationId(session('selected_organization')['id'])
            ->whenAssessmentSchemeId(request()->assessment_scheme_id)
            ->orderBy('order')
            ->get();

        return response()->json([
            'view' => view('organization.assessment_scheme_deductions._reorder', compact('deductions'))->render(),
        ]);

    }// end of getReorder

    public function postReorder(Request $request)
    {
        foreach ($request->ids as $index => $id) {

            AssessmentSchemeDeduction::findOrFail($id)
                ->update(['order' => $index + 1]);

        }//end of for each

        return response()->json(['success_message' => __('site.updated_successfully')]);

    }// end of postReorder

    public function edit(AssessmentSchemeDeduction $assessmentSchemeDeduction)
    {
        Gate::authorize('organization-assessment-scheme-deduction', $assessmentSchemeDeduction);

        $organizationId = session('selected_organization')['id'];

        $assessmentSchemes = AssessmentScheme::query()
            ->where('organization_id', $organizationId)
            ->get();

        return response()->json([
            'view' => view('organization.assessment_scheme_deductions._edit', compact('assessmentSchemeDeduction', 'assessmentSchemes'))->render(),
        ]);

    }// end of edit

    public function update(AssessmentSchemeDeductionRequest $request, AssessmentSchemeDeduction $assessmentSchemeDeduction)
    {
        Gate::authorize('organization-assessment-scheme-deduction', $assessmentSchemeDeduction);

        $assessmentSchemeDeduction->update($request->validated());

        return response()->json([
            'success_message' => __('site.updated_successfully'),
        ]);

    }// end of update

    public function destroy(AssessmentSchemeDeduction $assessmentSchemeDeduction)
    {
        Gate::authorize('organization-assessment-scheme-deduction', $assessmentSchemeDeduction);

        $this->delete($assessmentSchemeDeduction);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        $organizationId = session('selected_organization')['id'];

        foreach (json_decode(request()->record_ids) as $recordId) {

            $deduction = AssessmentSchemeDeduction::FindOrFail($recordId);

            abort_if($deduction->assessmentScheme->organization_id !== $organizationId, 404);

            $this->delete($deduction);

        }//end of for each

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(AssessmentSchemeDeduction $assessmentSchemeDeduction)
    {
        $assessmentSchemeDeduction->delete();

    }// end of delete

}//end of controller
