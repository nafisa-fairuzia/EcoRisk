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
        $reports = WasteReport::query()
            ->whereIn('status', ['reported', 'approved'])
            ->orderByDesc('risk_score')
            ->get();

        $lastSeenSessionKey = 'petugas_pickups_last_seen_'.$request->user()->getAuthIdentifier();
        $lastSeenReportId = (int) $request->session()->get($lastSeenSessionKey, 0);
        $latestVisibleReportId = (int) $reports->max('id');

        if ($latestVisibleReportId > $lastSeenReportId) {
            $request->session()->put($lastSeenSessionKey, $latestVisibleReportId);
        }

        $inProcessReports = WasteReport::query()
            ->where('status', 'in_process')
            ->orderByDesc('risk_score')
            ->get();

        return view('officer.pickups.index', compact('reports', 'inProcessReports'));
    }

    public function routes(): View
    {
        $routeReports = WasteReport::query()
            ->whereIn('status', ['reported', 'approved', 'in_process'])
            ->orderByDesc('risk_score')
            ->get();

        return view('officer.routes.index', compact('routeReports'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:in_process,completed'],
        ]);

        DB::transaction(function () use ($validated, $id): void {
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
