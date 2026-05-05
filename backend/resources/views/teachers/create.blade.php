<x-app-layout>
  <x-slot name="title">Add Teacher</x-slot>

  @push('styles')
  <style>
    .page-layout {
      display: flex;
      gap: 32px;
      margin-top: 24px;
      align-items: flex-start;
      width: 100%;
    }

    .form-create-card {
      background: #fff;
      border-radius: 20px;
      padding: 32px 40px;
      box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
      border: 1px solid rgba(0,0,0,0.04);
      flex: 1; /* Takes remaining space */
      min-width: 0; /* Prevents flex blowout */
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
      .form-create-card { padding: 24px; }
      .page-layout { gap: 0; }
    }
  </style>
  @endpush

  <div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <div>
      <h2 class="page-title">Add Teacher</h2>
      <p class="page-subtitle">Register a new teacher in your school</p>
    </div>
    <a href="{{ route('teachers.index') }}" class="btn-back-premium">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Back to Teachers
    </a>
  </div>

  <div class="page-layout">
    
    <!-- Main Form Card -->
    <div class="form-create-card">
      <form method="POST" action="{{ route('teachers.store') }}">
        @csrf

        <div class="section-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          Account Details
        </div>
        
        <div class="form-grid">
          <div>
            <label class="premium-label" for="name">Full Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="premium-input" placeholder="e.g. John Doe" required />
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="premium-label" for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="premium-input" placeholder="teacher@example.com" required />
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="premium-label" for="password">Password *</label>
            <input type="password" id="password" name="password" class="premium-input" placeholder="Create a strong password" required />
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="section-title" style="margin-top: 32px;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
          Academic Assignment
        </div>

        <div class="form-grid">
          <div style="grid-column: 1 / -1;">
            <label class="premium-label" for="subject_id">Primary Subject (Optional)</label>
            <select id="subject_id" name="subject_id" class="premium-input">
                <option value="">No Subject assigned yet</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }} (Class {{ $subject->class }})</option>
                @endforeach
            </select>
            <small style="color:#94a3b8; font-size:11px; margin-top:4px; display:block;">You can assign more subjects later from the teacher's profile.</small>
            @error('subject_id')<div class="form-error">{{ $message }}</div>@enderror
          </div>
        </div>

        <div style="display:flex; gap:12px; margin-top:32px; justify-content:flex-end; border-top: 1px solid #f1f5f9; padding-top: 24px;">
          <a href="{{ route('teachers.index') }}" class="btn-back-premium" style="box-shadow:none; background:transparent; border-color:transparent; color:#64748b;">Cancel</a>
          <button type="submit" class="btn-enroll-premium">Save Teacher</button>
        </div>
      </form>
    </div>
    
    <!-- Info Panel -->
    <div class="info-panel">
      <h3>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
        Teacher Setup Guide
      </h3>
      <ul class="info-list">
        <li><strong>Teacher Login:</strong> Provide a secure password. The teacher will use this email and password to log in.</li>
        <li><strong>Permissions:</strong> Newly added teachers will automatically have teacher-level access to the system.</li>
        <li><strong>Subject Assignment:</strong> Assigning a primary subject here links the teacher to that subject. They can evaluate and manage marks for it.</li>
      </ul>
      <div style="margin-top:24px; padding-top:20px; border-top:1px solid #f1f5f9; text-align:center;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" style="margin:0 auto 8px;"><path d="M16 21v-2a4 4 0 0 0-4-4H5c-1.1 0-2 .9-2 2v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
        <p style="font-size:12px; color:#94a3b8; margin:0; font-weight:500;">Faculty Management</p>
      </div>
    </div>

  </div>

</x-app-layout>
