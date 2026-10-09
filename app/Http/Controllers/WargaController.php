<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WargaController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        abort_unless($user instanceof User, 401);

        $myReports = WasteReport::query()
            ->where('user_id', $user->id)
            ->with('petugas')
            ->latest()
            ->get();

        $totalMyReports = $myReports->count();
        $completedCount = $myReports->where('status', 'completed')->count();
        $inProgressCount = $myReports->where('status', 'in_process')->count();
        $pendingCount = $myReports->whereIn('status', ['reported', 'approved'])->count();
        $section = request()->query('section', 'dashboard');
        $section = in_array($section, ['dashboard', 'create', 'reports'], true) ? $section : 'dashboard';

        return view('warga.dashboard', compact(
            'myReports',
            'totalMyReports',
            'completedCount',
            'inProgressCount',
            'pendingCount',
            'section'
        ));
    }

    public function storeReport(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user instanceof User, 401);

        $validated = $request->validate([
            'alamat' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'string', 'max:5'],
            'rw' => ['required', 'string', 'max:5'],
            'patokan' => ['nullable', 'string', 'max:255'],
            'jenis_tumpukan' => ['required', 'string'],
            'deskripsi' => ['required', 'string'],
            'urgensi' => ['required', 'in:rendah,sedang,tinggi'],
            'foto' => ['nullable', 'image', 'max:5120'], // maks 5MB
        ]);

        $photoPath = $request->file('foto')?->store('reports', 'public');
        $fotoUrl = $photoPath ? '/storage/' . $photoPath : '/images/sample_report.jpg';

        $kode = 'LPR-' . now()->year . '-' . str_pad(WasteReport::count() + 1, 3, '0', STR_PAD_LEFT);

        WasteReport::create([
            'kode_laporan' => $kode,
            'user_id' => $user->id,
            'address' => $validated['alamat'],
            'description' => $validated['deskripsi'],
            'photo' => $photoPath,
            'nama_pelapor' => $user->name,
            'telepon_pelapor' => $user->telepon ?? '08123456789',
            'alamat' => $validated['alamat'],
            'rt' => $validated['rt'],
            'rw' => $validated['rw'],
            'patokan' => $validated['patokan'] ?? null,
            'jenis_tumpukan' => $validated['jenis_tumpukan'],
            'deskripsi' => $validated['deskripsi'],
            'foto_url' => $fotoUrl,
            'urgensi' => $validated['urgensi'],
            'status' => 'reported',
        ]);

        return back()->with('success', "Aduan sampah {$kode} berhasil dikirim! Menunggu tindak lanjut petugas lapangan.");
    }
}
