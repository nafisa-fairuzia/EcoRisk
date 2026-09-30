<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WargaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $myReports = WasteReport::where('user_id', $user->id)
            ->with('petugas')
            ->latest()
            ->get();

        $totalMyReports = $myReports->count();
        $completedCount = $myReports->where('status', 'selesai')->count();
        $inProgressCount = $myReports->where('status', 'proses')->count();
        $pendingCount = $myReports->where('status', 'menunggu')->count();

        return view('warga.dashboard', compact(
            'myReports',
            'totalMyReports',
            'completedCount',
            'inProgressCount',
            'pendingCount'
        ));
    }

    public function storeReport(Request $request)
    {
        $validated = $request->validate([
            'alamat'          => ['required', 'string', 'max:255'],
            'rt'              => ['required', 'string', 'max:5'],
            'rw'              => ['required', 'string', 'max:5'],
            'patokan'         => ['nullable', 'string', 'max:255'],
            'jenis_tumpukan'  => ['required', 'string'],
            'deskripsi'       => ['required', 'string'],
            'urgensi'         => ['required', 'in:rendah,sedang,tinggi'],
            'foto'            => ['nullable', 'image', 'max:5120'], // maks 5MB
        ]);

        $fotoUrl = '/images/sample_report.jpg';
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('reports', 'public');
            $fotoUrl = '/storage/' . $path;
        }

        $kode = 'LPR-' . now()->year . '-' . str_pad(WasteReport::count() + 1, 3, '0', STR_PAD_LEFT);

        WasteReport::create([
            'kode_laporan'   => $kode,
            'user_id'        => Auth::id(),
            'nama_pelapor'   => Auth::user()->name,
            'telepon_pelapor' => Auth::user()->telepon ?? '08123456789',
            'alamat'         => $validated['alamat'],
            'rt'             => $validated['rt'],
            'rw'             => $validated['rw'],
            'patokan'        => $validated['patokan'] ?? null, // PERBAIKAN DI SINI
            'jenis_tumpukan' => $validated['jenis_tumpukan'],
            'deskripsi'      => $validated['deskripsi'],
            'foto_url'       => $fotoUrl,
            'urgensi'        => $validated['urgensi'],
            'status'         => 'menunggu',
        ]);

        return back()->with('success', "Aduan sampah {$kode} berhasil dikirim! Menunggu tindak lanjut petugas lapangan.");
    }
}
