<x-app-layout>
  <x-slot name="title">Edit Student</x-slot>

  <style>
    .form-page-header {
      text-align: center;
      margin-bottom: 32px;
    }
    .form-page-header h2 { font-family: 'Poppins', sans-serif; font-size: 28px; font-weight: 700; color: #111827; letter-spacing: -0.02em; }
    .form-page-header p { font-size: 15px; color: var(--text-muted); margin-top: 8px; }

    .premium-form-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      box-shadow: 0 10px 40px rgba(0,0,0,0.03);
      max-width: 760px;
      margin: 0 auto;
      overflow: hidden;
    }
    
    .pfc-header {
      background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
      padding: 24px 32px;
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
    }
    
    .pfc-body {
      padding: 32px;
    }

    .premium-form-group {
      margin-bottom: 24px;
    }
    .premium-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #374151;
      margin-bottom: 8px;
    }
    .premium-input {
      width: 100%;
      padding: 12px 16px;
      border-radius: 10px;
      border: 1px solid #d1d5db;
      background: #fdfdfd;
      font-size: 14px;
      font-family: 'Inter', sans-serif;
      color: #111827;
      transition: all 0.2s ease;
      box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
    }
    .premium-input:focus {
      outline: none;
      border-color: var(--primary);
      background: #fff;
      box-shadow: 0 0 0 4px rgba(108,92,231,0.1);
    }
    .premium-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }

    .pfc-footer {
      padding: 24px 32px;
      background: #f9fafb;
      border-top: 1px solid var(--border);
      display: flex;
      justify-content: flex-end;
      gap: 16px;
    }

    @media (max-width: 640px) {
      .premium-row { grid-template-columns: 1fr; gap: 0; }
    }
  </style>

  <div class="form-page-header">
    <h2>Edit Profile</h2>
    <p>Update information and academic details for {{ $student->name }}</p>
  </div>

  <div class="premium-form-card">
    <div class="pfc-header">
      <div style="font-weight:600; color:#111827; font-size:16px;">Student Information</div>
      <a href="{{ route('students.show', $student) }}" class="btn btn-outline" style="padding:6px 14px; font-size:13px;" id="back-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Profile
      </a>
    </div>

    <form method="POST" action="{{ route('students.update', $student) }}" id="edit-student-form">
      @csrf @method('PATCH')
      
      <div class="pfc-body">
        <div class="premium-row">
          <div class="premium-form-group">
            <label class="premium-label" for="name">Full Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" class="premium-input" required />
            @error('name')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
          </div>
          <div class="premium-form-group">
            <label class="premium-label" for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}" class="premium-input" placeholder="student@example.com" />
            @error('email')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="premium-row">
          <div class="premium-form-group">
            <label class="premium-label" for="roll_no">Roll Number *</label>
            <input type="text" id="roll_no" name="roll_no" value="{{ old('roll_no', $student->roll_no) }}" class="premium-input" required />
            @error('roll_no')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
          </div>
          <div class="premium-form-group">
            <label class="premium-label" for="class">Class *</label>
            <input type="text" id="class" name="class" value="{{ old('class', $student->class) }}" class="premium-input" required />
            @error('class')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="premium-row">
          <div class="premium-form-group">
            <label class="premium-label" for="section">Section</label>
            <input type="text" id="section" name="section" value="{{ old('section', $student->section) }}" class="premium-input" />
          </div>
          <div class="premium-form-group">
            <label class="premium-label" for="gender">Gender</label>
            <select id="gender" name="gender" class="premium-input">
              <option value="">Select gender</option>
              @foreach(['male','female','other'] as $g)
                <option value="{{ $g }}" {{ old('gender', $student->gender) == $g ? 'selected' : '' }}>{{ ucfirst($g) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="premium-row">
          <div class="premium-form-group">
            <label class="premium-label" for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" value="{{ old('dob', $student->dob?->format('Y-m-d')) }}" class="premium-input" />
          </div>
          <div class="premium-form-group">
            <label class="premium-label" for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $student->phone) }}" class="premium-input" placeholder="+1 (555) 000-0000" />
          </div>
        </div>

        <div class="premium-row">
          <div class="premium-form-group" style="margin-bottom:0;">
            <label class="premium-label" for="guardian_name">Guardian Name</label>
            <input type="text" id="guardian_name" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" class="premium-input" />
          </div>
          <div class="premium-form-group" style="margin-bottom:0;">
            <label class="premium-label" for="status">Account Status</label>
            <select id="status" name="status" class="premium-input">
              <option value="active"   {{ old('status', $student->status) == 'active'   ? 'selected' : '' }}>Active</option>
              <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>
        </div>
      </div>

      <div class="pfc-footer">
        <a href="{{ route('students.show', $student) }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary" id="update-student-btn">Save Changes</button>
      </div>
    </form>
  </div>

</x-app-layout>
