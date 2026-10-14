<?php

namespace App\Http\Controllers;

use App\Models\PickupLog;
use App\Models\WasteReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OfficerPickupController extends Controller
{
    public function index(Request $request): View
    {
        $waitingReportsQuery = WasteReport::query()
            ->whereIn('status', ['reported', 'approved'])
            ->orderByDesc('risk_score');

        $lastSeenSessionKey = 'petugas_pickups_last_seen_' . $request->user()->getAuthIdentifier();
        $lastSeenReportId = (int) $request->session()->get($lastSeenSessionKey, 0);
        $latestVisibleReportId = (int) (clone $waitingReportsQuery)->max('id');

        if ($latestVisibleReportId > $lastSeenReportId) {
            $request->session()->put($lastSeenSessionKey, $latestVisibleReportId);
        }

        $reports = WasteReport::query()
            ->whereIn('status', ['reported', 'approved', 'in_process'])
            ->orderByRaw("CASE WHEN status = 'in_process' THEN 1 ELSE 0 END")
            ->orderByDesc('risk_score')
            ->orderByDesc('id')
            ->paginate(10, ['*'], 'reportsPage')
            ->withQueryString();

        return view('officer.pickups.index', compact('reports'));
    }

    public function routes(): View
    {
        $routeReports = WasteReport::query()
            ->whereIn('status', ['reported', 'approved', 'in_process'])
            ->orderByDesc('risk_score')
            ->paginate(10)
            ->withQueryString();

        return view('officer.routes.index', compact('routeReports'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:in_process,completed'],
            'catatan_petugas' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $validated, $id): void {
            $report = WasteReport::query()->lockForUpdate()->findOrFail($id);
            $nextStatus = $validated['status'];

            $isStartingPickup = $nextStatus === 'in_process'
                && in_array($report->status, ['reported', 'approved'], true);
            $isCompletingPickup = $nextStatus === 'completed'
                && $report->status === 'in_process';

            if (! $isStartingPickup && ! $isCompletingPickup) {
                throw ValidationException::withMessages([
                    'status' => 'Perubahan status laporan tidak valid.',
                ]);
            }

            $report->status = $nextStatus;
            $report->petugas_id = $request->user()->id;
            $report->tanggal_update_status = now();
            $report->catatan_petugas = $validated['catatan_petugas'] ?? $report->catatan_petugas;
            $report->save();

            if ($isStartingPickup) {
                PickupLog::create([
                    'waste_report_id' => $report->id,
                    'officer_id' => Auth::id(),
                    'pickup_time' => now(),
                    'status' => 'in_process',
                ]);
            } else {
                $report->pickupLogs()
                    ->where('status', 'in_process')
                    ->latest()
                    ->first()
                    ?->update(['status' => 'completed']);
            }
        });

        return back()->with('success', 'Status pengangkutan berhasil diperbarui.');
    }
}
