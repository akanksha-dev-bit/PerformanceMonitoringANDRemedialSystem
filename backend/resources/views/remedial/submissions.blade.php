<x-app-layout>
  <x-slot name="title">Submissions — {{ $remedial->title }}</x-slot>

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:12px;">
    <div>
      <div style="font-size:12px; font-weight:700; color:#6C5CE7; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:4px;">
        {{ $remedial->action_type_label }}
      </div>
      <h2 style="font-size:22px; font-weight:800; color:#0f172a; margin:0 0 4px;">{{ $remedial->title }}</h2>
      <p style="font-size:13px; color:#64748b; margin:0;">{{ $submissions->count() }} student submission(s)</p>
    </div>
    <a href="{{ route('remedial.index') }}" style="padding:10px 18px; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:10px; font-size:13px; font-weight:700; color:#475569; text-decoration:none;">
      ← All Actions
    </a>
  </div>

  @if(session('success'))
    <div style="padding:14px 18px; background:#ecfdf5; color:#10b981; border:1px solid #a7f3d0; border-radius:12px; font-weight:700; margin-bottom:20px;">
      ✅ {{ session('success') }}
    </div>
  @endif

  <div style="background:#fff; border-radius:20px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.04); border:1px solid rgba(0,0,0,0.03);">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr style="background:#f8fafc;">
          <th style="text-align:left; padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #e2e8f0;">Student</th>
          <th style="text-align:left; padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #e2e8f0;">Submission Status</th>
          <th style="text-align:left; padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #e2e8f0;">Submitted At</th>
          <th style="text-align:left; padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #e2e8f0;">Score</th>
          <th style="text-align:left; padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #e2e8f0;">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($submissions as $sub)
          <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
            <td style="padding:16px 20px;">
              <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#6C5CE7,#5A4BD6); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:13px; flex-shrink:0;">
                  {{ strtoupper(substr($sub->student->name, 0, 2)) }}
                </div>
                <div>
                  <div style="font-weight:700; color:#0f172a;">{{ $sub->student->name }}</div>
                  <div style="font-size:12px; color:#64748b;">Roll: {{ $sub->student->roll_no }}</div>
                </div>
              </div>
            </td>
            <td style="padding:16px 20px;">
              <span style="display:inline-flex; padding:4px 12px; border-radius:100px; font-size:12px; font-weight:700; background:{{ $sub->status_color }}20; color:{{ $sub->status_color }};">
                {{ $sub->status_label }}
              </span>
            </td>
            <td style="padding:16px 20px; font-size:14px; color:#475569; font-weight:600;">
              {{ $sub->submitted_at ? $sub->submitted_at->format('d M Y, H:i') : '—' }}
            </td>
            <td style="padding:16px 20px; font-size:16px; font-weight:800; color:#0f172a;">
              @if($sub->teacher_score !== null)
                {{ $sub->teacher_score }}@if($remedial->max_score) / {{ $remedial->max_score }} @endif
              @else
                <span style="color:#94a3b8;">—</span>
              @endif
            </td>
            <td style="padding:16px 20px;">
              <a href="{{ route('remedial.review', $sub) }}"
                style="display:inline-flex; align-items:center; gap:6px; padding:7px 14px; background:linear-gradient(135deg,#6C5CE7,#5A4BD6); color:#fff; border-radius:8px; font-size:12px; font-weight:700; text-decoration:none;">
                {{ $sub->submission_status === 'reviewed' ? '✏️ Re-review' : '🔍 Review' }}
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align:center; padding:48px; color:#94a3b8; font-weight:500;">
              No submissions yet for this task.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</x-app-layout>
