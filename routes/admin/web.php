<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\NationalityController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\Profile\PasswordController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\WhatsappTemplateController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'redirect_to_dashboard',
    'role:admin|super_admin',
    'localization',
    'redirect_to_dashboard'
])->group(function () {

    Route::name('admin.')->prefix('admin')->group(function () {

        //home
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        //role routes
        Route::get('/roles/data', [RoleController::class, 'data'])->name('roles.data');
        Route::delete('/roles/bulk_delete', [RoleController::class, 'bulkDelete'])->name('roles.bulk_delete');
        Route::resource('roles', RoleController::class);

        //admin routes
        Route::get('/admin/toggle_dark_mode', [AdminController::class, 'toggleDarkMode'])->name('admin.toggle_dark_mode');
        Route::get('/admin/toggle_menu_collapsed', [AdminController::class, 'toggleMenuCollapsed'])->name('admin.toggle_menu_collapsed');
        Route::get('/admin/switch_language', [AdminController::class, 'switchLanguage'])->name('admin.switch_language');
        Route::get('/admins/data', [AdminController::class, 'data'])->name('admins.data');
        Route::delete('/admins/bulk_delete', [AdminController::class, 'bulkDelete'])->name('admins.bulk_delete');
        Route::resource('admins', AdminController::class);

        //setting routes
        Route::get('/settings/general_data', [SettingController::class, 'generalData'])->name('settings.general_data');
        Route::post('/settings/general_data', [SettingController::class, 'storeGeneralData'])->name('settings.general_data.store');

        //currency routes
        Route::get('/currencies/data', [CurrencyController::class, 'data'])->name('currencies.data');
        Route::delete('/currencies/bulk_delete', [CurrencyController::class, 'bulkDelete'])->name('currencies.bulk_delete');
        Route::resource('currencies', CurrencyController::class);

        //country routes
        Route::get('/countries/{country}/governorates', [CountryController::class, 'governorates'])->name('countries.governorates');
        Route::get('/countries/data', [CountryController::class, 'data'])->name('countries.data');
        Route::delete('/countries/bulk_delete', [CountryController::class, 'bulkDelete'])->name('countries.bulk_delete');
        Route::resource('countries', CountryController::class);

        //governorate routes
        Route::get('/governorates/{governorate}/areas', [GovernorateController::class, 'areas'])->name('governorates.areas');
        Route::get('/governorates/data', [GovernorateController::class, 'data'])->name('governorates.data');
        Route::delete('/governorates/bulk_delete', [GovernorateController::class, 'bulkDelete'])->name('governorates.bulk_delete');
        Route::resource('governorates', GovernorateController::class);

        //area routes
        Route::get('/areas/data', [AreaController::class, 'data'])->name('areas.data');
        Route::delete('/areas/bulk_delete', [AreaController::class, 'bulkDelete'])->name('areas.bulk_delete');
        Route::resource('areas', AreaController::class);

        //nationality routes
        Route::get('/nationalities/data', [NationalityController::class, 'data'])->name('nationalities.data');
        Route::delete('/nationalities/bulk_delete', [NationalityController::class, 'bulkDelete'])->name('nationalities.bulk_delete');
        Route::resource('nationalities', NationalityController::class);

        //whatsapp template routes
        Route::get('/whatsapp-templates/data', [WhatsappTemplateController::class, 'data'])->name('whatsapp_templates.data');
        Route::delete('/whatsapp-templates/bulk_delete', [WhatsappTemplateController::class, 'bulkDelete'])->name('whatsapp_templates.bulk_delete');
        Route::resource('whatsapp-templates', WhatsappTemplateController::class)->parameters(['whatsapp-templates' => 'whatsapp_template']);

        //organization routes
        Route::get('/organizations/data', [OrganizationController::class, 'data'])->name('organizations.data');
        Route::delete('/organizations/bulk_delete', [OrganizationController::class, 'bulkDelete'])->name('organizations.bulk_delete');
        Route::post('/organizations/{organization}/impersonate', [OrganizationController::class, 'impersonate'])->name('organizations.impersonate');
        Route::resource('organizations', OrganizationController::class);

        //branch routes
        Route::get('/branches/data', [BranchController::class, 'data'])->name('branches.data');
        Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');

        //profile routes
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/switch_language', [ProfileController::class, 'switchLanguage'])->name('profile.switch_language');

        Route::name('profile.')->namespace('Profile')->group(function () {

            //password routes
            Route::get('/password/edit', [PasswordController::class, 'edit'])->name('password.edit');
            Route::put('/password/update', [PasswordController::class, 'update'])->name('password.update');

        });

    });

});
