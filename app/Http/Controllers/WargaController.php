<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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
        $latestReport = $myReports->first();
        $section = request()->query('section', 'dashboard');
        $section = in_array($section, ['dashboard', 'create', 'reports'], true) ? $section : 'dashboard';

        return view('warga.dashboard', compact(
            'myReports',
            'totalMyReports',
            'completedCount',
            'inProgressCount',
            'pendingCount',
            'latestReport',
            'section'
        ));
    }

    public function storeReport(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user instanceof User, 401);

        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
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
        $latitude = (float) $validated['latitude'];
        $longitude = (float) $validated['longitude'];
        $address = sprintf('Titik peta (%0.7f, %0.7f)', $latitude, $longitude);

        $kode = 'LPR-' . now()->year . '-' . str_pad(WasteReport::count() + 1, 3, '0', STR_PAD_LEFT);

        WasteReport::create([
            'kode_laporan' => $kode,
            'user_id' => $user->id,
            'address' => $address,
            'description' => $validated['deskripsi'],
            'photo' => $photoPath,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'nama_pelapor' => $user->name,
            'telepon_pelapor' => $user->telepon ?? '08123456789',
            'alamat' => $address,
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

    public function editReport(Request $request, WasteReport $report): View
    {
        abort_unless($report->user_id === $request->user()->id, 404);
        abort_unless($report->canBeEditedByResident(), 403);

        $photoPath = $report->photo ?: $report->foto_url;
        $photoUrl = $photoPath === null
            ? null
            : (Str::startsWith($photoPath, ['/', 'http://', 'https://'])
                ? $photoPath
                : Storage::disk('public')->url($photoPath));

        return view('warga.reports.edit', compact('report', 'photoUrl'));
    }

    public function updateReport(Request $request, WasteReport $report): RedirectResponse
    {
        abort_unless($report->user_id === $request->user()->id, 404);

        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'rt' => ['required', 'string', 'max:5'],
            'rw' => ['required', 'string', 'max:5'],
            'patokan' => ['nullable', 'string', 'max:255'],
            'jenis_tumpukan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'urgensi' => ['required', 'in:rendah,sedang,tinggi'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $newPhotoPath = null;

        try {
            $oldPhotoPath = DB::transaction(function () use (
                $request,
                $report,
                $validated,
                &$newPhotoPath
            ): ?string {
                $lockedReport = WasteReport::query()
                    ->lockForUpdate()
                    ->findOrFail($report->id);

                abort_unless($lockedReport->user_id === $request->user()->id, 404);
                $this->ensureReportCanBeEdited($lockedReport);

                $oldPhotoPath = $this->storedReportPhotoPath($lockedReport);

                if ($request->hasFile('foto')) {
                    $newPhotoPath = $request->file('foto')->store('reports', 'public');
                    $lockedReport->photo = $newPhotoPath;
                    $lockedReport->foto_url = '/storage/'.$newPhotoPath;
                }

                $latitude = (float) $validated['latitude'];
                $longitude = (float) $validated['longitude'];
                $address = sprintf('Titik peta (%0.7f, %0.7f)', $latitude, $longitude);

                $lockedReport->latitude = $latitude;
                $lockedReport->longitude = $longitude;
                $lockedReport->address = $address;
                $lockedReport->alamat = $address;
                $lockedReport->rt = $validated['rt'];
                $lockedReport->rw = $validated['rw'];
                $lockedReport->patokan = $validated['patokan'] ?? null;
                $lockedReport->jenis_tumpukan = $validated['jenis_tumpukan'];
                $lockedReport->deskripsi = $validated['deskripsi'];
                $lockedReport->description = $validated['deskripsi'];
                $lockedReport->urgensi = $validated['urgensi'];
                $lockedReport->save();

                return $oldPhotoPath;
            });
        } catch (\Throwable $exception) {
            if ($newPhotoPath !== null) {
                Storage::disk('public')->delete($newPhotoPath);
            }

            throw $exception;
        }

        if ($newPhotoPath !== null && $oldPhotoPath !== null && $oldPhotoPath !== $newPhotoPath) {
            Storage::disk('public')->delete($oldPhotoPath);
        }

        return redirect()
            ->route('warga.laporan.show', $report)
            ->with('success', 'Aduan berhasil diperbarui.');
    }

    public function deleteReport(Request $request, WasteReport $report): RedirectResponse
    {
        abort_unless($report->user_id === $request->user()->id, 404);

        $photoPath = DB::transaction(function () use ($request, $report): ?string {
            $lockedReport = WasteReport::query()
                ->lockForUpdate()
                ->findOrFail($report->id);

            abort_unless($lockedReport->user_id === $request->user()->id, 404);
            $this->ensureReportCanBeEdited($lockedReport);

            $photoPath = $this->storedReportPhotoPath($lockedReport);
            $lockedReport->delete();

            return $photoPath;
        });

        if ($photoPath !== null) {
            Storage::disk('public')->delete($photoPath);
        }

        return redirect()
            ->route('warga.dashboard', ['section' => 'reports'])
            ->with('success', 'Aduan berhasil dihapus.');
    }

    private function ensureReportCanBeEdited(WasteReport $report): void
    {
        if (! $report->canBeEditedByResident()) {
            throw ValidationException::withMessages([
                'report' => 'Aduan tidak dapat diubah karena sudah diproses atau ditangani petugas.',
            ]);
        }
    }

    private function storedReportPhotoPath(WasteReport $report): ?string
    {
        $photoPath = $report->photo;

        if ($photoPath === null && Str::startsWith($report->foto_url ?? '', '/storage/')) {
            $photoPath = Str::after($report->foto_url, '/storage/');
        }

        if ($photoPath === null
            || Str::startsWith($photoPath, ['/', 'http://', 'https://'])
            || ! Str::startsWith($photoPath, 'reports/')
            || Str::contains($photoPath, ['../', '\\'])) {
            return null;
        }

        return $photoPath;
    }
}
