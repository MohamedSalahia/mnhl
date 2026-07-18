<?php

namespace App\Http\Middleware;

use App\Enums\UserTypeEnum;
use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToDashboard
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        $authService = new AuthService();

        $isAdmin = $user->hasRole(UserTypeEnum::SUPER_ADMIN) || $user->hasRole(UserTypeEnum::ADMIN);

        $isOrganizationAdmin = $user->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN) || $user->hasRole(UserTypeEnum::ORGANIZATION_ADMIN);

        $isTeacher = $user->hasRole(UserTypeEnum::TEACHER);

        $isExaminer = $user->hasRole(UserTypeEnum::EXAMINER);

        if ($isAdmin && request()->is('organization', 'organization/*')) {

            return redirect(route('admin.home', absolute: false));

        } else if ($isOrganizationAdmin && request()->is('admin', 'admin/*')) {

            return redirect(route('organization.home', absolute: false));

        } else if ($isTeacher && (request()->is('admin', 'admin/*') || request()->is('organization', 'organization/*'))) {

            return redirect(route('teacher.home', absolute: false));

        } else if ($isExaminer && !$isTeacher && (request()->is('admin', 'admin/*') || request()->is('organization', 'organization/*') || request()->is('teacher', 'teacher/*'))) {

            return redirect(route('examiner.home', absolute: false));

        }//end of if

        return $next($request);

    }//end of handle

}//end of middleware
