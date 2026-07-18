<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'super_admin' => [
            'roles' => 'c,r,u,d',
            'admins' => 'c,r,u,d',
            'organizations' => 'c,r,u,d',
            'branches' => 'r',
            'settings' => 'c,r,u,d',
            'countries' => 'c,r,u,d',
            'governorates' => 'c,r,u,d',
            'areas' => 'c,r,u,d',
            'nationalities' => 'c,r,u,d',
        ],

        'admin' => [],

        'organization_super_admin' => [
            'roles' => 'c,r,u,d',
            'admins' => 'c,r,u,d',
            'students' => 'c,r,u,d',
            'teachers' => 'c,r,u,d',
            'examiners' => 'c,r,u,d',
            'branches' => 'c,r,u,d',
            'currencies' => 'c,r,u,d',
            'payment_methods' => 'c,r,u,d',
            'installments' => 'c,r,u,d',
            'teacher_salaries' => 'c,r,u,d',
            'financial_transactions' => 'c,r,u,d',
            'subscription_types' => 'c,r,u,d',
            'evaluation_models' => 'c,r,u,d',
            'evaluation_items' => 'c,r,u,d',
            'projects' => 'c,r,u,d',
            'levels' => 'c,r,u,d',
            'curricula' => 'c,r,u,d',
            'classrooms' => 'c,r,u,d',
            'lessons' => 'c,r,u,d',
            'assessment_schemes' => 'c,r,u,d',
            'assessment_scheme_deductions' => 'c,r,u,d',
            'settings' => 'c,r,u,d',
        ],

        'organization_admin' => [],

        'student' => [],

        'teacher' => [],

        'examiner' => [],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
