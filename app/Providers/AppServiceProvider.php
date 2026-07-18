<?php

namespace App\Providers;

use App\Enums\UserTypeEnum;
use App\Models\AssessmentScheme;
use App\Models\AssessmentSchemeDeduction;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\FinancialTransaction;
use App\Models\Fund;
use App\Models\Curriculum;
use App\Models\EvaluationItem;
use App\Models\EvaluationModel;
use App\Models\Installment;
use App\Models\Language;
use App\Models\Level;
use App\Models\PaymentMethod;
use App\Models\Project;
use App\Models\Role;
use App\Models\SubscriptionType;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Schema::defaultStringLength(191);

        // Disable wrapping of API resources in "data" key
        JsonResource::withoutWrapping();

        Vite::prefetch(concurrency: 3);

        if (Schema::hasTable('languages')) {

            $activeLanguages = Language::active()->get();

            $this->setSupportedLocale($activeLanguages);

            View::share('activeLanguages', $activeLanguages);

        }

        $this->defineGates();

        ResponseFactory::macro('api', function ($data = null, $error = 0, $message = '') {
            return response()->json([
                'data' => $data,
                'error' => $error, //1 or 0
                'message' => $message,
            ]);
        });

    }//end of boot

    private function setSupportedLocale($activeLanguages)
    {
        $supportedLocales = [];
        $translatableLocales = [];

        foreach ($activeLanguages as $activeLanguage) {

            $supportedLocales[$activeLanguage->code] = [
                'name' => $activeLanguage->name,
                'native' => $activeLanguage->name,
                'country_flag_code' => $activeLanguage->country_flag_code,
                'script' => $activeLanguage->code == 'ar' ? 'Arab' : 'qwe',
            ];

            $translatableLocales[] = $activeLanguage->code;

        }//end of for each

        config(['localization.supportedLocales' => $supportedLocales]);

        config(['translatable.locales' => $translatableLocales]);

    }// end of setSupportedLocale

    private function defineGates()
    {
        Gate::define('organization-role', function ($user, Role $role) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return $role->organization_id === $organizationId;
        });

        Gate::define('organization-branch', function ($user, Branch $branch) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return $branch->organization_id === $organizationId;
        });

        Gate::define('organization-currency', function ($user, Currency $currency) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return (int) $currency->organization_id === (int) $organizationId;
        });

        Gate::define('organization-payment-method', function ($user, PaymentMethod $paymentMethod) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return (int) $paymentMethod->organization_id === (int) $organizationId;
        });

        Gate::define('organization-subscription-type', function ($user, SubscriptionType $subscriptionType) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return (int) $subscriptionType->organization_id === (int) $organizationId;
        });

        Gate::define('organization-evaluation-model', function ($user, EvaluationModel $evaluationModel) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return $evaluationModel->organization_id === $organizationId;
        });

        Gate::define('organization-assessment-scheme', function ($user, AssessmentScheme $assessmentScheme) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return $assessmentScheme->organization_id === $organizationId;
        });

        Gate::define('organization-assessment-scheme-deduction', function ($user, AssessmentSchemeDeduction $deduction) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return $deduction->organization_id === $organizationId;
        });

        Gate::define('organization-evaluation-item', function ($user, EvaluationItem $evaluationItem) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return $evaluationItem->organization_id === $organizationId;
        });

        Gate::define('organization-project', function ($user, Project $project) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return $project->organization_id === $organizationId;
        });

        Gate::define('organization-installment', function ($user, Installment $installment) {
            $organizationId = session('selected_organization')['id'] ?? null;
            $branchId = session('selected_branch')['id'] ?? null;

            if ($organizationId === null || $branchId === null) {

                return false;

            }//end of if

            return (int) $installment->organization_id === (int) $organizationId
                && (int) $installment->branch_id === (int) $branchId;

        });

        Gate::define('organization-level', function ($user, Level $level) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return $level->organization_id === $organizationId;
        });

        Gate::define('organization-curriculum', function ($user, Curriculum $curriculum) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return $curriculum->organization_id === $organizationId;
        });

        Gate::define('organization-student', function ($user, User $student) {
            $selectedBranch = session('selected_branch');
            $selectedOrganization = session('selected_organization');

            if (!$selectedBranch || !isset($selectedBranch['team_id'])) {
                return false;
            }

            if (!$selectedOrganization || !isset($selectedOrganization['id'])) {
                return false;
            }

            // Check if student has STUDENT role for the branch's team
            $hasRole = $student->hasRole(UserTypeEnum::STUDENT, $selectedBranch['team_id']);

            // Check if student is related to the organization via organization_student table
            $belongsToOrganization = $student->studentOrganizations()
                ->where('organizations.id', $selectedOrganization['id'])
                ->exists();

            return $hasRole && $belongsToOrganization;
        });

        Gate::define('organization-teacher', function ($user, User $teacher) {
            $selectedBranch = session('selected_branch');
            $selectedOrganization = session('selected_organization');

            if (!$selectedBranch || !isset($selectedBranch['team_id'])) {
                return false;
            }

            if (!$selectedOrganization || !isset($selectedOrganization['id'])) {
                return false;
            }

            // Check if teacher has TEACHER role for the branch's team
            $hasRole = $teacher->hasRole(UserTypeEnum::TEACHER, $selectedBranch['team_id']);

            // Check if teacher is related to the organization via organization_teacher table
            $belongsToOrganization = $teacher->teacherOrganizations()
                ->where('organizations.id', $selectedOrganization['id'])
                ->exists();

            return $hasRole && $belongsToOrganization;
        });

        Gate::define('organization-classroom', function ($user, \App\Models\Classroom $classroom) {
            $selectedBranch = session('selected_branch');
            $selectedOrganization = session('selected_organization');

            if (!$selectedBranch || !isset($selectedBranch['id'])) {
                return false;
            }

            if (!$selectedOrganization || !isset($selectedOrganization['id'])) {
                return false;
            }

            // Check if classroom belongs to the selected branch
            return $classroom->branch_id == $selectedBranch['id'];
        });

        Gate::define('organization-fund', function ($user, Fund $fund) {
            $organizationId = session('selected_organization')['id'] ?? null;

            return (int) $fund->organization_id === (int) $organizationId;
        });

        Gate::define('organization-financial-transaction', function ($user, FinancialTransaction $transaction) {
            $organizationId = session('selected_organization')['id'] ?? null;
            $branchId       = session('selected_branch')['id'] ?? null;

            return (int) $transaction->organization_id === (int) $organizationId
                && (int) $transaction->branch_id === (int) $branchId;
        });

    }// end of defineGates

}//end of app service provider
