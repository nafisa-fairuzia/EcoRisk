<?php

namespace App\Http\Controllers;

use App\Models\DistributionRecord;
use App\Models\SortingRecord;
use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $totalReports = WasteReport::count();
        $completedReports = WasteReport::where('status', 'completed')->count();
        $pendingReports = WasteReport::whereIn('status', ['reported', 'approved'])->count();
        $inProgressReports = WasteReport::where('status', 'in_process')->count();

        $completionRate = $totalReports > 0 ? round(($completedReports / $totalReports) * 100) : 0;

        $totalPetugas = User::where('role', 'petugas')->where('status', 'Aktif')->count();
        $totalSortedKg = SortingRecord::sum('total_kg');
        $totalDistributedKg = DistributionRecord::sum('berat_kg');

        $reports = WasteReport::with(['pelapor', 'petugas'])->latest()->paginate(10);

        return view('admin.dashboard', compact(
            'totalReports',
            'completedReports',
            'pendingReports',
            'inProgressReports',
            'completionRate',
            'totalPetugas',
            'totalSortedKg',
            'totalDistributedKg',
            'reports'
        ));
    }

    public function impact(): View
    {
        $impactTotals = DB::table('waste_sortings')
            ->selectRaw('COALESCE(SUM(organic_weight_kg + inorganic_weight_kg + paper_weight_kg), 0) as total_weight_kg')
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

        return view('admin.impact', compact(
            'impactTotals',
            'channelDistribution'
        ));
    }

    public function users(): View
    {
        $users = User::query()
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function toggleUserStatus(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($request->user()?->is($user)) {
            return back()->withErrors([
                'user' => 'Anda tidak dapat mengubah status akun yang sedang digunakan.',
            ]);
        }

        $user->status = $user->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $user->save();

        return back()->with('success', "Status akun {$user->name} berhasil diubah menjadi {$user->status}.");
    }
}
