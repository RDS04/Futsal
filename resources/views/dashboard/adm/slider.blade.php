@extends('layouts.app')
@section('content')

<div class="content-header mb-3">
    <div class="container-fluid">
        <h1 class="m-0 fw-semibold">Kelola Slider</h1>
        <small class="text-muted">Tambah, edit, atau hapus gambar slider</small>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        {{-- CARD FORM UPLOAD --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-images me-2"></i>Upload Gambar Slider Baru
                </h3>
            </div>

            <div class="card-body">
                <form action="{{ route('lapangan.slider.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">Upload Gambar Slider</label>

                        <div id="uploadBox"
                            class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-blue-500 transition">

                            <input type="file" name="gambar" id="imageInput" accept="image/*" hidden required>

                            <div id="uploadPlaceholder">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-3" width="48" height="48"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-width="2"
                                        d="M7 16V12M12 16V8M17 16V10M3 20h18" />
                                </svg>
                                <p class="text-gray-600 text-sm">
                                    Klik untuk upload atau drag & drop gambar
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    JPG, PNG (Max 2MB)
                                </p>
                            </div>

                            <!-- PREVIEW -->
                            <div id="previewWrapper" class="hidden">
                                <img id="previewImage"
                                    class="mx-auto rounded-lg shadow max-h-48 object-cover">
                                <button type="button"
                                    onclick="removeImage()"
                                    class="mt-3 px-4 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600">
                                    Hapus Gambar
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-full mt-4">Simpan Slider</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- CARD LIST SLIDER --}}
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Daftar Slider
                </h3>
            </div>

            <div class="card-body">
                @if($slider->count() > 0)
                    <div class="row">
                        @foreach($slider as $s)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <img src="{{ asset('storage/' . $s->gambar) }}"
                                         class="card-img-top"
                                         alt="Slider"
                                         style="height: 200px; object-fit: cover;">
                                    
                                    <div class="card-body">
                                        <p class="card-text text-muted text-sm">
                                            <small>ID: {{ $s->id }}</small>
                                        </p>
                                    </div>

                                    <div class="card-footer bg-light d-flex gap-2">
                                        <a href="{{ route('lapangan.slider.edit', $s->id) }}"
                                           class="btn btn-sm btn-warning flex-grow-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form method="POST"
                                              action="{{ route('lapangan.slider.destroy', $s->id) }}"
                                              style="display: inline;"
                                              onsubmit="return confirm('Yakin ingin menghapus slider ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger w-100">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Belum ada slider. Silahkan upload gambar terlebih dahulu.
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
    const uploadBox = document.getElementById('uploadBox');
    const imageInput = document.getElementById('imageInput');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const previewWrapper = document.getElementById('previewWrapper');
    const previewImage = document.getElementById('previewImage');   
    uploadBox.addEventListener('click', () => {
        imageInput.click();
    }); 
    imageInput.addEventListener('change', () => {
        if(imageInput.files.length > 0) {
            const file = imageInput.files[0];
            const reader = new FileReader();
            reader.onloadend = () => {
                previewImage.src = reader.result;
                uploadPlaceholder.classList.add('hidden');
                previewWrapper.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            uploadPlaceholder.classList.remove('hidden');
            previewWrapper.classList.add('hidden');
        }
    });
    function removeImage() {
        imageInput.value = '';
        previewImage.src = '';
        uploadPlaceholder.classList.remove('hidden');
        previewWrapper.classList.add('hidden');
    }
</script>

@endsection