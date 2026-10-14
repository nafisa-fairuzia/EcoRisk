<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use App\Models\WasteSorting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WasteSortingController extends Controller
{
    public function index(): View
    {
        $reports = WasteReport::query()
            ->where('status', 'completed')
            ->whereDoesntHave('wasteSorting')
            ->orderByDesc('risk_score')
            ->paginate(10)
            ->withQueryString();

        return view('officer.sortings.index', compact('reports'));
    }

    public function create(int $report_id): View
    {
        $report = WasteReport::query()
            ->whereIn('status', ['completed', 'in_process'])
            ->findOrFail($report_id);

        abort_if($report->wasteSorting()->exists(), 404);

        return view('officer.sortings.create', compact('report'));
    }

    public function store(Request $request, int $report_id): RedirectResponse
    {
        $validated = $request->validate([
            'organic_weight_kg' => ['required', 'numeric', 'min:0'],
            'inorganic_weight_kg' => ['required', 'numeric', 'min:0'],
            'paper_weight_kg' => ['required', 'numeric', 'min:0'],
            'channel_type' => [
                'required',
                'in:maggot_bsf,compost_community,waste_bank,recycling_umkm',
            ],
            'prevented_burning_risk' => ['required', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $report_id): void {
            $report = WasteReport::query()
                ->whereIn('status', ['completed', 'in_process'])
                ->lockForUpdate()
                ->findOrFail($report_id);

            if ($report->wasteSorting()->exists()) {
                throw ValidationException::withMessages([
                    'report_id' => 'Catatan pemilahan untuk laporan ini sudah tersedia.',
                ]);
            }

            $sorting = new WasteSorting([
                'organic_weight_kg' => $validated['organic_weight_kg'],
                'inorganic_weight_kg' => $validated['inorganic_weight_kg'],
                'paper_weight_kg' => $validated['paper_weight_kg'],
                'channel_type' => $validated['channel_type'],
                'prevented_burning_risk' => $validated['prevented_burning_risk'],
            ]);
            $sorting->prevented_carbon_emissions_kg = $sorting->calculatePreventedEmissions();

            $report->wasteSorting()->save($sorting);

            if ($report->status !== 'completed') {
                $report->status = 'completed';
                $report->save();
            }
        });

        return redirect()
            ->route('petugas.sortings.index')
            ->with('success', 'Pemilahan dan dampak lingkungan berhasil dicatat.');
    }
}
