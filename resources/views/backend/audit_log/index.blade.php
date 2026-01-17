@extends('layout.appv2')
@section('title', 'Audit Log')
@section('audit-log', 'active')

@section('content')
    <div class="content">
        <div class="page-title">
            <h3>Audit Log</h3>
            <p>Riwayat aktivitas pengguna sistem</p>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-modern">
                    <div class="card-header">
                        <h4 class="section-title-modern">Aktivitas Sistem</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('audit_log.index') }}" method="GET" class="mb-4">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-3">
                                    <label for="start_date" class="form-label fw-bold small">Dari Tanggal</label>
                                    <input type="text" class="form-control form-control-sm datepicker" id="start_date"
                                        name="start_date" placeholder="Pilih Tanggal" value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date" class="form-label fw-bold small">Sampai Tanggal</label>
                                    <input type="text" class="form-control form-control-sm datepicker" id="end_date"
                                        name="end_date" placeholder="Pilih Tanggal" value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-filter me-1"></i>
                                        Filter</button>
                                    <a href="{{ route('audit_log.index') }}" class="btn btn-sm btn-secondary"><i
                                            class="fas fa-sync me-1"></i> Reset</a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-modern table-bordered table-hover w-100" id="auditTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>User / Pengguna</th>
                                        <th>Aktivitas</th>
                                        <th>Entitas / Data</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($activities as $activity)
                                        <tr>
                                            <td style="white-space: nowrap;">
                                                {{ $activity->created_at->format('d M Y H:i:s') }}
                                                <small
                                                    class="d-block text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                @if ($activity->causer)
                                                    <span class="fw-bold">{{ $activity->causer->name }}</span>
                                                    <br><small class="text-muted">{{ $activity->causer->email }}</small>
                                                @else
                                                    <span class="badge bg-secondary">System / Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $badgeClass = match ($activity->event) {
                                                        'created' => 'success',
                                                        'updated' => 'warning',
                                                        'deleted' => 'danger',
                                                        default => 'info',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $badgeClass }}">{{ ucfirst($activity->event) }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ class_basename($activity->subject_type) }}</span>
                                                <br>
                                                <small class="text-muted">ID: {{ $activity->subject_id }}</small>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal{{ $activity->id }}">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>

                                                <!-- Detail Modal -->
                                                <div class="modal fade" id="detailModal{{ $activity->id }}" tabindex="-1"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Detail Aktivitas
                                                                    #{{ $activity->id }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <strong>User:</strong>
                                                                        {{ $activity->causer->name ?? 'System' }}<br>
                                                                        <strong>Event:</strong> <span
                                                                            class="badge bg-{{ $badgeClass }}">{{ ucfirst($activity->event) }}</span><br>
                                                                        <strong>Time:</strong>
                                                                        {{ $activity->created_at->format('d M Y H:i:s') }}
                                                                    </div>
                                                                    <div class="col-md-6 text-end">
                                                                        <strong>Subject:</strong>
                                                                        {{ $activity->subject_type }} (ID:
                                                                        {{ $activity->subject_id }})
                                                                    </div>
                                                                </div>

                                                                <h6>Perubahan Data:</h6>
                                                                @if ($activity->properties->has('attributes') || $activity->properties->has('old'))
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered table-sm">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Field</th>
                                                                                    @if ($activity->event === 'updated')
                                                                                        <th>Nilai Lama (Old)</th>
                                                                                        <th>Nilai Baru (New)</th>
                                                                                    @elseif($activity->event === 'created')
                                                                                        <th>Nilai (Attributes)</th>
                                                                                    @elseif($activity->event === 'deleted')
                                                                                        <th>Nilai Lama (Attributes)</th>
                                                                                    @else
                                                                                        <th>Data</th>
                                                                                    @endif
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @php
                                                                                    $attributes =
                                                                                        $activity->properties[
                                                                                            'attributes'
                                                                                        ] ?? [];
                                                                                    $old =
                                                                                        $activity->properties['old'] ??
                                                                                        [];
                                                                                    $allKeys = array_unique(
                                                                                        array_merge(
                                                                                            array_keys($attributes),
                                                                                            array_keys($old),
                                                                                        ),
                                                                                    );
                                                                                @endphp

                                                                                @foreach ($allKeys as $key)
                                                                                    @if ($key === 'created_at' || $key === 'updated_at' || $key === 'deleted_at')
                                                                                        @continue
                                                                                    @endif
                                                                                    <tr>
                                                                                        <td class="fw-bold">
                                                                                            {{ $key }}</td>
                                                                                        @if ($activity->event === 'updated')
                                                                                            <td class="text-danger">
                                                                                                @if (is_array($old[$key] ?? null))
                                                                                                    {{ json_encode($old[$key]) }}
                                                                                                @else
                                                                                                    {{ $old[$key] ?? '-' }}
                                                                                                @endif
                                                                                            </td>
                                                                                            <td class="text-success">
                                                                                                @if (is_array($attributes[$key] ?? null))
                                                                                                    {{ json_encode($attributes[$key]) }}
                                                                                                @else
                                                                                                    {{ $attributes[$key] ?? '-' }}
                                                                                                @endif
                                                                                            </td>
                                                                                        @elseif($activity->event === 'created')
                                                                                            <td class="text-success">
                                                                                                @if (is_array($attributes[$key] ?? null))
                                                                                                    {{ json_encode($attributes[$key]) }}
                                                                                                @else
                                                                                                    {{ $attributes[$key] ?? '-' }}
                                                                                                @endif
                                                                                            </td>
                                                                                        @elseif($activity->event === 'deleted')
                                                                                            <td class="text-danger">
                                                                                                @php
                                                                                                    $val =
                                                                                                        $attributes[
                                                                                                            $key
                                                                                                        ] ??
                                                                                                        ($old[$key] ??
                                                                                                            '-');
                                                                                                @endphp
                                                                                                @if (is_array($val))
                                                                                                    {{ json_encode($val) }}
                                                                                                @else
                                                                                                    {{ $val }}
                                                                                                @endif
                                                                                            </td>
                                                                                        @else
                                                                                            <td>{{ json_encode($activity->properties[$key] ?? '') }}
                                                                                            </td>
                                                                                        @endif
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                @else
                                                                    <div class="alert alert-secondary">
                                                                        Tidak ada detail perubahan properti yang tercatat
                                                                        (atau data kosong)
                                                                        .
                                                                        <br>
                                                                        <small>Raw:
                                                                            {{ json_encode($activity->properties) }}</small>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada aktivitas yang tercatat.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $activities->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
