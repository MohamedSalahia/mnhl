<?php

namespace App\Http\Middleware;

use App\Enums\UserTypeEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetSelectedOrganization
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Set organization if not exists in session
        if (!session()->has('selected_organization')) {

            $selectedOrganization = null;

            // Check if user is admin or organization admin
            if ($user->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN) || $user->hasRole(UserTypeEnum::ORGANIZATION_ADMIN)) {
                $selectedOrganization = $user->adminOrganizations()->first();
            }
            // Check if user is teacher
            elseif ($user->hasRole(UserTypeEnum::TEACHER)) {
                $selectedOrganization = $user->teacherOrganizations()->first();
            }
            // Check if user is examiner
            elseif ($user->hasRole(UserTypeEnum::EXAMINER)) {
                // Get examiner's organizations through branches
                $examinerBranches = $user->examinerBranches()->with('organization')->get();
                $selectedOrganization = $examinerBranches->first()?->organization;
            }

            if ($selectedOrganization) {
                session(['selected_organization' => $selectedOrganization]);
            }
        }

        // Set branch if not exists in session and organization is set
        if (!session()->has('selected_branch') && session()->has('selected_organization')) {

            $selectedOrganization = session('selected_organization');

            $selectedBranch = null;

            // Check if user is organization super admin
            if ($user->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN)) {
                $selectedBranch = $selectedOrganization->branches()->first();
            }
            // Check if user is organization admin
            elseif ($user->hasRole(UserTypeEnum::ORGANIZATION_ADMIN)) {
                $selectedBranch = $user->adminBranches()
                    ->where('organization_id', $selectedOrganization['id'])
                    ->first();
            }
            // Check if user is teacher
            elseif ($user->hasRole(UserTypeEnum::TEACHER)) {
                $selectedBranch = $user->teacherBranches()
                    ->where('organization_id', $selectedOrganization['id'])
                    ->first();
            }
            // Check if user is examiner
            elseif ($user->hasRole(UserTypeEnum::EXAMINER)) {
                $selectedBranch = $user->examinerBranches()
                    ->where('organization_id', $selectedOrganization['id'])
                    ->first();
            }

            if ($selectedBranch) {
                session(['selected_branch' => $selectedBranch]);
            }

        }
        
        return $next($request);

    }//end of handle

}//end of middleware

