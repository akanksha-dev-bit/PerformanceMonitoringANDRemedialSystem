<x-app-layout>
  <x-slot name="title">My Quizzes</x-slot>

  @push('styles')
  <style>
    .quiz-page-header {
      background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
      border: 1px solid rgba(0,0,0,0.06);
      border-radius: 20px;
      padding: 28px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 28px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
      flex-wrap: wrap;
      gap: 16px;
    }
    .quiz-title { font-family:'Poppins',sans-serif; font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; margin: 0 0 4px; }
    .quiz-subtitle { font-size: 14px; color: #64748b; margin: 0; }

    .btn-create {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 20px;
      background: linear-gradient(135deg, #6C5CE7, #8B5CF6);
      color: #fff; border-radius: 12px; font-size: 13px; font-weight: 700;
      text-decoration: none; border: none; cursor: pointer;
      box-shadow: 0 4px 14px rgba(108,92,231,0.28);
      transition: all 0.22s cubic-bezier(.4,0,.2,1);
    }
    .btn-create:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(108,92,231,0.35); color:#fff; }

    .quiz-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 20px;
    }

    .quiz-card {
      background: #fff;
      border: 1px solid rgba(0,0,0,0.05);
      border-radius: 18px;
      padding: 24px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.03);
      transition: all 0.25s cubic-bezier(.4,0,.2,1);
      display: flex;
      flex-direction: column;
    }
    .quiz-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 14px 32px rgba(0,0,0,0.07);
    }

    .qc-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
    .qc-subject {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 4px 10px; border-radius: 100px;
      font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
      background: #eef2ff; color: #6366f1;
    }
    .qc-diff {
      display: inline-flex; padding: 4px 10px; border-radius: 100px;
      font-size: 11px; font-weight: 700;
    }
    .diff-easy   { background: #ecfdf5; color: #10b981; }
    .diff-medium { background: #fffbeb; color: #f59e0b; }
    .diff-hard   { background: #fef2f2; color: #ef4444; }

    .qc-title { font-family:'Poppins',sans-serif; font-size: 17px; font-weight: 700; color: #0f172a; margin: 0 0 6px; }
    .qc-desc { font-size: 13px; color: #64748b; line-height: 1.5; margin: 0 0 16px; flex: 1; }

    .qc-meta { display: flex; gap: 16px; margin-bottom: 16px; }
    .qc-meta-item { font-size: 12px; color: #94a3b8; display: flex; align-items: center; gap: 4px; }
    .qc-meta-item strong { color: #475569; font-weight: 700; }

    .qc-actions { display: flex; gap: 8px; }
    .btn-qc {
      flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      padding: 9px 14px; border-radius: 10px;
      font-size: 12px; font-weight: 700; text-decoration: none;
      transition: all 0.2s; cursor: pointer; border: none;
    }
    .btn-qc-primary { background: #eef2ff; color: #6C5CE7; }
    .btn-qc-primary:hover { background: #6C5CE7; color: #fff; }
    .btn-qc-dark { background: #f1f5f9; color: #334155; }
    .btn-qc-dark:hover { background: #0f172a; color: #fff; }
    .btn-qc-success { background: #ecfdf5; color: #10b981; }
    .btn-qc-success:hover { background: #10b981; color: #fff; }

    .empty-state {
      text-align: center; padding: 60px 32px;
      background: #fff; border-radius: 20px; border: 1px dashed #d1d5db;
    }
    .empty-state h3 { font-family:'Poppins',sans-serif; font-size: 20px; font-weight: 700; color: #0f172a; margin: 16px 0 8px; }
    .empty-state p { font-size: 14px; color: #64748b; margin: 0 0 20px; }
  </style>
  @endpush

  <div class="quiz-page-header">
    <div>
      <h2 class="quiz-title">My Quizzes 📝</h2>
      <p class="quiz-subtitle">Create and manage remedial quizzes for your students.</p>
    </div>
    <a href="{{ route('quizzes.create') }}" class="btn-create">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Create Quiz
    </a>
  </div>

  @if($quizzes->count() > 0)
  <div class="quiz-grid">
    @foreach($quizzes as $quiz)
    <div class="quiz-card">
      <div class="qc-header">
        <span class="qc-subject">{{ $quiz->subject->name ?? 'General' }}</span>
        <span class="qc-diff diff-{{ $quiz->difficulty_level }}">{{ ucfirst($quiz->difficulty_level) }}</span>
      </div>
      <h3 class="qc-title">{{ $quiz->title }}</h3>
      <p class="qc-desc">{{ Str::limit($quiz->description ?? 'No description provided.', 80) }}</p>
      <div class="qc-meta">
        <span class="qc-meta-item">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <strong>{{ $quiz->duration_minutes }}m</strong>
        </span>
        <span class="qc-meta-item">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
          <strong>{{ $quiz->question_count }}</strong> Q's
        </span>
        <span class="qc-meta-item">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          <strong>{{ $quiz->assignments->count() }}</strong> assigned
        </span>
      </div>
      <div class="qc-actions">
        <a href="{{ route('quizzes.show', $quiz) }}" class="btn-qc btn-qc-primary">View</a>
        <a href="{{ route('quizzes.assign', $quiz) }}" class="btn-qc btn-qc-success">Assign</a>
        <a href="{{ route('quizzes.edit', $quiz) }}" class="btn-qc btn-qc-dark">Edit</a>
      </div>
    </div>
    @endforeach
  </div>
  <div style="margin-top: 24px;">{{ $quizzes->links() }}</div>
  @else
  <div class="empty-state">
    <div style="font-size: 48px;">📋</div>
    <h3>No Quizzes Yet</h3>
    <p>Create your first quiz to start assigning practice sessions to students.</p>
    <a href="{{ route('quizzes.create') }}" class="btn-create">Create Your First Quiz</a>
  </div>
  @endif

</x-app-layout>
