<x-app-layout>
  <x-slot name="title">Add Student</x-slot>

  @push('styles')
  <style>
    .page-layout {
      display: flex;
      gap: 32px;
      margin-top: 24px;
      align-items: flex-start;
      width: 100%;
    }
    .student-create-card {
      background: #fff;
      border-radius: 20px;
      padding: 32px 40px;
      box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
      border: 1px solid rgba(0,0,0,0.04);
      flex: 1; 
      min-width: 0; 
    }

    .info-panel {
      width: 320px;
      background: #fff;
      border-radius: 20px;
      padding: 24px;
      box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
      border: 1px solid rgba(0,0,0,0.04);
      position: sticky;
      top: 24px;
      flex-shrink: 0;
    }

    .info-panel h3 {
      font-size: 16px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .info-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .info-list li {
      position: relative;
      padding-left: 20px;
      font-size: 13px;
      color: #475569;
      margin-bottom: 16px;
      line-height: 1.6;
    }

    .info-list li::before {
      content: "•";
      color: #6C5CE7;
      font-size: 16px;
      font-weight: bold;
      position: absolute;
      left: 0;
      top: -2px;
    }

    .section-title {
      font-size: 16px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .premium-input {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 12px 16px;
      font-size: 14px;
      transition: all 0.2s;
      width: 100%;
      color: #1e293b;
    }
    
    .premium-input:focus {
      background: #fff;
      border-color: #6C5CE7;
      box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1);
      outline: none;
    }
    
    .premium-label {
      font-size: 13px;
      font-weight: 600;
      color: #475569;
      margin-bottom: 8px;
      display: block;
    }
    
    .btn-back-premium {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      background: rgba(108, 92, 231, 0.08);
      border: 1px solid rgba(108, 92, 231, 0.2);
      border-radius: 100px;
      color: #6C5CE7;
      font-size: 13px;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.2s;
      box-shadow: 0 2px 4px rgba(108, 92, 231, 0.05);
    }
    
    .btn-back-premium:hover {
      background: rgba(108, 92, 231, 0.15);
      color: #5A4BD6;
      transform: translateY(-1px);
      box-shadow: 0 4px 6px rgba(108, 92, 231, 0.1);
    }
    
    .btn-enroll-premium {
      background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
      color: #fff;
      border: none;
      padding: 12px 24px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.2s;
      box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
    }
    
    .btn-enroll-premium:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(108, 92, 231, 0.4);
    }
    
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 24px;
    }
    
    .form-error {
      color: #ef4444;
      font-size: 12px;
      margin-top: 6px;
    }
    
    @media (max-width: 1024px) {
      .info-panel { display: none; }
    }
    
    @media (max-width: 768px) {
      .form-grid { grid-template-columns: 1fr; }
      .student-create-card { padding: 24px; }
      .page-layout { gap: 0; }
    }
  </style>
  @endpush

  <div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <div>
      <h2 class="page-title">Add Student</h2>
      <p class="page-subtitle">Enroll a new student and set up their account</p>
    </div>
    <a href="{{ route('students.index') }}" class="btn-back-premium">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Back to Students
    </a>
  </div>

  <div class="page-layout">
    
    <!-- Main Form Card -->
    <div class="student-create-card">
      <form method="POST" action="{{ route('students.store') }}">
        @csrf

        <div class="section-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          Account Details (For Login)
        </div>
        
        <div class="form-grid">
          <div>
            <label class="premium-label" for="name">Full Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="premium-input" placeholder="e.g. Aditya Sharma" required />
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="premium-label" for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="premium-input" placeholder="student@example.com" />
            <small style="color:#94a3b8; font-size:11px; margin-top:4px; display:block;">Required for student login</small>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="premium-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="premium-input" placeholder="Create a strong password" />
            <small style="color:#94a3b8; font-size:11px; margin-top:4px; display:block;">Leave blank to use default (password123)</small>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="section-title" style="margin-top: 32px;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
          Academic Information
        </div>

        <div class="form-grid">
          <div>
            <label class="premium-label" for="roll_no">Roll Number *</label>
            <input type="text" id="roll_no" name="roll_no" value="{{ old('roll_no') }}" class="premium-input" placeholder="e.g. 2024-CS-001" required />
            @error('roll_no')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="premium-label" for="class">Class *</label>
            <input type="text" id="class" name="class" value="{{ old('class') }}" class="premium-input" placeholder="e.g. 10, BSc-1, etc." required />
            @error('class')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="premium-label" for="section">Section</label>
            <input type="text" id="section" name="section" value="{{ old('section') }}" class="premium-input" placeholder="A, B, C…" />
          </div>
          <div>
            <label class="premium-label" for="gender">Gender</label>
            <select id="gender" name="gender" class="premium-input">
              <option value="">Select gender</option>
              <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>Male</option>
              <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
              <option value="other"  {{ old('gender') == 'other'  ? 'selected' : '' }}>Other</option>
            </select>
          </div>
        </div>

        <div class="section-title" style="margin-top: 32px;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
          Personal Details
        </div>

        <div class="form-grid">
          <div>
            <label class="premium-label" for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" value="{{ old('dob') }}" class="premium-input" />
          </div>
          <div>
            <label class="premium-label" for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="premium-input" placeholder="+91 98000 00000" />
          </div>
          <div style="grid-column: 1 / -1;">
            <label class="premium-label" for="guardian_name">Guardian Name</label>
            <input type="text" id="guardian_name" name="guardian_name" value="{{ old('guardian_name') }}" class="premium-input" placeholder="Parent / Guardian full name" />
          </div>
        </div>

        <div style="display:flex; gap:12px; margin-top:32px; justify-content:flex-end; border-top: 1px solid #f1f5f9; padding-top: 24px;">
          <a href="{{ route('students.index') }}" class="btn-back-premium" style="box-shadow:none;">Cancel</a>
          <button type="submit" class="btn-enroll-premium">Enroll Student</button>
        </div>
      </form>
    </div>
    
    <!-- Info Panel -->
    <div class="info-panel">
      <h3>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
        Student Setup Guide
      </h3>
      <ul class="info-list">
        <li><strong>Email Login:</strong> The email provided will be used as the student's username to access the dashboard.</li>
        <li><strong>Default Password:</strong> If left blank, the default password <code>password123</code> is assigned automatically.</li>
        <li><strong>Class & Section:</strong> Ensure these match your existing system exactly (e.g., "10" and "A").</li>
        <li><strong>Roll Numbers:</strong> Must be unique within the school to prevent data conflicts.</li>
      </ul>
      <div style="margin-top:24px; padding-top:20px; border-top:1px solid #f1f5f9; text-align:center;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" style="margin:0 auto 8px;"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
        <p style="font-size:12px; color:#94a3b8; margin:0; font-weight:500;">Secure Student Enrollment</p>
      </div>
    </div>

  </div>

</x-app-layout>
