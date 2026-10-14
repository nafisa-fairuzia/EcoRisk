<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicDashboardController extends Controller
{
    public function index(): View
    {
        $totals = DB::table('waste_sortings')
            ->selectRaw(
                'COALESCE(SUM(organic_weight_kg + inorganic_weight_kg + paper_weight_kg), 0) as total_weight_kg'
            )
            ->selectRaw('COALESCE(SUM(prevented_carbon_emissions_kg), 0) as prevented_emissions_kg')
            ->selectRaw('COALESCE(SUM(CASE WHEN prevented_burning_risk = 1 THEN 1 ELSE 0 END), 0) as prevented_burning_count')
            ->first();

        $channelWeights = DB::table('waste_sortings')
            ->select('channel_type')
            ->selectRaw('SUM(organic_weight_kg + inorganic_weight_kg + paper_weight_kg) as total_weight_kg')
            ->whereNotNull('channel_type')
            ->groupBy('channel_type')
            ->get()
            ->keyBy('channel_type');

        $channelTotalWeight = (float) $channelWeights->sum('total_weight_kg');
        $channelLabels = [
            'maggot_bsf' => 'Maggot BSF',
            'compost_community' => 'Kompos komunitas',
            'waste_bank' => 'Bank sampah',
            'recycling_umkm' => 'Daur ulang UMKM',
        ];

        $channelDistribution = collect($channelLabels)
            ->map(function (string $label, string $channelType) use ($channelWeights, $channelTotalWeight): array {
                $weightKg = (float) ($channelWeights->get($channelType)?->total_weight_kg ?? 0);

                return [
                    'label' => $label,
                    'weight_kg' => $weightKg,
                    'percentage' => $channelTotalWeight > 0
                        ? round(($weightKg / $channelTotalWeight) * 100, 2)
                        : 0,
                ];
            });

        $recentHandledReports = WasteReport::query()
            ->where('status', 'completed')
            ->whereHas('wasteSorting')
            ->select([
                'id',
                'address',
                'latitude',
                'longitude',
                'priority_level',
                'risk_score',
                'updated_at',
            ])
            ->orderByDesc('updated_at')
            ->paginate(5)
            ->withQueryString();

        $totalWeightKg = (float) $totals->total_weight_kg;
        $totalWeightTon = $totalWeightKg / 1000;
        $preventedEmissionsKg = (float) $totals->prevented_emissions_kg;
        $preventedBurningCount = (int) $totals->prevented_burning_count;

        return view('public.dashboard', compact(
            'totalWeightKg',
            'totalWeightTon',
            'preventedEmissionsKg',
            'preventedBurningCount',
            'channelDistribution',
            'recentHandledReports',
        ));
    }
}
