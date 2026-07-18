<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionWithTeam
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        // Get team_id from selected_branch in session
        $selectedBranch = session('selected_branch');

        $teamId = $selectedBranch['team_id'] ?? null;

        // If no team_id is found, abort
        if (!$teamId) {
            abort(403, 'No branch selected');
        }

        if (!$user->hasPermission($permission, $teamId)) {
            abort(403);
        }

        return $next($request);
    }
}

