@extends('layouts.teacher')

@section('title', 'Intervention Detail')
@section('page-icon', '🤝')
@section('page-heading', 'Intervention Detail')

@section('content')

<div class="page-header">
    <div>
        <h1>Intervention Record</h1>
        <div class="page-subtitle">{{ $intervention->student?->fullName() }}</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('teacher.students.show', $intervention->student_id) }}" class="btn btn-outline">
            <i class="fas fa-user-graduate"></i> Student Profile
        </a>
        <a href="{{ route('teacher.interventions.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="grid-2" style="gap:22px;align-items:start;">

    {{-- Left: Student info + Assessment context --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Student Card --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-user-graduate" style="color:var(--primary);"></i> Student
                </div>
                @if($intervention->status === 'Active')
                    <span class="badge badge-danger">
                        <i class="fas fa-circle" style="font-size:7px;"></i> Active
                    </span>
                @elseif($intervention->status === 'Completed')
                    <span class="badge badge-success">
                        <i class="fas fa-check-circle"></i> Completed
                    </span>
                @else
                    <span class="badge badge-secondary">Cancelled</span>
                @endif
            </div>
            <div style="padding:20px;display:flex;align-items:center;gap:16px;">
                <img src="{{ $intervention->student?->profilePhotoUrl() }}"
                     style="width:64px;height:64px;border-radius:50%;object-fit:cover;
                            border:3px solid var(--border);flex-shrink:0;">
                <div>
                    <div style="font-size:17px;font-weight:800;margin-bottom:4px;">
                        {{ $intervention->student?->fullName() }}
                    </div>
                    @if($intervention->student?->section)
                        <span class="badge badge-info">
                            Grade {{ $intervention->student->section->grade_level }}
                            – {{ $intervention->student->section->name }}
                        </span>
                    @endif
                    @if($intervention->student?->readingLevel)
                        <span class="badge badge-primary" style="margin-left:4px;">
                            {{ $intervention->student->readingLevel->name }}
                        </span>
                    @endif
                </div>
            </div>
            <div style="padding:14px 20px;border-top:1px solid var(--border);
                        display:flex;gap:20px;background:#f8faff;">
                <div>
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;
                                letter-spacing:0.8px;color:var(--muted);">Started</div>
                    <div style="font-size:13.5px;font-weight:700;">
                        {{ $intervention->started_on?->format('M d, Y') }}
                    </div>
                </div>
                @if($intervention->ended_on)
                <div>
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;
                                letter-spacing:0.8px;color:var(--muted);">Ended</div>
                    <div style="font-size:13.5px;font-weight:700;">
                        {{ $intervention->ended_on->format('M d, Y') }}
                    </div>
                </div>
                @endif
                <div>
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;
                                letter-spacing:0.8px;color:var(--muted);">Duration</div>
                    <div style="font-size:13.5px;font-weight:700;">
                        {{ $intervention->started_on?->diffForHumans($intervention->ended_on ?? now(), true) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Assessment That Triggered This --}}
        @if($intervention->assessment)
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-clipboard-check" style="color:var(--primary);"></i>
                    Triggering Assessment
                </div>
                <span class="text-muted text-small">
                    {{ $intervention->assessment->assessed_on?->format('M d, Y') }}
                </span>
            </div>
            <div style="padding:20px;">
                @php $a = $intervention->assessment; @endphp

                <div style="margin-bottom:14px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                        <span style="font-size:12.5px;font-weight:600;">Fluency Score</span>
                        <span style="font-weight:800;font-size:14px;
                            color:{{ $a->fluency_score >= 85 ? 'var(--success)' :
                                     ($a->fluency_score >= 70 ? '#b8860b' : 'var(--danger)') }};">
                            {{ $a->fluency_score }}%
                        </span>
                    </div>
                    <div class="risk-bar">
                        <div class="risk-bar-fill" style="width:{{ $a->fluency_score }}%;
                            background:{{ $a->fluency_score >= 85 ? 'var(--success)' :
                                         ($a->fluency_score >= 70 ? 'var(--warning)' : 'var(--danger)') }};"></div>
                    </div>
                </div>

                <div style="margin-bottom:14px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                        <span style="font-size:12.5px;font-weight:600;">Comprehension Score</span>
                        <span style="font-weight:800;font-size:14px;
                            color:{{ $a->comprehension_score >= 80 ? 'var(--success)' :
                                     ($a->comprehension_score >= 65 ? '#b8860b' : 'var(--danger)') }};">
                            {{ $a->comprehension_score }}%
                        </span>
                    </div>
                    <div class="risk-bar">
                        <div class="risk-bar-fill" style="width:{{ $a->comprehension_score }}%;
                            background:{{ $a->comprehension_score >= 80 ? 'var(--success)' :
                                         ($a->comprehension_score >= 65 ? 'var(--warning)' : 'var(--danger)') }};"></div>
                    </div>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;
                            padding:10px 14px;background:#fff8f8;border-radius:8px;">
                    <span style="font-size:12.5px;font-weight:600;">Sessions / Week</span>
                    <span class="badge {{ $a->reading_sessions_per_week <= 1 ? 'badge-danger' : 'badge-success' }}">
                        {{ $a->reading_sessions_per_week }} / week
                    </span>
                </div>

                @if($a->notes)
                    <div style="margin-top:14px;padding:12px 14px;background:#f8faff;
                                border-radius:8px;font-size:13px;color:var(--muted);line-height:1.6;">
                        <strong style="color:var(--text);">Assessment Note:</strong><br>
                        {{ $a->notes }}
                    </div>
                @endif

                <a href="{{ route('teacher.assessments.show', $a) }}"
                   class="btn btn-outline btn-sm" style="margin-top:14px;width:100%;justify-content:center;">
                    <i class="fas fa-eye"></i> View Full Assessment
                </a>
            </div>
        </div>
        @endif

    </div>

    {{-- Right: Update form --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Update Intervention --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-edit" style="color:var(--primary);"></i>
                    Update Intervention
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('teacher.interventions.update', $intervention) }}">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                            @foreach(['Active','Completed','Cancelled'] as $statusOpt)
                                <label style="cursor:pointer;">
                                    <input type="radio" name="status" value="{{ $statusOpt }}"
                                           {{ old('status', $intervention->status) === $statusOpt ? 'checked' : '' }}
                                           style="display:none;" class="status-radio">
                                    <div class="status-option {{ old('status', $intervention->status) === $statusOpt ? 'selected-' . strtolower($statusOpt) : '' }}"
                                         style="padding:10px;border:2px solid var(--border);border-radius:8px;
                                                text-align:center;font-size:13px;font-weight:600;transition:all 0.2s;">
                                        @if($statusOpt === 'Active')
                                            <i class="fas fa-circle" style="color:var(--danger);font-size:10px;display:block;margin-bottom:4px;"></i>
                                        @elseif($statusOpt === 'Completed')
                                            <i class="fas fa-check-circle" style="color:var(--success);font-size:14px;display:block;margin-bottom:4px;"></i>
                                        @else
                                            <i class="fas fa-ban" style="color:var(--muted);font-size:14px;display:block;margin-bottom:4px;"></i>
                                        @endif
                                        {{ $statusOpt }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">End Date
                            <small class="text-muted" style="font-weight:400;">(auto-set on completion)</small>
                        </label>
                        <input type="date" name="ended_on" class="form-control"
                               value="{{ old('ended_on', $intervention->ended_on?->format('Y-m-d')) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Intervention Notes
                            <small class="text-muted" style="font-weight:400;">
                                — What strategies are being used?
                            </small>
                        </label>
                        <textarea name="intervention_notes" class="form-control" rows="7"
                                  placeholder="Describe the reading support strategies being applied, student progress observed, materials used, and any recommendations...">{{ old('intervention_notes', $intervention->intervention_notes) }}</textarea>
                        @error('intervention_notes')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <div class="form-hint">Max 2,000 characters. Be specific about strategies used.</div>
                    </div>

                    <div style="display:flex;gap:10px;">
                        @if($intervention->status === 'Active')
                            <button type="submit" name="status" value="Completed"
                                    class="btn btn-success"
                                    onclick="document.querySelector('[name=status][value=Completed]').checked=true"
                                    style="flex:1;justify-content:center;">
                                <i class="fas fa-check"></i> Mark Complete & Save
                            </button>
                        @endif
                        <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;">
                            <i class="fas fa-save"></i> Save Notes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Quick Completion Box --}}
        @if($intervention->status === 'Active')
        <div class="card" style="border:2px dashed var(--success);background:rgba(40,167,69,0.03);">
            <div class="card-body" style="text-align:center;padding:24px;">
                <div style="width:52px;height:52px;background:rgba(40,167,69,0.12);border-radius:50%;
                            display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                    <i class="fas fa-check-circle" style="color:var(--success);font-size:24px;"></i>
                </div>
                <h3 style="font-size:15px;font-weight:700;margin-bottom:6px;">Intervention Complete?</h3>
                <p style="font-size:13px;color:var(--muted);margin-bottom:16px;">
                    Has the student shown sufficient reading improvement?
                    Mark this intervention as complete.
                </p>
                <form method="POST"
                      action="{{ route('teacher.interventions.complete', $intervention) }}"
                      onsubmit="return confirm('Mark this intervention as completed?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-success" style="width:100%;justify-content:center;">
                        <i class="fas fa-check"></i> Mark as Completed
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
// Visual radio button toggle for status
document.querySelectorAll('.status-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.status-option').forEach(opt => {
            opt.style.borderColor = 'var(--border)';
            opt.style.background  = '#fff';
        });
        const label = this.nextElementSibling;
        const color = this.value === 'Active' ? 'var(--danger)' :
                      this.value === 'Completed' ? 'var(--success)' : 'var(--muted)';
        label.style.borderColor = color;
        label.style.background  = color.replace(')', ', 0.06)').replace('var(', 'rgba(').replace(')', '');
    });
});
</script>
@endpush
