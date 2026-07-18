<?php

namespace App\Http\Controllers\Examiner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ProfileRequest;
use App\Models\Branch;
use App\Models\Organization;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('examiner.profile.edit');

    }// end of edit

    public function update(ProfileRequest $request)
    {
        $requestData = $request->validated();

        if ($request->image) {
            $requestData['image'] = $request->image->hashName();
            $request->image->store('uploads', 'public');
        }

        auth()->user()->update($requestData);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('examiner.home')
        ]);

    }// end of update

    public function switchLanguage(Request $request)
    {
        request()->validate([
            'locale' => 'required|in:' . implode(',', array_keys(config('localization.supportedLocales'))),
        ]);

        auth()->user()->update(['locale' => $request['locale']]);

        session(['locale' => $request['locale']]);

        return redirect()->back();

    }// end of switchLanguage

    public function toggleDarkMode()
    {
        auth()->user()->update([
            'dark_mode' => !auth()->user()->dark_mode
        ]);

    }// end of toggleDarkMode

    public function toggleMenuCollapsed()
    {
        auth()->user()->update([
            'menu_collapsed' => !auth()->user()->menu_collapsed
        ]);

    }// end of toggleMenuCollapsed

    public function switchOrganization(Organization $organization)
    {
        $user = auth()->user();

        // Get examiner's organizations through branches
        $examinerOrganizations = Organization::query()
            ->whereHas('branches.examiners', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->get();

        if (!$examinerOrganizations->contains('id', $organization->id)) {
            session()->flash('error', __('site.unauthorized_access'));
            return redirect()->back();
        }

        session(['selected_organization' => $organization]);

        session()->forget('selected_branch');

        return redirect()->route('examiner.home');

    }// end of switchOrganization

    public function switchBranch(Branch $branch)
    {
        $user = auth()->user();

        // Check if user has access to this branch
        $selectedOrganization = session('selected_organization');

        if (!$selectedOrganization) {
            session()->flash('error', __('site.unauthorized_access'));
            return redirect()->back();
        }

        // Check if examiner has access to this branch
        if (!$user->examinerBranches()->where('branches.id', $branch->id)->exists()) {
            session()->flash('error', __('site.unauthorized_access'));
            return redirect()->back();
        }

        // Verify branch belongs to selected organization
        if ($branch->organization_id != $selectedOrganization['id']) {
            session()->flash('error', __('site.unauthorized_access'));
            return redirect()->back();
        }

        session(['selected_branch' => $branch]);

        return redirect()->route('examiner.home');

    }// end of switchBranch

}//end of controller
