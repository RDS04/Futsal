@extends('layouts.app')
@section('content')

<div class="content-header mb-3">
    <div class="container-fluid">
        <h1 class="m-0 fw-semibold">Edit Slider</h1>
        <small class="text-muted">Perbarui gambar slider</small>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h3 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Form Edit Slider
                </h3>
            </div>

            <form action="{{ route('lapangan.slider.update', $slider->id) }}"
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

                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">Edit Gambar Slider</label>

                        <div id="uploadBox"
                            class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-blue-500 transition">

                            <input type="file" name="gambar" id="imageInput" accept="image/*" hidden>

                            <div id="uploadPlaceholder">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-3" width="48" height="48"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-width="2"
                                        d="M7 16V12M12 16V8M17 16V10M3 20h18" />
                                </svg>
                                <p class="text-gray-600 text-sm">
                                    Klik untuk upload atau drag & drop gambar baru
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    JPG, PNG (Max 2MB)
                                </p>
                            </div>

                            <!-- PREVIEW BARU -->
                            <div id="previewWrapper" class="hidden">
                                <img id="previewImage"
                                    class="mx-auto rounded-lg shadow max-h-48 object-cover">
                                <button type="button"
                                    onclick="removeImage()"
                                    class="mt-3 px-4 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600">
                                    Hapus Gambar Baru
                                </button>
                            </div>
                        </div>

                        <!-- PREVIEW GAMBAR LAMA -->
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-2">Gambar Saat Ini:</p>
                            <img src="{{ asset('storage/' . $slider->gambar) }}"
                                alt="Slider"
                                class="rounded-lg shadow max-h-48 object-cover">
                            <small class="text-muted d-block mt-2">
                                Upload gambar baru untuk mengganti, atau biarkan kosong untuk tetap menggunakan gambar lama
                            </small>
                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="{{ route('lapangan.slider') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Slider
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- UPLOAD SCRIPT --}}
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
        if (imageInput.files.length > 0) {
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
