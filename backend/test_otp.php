<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::factory()->unverified()->create([
    'email' => 'test_otp_' . time() . '@example.com',
]);
$user->sendEmailVerificationNotification();
echo "OTP is: " . $user->email_verification_otp . "\n";
