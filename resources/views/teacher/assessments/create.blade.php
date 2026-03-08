@extends('layouts.teacher')

@section('title', 'New Assessment')
@section('page-icon', '📊')
@section('page-heading', 'New Assessment')


@push('styles')
<style>
html, body { overflow: hidden !important; height: 100% !important; }
.main-area   { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
.page-content { flex: 1; overflow: hidden; display: flex; flex-direction: column; }
.scroll-body { flex: 1; min-height: 0; overflow-y: auto; overflow-x: hidden; padding-bottom: 20px; }
.scroll-body::-webkit-scrollbar { width: 5px; }
.scroll-body::-webkit-scrollbar-thumb { background: #d1d9f0; border-radius: 99px; }
</style>
@endpush
@section('content')

<div style="display:flex;flex-direction:column;height:100%;">
<div class="page-header">
    <div><h1>Record Reading Assessment</h1></div>
    <a href="{{ route('teacher.assessments.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="scroll-body">
{{-- Risk Level Reference --}}
<div class="card" style="margin-bottom:20px;padding:16px 20px;">
    <div style="display:flex;gap:16px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:8px;">
            <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Below Standard</span>
            <span class="text-muted text-small">Fluency &lt; 70 OR Comprehension &lt; 65</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            <span class="badge badge-warning"><i class="fas fa-chart-line"></i> Approaching</span>
            <span class="text-muted text-small">Fluency &lt; 85 OR Comprehension &lt; 80</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            <span class="badge badge-success"><i class="fas fa-star"></i> Meeting Standard</span>
            <span class="text-muted text-small">Fluency ≥ 85 AND Comprehension ≥ 80</span>
        </div>
    </div>
</div>

<div style="max-width:680px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-clipboard-check"></i> Assessment Form</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.assessments.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Student <span class="required">*</span></label>
                    <select name="student_id" class="form-control {{ $errors->has('student_id') ? 'is-invalid' : '' }}" required>
                        <option value="">— Select Student —</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                {{ (old('student_id', $selectedStudent?->id) == $student->id) ? 'selected' : '' }}>
                                {{ $student->fullName() }}
                                @if($student->section) — {{ $student->section->name }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('student_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Assessment Date <span class="required">*</span></label>
                    <input type="date" name="assessed_on"
                           class="form-control {{ $errors->has('assessed_on') ? 'is-invalid' : '' }}"
                           value="{{ old('assessed_on', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}">
                    @error('assessed_on') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">
                            Fluency Score <span class="required">*</span>
                            <small class="text-muted">(0–100)</small>
                        </label>
                        <input type="number" name="fluency_score"
                               class="form-control {{ $errors->has('fluency_score') ? 'is-invalid' : '' }}"
                               value="{{ old('fluency_score') }}"
                               min="0" max="100" step="0.01" placeholder="e.g. 78.50" required
                               oninput="updateRiskPreview()">
                        @error('fluency_score') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        <div class="form-hint">Words per minute / accuracy score</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Comprehension Score <span class="required">*</span>
                            <small class="text-muted">(0–100)</small>
                        </label>
                        <input type="number" name="comprehension_score"
                               class="form-control {{ $errors->has('comprehension_score') ? 'is-invalid' : '' }}"
                               value="{{ old('comprehension_score') }}"
                               min="0" max="100" step="0.01" placeholder="e.g. 72.00" required
                               oninput="updateRiskPreview()">
                        @error('comprehension_score') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        <div class="form-hint">Reading comprehension percentage</div>
                    </div>
                </div>

                {{-- Live risk preview --}}
                <div id="riskPreview" style="display:none;margin-bottom:16px;">
                    <div style="padding:14px 18px;border-radius:8px;border-left:4px solid #ccc;background:#f8faff;"
                         id="riskBox">
                        <div style="font-weight:700;font-size:13px;" id="riskLabel"></div>
                        <div style="font-size:12px;color:var(--muted);margin-top:4px;" id="riskReason"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Notes / Observations</label>
                    <textarea name="notes" class="form-control" rows="3"
                              placeholder="Describe the student's reading behavior, strengths, or areas for improvement...">{{ old('notes') }}</textarea>
                </div>

                <div style="display:flex;gap:12px;justify-content:flex-end;padding-top:8px;">
                    <a href="{{ route('teacher.assessments.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Assessment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
</div>
@endsection

@push('scripts')
<script>
function updateRiskPreview() {
    const fluency = parseFloat(document.querySelector('[name=fluency_score]').value) || null;
    const comp    = parseFloat(document.querySelector('[name=comprehension_score]').value) || null;
    const preview = document.getElementById('riskPreview');
    const box     = document.getElementById('riskBox');
    const label   = document.getElementById('riskLabel');
    const reason  = document.getElementById('riskReason');

    if (fluency === null || comp === null) {
        preview.style.display = 'none';
        return;
    }

    preview.style.display = 'block';

    let risk, color, reasonText = [];

    if (fluency < 70 || comp < 65) {
        risk  = '⚠️ Below Expected Literacy Standard';
        color = '#C8102E';
        if (fluency < 70) reasonText.push('Fluency below 70');
        if (comp < 65)    reasonText.push('Comprehension below 65');
    } else if (fluency < 85 || comp < 80) {
        risk  = '📈 Approaching Expected Literacy Standard';
        color = '#b8860b';
        if (fluency < 85) reasonText.push('Fluency below 85');
        if (comp < 80)    reasonText.push('Comprehension below 80');
    } else {
        risk       = '✅ Meeting or Exceeding Literacy Standard';
        color      = '#28a745';
        reasonText = ['Student meets all benchmarks.'];
    }

    box.style.borderLeftColor = color;
    box.style.background      = color + '12';
    label.style.color         = color;
    label.textContent         = risk;
    reason.textContent        = reasonText.join(' · ');
}
</script>
@endpush