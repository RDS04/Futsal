@extends('layouts.app')
@section('content')

<div class="content-header mb-3">
    <div class="container-fluid">
        <h1 class="m-0 fw-semibold">Edit Lapangan Futsal</h1>
        <small class="text-muted">Perbarui data lapangan</small>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h3 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Form Edit Lapangan
                </h3>
            </div>

            <form action="{{ route('lapangan.update', $lapangan->id) }}"
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
                                <label class="form-label fw-semibold">Nama Lapangan</label>
                                <input type="text"
                                       name="namaLapangan"
                                       class="form-control"
                                       value="{{ old('namaLapangan', $lapangan->namaLapangan) }}"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis Lapangan</label>
                                <input type="text"
                                       name="jenisLapangan"
                                       class="form-control"
                                       value="{{ old('jenisLapangan', $lapangan->jenisLapangan) }}"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Harga per Jam</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number"
                                           name="harga"
                                           class="form-control"
                                           value="{{ old('harga', $lapangan->harga) }}"
                                           required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status Lapangan</label>
                                <select name="status" class="form-select">
                                    <option value="tersedia"
                                        {{ $lapangan->status === 'tersedia' ? 'selected' : '' }}>
                                        Tersedia
                                    </option>
                                    <option value="tidak tersedia"
                                        {{ $lapangan->status === 'tidak tersedia' ? 'selected' : '' }}>
                                        Tidak Tersedia
                                    </option>
                                </select>
                            </div>

                        </div>

                        {{-- KOLOM KANAN --}}
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="deskripsi"
                                          class="form-control"
                                          rows="4">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Gambar Lapangan</label>
                                <input type="file"
                                       name="gambar"
                                       class="form-control"
                                       accept="image/*"
                                       onchange="previewImage(event)">

                                {{-- GAMBAR LAMA --}}
                                <div class="mt-3 text-center">
                                    <img id="preview"
                                         src="{{ asset('storage/' . $lapangan->gambar) }}"
                                         class="img-fluid rounded border"
                                         style="max-height: 180px;">
                                </div>

                                <small class="text-muted">
                                    Kosongkan jika tidak ingin mengganti gambar
                                </small>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="{{ route('inputLapangan.Lapangan') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Data
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
