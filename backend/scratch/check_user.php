<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('email', 'abijithc140@gmail.com')->first();
echo json_encode([
    'exists' => !!$user,
    'hash_starts_with' => $user ? substr($user->password, 0, 7) : null,
    'check_admin123' => $user ? \Illuminate\Support\Facades\Hash::check('Password123!', $user->password) : false,
], JSON_PRETTY_PRINT) . PHP_EOL;
