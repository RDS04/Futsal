@extends('layouts.app')
@section('content')

<div class="content-header mb-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="m-0 fw-semibold">Tambah Lapangan Futsal</h1>
                <small class="text-muted">Kelola data lapangan per region</small>
            </div>
            <a href="{{ route('lapangan.daftar.Lapangan') }}" class="btn btn-info">
                <i class="fas fa-list"></i> Lihat Daftar Lapangan
            </a>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-futbol me-2"></i>Form Data Lapangan
                </h3>
            </div>

            <form action="{{ route('lapangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

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
                                <input type="text" name="namaLapangan" class="form-control"
                                    placeholder="Contoh: Lapangan A" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis Lapangan</label>
                                <input type="text" name="jenisLapangan" class="form-control"
                                    placeholder="Vinyl / Rumput Sintetis / Parquet" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Harga per Jam</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga" class="form-control"
                                        placeholder="150000" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status Lapangan</label>
                                <select name="status" class="form-select" required>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="tidak tersedia">Tidak Tersedia</option>
                                </select>
                            </div>

                        </div>

                        {{-- KOLOM KANAN --}}
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4"
                                    placeholder="Keterangan tambahan lapangan..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Upload Gambar</label>
                                <input type="file" name="gambar" class="form-control"
                                    accept="image/*" onchange="previewImage(event)" required>

                                <div class="mt-3 text-center">
                                    <img id="preview"
                                        class="img-fluid rounded border d-none"
                                        style="max-height: 180px;">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="{{ route('inputLapangan.Lapangan') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Data
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
        img.classList.remove('d-none');
    }
</script>

@endsection
