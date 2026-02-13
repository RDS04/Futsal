@extends('layouts.app')
@section('content')

    <div class="content-header mb-3">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="m-0 fw-semibold">Daftar Lapangan Futsal</h1>
                    <small class="text-muted">Kelola semua data lapangan</small>
                </div>
                <a href="{{ route('inputLapangan.Lapangan') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Lapangan
                </a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            {{-- SUCCESS MESSAGE --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ERROR MESSAGE --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Tabel Data Lapangan
                    </h3>
                </div>

                <div class="card-body table-responsive">
                    @if ($lapangan->count() > 0)
                        <table class="table table-hover table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 15%">Nama Lapangan</th>
                                    <th style="width: 12%">Jenis</th>
                                    <th style="width: 10%">Harga/Jam</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="width: 30%">Deskripsi</th>
                                    <th style="width: 18%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lapangan as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->namaLapangan }}</strong>
                                        </td>
                                        <td>{{ $item->jenisLapangan }}</td>
                                        <td>
                                            <span class="badge bg-info">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            @if ($item->status === 'tersedia')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> Tersedia
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle"></i> Tidak Tersedia
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($item->deskripsi, 40) }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                {{-- VIEW BUTTON --}}
                                                <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $item->id }}" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                {{-- EDIT BUTTON --}}
                                                <a href="{{ route('lapangan.edit', $item->id) }}" class="btn btn-warning"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- DELETE BUTTON --}}
                                                <form action="{{ route('lapangan.destroy', $item->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus lapangan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- MODAL VIEW DETAIL --}}
                                    <div class="modal fade" id="viewModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-futbol me-2"></i>Detail Lapangan
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6 class="fw-semibold text-primary mb-3">Informasi Lapangan</h6>
                                                            <dl class="row">
                                                                <dt class="col-sm-5">Nama:</dt>
                                                                <dd class="col-sm-7">
                                                                    <strong>{{ $item->namaLapangan }}</strong>
                                                                </dd>

                                                                <dt class="col-sm-5">Jenis:</dt>
                                                                <dd class="col-sm-7">{{ $item->jenisLapangan }}</dd>

                                                                <dt class="col-sm-5">Harga:</dt>
                                                                <dd class="col-sm-7">
                                                                    Rp {{ number_format($item->harga, 0, ',', '.') }}/jam
                                                                </dd>

                                                                <dt class="col-sm-5">Status:</dt>
                                                                <dd class="col-sm-7">
                                                                    @if ($item->status === 'tersedia')
                                                                        <span class="badge bg-success">Tersedia</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Tidak Tersedia</span>
                                                                    @endif
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="fw-semibold text-primary mb-3">Gambar</h6>
                                                            <img src="{{ asset('storage/' . $item->gambar) }}"
                                                                alt="{{ $item->namaLapangan }}" class="img-fluid rounded border"
                                                                style="max-height: 250px; width: 100%; object-fit: cover;">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-12">
                                                            <h6 class="fw-semibold text-primary mb-2">Deskripsi</h6>
                                                            <p class="text-muted">{{ $item->deskripsi }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3 mb-3">Tidak ada data lapangan</p>
                            <a href="{{ route('inputLapangan.Lapangan') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Lapangan Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

@endsection