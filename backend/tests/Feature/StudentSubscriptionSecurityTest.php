<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\AcademicClass;
use App\Models\Classroom;
use App\Models\SchoolSubscription;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentSubscriptionSecurityTest extends TestCase
{
    use RefreshDatabase;

    private School $school;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('plans.enforcement_enabled', true);
        config()->set('plans.plans.starter.student_limit', 1);
        $this->school = School::create(['name' => 'Security School', 'school_code' => 'PMRS-SECURITY']);
        $this->admin = User::factory()->create([
            'role' => 'admin', 'school_id' => $this->school->id, 'profile_completed' => true,
        ]);
        SchoolSubscription::create([
            'school_id' => $this->school->id, 'plan_slug' => 'starter', 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addYear(),
        ]);
    }

    private function student(string $roll, bool $active = true): Student
    {
        $user = User::factory()->create([
            'role' => 'student', 'school_id' => $this->school->id,
            'profile_completed' => true, 'is_active' => $active,
        ]);
        return Student::create([
            'school_id' => $this->school->id, 'user_id' => $user->id,
            'roll_no' => $roll, 'is_active' => $active,
        ]);
    }

    public function test_inactive_student_cannot_log_in_or_use_an_existing_session(): void
    {
        $student = $this->student('001', false);
        $this->post('/login', ['email' => $student->user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->actingAs($student->user)->getJson('/api/user')
            ->assertForbidden()->assertJsonPath('code', 'STUDENT_INACTIVE');
        $this->assertGuest();
    }

    public function test_inactive_student_cannot_enter_through_the_email_otp_flow(): void
    {
        $student = $this->student('001', false);
        $student->user->forceFill([
            'email_verified_at' => null, 'email_verification_otp' => '123456',
        ])->save();
        $this->withSession(['verify_email' => $student->user->email])
            ->post('/verify-otp', ['otp' => '123456'])->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invited_student_must_complete_profile_before_using_academic_routes(): void
    {
        $invitee = User::factory()->create([
            'role' => 'student', 'school_id' => $this->school->id,
            'profile_completed' => false, 'is_active' => true,
        ]);
        $this->actingAs($invitee)->getJson('/dashboard/student')
            ->assertForbidden()->assertJsonPath('code', 'PROFILE_INCOMPLETE');
    }

    public function test_deactivation_revokes_an_existing_session_and_reactivation_obeys_capacity(): void
    {
        $student = $this->student('001');
        $this->actingAs($this->admin)->putJson("/students/{$student->id}", ['is_active' => false])->assertOk();
        $this->actingAs($student->user->fresh())->getJson('/dashboard/student')->assertForbidden();

        $this->actingAs($this->admin)->postJson('/students', [
            'name' => 'Replacement', 'roll_no' => '002',
        ])->assertCreated();
        $this->putJson("/students/{$student->id}", ['is_active' => true])
            ->assertUnprocessable()->assertJsonValidationErrors('student_limit');
    }

    public function test_students_cannot_mutate_school_student_records(): void
    {
        $student = $this->student('001');
        $this->actingAs($student->user);
        $this->postJson('/students', ['name' => 'Unauthorized', 'roll_no' => '002'])->assertForbidden();
        $this->putJson("/students/{$student->id}", ['is_active' => false])->assertForbidden();
        $this->deleteJson("/students/{$student->id}")->assertForbidden();
        $this->assertDatabaseHas('students', ['id' => $student->id, 'is_active' => true]);
    }

    public function test_other_schools_student_records_cannot_be_read_or_changed(): void
    {
        $otherSchool = School::create(['name' => 'Other School', 'school_code' => 'PMRS-OTHER']);
        $otherUser = User::factory()->create(['role' => 'student', 'school_id' => $otherSchool->id]);
        $otherStudent = Student::create([
            'school_id' => $otherSchool->id, 'user_id' => $otherUser->id,
            'roll_no' => '001', 'is_active' => true,
        ]);
        $this->actingAs($this->admin);
        $this->getJson("/students/{$otherStudent->id}")->assertNotFound();
        $this->putJson("/students/{$otherStudent->id}", ['is_active' => false])->assertNotFound();
        $this->deleteJson("/students/{$otherStudent->id}")->assertNotFound();
        $this->assertDatabaseHas('students', ['id' => $otherStudent->id, 'is_active' => true]);
    }

    public function test_student_creation_requires_a_password_for_email_login_and_never_auto_verifies(): void
    {
        $this->actingAs($this->admin);
        $this->postJson('/students', [
            'name' => 'Email Student', 'email' => 'student@example.com', 'roll_no' => '001',
        ])->assertUnprocessable()->assertJsonValidationErrors('password');

        $this->postJson('/students', [
            'name' => 'Email Student', 'email' => 'student@example.com',
            'password' => 'Password123!', 'roll_no' => '001',
        ])->assertCreated();
        $user = User::where('email', 'student@example.com')->firstOrFail();
        $this->assertNull($user->email_verified_at);
        $this->assertTrue(Hash::check('Password123!', $user->password));
    }

    public function test_missing_optional_fields_and_no_email_use_an_unusable_random_password(): void
    {
        $this->actingAs($this->admin)->postJson('/students', [
            'name' => 'No Email', 'roll_no' => '001',
        ])->assertCreated();
        $student = Student::where('roll_no', '001')->firstOrFail();
        $this->assertNull($student->dob);
        $this->assertNull($student->guardian_name);
        $this->assertNull($student->user->email_verified_at);
        $this->assertFalse(Hash::check('password123', $student->user->password));

        $this->putJson("/students/{$student->id}", [
            'name' => 'No Email Updated', 'roll_no' => '001',
        ])->assertOk();
    }

    public function test_changing_a_student_email_resets_verification_and_requires_credentials_for_a_placeholder(): void
    {
        $this->actingAs($this->admin)->postJson('/students', [
            'name' => 'Placeholder', 'roll_no' => '001', 'phone' => '5551234',
        ])->assertCreated();
        $student = Student::where('roll_no', '001')->firstOrFail();
        $student->user->forceFill(['email_verification_otp' => '123456'])->save();
        $this->putJson("/students/{$student->id}", [
            'name' => 'Placeholder', 'roll_no' => '001', 'email' => 'new@example.com',
        ])->assertUnprocessable()->assertJsonValidationErrors('password');
        $this->putJson("/students/{$student->id}", [
            'name' => 'Placeholder', 'roll_no' => '001',
            'email' => 'new@example.com', 'password' => 'Password123!',
        ])->assertOk();
        $student->refresh();
        $this->assertSame('5551234', $student->phone);
        $this->assertSame('new@example.com', $student->user->email);
        $this->assertNull($student->user->email_verified_at);
        $this->assertNull($student->user->email_verification_otp);
    }

    public function test_inactive_records_cannot_be_used_as_seats_until_reactivated(): void
    {
        $this->actingAs($this->admin);
        $this->postJson('/students', ['name' => 'Active', 'roll_no' => '001'])->assertCreated();
        $this->postJson('/students', [
            'name' => 'Inactive', 'roll_no' => '002', 'is_active' => false,
        ])->assertCreated();
        $inactive = Student::where('roll_no', '002')->firstOrFail();
        $this->putJson("/students/{$inactive->id}", ['is_active' => true])
            ->assertUnprocessable()->assertJsonValidationErrors('student_limit');
        $this->assertFalse($inactive->fresh()->is_active);
    }

    public function test_invited_student_cannot_complete_profile_after_the_school_reaches_capacity(): void
    {
        $this->student('001');
        $class = AcademicClass::create(['school_id' => $this->school->id, 'name' => 'Class 9']);
        $room = Classroom::create([
            'school_id' => $this->school->id, 'academic_class_id' => $class->id, 'name' => 'A',
        ]);
        $invitee = User::factory()->create([
            'role' => 'student', 'school_id' => $this->school->id,
            'profile_completed' => false, 'is_active' => true,
        ]);
        $this->actingAs($invitee)->postJson('/complete-profile', [
            'classroom_id' => $room->id, 'roll_number' => '002', 'phone' => '5551234',
        ])->assertUnprocessable()->assertJsonValidationErrors('student_limit');
        $this->assertDatabaseMissing('students', ['user_id' => $invitee->id]);
    }
}
