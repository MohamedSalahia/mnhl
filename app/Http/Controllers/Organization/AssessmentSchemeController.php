<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\AssessmentSchemeRequest;
use App\Models\AssessmentScheme;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class AssessmentSchemeController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_assessment_schemes', only: ['index', 'data', 'show']),
            new Middleware('permission_with_team:create_assessment_schemes', only: ['createBasicInformation', 'storeBasicInformation']),
            new Middleware('permission_with_team:update_assessment_schemes', only: ['editBasicInformation', 'updateBasicInformation']),
            new Middleware('permission_with_team:delete_assessment_schemes', only: ['destroy', 'bulkDelete']),
        ];

    }// end of middleware

    public function index()
    {
        return view('organization.assessment_schemes.index');

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'];

        $assessmentSchemes = AssessmentScheme::query()
            ->withCount('deductions')
            ->whenOrganizationId($organizationId);

        return DataTables::of($assessmentSchemes)
            ->addColumn('record_select', 'organization.assessment_schemes.data_table.record_select')
            ->editColumn('created_at', function (AssessmentScheme $assessmentScheme) {
                return $assessmentScheme->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.assessment_schemes.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function createBasicInformation()
    {
        return view('organization.assessment_schemes.basic_information.create');

    }// end of createBasicInformation

    public function storeBasicInformation(AssessmentSchemeRequest $request)
    {
        $assessmentScheme = AssessmentScheme::create($request->validated());

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.assessment_schemes.deduction_information.edit', $assessmentScheme->id),
        ]);

    }// end of storeBasicInformation

    public function show(AssessmentScheme $assessmentScheme)
    {
        Gate::authorize('organization-assessment-scheme', $assessmentScheme);

        $assessmentScheme->load('deductions');

        return view('organization.assessment_schemes.show', compact('assessmentScheme'));

    }// end of show

    public function editBasicInformation(AssessmentScheme $assessmentScheme)
    {
        Gate::authorize('organization-assessment-scheme', $assessmentScheme);

        return view('organization.assessment_schemes.basic_information.edit', compact('assessmentScheme'));

    }// end of editBasicInformation

    public function updateBasicInformation(AssessmentSchemeRequest $request, AssessmentScheme $assessmentScheme)
    {
        Gate::authorize('organization-assessment-scheme', $assessmentScheme);

        $assessmentScheme->update($request->validated());

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.assessment_schemes.deduction_information.edit', $assessmentScheme->id),
        ]);

    }// end of updateBasicInformation

    public function editDeductionInformation(AssessmentScheme $assessmentScheme)
    {
        Gate::authorize('organization-assessment-scheme', $assessmentScheme);

        return view('organization.assessment_schemes.deduction_information.edit', compact('assessmentScheme'));

    }// end of editDeductionInformation

    public function destroy(AssessmentScheme $assessmentScheme)
    {
        Gate::authorize('organization-assessment-scheme', $assessmentScheme);

        $this->delete($assessmentScheme);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $assessmentScheme = AssessmentScheme::FindOrFail($recordId);

            Gate::authorize('organization-assessment-scheme', $assessmentScheme);

            $this->delete($assessmentScheme);

        }//end of for each

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(AssessmentScheme $assessmentScheme)
    {
        $assessmentScheme->delete();

    }// end of delete

}//end of controller
