@extends('layouts.app')
@section('content')

<div class="content-header mb-3">
    <div class="container-fluid">
        <h1 class="m-0 fw-semibold">Edit Event</h1>
        <small class="text-muted">Perbarui data event</small>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h3 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Form Edit Event
                </h3>
            </div>

            <form action="{{ route('lapangan.event.update', $event->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">

                    {{-- ERROR --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">

                        {{-- KOLOM KIRI --}}
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Judul Event</label>
                                <input type="text"
                                       name="judul"
                                       class="form-control"
                                       value="{{ old('judul', $event->judul) }}"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tanggal Mulai</label>
                                <input type="date"
                                       name="tanggal_mulai"
                                       class="form-control"
                                       value="{{ old('tanggal_mulai', is_string($event->tanggal_mulai) ? $event->tanggal_mulai : $event->tanggal_mulai->format('Y-m-d')) }}"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tanggal Selesai</label>
                                <input type="date"
                                       name="tanggal_selesai"
                                       class="form-control"
                                       value="{{ old('tanggal_selesai', is_string($event->tanggal_selesai) ? $event->tanggal_selesai : $event->tanggal_selesai->format('Y-m-d')) }}"
                                       required>
                            </div>

                        </div>

                        {{-- KOLOM KANAN --}}
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="deskripsi"
                                          class="form-control"
                                          rows="4"
                                          required>{{ old('deskripsi', $event->deskripsi) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status Event</label>
                                <select name="status" class="form-control" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="akan_datang" {{ old('status', $event->status) === 'akan_datang' ? 'selected' : '' }}>Akan Datang</option>
                                    <option value="berlangsung" {{ old('status', $event->status) === 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                    <option value="selesai" {{ old('status', $event->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gambar Event</label>
                        <input type="file"
                               name="gambar"
                               class="form-control"
                               accept="image/*"
                               onchange="previewImage(event)">

                        {{-- GAMBAR LAMA --}}
                        <div class="mt-3 text-center">
                            <img id="preview"
                                 src="{{ asset('storage/' . $event->gambar) }}"
                                 class="img-fluid rounded border"
                                 style="max-height: 180px;">
                        </div>

                        <small class="text-muted">
                            Kosongkan jika tidak ingin mengganti gambar
                        </small>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="{{ route('lapangan.event') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Event
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- PREVIEW SCRIPT --}}
<script>
function previewImage(event) {
    const img = document.getElementById('preview');
    img.src = URL.createObjectURL(event.target.files[0]);
}
</script>

@endsection
