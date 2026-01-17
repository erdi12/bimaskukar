@extends('layout.appv2')

@section('title', 'Data Pegawai')

@section('content')
    <div class="content">
        <div class="page-title mb-4">
            <h3>Daftar Pegawai / Staff</h3>
            <p class="text-muted">Kelola data pegawai, foto profil, dan sambutan kepala seksi.</p>
        </div>

        <div class="card card-modern">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="section-title-modern mb-0">Data Pegawai</h5>
                <a href="{{ route('pegawai.create') }}" class="btn btn-primary btn-sm rounded-pill">
                    <i class="fas fa-plus me-1"></i> Tambah Pegawai
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-modern table-bordered table-striped" id="tablePegawai">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="10%">Foto</th>
                                <th>Nama & Jabatan</th>
                                <th>NIP</th>
                                <th width="10%" class="text-center">Kepala?</th>
                                <th>Urutan</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pegawais as $pegawai)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($pegawai->foto)
                                            <img src="{{ asset('uploads/pegawai/' . $pegawai->foto) }}" alt="Foto"
                                                class="img-thumbnail rounded-circle"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <span class="text-muted text-xs">No img</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="d-block">{{ $pegawai->nama }}</strong>
                                        <small class="text-muted">{{ $pegawai->jabatan }}</small>
                                    </td>
                                    <td>{{ $pegawai->nip ?? '-' }}</td>
                                    <td class="text-center">
                                        @if ($pegawai->is_kepala)
                                            <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i>
                                                Ya</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill text-white-50">Tidak</span>
                                        @endif
                                    </td>
                                    <td>{{ $pegawai->urutan }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('pegawai.edit', $pegawai->id) }}"
                                            class="btn btn-warning btn-sm btn-icon"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm btn-icon"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tablePegawai').DataTable();
        });
    </script>
@endpush
