@extends('layouts.app')
@section('content')

<div class="content-header mb-3">
    <div class="container-fluid">
        <h1 class="m-0 fw-semibold">Kelola Event</h1>
        <small class="text-muted">Tambah, edit, atau hapus event futsal</small>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        {{-- CARD FORM INPUT EVENT --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-plus me-2"></i>Tambah Event Baru
                </h3>
            </div>

            <form action="{{ route('lapangan.event.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    <div class="row">

                        {{-- KOLOM KIRI --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Judul Event</label>
                                <input type="text" name="judul" class="form-control"
                                       placeholder="Contoh: Liga Futsal Cup 2024" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status Event</label>
                                <select name="status" class="form-control" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="akan_datang">Akan Datang</option>
                                    <option value="berlangsung">Berlangsung</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                        </div>

                        {{-- KOLOM KANAN --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi Event</label>
                                <textarea name="deskripsi" rows="5" class="form-control"
                                          placeholder="Deskripsi lengkap event..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Upload Poster Event</label>
                                <input type="file" name="gambar" class="form-control"
                                       accept="image/*" onchange="previewEvent(event)" required>

                                <div class="mt-3 text-center">
                                    <img id="preview" class="img-fluid rounded d-none"
                                         style="max-height:200px;">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Event
                    </button>
                </div>

            </form>
        </div>

        {{-- CARD LIST EVENT --}}
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Daftar Event
                </h3>
            </div>

            <div class="card-body">
                @if($events->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Judul Event</th>
                                    <th width="15%">Tanggal Mulai</th>
                                    <th width="15%">Tanggal Selesai</th>
                                    <th width="20%">Deskripsi</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $key => $event)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <strong>{{ $event->judul }}</strong>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($event->deskripsi, 50) }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $statusBadge = match($event->status) {
                                                    'akan_datang' => 'badge bg-info',
                                                    'berlangsung' => 'badge bg-warning',
                                                    'selesai' => 'badge bg-success',
                                                    default => 'badge bg-secondary'
                                                };
                                                $statusLabel = match($event->status) {
                                                    'akan_datang' => 'Akan Datang',
                                                    'berlangsung' => 'Berlangsung',
                                                    'selesai' => 'Selesai',
                                                    default => 'Unknown'
                                                };
                                            @endphp
                                            <span class="{{ $statusBadge }}">{{ $statusLabel }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('lapangan.event.edit', $event->id) }}"
                                                   class="btn btn-warning"
                                                   title="Edit event">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('lapangan.event.destroy', $event->id) }}"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Yakin ingin menghapus event ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                            title="Hapus event">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Belum ada event. Silahkan tambahkan event terlebih dahulu.
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
function previewEvent(e) {
    const img = document.getElementById('preview');
    img.src = URL.createObjectURL(e.target.files[0]);
    img.classList.remove('d-none');
}
</script>

@endsection
