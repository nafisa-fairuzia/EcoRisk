<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WasteReport;
use App\Models\SortingRecord;
use App\Models\DistributionRecord;
use App\Models\WasteCategory;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalReports = WasteReport::count();
        $completedReports = WasteReport::where('status', 'selesai')->count();
        $pendingReports = WasteReport::where('status', 'menunggu')->count();
        $inProgressReports = WasteReport::where('status', 'proses')->count();

        $completionRate = $totalReports > 0 ? round(($completedReports / $totalReports) * 100) : 0;

        $totalPetugas = User::where('role', 'petugas')->where('status', 'Aktif')->count();
        $totalSortedKg = SortingRecord::sum('total_kg');
        $totalDistributedKg = DistributionRecord::sum('berat_kg');

        $reports = WasteReport::with(['pelapor', 'petugas'])->latest()->paginate(10);
        $categories = WasteCategory::all();
        $users = User::all();

        return view('admin.dashboard', compact(
            'totalReports',
            'completedReports',
            'pendingReports',
            'inProgressReports',
            'completionRate',
            'totalPetugas',
            'totalSortedKg',
            'totalDistributedKg',
            'reports',
            'categories',
            'users'
        ));
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $user->save();

        return back()->with('success', "Status akun {$user->name} berhasil diubah menjadi {$user->status}.");
    }
}
