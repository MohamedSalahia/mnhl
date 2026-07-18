<?php

return [
    'available_roles' => [
        ['entity' => 'roles', 'translation' => 'roles.roles', 'permissions' => ['create', 'read', 'update', 'delete']],
        ['entity' => 'admins', 'translation' => 'admins.admins', 'permissions' => ['create', 'read', 'update', 'delete']],
        // ['entity' => 'users', 'translation' => 'users.users', 'permissions' => ['read']],
        ['entity' => 'countries', 'translation' => 'countries.countries', 'permissions' => ['create', 'read', 'update', 'delete']],
        ['entity' => 'governorates', 'translation' => 'governorates.governorates', 'permissions' => ['create', 'read', 'update', 'delete']],
        ['entity' => 'areas', 'translation' => 'areas.areas', 'permissions' => ['create', 'read', 'update', 'delete']],
    ],

    'organization_roles' => [
        ['entity' => 'branches', 'translation' => 'branches.branches', 'permissions' => ['create', 'read', 'update', 'delete']],
        ['entity' => 'students', 'translation' => 'users.students', 'permissions' => ['create', 'read', 'update', 'delete']],
        ['entity' => 'teachers', 'translation' => 'users.teachers', 'permissions' => ['create', 'read', 'update', 'delete']],
        ['entity' => 'examiners', 'translation' => 'users.examiners', 'permissions' => ['create', 'read', 'update', 'delete']],
    ],

];
