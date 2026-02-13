<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Event;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class InputLapanganController extends Controller
{
    /**
     * Get admin yang sedang login
     */
    protected function getAdmin()
    {
        return Auth::guard('admin')->user();
    }

    /**
     * Get region admin yang sedang login
     */
    protected function getAdminRegion()
    {
        return $this->getAdmin()->region;
    }

    public function inputLapangan()
    {
        $admin = $this->getAdmin();
        $lapangan = Lapangan::byRegion($admin->region)->get();
        return view("dashboard.adm.inputLapangan", compact('lapangan'));
    }

    public function daftarLapangan()
    {
        $admin = $this->getAdmin();
        $lapangan = Lapangan::byRegion($admin->region)->get();
        return view("dashboard.adm.daftarLapangan", compact('lapangan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'namaLapangan' => 'required|string',
            'jenisLapangan' => 'required|string',
            'harga' => 'required|numeric',
            'status' => 'required|string',
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // =====================
        // UPLOAD GAMBAR
        // =====================
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('lapangan_images', 'public');
            $validated['gambar'] = $path;
        }

        // Tambah region dan admin_id otomatis
        $admin = $this->getAdmin();
        $validated['region'] = $admin->region;
        $validated['admin_id'] = $admin->id;

        Lapangan::create($validated);
        return redirect()
            ->route('inputLapangan.Lapangan')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function viewLapangan()
    {
        $admin = $this->getAdmin();
        $lapangan = Lapangan::byRegion($admin->region)->get();
        return view("dashboard.adm.daftarLapangan", compact('lapangan'));
    }

    public function editLapangan($id)
    {
        $admin = $this->getAdmin();
        // Cek apakah lapangan milik admin yang login
        $lapangan = Lapangan::byRegion($admin->region)->findOrFail($id);
        return view("dashboard.adm.edit", compact('lapangan'));
    }
    public function destroy($id)
    {
        $admin = $this->getAdmin();
        // Cek apakah lapangan milik admin yang login
        $lapangan = Lapangan::byRegion($admin->region)->findOrFail($id);
        
        // Hapus gambar jika ada
        if ($lapangan->gambar) {
            Storage::disk('public')->delete($lapangan->gambar);
        }
        
        $lapangan->delete();

        return redirect()
            ->route('lapangan.daftar.Lapangan')
            ->with('success', 'Lapangan berhasil dihapus.');
    }
    public function update(Request $request, $id)
    {
        $admin = $this->getAdmin();
        // Cek apakah lapangan milik admin yang login
        $lapangan = Lapangan::byRegion($admin->region)->findOrFail($id);
        
        $validated = $request->validate([
            'namaLapangan' => 'required|string',
            'jenisLapangan' => 'required|string',
            'harga' => 'required|numeric',
            'status' => 'required|string',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle gambar upload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($lapangan->gambar) {
                Storage::disk('public')->delete($lapangan->gambar);
            }
            
            $file = $request->file('gambar');
            $path = $file->store('lapangan_images', 'public');
            $validated['gambar'] = $path;
        }

        $lapangan->update($validated);
        return redirect()
            ->route('lapangan.daftar.Lapangan')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function slider()
    {
        $admin = $this->getAdmin();
        $slider = Slider::byRegion($admin->region)->get();
        return view("dashboard.adm.slider", compact('slider'));
    }

    public function storeSlider(Request $request)
    {
        $validated = $request->validate([
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('lapangan_images', 'public');
            $validated['gambar'] = $path;
        }
        
        // Tambah region dan admin_id otomatis
        $admin = $this->getAdmin();
        $validated['region'] = $admin->region;
        $validated['admin_id'] = $admin->id;
        
        Slider::create($validated);
        return redirect()
            ->route('lapangan.slider')
            ->with('success', 'Slider berhasil ditambahkan.');
    }

    public function editSlider($id)
    {
        $admin = $this->getAdmin();
        $slider = Slider::byRegion($admin->region)->findOrFail($id);
        return view("dashboard.adm.editSlider", compact('slider'));
    }

    public function updateSlider(Request $request, $id)
    {
        $admin = $this->getAdmin();
        $slider = Slider::byRegion($admin->region)->findOrFail($id);
        
        $validated = $request->validate([
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($slider->gambar) {
                Storage::disk('public')->delete($slider->gambar);
            }
            
            $file = $request->file('gambar');
            $path = $file->store('lapangan_images', 'public');
            $validated['gambar'] = $path;
        }

        $slider->update($validated);
        return redirect()
            ->route('lapangan.slider')
            ->with('success', 'Slider berhasil diperbarui.');
    }

    public function destroySlider($id)
    {
        $admin = $this->getAdmin();
        $slider = Slider::byRegion($admin->region)->findOrFail($id);
        
        // Hapus gambar dari storage
        if ($slider->gambar) {
            Storage::disk('public')->delete($slider->gambar);
        }
        
        $slider->delete();
        return redirect()
            ->route('lapangan.slider')
            ->with('success', 'Slider berhasil dihapus.');
    }

    public function event()
    {
        $admin = $this->getAdmin();
        $events = Event::byRegion($admin->region)->get();
        return view("dashboard.adm.event", compact('events'));
    }
    
    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string',
            'tanggal_mulai' => 'required|date_format:Y-m-d',
            'tanggal_selesai' => 'required|date_format:Y-m-d|after_or_equal:tanggal_mulai',
            'status' => 'required|in:akan_datang,berlangsung,selesai',
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'tanggal_mulai.date_format' => 'Format tanggal mulai harus YYYY-MM-DD',
            'tanggal_selesai.date_format' => 'Format tanggal selesai harus YYYY-MM-DD',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau lebih besar dari tanggal mulai',
            'status.in' => 'Status harus salah satu dari: Akan Datang, Berlangsung, atau Selesai',
        ]);
        
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('event', 'public');
            $validated['gambar'] = $path;
        }
        
        // Tambah region dan admin_id otomatis
        $admin = $this->getAdmin();
        $validated['region'] = $admin->region;
        $validated['admin_id'] = $admin->id;
        // Jika status tidak diset, gunakan default 'akan_datang'
        if (empty($validated['status'])) {
            $validated['status'] = 'akan_datang';
        }
        
        Event::create($validated);
        return redirect()
            ->route('lapangan.event')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    public function editEvent($id)
    {
        $admin = $this->getAdmin();
        $event = Event::byRegion($admin->region)->findOrFail($id);
        return view("dashboard.adm.editEvent", compact('event'));
    }

    public function updateEvent(Request $request, $id)
    {
        $admin = $this->getAdmin();
        $event = Event::byRegion($admin->region)->findOrFail($id);
        
        $validated = $request->validate([
            'judul' => 'required|string',
            'tanggal_mulai' => 'required|date_format:Y-m-d',
            'tanggal_selesai' => 'required|date_format:Y-m-d|after_or_equal:tanggal_mulai',
            'status' => 'required|in:akan_datang,berlangsung,selesai',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'tanggal_mulai.date_format' => 'Format tanggal mulai harus YYYY-MM-DD',
            'tanggal_selesai.date_format' => 'Format tanggal selesai harus YYYY-MM-DD',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau lebih besar dari tanggal mulai',
            'status.in' => 'Status harus salah satu dari: Akan Datang, Berlangsung, atau Selesai',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($event->gambar) {
                Storage::disk('public')->delete($event->gambar);
            }
            
            $file = $request->file('gambar');
            $path = $file->store('event', 'public');
            $validated['gambar'] = $path;
        }

        $event->update($validated);
        return redirect()
            ->route('lapangan.event')
            ->with('success', 'Event berhasil diperbarui.');
    }

    public function destroyEvent($id)
    {
        $admin = $this->getAdmin();
        $event = Event::byRegion($admin->region)->findOrFail($id);
        
        // Hapus gambar dari storage
        if ($event->gambar) {
            Storage::disk('public')->delete($event->gambar);
        }
        
        $event->delete();
        return redirect()
            ->route('lapangan.event')
            ->with('success', 'Event berhasil dihapus.');
    }
}
