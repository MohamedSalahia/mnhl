<?php

namespace App\Http\Controllers;

use App\Enums\OrganizationTeacherStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Requests\TeacherRegistrationRequest;
use App\Models\Branch;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TeacherController extends Controller
{
    public function create(Request $request)
    {
        $organization = Organization::findByHashIdOrFail($request->organization_id);

        $branch = Branch::findByHashIdOrFail($request->branch_id);

        $settings = $organization->teacher_registration_settings ?? [];

        return view('teachers.create', compact('organization', 'branch', 'settings'));

    }// end of create

    public function store(TeacherRegistrationRequest $request)
    {
        $organization = Organization::findByHashIdOrFail($request->organization_id);

        $branch = Branch::findByHashIdOrFail($request->branch_id);

        $requestData = $request->validated();

        if ($request->file('image')) {
            $requestData['image'] = $request->file('image')->hashName();
            $request->file('image')->store('uploads', 'public');
        }

        if ($request->file('identity_document_file')) {
            $requestData['identity_document_file'] = $request->file('identity_document_file')->hashName();
            $request->file('identity_document_file')->store('uploads', 'public');
        }

        if (empty($requestData['email'])) {
            $requestData['email'] = 'auto_' . Str::random(16) . '@noreply.manhal';
        }

        $teacher = User::firstOrCreate(
            ['email' => $requestData['email']],
            $requestData
        );

        $existingRelation = $teacher->teacherOrganizations()
            ->where('organizations.id', $organization->id)
            ->exists();

        if ($existingRelation) {
            throw ValidationException::withMessages([
                'email' => __('teachers.teacher_already_exists')
            ]);
        }

        $teacher->teacherOrganizations()->sync([
            $organization->id => ['status' => OrganizationTeacherStatusEnum::PENDING]
        ]);

        // Attach teacher to branch
        $teacher->teacherBranches()->syncWithoutDetaching([
            $branch->id => []
        ]);

        $teacher->syncRoles([UserTypeEnum::TEACHER]);

        return response()->json([
            'success_message' => __('site.added_successfully'),
        ]);

    }// end of store

}//end of controller
