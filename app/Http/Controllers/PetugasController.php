<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use App\Models\SortingRecord;
use App\Models\DistributionRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $pendingReports = WasteReport::where('status', 'menunggu')->latest()->get();
        $inProgressReports = WasteReport::where('status', 'proses')->latest()->get();
        $completedReports = WasteReport::where('status', 'selesai')->latest()->take(10)->get();

        $sortingRecords = SortingRecord::where('petugas_id', $user->id)->latest()->take(10)->get();
        $distributionRecords = DistributionRecord::where('petugas_id', $user->id)->latest()->take(10)->get();

        $totalSortedKg = SortingRecord::where('petugas_id', $user->id)->sum('total_kg');
        $totalDistributedKg = DistributionRecord::where('petugas_id', $user->id)->sum('berat_kg');

        return view('petugas.dashboard', compact(
            'pendingReports',
            'inProgressReports',
            'completedReports',
            'sortingRecords',
            'distributionRecords',
            'totalSortedKg',
            'totalDistributedKg'
        ));
    }

    public function updateReportStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:proses,selesai,ditolak'],
            'catatan_petugas' => ['nullable', 'string', 'max:500'],
            'foto_selesai' => ['nullable', 'image', 'max:5120'], // maks 5MB
        ]);

        $report = WasteReport::findOrFail($id);
        $report->status = $request->status;
        $report->petugas_id = Auth::id();
        $report->catatan_petugas = $request->catatan_petugas ?? $report->catatan_petugas;
        $report->tanggal_update_status = now();

        if ($request->hasFile('foto_selesai')) {
            $path = $request->file('foto_selesai')->store('reports_completed', 'public');
            $report->foto_selesai_url = '/storage/' . $path;
        }

        $report->save();

        return back()->with('success', "Status laporan {$report->kode_laporan} berhasil diperbarui menjadi {$report->status}.");
    }

    public function storeSorting(Request $request)
    {
        $validated = $request->validate([
            'lokasi_tps'        => ['required', 'string'],
            'organik_kg'        => ['required', 'numeric', 'min:0'],
            'plastik_kg'        => ['required', 'numeric', 'min:0'],
            'kertas_karton_kg'  => ['required', 'numeric', 'min:0'],
            'logam_kaca_kg'     => ['required', 'numeric', 'min:0'],
            'residu_b3_kg'      => ['required', 'numeric', 'min:0'],
            'catatan'           => ['nullable', 'string'],
        ]);

        $total = $validated['organik_kg'] + $validated['plastik_kg'] + $validated['kertas_karton_kg'] + $validated['logam_kaca_kg'] + $validated['residu_b3_kg'];

        $kode = 'PILAH-' . str_pad(SortingRecord::count() + 1, 3, '0', STR_PAD_LEFT);

        SortingRecord::create([
            'kode_pilah'       => $kode,
            'petugas_id'       => Auth::id(),
            'tanggal'          => now()->toDateString(),
            'lokasi_tps'       => $validated['lokasi_tps'],
            'organik_kg'       => $validated['organik_kg'],
            'plastik_kg'       => $validated['plastik_kg'],
            'kertas_karton_kg' => $validated['kertas_karton_kg'],
            'logam_kaca_kg'    => $validated['logam_kaca_kg'],
            'residu_b3_kg'     => $validated['residu_b3_kg'],
            'total_kg'         => $total,
            'catatan'          => $validated['catatan'],
        ]);

        return back()->with('success', "Hasil penimbangan sampah ({$total} kg) berhasil disimpan ke sistem!");
    }

    public function storeDistribution(Request $request)
    {
        $validated = $request->validate([
            'mitra_tujuan'    => ['required', 'string'],
            'kategori_sampah' => ['required', 'string'],
            'berat_kg'        => ['required', 'numeric', 'min:0.1'],
            'catatan'         => ['nullable', 'string'],
        ]);

        $noSuratJalan = 'SJ/' . strtoupper(substr($validated['kategori_sampah'], 0, 3)) . '/' . now()->format('Y/m/') . str_pad(DistributionRecord::count() + 1, 3, '0', STR_PAD_LEFT);

        DistributionRecord::create([
            'no_surat_jalan'     => $noSuratJalan,
            'petugas_id'         => Auth::id(),
            'tanggal'            => now()->toDateString(),
            'mitra_tujuan'       => $validated['mitra_tujuan'],
            'kategori_sampah'    => $validated['kategori_sampah'],
            'berat_kg'           => $validated['berat_kg'],
            'catatan'            => $validated['catatan'] ?? '-',
            'status_serah_terima'=> 'Terkirim & Diterima',
        ]);

        return back()->with('success', "Log penyaluran {$validated['berat_kg']} kg ke {$validated['mitra_tujuan']} berhasil dicatat!");
    }
}
