<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WasteReportController extends Controller
{
    public function index(): View
    {
        $reports = WasteReport::query()
            ->whereIn('status', ['reported', 'approved', 'in_process'])
            ->orderByDesc('risk_score')
            ->get();

        $mappedReports = $reports
            ->filter(fn(WasteReport $report): bool => is_numeric($report->latitude)
                && is_numeric($report->longitude)
                && (float) $report->latitude >= -90
                && (float) $report->latitude <= 90
                && (float) $report->longitude >= -180
                && (float) $report->longitude <= 180);

        $mapPoints = $mappedReports
            ->map(fn(WasteReport $report): array => [
                'id' => $report->id,
                'latitude' => (float) $report->latitude,
                'longitude' => (float) $report->longitude,
                'risk_score' => (float) $report->risk_score,
                'priority_level' => $report->priority_level,
                'address' => $report->address,
                'code' => $report->kode_laporan,
            ])
            ->values();

        $riskZones = $mappedReports
            ->groupBy(fn(WasteReport $report): string => $report->rw ? 'RW ' . $report->rw : 'Area belum ditentukan')
            ->map(function (Collection $zoneReports, string $zoneName): array {
                $maxRisk = (int) $zoneReports->max('risk_score');

                return [
                    'name' => $zoneName,
                    'reports_count' => $zoneReports->count(),
                    'max_risk' => $maxRisk,
                    'critical_count' => $zoneReports->where('priority_level', 'critical')->count(),
                    'tone' => match (true) {
                        $maxRisk >= 85 => 'rose',
                        $maxRisk >= 60 => 'orange',
                        $maxRisk >= 30 => 'amber',
                        default => 'emerald',
                    },
                ];
            })
            ->sortByDesc('max_risk')
            ->values();

        $riskSummary = [
            'active_reports' => $reports->count(),
            'mapped_reports' => $mapPoints->count(),
            'high_priority_reports' => $reports->whereIn('priority_level', ['high', 'critical'])->count(),
            'critical_reports' => $reports->where('priority_level', 'critical')->count(),
            'monitored_zones' => $riskZones->count(),
        ];

        $tableReports = WasteReport::query()
            ->whereIn('status', ['reported', 'approved', 'in_process'])
            ->orderByDesc('risk_score')
            ->paginate(10)
            ->withQueryString();

        return view('reports.index', compact('reports', 'mapPoints', 'riskZones', 'riskSummary', 'tableReports'));
    }

    public function show(Request $request, WasteReport $report): View
    {
        $user = $request->user();
        abort_unless(
            $user->isAdmin() || $user->isPetugas() || ($user->isWarga() && $report->user_id === $user->id),
            404
        );

        $report->load(['pelapor', 'petugas', 'statusHistories']);

        $publicUrl = static fn (?string $path): ?string => $path === null
            ? null
            : (Str::startsWith($path, ['/', 'http://', 'https://'])
                ? $path
                : Storage::disk('public')->url($path));
        $photoUrl = $publicUrl($report->photo) ?? $publicUrl($report->foto_url);
        $completionPhotoUrl = $publicUrl($report->foto_selesai_url);
        $canEditReport = $user->isWarga() && $report->canBeEditedByResident();

        return view('reports.show', compact('report', 'photoUrl', 'completionPhotoUrl', 'canEditReport'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'is_near_river' => ['sometimes', 'boolean'],
            'is_dense_populated' => ['sometimes', 'boolean'],
            'history_burned' => ['sometimes', 'boolean'],
        ]);

        $report = new WasteReport([
            'user_id' => $request->user()->id,
            'photo' => $request->file('photo')->store('reports', 'public'),
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'address' => $validated['address'],
            'description' => $validated['description'],
            'is_near_river' => $validated['is_near_river'] ?? false,
            'is_dense_populated' => $validated['is_dense_populated'] ?? false,
            'history_burned' => $validated['history_burned'] ?? false,
            'status' => 'reported',
        ]);

        $report->risk_score = $report->calculateRiskScore();
        $report->priority_level = $report->determinePriorityLevel((int) $report->risk_score);
        $report->save();

        return back()->with('success', 'Laporan sampah berhasil dikirim.');
    }
}
