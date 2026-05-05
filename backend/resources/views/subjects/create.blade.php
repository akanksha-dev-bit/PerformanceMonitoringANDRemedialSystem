<x-app-layout>
  <x-slot name="title">Add Subject</x-slot>

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

    .checkbox-wrapper {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }

    .checkbox-wrapper input[type="checkbox"] {
      width: 18px;
      height: 18px;
      border-radius: 4px;
      border: 1px solid #cbd5e1;
      cursor: pointer;
      accent-color: #6C5CE7;
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
      <h2 class="page-title">Add Subject</h2>
      <p class="page-subtitle">Create a new subject in the system</p>
    </div>
    <a href="{{ route('subjects.index') }}" class="btn-back-premium">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Back to Subjects
    </a>
  </div>

  <div class="page-layout">
    
    <!-- Main Form Card -->
    <div class="form-create-card">
      <form method="POST" action="{{ route('subjects.store') }}">
        @csrf

        <div class="section-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
          Subject Details
        </div>
        
        <div class="form-grid">
          <div>
            <label class="premium-label" for="name">Subject Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="premium-input" placeholder="e.g. Mathematics" required />
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="premium-label" for="code">Subject Code *</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" class="premium-input" placeholder="e.g. MAT101" required />
            @error('code')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="premium-label" for="class">Class *</label>
            <select id="class" name="class" class="premium-input" required>
                <option value="">Select Class</option>
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ old('class') == $i ? 'selected' : '' }}>Class {{ $i }}</option>
                @endfor
                <option value="All" {{ old('class') == 'All' ? 'selected' : '' }}>All Classes</option>
            </select>
            @error('class')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="premium-label" for="type">Type</label>
            <select id="type" name="type" class="premium-input">
                <option value="">Select Type</option>
                <option value="theory" {{ old('type') == 'theory' ? 'selected' : '' }}>Theory</option>
                <option value="practical" {{ old('type') == 'practical' ? 'selected' : '' }}>Practical</option>
                <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Both</option>
            </select>
            @error('type')<div class="form-error">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="section-title" style="margin-top: 32px;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
          Evaluation Settings
        </div>

        <div class="form-grid">
          <div>
            <label class="premium-label" for="max_marks">Max Marks</label>
            <input type="number" id="max_marks" name="max_marks" value="{{ old('max_marks', 100) }}" class="premium-input" placeholder="100" />
            @error('max_marks')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div style="display: flex; align-items: center; padding-top: 24px;">
            <label class="checkbox-wrapper">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} />
                <span style="font-weight: 600; color: #1e293b; font-size: 14px;">Subject is Active</span>
            </label>
          </div>
        </div>

        <div style="display:flex; gap:12px; margin-top:32px; justify-content:flex-end; border-top: 1px solid #f1f5f9; padding-top: 24px;">
          <a href="{{ route('subjects.index') }}" class="btn-back-premium" style="box-shadow:none;">Cancel</a>
          <button type="submit" class="btn-enroll-premium">Save Subject</button>
        </div>
      </form>
    </div>
    
    <!-- Info Panel -->
    <div class="info-panel">
      <h3>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
        Subject Setup Guide
      </h3>
      <ul class="info-list">
        <li><strong>Subject Code:</strong> Use a unique, standard identifier (like MAT101) to easily distinguish subjects.</li>
        <li><strong>Class Allocation:</strong> Assign the subject to a specific class or select "All Classes" for general subjects.</li>
        <li><strong>Evaluation Type:</strong> Define whether the subject is evaluated based on Theory, Practical, or Both.</li>
        <li><strong>Active Status:</strong> Inactive subjects will be hidden from student dashboards and new grading forms.</li>
      </ul>
      <div style="margin-top:24px; padding-top:20px; border-top:1px solid #f1f5f9; text-align:center;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" style="margin:0 auto 8px;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
        <p style="font-size:12px; color:#94a3b8; margin:0; font-weight:500;">Curriculum Management</p>
      </div>
    </div>

  </div>

</x-app-layout>
