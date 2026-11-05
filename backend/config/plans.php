<?php

// The backend is the authority for prices, limits and entitlements. Setup fees
// remain configurable and default to zero until the school confirms them.
return [
    'currency' => 'INR',
    'checkout_enabled' => (bool) env('PMRS_TEST_CHECKOUT_ENABLED', false) && env('RAZORPAY_MODE', 'test') === 'test',
    'enforcement_enabled' => (bool) env('PMRS_SUBSCRIPTION_ENFORCEMENT_ENABLED', false),
    'plans' => [
        'starter' => [
            'name' => 'Starter',
            'annual_price_inr' => 59000,
            'setup_fee_inr' => max(0, (int) env('PMRS_STARTER_SETUP_FEE_INR', 0)),
            'student_limit' => 200,
            'features' => ['students', 'teachers', 'academic_structure', 'attendance', 'marks', 'performance', 'remedial', 'basic_reports', 'quizzes', 'timetable'],
        ],
        'professional' => [
            'name' => 'Professional',
            'annual_price_inr' => 89000,
            'setup_fee_inr' => max(0, (int) env('PMRS_PROFESSIONAL_SETUP_FEE_INR', 0)),
            'student_limit' => 500,
            'features' => ['students', 'teachers', 'academic_structure', 'attendance', 'marks', 'performance', 'remedial', 'basic_reports', 'quizzes', 'timetable', 'allocations', 'attendance_analytics'],
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'annual_price_inr' => 139000,
            'setup_fee_inr' => max(0, (int) env('PMRS_ENTERPRISE_SETUP_FEE_INR', 0)),
            'student_limit' => 1000,
            'features' => ['students', 'teachers', 'academic_structure', 'attendance', 'marks', 'performance', 'remedial', 'basic_reports', 'quizzes', 'timetable', 'allocations', 'attendance_analytics'],
        ],
    ],
    'future_features' => ['ai_personalization', 'ai_study_assistant', 'institutional_ai_assistant', 'data_export', 'custom_reports'],
];
