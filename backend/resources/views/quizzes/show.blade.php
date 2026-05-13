<x-app-layout>
  <x-slot name="title">{{ $quiz->title }}</x-slot>

  @push('styles')
  <style>
    .quiz-detail-header {
      background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
      border: 1px solid rgba(0,0,0,0.06); border-radius: 20px;
      padding: 28px 32px; margin-bottom: 28px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .qdh-top { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
    .qdh-title { font-family:'Poppins',sans-serif; font-size: 24px; font-weight: 800; color: #0f172a; margin: 0 0 8px; }
    .qdh-badges { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
    .badge-pill { display: inline-flex; padding: 4px 12px; border-radius: 100px; font-size: 12px; font-weight: 700; }
    .badge-subject { background: #eef2ff; color: #6366f1; }
    .badge-easy { background: #ecfdf5; color: #10b981; }
    .badge-medium { background: #fffbeb; color: #f59e0b; }
    .badge-hard { background: #fef2f2; color: #ef4444; }
    .qdh-meta { font-size: 13px; color: #64748b; display: flex; gap: 20px; flex-wrap: wrap; }
    .qdh-meta strong { color: #334155; }

    .qdh-actions { display: flex; gap: 8px; }
    .btn-sm-action {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 9px 16px; border-radius: 10px;
      font-size: 12px; font-weight: 700; text-decoration: none;
      transition: all 0.2s; cursor: pointer; border: none;
    }
    .btn-assign { background: linear-gradient(135deg,#6C5CE7,#8B5CF6); color: #fff; box-shadow: 0 4px 14px rgba(108,92,231,0.28); }
    .btn-assign:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(108,92,231,0.35); color:#fff; }
    .btn-edit-q { background: #f1f5f9; color: #334155; }
    .btn-edit-q:hover { background: #0f172a; color: #fff; }

    .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
    @media(max-width:1024px) { .main-grid { grid-template-columns: 1fr; } }

    .p-card { background: #fff; border: 1px solid rgba(0,0,0,0.04); border-radius: 20px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.03); }
    .p-card-title { font-family:'Poppins',sans-serif; font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 16px; }

    .question-preview {
      background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px;
      padding: 20px; margin-bottom: 12px;
    }
    .question-preview:last-child { margin-bottom: 0; }
    .qp-num { display: inline-flex; padding: 2px 8px; border-radius: 6px; background: #eef2ff; color: #6C5CE7; font-size: 11px; font-weight: 800; margin-bottom: 8px; }
    .qp-text { font-size: 14px; font-weight: 600; color: #0f172a; margin: 0 0 10px; }
    .qp-options { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .qp-opt {
      padding: 8px 12px; border-radius: 8px; font-size: 13px;
      background: #fff; border: 1px solid #e2e8f0; color: #475569;
    }
    .qp-opt.correct { background: #ecfdf5; border-color: #a7f3d0; color: #059669; font-weight: 600; }

    .assignment-row {
      display: flex; align-items: center; justify-content: space-between;
      padding: 12px 0; border-bottom: 1px solid #f1f5f9;
    }
    .assignment-row:last-child { border-bottom: none; }
    .ar-student { font-size: 14px; font-weight: 600; color: #0f172a; }
    .ar-meta { font-size: 12px; color: #94a3b8; }
    .ar-link { font-size: 12px; font-weight: 700; color: #6C5CE7; text-decoration: none; }
    .ar-link:hover { text-decoration: underline; }
  </style>
  @endpush

  <div class="quiz-detail-header">
    <div class="qdh-top">
      <div>
        <div class="qdh-badges">
          <span class="badge-pill badge-subject">{{ $quiz->subject->name ?? 'General' }}</span>
          <span class="badge-pill badge-{{ $quiz->difficulty_level }}">{{ ucfirst($quiz->difficulty_level) }}</span>
        </div>
        <h1 class="qdh-title">{{ $quiz->title }}</h1>
        <div class="qdh-meta">
          <span>⏱ <strong>{{ $quiz->duration_minutes }} min</strong></span>
          <span>📝 <strong>{{ $quiz->questions->count() }}</strong> questions</span>
          <span>🎯 <strong>{{ $quiz->total_marks }}</strong> total marks</span>
          <span>👤 Created by <strong>{{ $quiz->creator->name ?? 'Unknown' }}</strong></span>
        </div>
      </div>
      <div class="qdh-actions">
        <a href="{{ route('quizzes.assign', $quiz) }}" class="btn-sm-action btn-assign">Assign to Student</a>
        <a href="{{ route('quizzes.edit', $quiz) }}" class="btn-sm-action btn-edit-q">Edit Quiz</a>
      </div>
    </div>
  </div>

  <div class="main-grid">
    <div>
      <div class="p-card">
        <h3 class="p-card-title">Questions Preview</h3>
        @foreach($quiz->questions as $idx => $q)
        <div class="question-preview">
          <span class="qp-num">Q{{ $idx + 1 }} · {{ $q->marks }} mark{{ $q->marks > 1 ? 's' : '' }}</span>
          <p class="qp-text">{{ $q->question }}</p>
          <div class="qp-options">
            <div class="qp-opt {{ $q->correct_answer === 'A' ? 'correct' : '' }}">A) {{ $q->option_a }}</div>
            <div class="qp-opt {{ $q->correct_answer === 'B' ? 'correct' : '' }}">B) {{ $q->option_b }}</div>
            <div class="qp-opt {{ $q->correct_answer === 'C' ? 'correct' : '' }}">C) {{ $q->option_c }}</div>
            <div class="qp-opt {{ $q->correct_answer === 'D' ? 'correct' : '' }}">D) {{ $q->option_d }}</div>
          </div>
          @if($q->explanation)
          <div style="margin-top:10px;padding:10px 12px;background:#fffbeb;border-radius:8px;font-size:12px;color:#92400e;">
            💡 {{ $q->explanation }}
          </div>
          @endif
        </div>
        @endforeach
      </div>
    </div>

    <div>
      <div class="p-card">
        <h3 class="p-card-title">Assignments ({{ $quiz->assignments->count() }})</h3>
        @forelse($quiz->assignments as $asgn)
        <div class="assignment-row">
          <div>
            <div class="ar-student">{{ $asgn->student->user->name ?? 'Unknown' }}</div>
            <div class="ar-meta">{{ $asgn->repeat_days }} days · {{ $asgn->attempts->whereNotNull('completed_at')->count() }}/{{ $asgn->repeat_days }} done</div>
          </div>
          <a href="{{ route('quizzes.analytics', $asgn) }}" class="ar-link">Analytics →</a>
        </div>
        @empty
        <div style="padding:24px;text-align:center;background:#f8fafc;border-radius:12px;border:1px dashed #d1d5db;">
          <p style="color:#64748b;font-size:13px;margin:0;">No students assigned yet.</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>

</x-app-layout>
