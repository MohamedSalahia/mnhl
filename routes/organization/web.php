<?php

use App\Http\Controllers\Organization\AdminController;
use App\Http\Controllers\Organization\AssetController;
use App\Http\Controllers\Organization\AssessmentController;
use App\Http\Controllers\Organization\AssessmentSchemeController;
use App\Http\Controllers\Organization\AssessmentSchemeDeductionController;
use App\Http\Controllers\Organization\BranchController;
use App\Http\Controllers\Organization\ClassroomController;
use App\Http\Controllers\Organization\CountryController;
use App\Http\Controllers\Organization\CurrencyController;
use App\Http\Controllers\Organization\CurriculumController;
use App\Http\Controllers\Organization\EvaluationItemController;
use App\Http\Controllers\Organization\EvaluationModelController;
use App\Http\Controllers\Organization\GovernorateController;
use App\Http\Controllers\Organization\HomeController;
use App\Http\Controllers\Organization\InstallmentController;
use App\Http\Controllers\Organization\FinancialTransactionController;
use App\Http\Controllers\Organization\FundController;
use App\Http\Controllers\Organization\TeacherSalaryController;
use App\Http\Controllers\Organization\LessonController;
use App\Http\Controllers\Organization\LevelController;
use App\Http\Controllers\Organization\PaymentMethodController;
use App\Http\Controllers\Organization\Profile\PasswordController;
use App\Http\Controllers\Organization\ProfileController;
use App\Http\Controllers\Organization\ProjectController;
use App\Http\Controllers\Organization\RoleController;
use App\Http\Controllers\Organization\SettingController;
use App\Http\Controllers\Organization\StudentController;
use App\Http\Controllers\Organization\SubscriptionTypeController;
use App\Http\Controllers\Organization\TeacherController;
use App\Http\Controllers\Organization\ExaminerController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'redirect_to_dashboard',
    'role:organization_admin|organization_super_admin',
    'localization',
    'set_selected_organization',
    'redirect_to_dashboard'
])->group(function () {

    Route::name('organization.')->prefix('organization')->group(function () {

        //home
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        //role routes
        Route::get('/roles/data', [RoleController::class, 'data'])->name('roles.data');
        Route::delete('/roles/bulk_delete', [RoleController::class, 'bulkDelete'])->name('roles.bulk_delete');
        Route::resource('roles', RoleController::class);

        //admin routes
        Route::get('/admin/toggle_dark_mode', [AdminController::class, 'toggleDarkMode'])->name('admins.toggle_dark_mode');
        Route::get('/admin/toggle_menu_collapsed', [AdminController::class, 'toggleMenuCollapsed'])->name('admins.toggle_menu_collapsed');
        Route::get('/admins/switch_language', [AdminController::class, 'switchLanguage'])->name('admins.switch_language');
        Route::get('/admins/switch_organization/{organization}', [AdminController::class, 'switchOrganization'])->name('admins.switch_organization');
        Route::get('/admins/switch_branch/{branch}', [AdminController::class, 'switchBranch'])->name('admins.switch_branch');
        Route::get('/admins/leave_impersonate', [AdminController::class, 'leaveImpersonate'])->name('admins.leave_impersonate');
        Route::get('/admins/data', [AdminController::class, 'data'])->name('admins.data');
        Route::delete('/admins/bulk_delete', [AdminController::class, 'bulkDelete'])->name('admins.bulk_delete');
        Route::resource('admins', AdminController::class);

        //branch routes
        Route::get('/branches/data', [BranchController::class, 'data'])->name('branches.data');
        Route::delete('/branches/bulk_delete', [BranchController::class, 'bulkDelete'])->name('branches.bulk_delete');
        Route::resource('branches', BranchController::class);

        //currency routes
        Route::get('/currencies/data', [CurrencyController::class, 'data'])->name('currencies.data');
        Route::resource('currencies', CurrencyController::class)->except(['show']);

        //payment method routes
        Route::get('/payment_methods/data', [PaymentMethodController::class, 'data'])->name('payment_methods.data');
        Route::resource('payment_methods', PaymentMethodController::class)->except(['show']);

        //installment routes
        Route::get('/installments/data', [InstallmentController::class, 'data'])->name('installments.data');
        Route::resource('installments', InstallmentController::class);

        //teacher salaries routes
        Route::get('/teacher_salaries/data', [TeacherSalaryController::class, 'data'])->name('teacher_salaries.data');
        Route::resource('teacher_salaries', TeacherSalaryController::class);

        //financial transactions routes
        Route::get('/financial_transactions/data', [FinancialTransactionController::class, 'data'])->name('financial_transactions.data');
        Route::resource('financial_transactions', FinancialTransactionController::class);

        //funds routes
        Route::resource('funds', FundController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

        //subscription type routes
        Route::get('/subscription_types/data', [SubscriptionTypeController::class, 'data'])->name('subscription_types.data');
        Route::resource('subscription_types', SubscriptionTypeController::class)->except(['show']);

        //student routes
        Route::get('/students/data', [StudentController::class, 'data'])->name('students.data');
        Route::delete('/students/bulk_delete', [StudentController::class, 'bulkDelete'])->name('students.bulk_delete');
        Route::get('/students/{student}/details', [StudentController::class, 'details'])->name('students.details');
        Route::get('/students/{student}/lessons', [StudentController::class, 'lessons'])->name('students.lessons');
        Route::get('/students/{student}/installments', [StudentController::class, 'installments'])->name('students.installments');
        Route::get('/students/{student}/branch_enrollment/confirm_accept', [StudentController::class, 'confirmAcceptEnrollment'])->name('students.branch_enrollment.confirm_accept');
        Route::get('/students/{student}/branch_enrollment/confirm_reject', [StudentController::class, 'confirmRejectEnrollment'])->name('students.branch_enrollment.confirm_reject');
        Route::post('/students/{student}/accept_enrollment', [StudentController::class, 'acceptBranchEnrollment'])->name('students.branch_enrollment.accept');
        Route::post('/students/{student}/reject_enrollment', [StudentController::class, 'rejectBranchEnrollment'])->name('students.branch_enrollment.reject');
        Route::put('/students/{student}/branch-students/{branchStudent}/status', [StudentController::class, 'toggleBranchStudentStatus'])->name('students.toggle_branch_student_status');
        Route::get('/students/{student}/branch_enrollment/edit', [StudentController::class, 'editBranchEnrollment'])->name('students.branch_enrollment.edit');
        Route::put('/students/{student}/branch_enrollment', [StudentController::class, 'updateBranchEnrollment'])->name('students.branch_enrollment.update');
        Route::get('/students/{student}/extra_project_enrollment', [StudentController::class, 'createExtraProjectEnrollment'])->name('students.create_extra_project_enrollment');
        Route::post('/students/{student}/extra_project_enrollment', [StudentController::class, 'storeExtraProjectEnrollment'])->name('students.store_extra_project_enrollment');
        Route::resource('students', StudentController::class);

        //teacher routes
        Route::get('/teachers/data', [TeacherController::class, 'data'])->name('teachers.data');
        Route::delete('/teachers/bulk_delete', [TeacherController::class, 'bulkDelete'])->name('teachers.bulk_delete');
        Route::get('/teachers/{teacher}/details', [TeacherController::class, 'details'])->name('teachers.details');
        Route::get('/teachers/{teacher}/lessons', [TeacherController::class, 'lessons'])->name('teachers.lessons');
        Route::get('/teachers/{teacher}/salaries', [TeacherController::class, 'salaries'])->name('teachers.salaries');
        Route::get('/teachers/{teacher}/salary_settings/edit', [TeacherController::class, 'editSalarySettings'])->name('teachers.salary_settings.edit');
        Route::put('/teachers/{teacher}/salary_settings', [TeacherController::class, 'updateSalarySettings'])->name('teachers.salary_settings.update');
        Route::get('/teachers/{teacher}/confirm_accept_enrollment', [TeacherController::class, 'confirmAcceptEnrollment'])->name('teachers.confirm_accept_enrollment');
        Route::get('/teachers/{teacher}/confirm_reject_enrollment', [TeacherController::class, 'confirmRejectEnrollment'])->name('teachers.confirm_reject_enrollment');
        Route::post('/teachers/{teacher}/accept_enrollment', [TeacherController::class, 'acceptEnrollment'])->name('teachers.accept_enrollment');
        Route::post('/teachers/{teacher}/reject_enrollment', [TeacherController::class, 'rejectEnrollment'])->name('teachers.reject_enrollment');
        Route::post('/teachers/{teacher}/toggle_examiner', [TeacherController::class, 'toggleExaminer'])->name('teachers.toggle_examiner');
        Route::post('/teachers/{teacher}/impersonate', [TeacherController::class, 'impersonate'])->name('teachers.impersonate');
        Route::resource('teachers', TeacherController::class);

        //examiner routes
        Route::get('/examiners/data', [ExaminerController::class, 'data'])->name('examiners.data');
        Route::delete('/examiners/bulk_delete', [ExaminerController::class, 'bulkDelete'])->name('examiners.bulk_delete');
        Route::resource('examiners', ExaminerController::class);

        //lesson routes
        Route::get('/lessons/{lesson}/download_report', [LessonController::class, 'downloadReport'])->name('lessons.download_report');
        Route::get('/lessons/data', [LessonController::class, 'data'])->name('lessons.data');
        Route::resource('lessons', LessonController::class);


        //evaluation model routes
        Route::get('/evaluation_models/data', [EvaluationModelController::class, 'data'])->name('evaluation_models.data');
        Route::delete('/evaluation_models/bulk_delete', [EvaluationModelController::class, 'bulkDelete'])->name('evaluation_models.bulk_delete');
        Route::get('/evaluation_models/basic_information/create', [EvaluationModelController::class, 'createBasicInformation'])->name('evaluation_models.basic_information.create');
        Route::post('/evaluation_models/basic_information', [EvaluationModelController::class, 'storeBasicInformation'])->name('evaluation_models.basic_information.store');
        Route::get('/evaluation_models/{evaluation_model}/basic_information/edit', [EvaluationModelController::class, 'editBasicInformation'])->name('evaluation_models.basic_information.edit');
        Route::put('/evaluation_models/{evaluation_model}/basic_information', [EvaluationModelController::class, 'updateBasicInformation'])->name('evaluation_models.basic_information.update');
        Route::get('/evaluation_models/{evaluation_model}/evaluation_item_information/edit', [EvaluationModelController::class, 'editEvaluationItemInformation'])->name('evaluation_models.evaluation_item_information.edit');
        Route::resource('evaluation_models', EvaluationModelController::class)->only(['index', 'show', 'destroy']);

        //evaluation item routes
        Route::get('/evaluation_items/reorder', [EvaluationItemController::class, 'getReorder'])->name('evaluation_items.reorder');
        Route::post('/evaluation_items/reorder', [EvaluationItemController::class, 'postReorder'])->name('evaluation_items.reorder.store');
        Route::get('/evaluation_items/data', [EvaluationItemController::class, 'data'])->name('evaluation_items.data');
        Route::delete('/evaluation_items/bulk_delete', [EvaluationItemController::class, 'bulkDelete'])->name('evaluation_items.bulk_delete');
        Route::resource('evaluation_items', EvaluationItemController::class);

        //assessment scheme routes
        Route::get('/assessment_schemes/data', [AssessmentSchemeController::class, 'data'])->name('assessment_schemes.data');
        Route::delete('/assessment_schemes/bulk_delete', [AssessmentSchemeController::class, 'bulkDelete'])->name('assessment_schemes.bulk_delete');
        Route::get('/assessment_schemes/basic_information/create', [AssessmentSchemeController::class, 'createBasicInformation'])->name('assessment_schemes.basic_information.create');
        Route::post('/assessment_schemes/basic_information', [AssessmentSchemeController::class, 'storeBasicInformation'])->name('assessment_schemes.basic_information.store');
        Route::get('/assessment_schemes/{assessment_scheme}/basic_information/edit', [AssessmentSchemeController::class, 'editBasicInformation'])->name('assessment_schemes.basic_information.edit');
        Route::put('/assessment_schemes/{assessment_scheme}/basic_information', [AssessmentSchemeController::class, 'updateBasicInformation'])->name('assessment_schemes.basic_information.update');
        Route::get('/assessment_schemes/{assessment_scheme}/deduction_information/edit', [AssessmentSchemeController::class, 'editDeductionInformation'])->name('assessment_schemes.deduction_information.edit');
        Route::resource('assessment_schemes', AssessmentSchemeController::class)->only(['index', 'show', 'destroy']);

        //assessment scheme deduction routes
        Route::get('/assessment_scheme_deductions/reorder', [AssessmentSchemeDeductionController::class, 'getReorder'])->name('assessment_scheme_deductions.reorder');
        Route::post('/assessment_scheme_deductions/reorder', [AssessmentSchemeDeductionController::class, 'postReorder'])->name('assessment_scheme_deductions.reorder.store');
        Route::get('/assessment_scheme_deductions/data', [AssessmentSchemeDeductionController::class, 'data'])->name('assessment_scheme_deductions.data');
        Route::delete('/assessment_scheme_deductions/bulk_delete', [AssessmentSchemeDeductionController::class, 'bulkDelete'])->name('assessment_scheme_deductions.bulk_delete');
        Route::resource('assessment_scheme_deductions', AssessmentSchemeDeductionController::class);

        //assessment routes
        Route::get('/assessments/data', [AssessmentController::class, 'data'])->name('assessments.data');
        Route::get('/assessments/{assessment}/examiner', [AssessmentController::class, 'editExaminer'])->name('assessments.examiner.edit');
        Route::put('/assessments/{assessment}/examiner', [AssessmentController::class, 'updateExaminer'])->name('assessments.examiner.update');
        Route::get('/assessments/{assessment}/start', [AssessmentController::class, 'start'])->name('assessments.start');
        Route::get('/assessments/{assessment}/resume', [AssessmentController::class, 'resume'])->name('assessments.resume');
        Route::post('/assessments/{assessment}/deductions', [AssessmentController::class, 'storeDeductions'])->name('assessments.deductions.store');
        Route::resource('assessments', AssessmentController::class)->only(['index', 'show']);

        //project routes
        Route::get('/projects/{project}/levels', [ProjectController::class, 'levels'])->name('projects.levels');
        Route::get('/projects/data', [ProjectController::class, 'data'])->name('projects.data');
        Route::delete('/projects/bulk_delete', [ProjectController::class, 'bulkDelete'])->name('projects.bulk_delete');
        Route::get('/projects/basic_information/create', [ProjectController::class, 'createBasicInformation'])->name('projects.basic_information.create');
        Route::post('/projects/basic_information', [ProjectController::class, 'storeBasicInformation'])->name('projects.basic_information.store');
        Route::get('/projects/{project}/basic_information/edit', [ProjectController::class, 'editBasicInformation'])->name('projects.basic_information.edit');
        Route::put('/projects/{project}/basic_information', [ProjectController::class, 'updateBasicInformation'])->name('projects.basic_information.update');
        Route::get('/projects/{project}/level_information/edit', [ProjectController::class, 'editLevelInformation'])->name('projects.level_information.edit');
        Route::get('/projects/reorder', [ProjectController::class, 'getReorder'])->name('projects.reorder');
        Route::post('/projects/reorder', [ProjectController::class, 'postReorder'])->name('projects.reorder.store');
        Route::resource('projects', ProjectController::class)->only(['index', 'show', 'destroy']);

        //level routes
        Route::get('/levels/reorder', [LevelController::class, 'getReorder'])->name('levels.reorder');
        Route::post('/levels/reorder', [LevelController::class, 'postReorder'])->name('levels.reorder.store');
        Route::get('/levels/data', [LevelController::class, 'data'])->name('levels.data');
        Route::delete('/levels/bulk_delete', [LevelController::class, 'bulkDelete'])->name('levels.bulk_delete');
        Route::resource('levels', LevelController::class);

        //curriculum routes
        Route::get('/curricula/{curriculum}/projects', [CurriculumController::class, 'projects'])->name('curricula.projects');
        Route::get('/curricula/data', [CurriculumController::class, 'data'])->name('curricula.data');
        Route::delete('/curricula/bulk_delete', [CurriculumController::class, 'bulkDelete'])->name('curricula.bulk_delete');
        Route::resource('curricula', CurriculumController::class);

        //classroom routes
        Route::get('/classrooms/{classroom}/details', [ClassroomController::class, 'details'])->name('classrooms.details');
        Route::get('/classrooms/{classroom}/students', [ClassroomController::class, 'students'])->name('classrooms.students');
        Route::get('/classrooms/{classroom}/lessons', [ClassroomController::class, 'lessons'])->name('classrooms.lessons');
        Route::get('/classrooms/data', [ClassroomController::class, 'data'])->name('classrooms.data');
        Route::delete('/classrooms/bulk_delete', [ClassroomController::class, 'bulkDelete'])->name('classrooms.bulk_delete');
        Route::resource('classrooms', ClassroomController::class);

        //country routes
        Route::get('/countries/{country}/governorates', [CountryController::class, 'governorates'])->name('countries.governorates');

        //governorate routes
        Route::get('/governorates/{governorate}/areas', [GovernorateController::class, 'areas'])->name('governorates.areas');

        //asset routes
        Route::post('assets/reorder', [AssetController::class, 'reorder'])->name('assets.reorder');
        Route::resource('assets', AssetController::class)->only(['store', 'edit', 'update', 'destroy']);

        //setting routes
        Route::get('/settings/student_registration', [SettingController::class, 'createStudentRegistration'])->name('settings.student_registration');
        Route::post('/settings/student_registration', [SettingController::class, 'storeStudentRegistration'])->name('settings.student_registration.store');
        Route::get('/settings/teacher_registration', [SettingController::class, 'createTeacherRegistration'])->name('settings.teacher_registration');
        Route::post('/settings/teacher_registration', [SettingController::class, 'storeTeacherRegistration'])->name('settings.teacher_registration.store');

        //profile routes
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/switch_language', [ProfileController::class, 'switchLanguage'])->name('profile.switch_language');

        Route::name('profile.')->group(function () {

            //password routes
            Route::get('/password/edit', [PasswordController::class, 'edit'])->name('password.edit');
            Route::put('/password/update', [PasswordController::class, 'update'])->name('password.update');

        });

    });

});
