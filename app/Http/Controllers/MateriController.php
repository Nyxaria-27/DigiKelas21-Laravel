<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Materi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewMateriNotification;
use Illuminate\Support\Facades\Notification;

class MateriController extends Controller
{
    // show form create for a specific kelas: route kelas.materi.create
    public function create(Kelas $kelas)
    {
        // hanya guru pemilik kelas boleh
        if (auth()->id() !== $kelas->guru_id) abort(403);
        return view('materi.create', compact('kelas'));
    }

    public function store(Request $request, Kelas $kelas)
    {
        if (auth()->id() !== $kelas->guru_id) abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,ppt,pptx,doc,docx,zip,rar|max:15360' // 15MB
        ]);

        $path = null;
        $tipe = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('materi', 'public'); // storage/app/public/materi
            $tipe = $file->getClientOriginalExtension();
        }

        $materi = Materi::create([
            'kelas_id' => $kelas->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tipe_file' => $tipe,
            'path_file' => $path,
        ]);

        Notification::send($kelas->siswa()->get(), new NewMateriNotification($materi));

        return redirect()->route('kelas.show', $kelas)->with('success', 'Materi berhasil diupload');
    }

    public function show(Materi $materi)
    {
        $user = auth()->user();
        $kelas = $materi->kelas;

        // check access: guru pemilik atau siswa tergabung
        if ($user->role === 'Guru' && $kelas->guru_id !== $user->id) abort(403);
        if ($user->role === 'Siswa' && ! $user->kelas->contains($kelas->id)) abort(403);

        return view('materi.show', compact('materi'));
    }

    public function destroy(Materi $materi)
    {
        $kelas = $materi->kelas;
        if (auth()->id() !== $kelas->guru_id) abort(403);

        // hapus file jika ada
        if ($materi->path_file && Storage::disk('public')->exists($materi->path_file)) {
            Storage::disk('public')->delete($materi->path_file);
        }
        $materi->delete();
        return back()->with('success', 'Materi dihapus');
    }

    // (opsional) edit/update jika butuh
}
