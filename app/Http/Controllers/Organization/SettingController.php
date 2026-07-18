<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\StudentRegistrationSettingsRequest;
use App\Http\Requests\Organization\TeacherRegistrationSettingsRequest;
use App\Models\Organization;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SettingController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_settings'),
        ];
    }

    public function createStudentRegistration()
    {
        $selectedOrganization = session('selected_organization');
        $organization = Organization::findOrFail($selectedOrganization['id']);

        return view('organization.settings.student_registration', compact('organization'));

    }// end of createStudentRegistration

    public function storeStudentRegistration(StudentRegistrationSettingsRequest $request)
    {
        $selectedOrganization = session('selected_organization');
        $organization = Organization::findOrFail($selectedOrganization['id']);

        $settings = [];
        $fields = [
            'name', 'email', 'gender', 'mobile', 'date_of_birth', 'birth_place',
            'father_name', 'mother_name', 'father_occupation', 'mother_occupation',
            'father_mobile', 'mother_mobile', 'image', 'identity_document_file',
            'current_address', 'has_previous_education', 'previous_education_details'
        ];

        foreach ($fields as $field) {
            $settings[$field] = $request->has($field) && $request->input($field) == '1';
        }

        $organization->update([
            'student_registration_settings' => $settings
        ]);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.students.index'),
        ]);

    }// end of storeStudentRegistration

    public function createTeacherRegistration()
    {
        $selectedOrganization = session('selected_organization');
        $organization = Organization::findOrFail($selectedOrganization['id']);

        return view('organization.settings.teacher_registration', compact('organization'));

    }// end of createTeacherRegistration

    public function storeTeacherRegistration(TeacherRegistrationSettingsRequest $request)
    {
        $selectedOrganization = session('selected_organization');
        $organization = Organization::findOrFail($selectedOrganization['id']);

        $settings = [];
        $fields = [
            'name', 'email', 'gender', 'mobile', 'date_of_birth', 'birth_place',
            'marital_status', 'nationality_id', 'image', 'identity_document_file',
            'current_address', 'last_educational_certificate'
        ];

        foreach ($fields as $field) {
            $settings[$field] = $request->has($field) && $request->input($field) == '1';
        }

        $organization->update([
            'teacher_registration_settings' => $settings
        ]);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.teachers.index'),
        ]);

    }// end of storeTeacherRegistration

}//end of controller

