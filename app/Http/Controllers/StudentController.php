<?php

namespace App\Http\Controllers;

use App\Enums\OrganizationStudentStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Requests\StudentRegistrationRequest;
use App\Models\Branch;
use App\Models\BranchStudent;
use App\Models\Classroom;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Services\WhatsappService;


class StudentController extends Controller
{
    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }
    public function create(Request $request)
    {

        $organization = Organization::findOrFail(Organization::keyFromHashId($request->organization_id ?? null));

        $branch = Branch::findOrFail(Branch::keyFromHashId($request->branch_id ?? null));

        $settings = $organization->student_registration_settings ?? [];

        $classrooms = Classroom::query()
            ->whenBranchId($branch->id)
            ->get();

        return view('students.create', compact('organization', 'branch', 'settings', 'classrooms'));

    }// end of create

    public function store(StudentRegistrationRequest $request)
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

        $student = User::firstOrCreate(
            ['email' => $requestData['email']],
            $requestData
        );

        $existingRelation = $student->studentOrganizations()
            ->where('organizations.id', $organization->id)
            ->exists();

        if ($existingRelation) {
            throw ValidationException::withMessages([
                'email' => __('students.student_already_exists')
            ]);
        }

        $student->studentOrganizations()->sync([
            $organization->id => ['status' => OrganizationStudentStatusEnum::PENDING]
        ]);

        // Attach student to branch (one enrollment line; other branches unchanged)
        BranchStudent::query()
            ->whenStudentId($student->id)
            ->whenBranchId($branch->id)
            ->delete();

        BranchStudent::create([
            'student_id' => $student->id,
            'branch_id' => $branch->id,
            'classroom_id' => $request->classroom_id,
        ]);

        $student->syncRoles([UserTypeEnum::STUDENT]);

        //dd($student);

        if (!empty($student->mobile)) {


            // المتغيرات التي سيتم تعويضها داخل القالب (مثل [student_name] أو [organization_name])
            $variables = [
                'student_name'      => $student->name,
            ];

            // نقوم بالإرسال بناءً على نوع القالب المخصص للتسجيل
            $this->whatsappService->sendByType('student_registered', $student->mobile, $variables);

        }

        return response()->json([
            'success_message' => __('site.added_successfully'),
        ]);

    }// end of store

} //end of controller

