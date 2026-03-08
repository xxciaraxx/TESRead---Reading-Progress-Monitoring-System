@extends('layouts.teacher')

@section('title', 'Assessments')
@section('page-icon', '📊')
@section('page-heading', 'Assessments')

@push('styles')
<style>
html, body { overflow: hidden !important; height: 100% !important; }
.main-area  { height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
.page-content { flex: 1; overflow: hidden !important; display: flex; flex-direction: column; padding-bottom: 0 !important; }

.assessments-layout { display: flex; flex-direction: column; height: 100%; }
.assessments-table-card { flex: 1; min-height: 0; display: flex; flex-direction: column; overflow: hidden; }
.assessments-table-card .table-wrap { flex: 1; overflow-y: auto; overflow-x: auto; }
.assessments-table-card .table-wrap::-webkit-scrollbar { width: 5px; height: 5px; }
.assessments-table-card .table-wrap::-webkit-scrollbar-thumb { background: #d1d9f0; border-radius: 99px; }
</style>
@endpush

@section('content')

<div class="assessments-layout">
<div class="page-header">
    <div><h1>Reading Assessments</h1></div>
    <a href="{{ route('teacher.assessments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Assessment
    </a>
</div>

<div class="card assessments-table-card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Date</th>
                    <th>Fluency</th>
                    <th>Comprehension</th>
                    <th>Sessions/wk</th>
                    <th>Risk Level</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($assessments as $assessment)
                <tr>
                    <td>
                        <div class="user-row">
                            <img src="{{ $assessment->student?->profilePhotoUrl() }}"
                                 class="user-avatar-sm" alt="">
                            <span class="user-name">{{ $assessment->student?->fullName() ?? '—' }}</span>
                        </div>
                    </td>
                    <td class="text-muted text-small">{{ $assessment->assessed_on?->format('M d, Y') }}</td>
                    <td>
                        @php $f = $assessment->fluency_score; @endphp
                        <span class="{{ $f >= 85 ? 'risk-meeting' : ($f >= 70 ? 'risk-approaching' : 'risk-below') }}">
                            {{ $f }}%
                        </span>
                    </td>
                    <td>
                        @php $c = $assessment->comprehension_score; @endphp
                        <span class="{{ $c >= 80 ? 'risk-meeting' : ($c >= 65 ? 'risk-approaching' : 'risk-below') }}">
                            {{ $c }}%
                        </span>
                    </td>
                    <td class="text-muted">{{ $assessment->reading_sessions_per_week }}/wk</td>
                    <td>
                        @if($assessment->risk_level)
                            @if(str_contains($assessment->risk_level,'Below'))
                                <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Below</span>
                            @elseif(str_contains($assessment->risk_level,'Approaching'))
                                <span class="badge badge-warning"><i class="fas fa-chart-line"></i> Approaching</span>
                            @else
                                <span class="badge badge-success"><i class="fas fa-star"></i> Meeting</span>
                            @endif
                        @else
                            <span class="badge badge-secondary">Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('teacher.assessments.show', $assessment) }}" class="btn-icon" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">📊</div>
                                <h3>No assessments yet</h3>
                                <p>Start recording your students' reading progress.</p>
                                <a href="{{ route('teacher.assessments.create') }}" class="btn btn-primary" style="margin-top:12px;">
                                    <i class="fas fa-plus"></i> Record First Assessment
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($assessments->hasPages())
        <div class="pagination">{{ $assessments->withQueryString()->links() }}</div>
    @endif
</div>

</div>
@endsection