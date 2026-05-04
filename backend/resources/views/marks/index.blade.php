<x-app-layout>
  <x-slot name="title">Marks</x-slot>

  <div class="page-header">
    <div>
      <h2 class="page-title">{{ auth()->user()->isStudent() ? 'My Marks' : 'Marks Management' }}</h2>
      <p class="page-subtitle">{{ auth()->user()->isStudent() ? 'View all your recorded academic performance' : 'View and manage all recorded marks' }}</p>
    </div>
    @if(!auth()->user()->isStudent())
    <a href="{{ route('marks.create') }}" class="btn btn-primary" id="add-marks-btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Add Marks
    </a>
    @endif
  </div>

  {{-- Filters (Hide for students) --}}
  @if(!auth()->user()->isStudent())
  <div class="card mb-6">
    <form method="GET" class="flex gap-3" style="flex-wrap:wrap; align-items:flex-end;">
      <div class="form-group" style="margin:0; min-width:200px;">
        <label class="form-label">Student</label>
        <select name="student_id" class="form-select" id="marks-student-filter">
          <option value="">All Students</option>
          @foreach($students as $s)
            <option value="{{ $s->id }}" {{ request('student_id') == $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->roll_no }})</option>
          @endforeach
        </select>
      </div>
      <div class="form-group" style="margin:0; min-width:180px;">
        <label class="form-label">Subject</label>
        <select name="subject_id" class="form-select" id="marks-subject-filter">
          <option value="">All Subjects</option>
          @foreach($subjects as $sub)
            <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex; gap:8px; padding-bottom:0;">
        <button type="submit" class="btn btn-primary" id="marks-filter-btn">Filter</button>
        <a href="{{ route('marks.index') }}" class="btn btn-outline" id="marks-reset-btn">Reset</a>
      </div>
    </form>
  </div>
  @endif

  <div class="card">
    <div class="table-wrapper">
      <table class="pmrs-table">
        <thead>
          <tr>
            @if(!auth()->user()->isStudent())
              <th>Student</th>
            @endif
            <th>Subject</th><th>Term / Exam</th><th>Marks</th><th>%</th><th>Year</th><th>Result</th>
            @if(!auth()->user()->isStudent())
              <th>Action</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @forelse($marks as $mark)
          <tr>
            @if(!auth()->user()->isStudent())
            <td>
              <div class="student-cell">
                <div class="student-avatar">{{ strtoupper(substr($mark->student->name, 0, 2)) }}</div>
                <div>
                  <div class="student-name">{{ $mark->student->name }}</div>
                  <div class="student-roll">{{ $mark->student->roll_no }}</div>
                </div>
              </div>
            </td>
            @endif
            <td>{{ $mark->subject->name ?? '—' }}</td>
            <td>
              <div style="display:flex; flex-direction:column;">
                <span style="font-weight:600;">{{ $mark->term }}</span>
                <span class="badge badge-muted" style="width:fit-content; margin-top:4px;">{{ str_replace('_', ' ', ucfirst($mark->exam_type)) }}</span>
              </div>
            </td>
            <td>{{ $mark->marks_obtained }} / {{ $mark->max_marks }}</td>
            <td style="font-weight:600; color:{{ $mark->percentage >= 75 ? 'var(--success)' : ($mark->percentage >= 40 ? 'var(--warning)' : 'var(--error)') }}">{{ $mark->percentage }}%</td>
            <td>{{ $mark->academic_year }}</td>
            <td>
              @if($mark->is_pass)
                <span class="badge badge-success">Pass</span>
              @else
                <span class="badge badge-error">Fail</span>
              @endif
            </td>
            @if(!auth()->user()->isStudent())
            <td>
              <form method="POST" action="{{ route('marks.destroy', $mark) }}" onsubmit="return confirm('Delete this mark entry?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" id="delete-mark-{{ $mark->id }}">Delete</button>
              </form>
            </td>
            @endif
          </tr>
          @empty
          <tr>
            <td colspan="{{ auth()->user()->isStudent() ? 6 : 8 }}" style="text-align:center; padding:48px; color:var(--text-muted);">
              No marks recorded yet. 
              @if(!auth()->user()->isStudent())
                <a href="{{ route('marks.create') }}">Add marks →</a>
              @endif
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="pagination-wrapper">{{ $marks->links() }}</div>
  </div>

</x-app-layout>
