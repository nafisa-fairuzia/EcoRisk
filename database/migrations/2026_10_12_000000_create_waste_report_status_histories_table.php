<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_report_status_histories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('waste_report_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['waste_report_id', 'created_at']);
        });

        DB::table('waste_reports')
            ->orderBy('id')
            ->chunkById(500, function ($reports): void {
                $reportIds = $reports->pluck('id');
                $pickupLogs = DB::table('pickup_logs')
                    ->whereIn('waste_report_id', $reportIds)
                    ->orderBy('pickup_time')
                    ->get()
                    ->groupBy('waste_report_id');

                foreach ($reports as $report) {
                    $events = [[
                        'status' => 'reported',
                        'created_at' => $report->created_at,
                    ]];

                    $logs = $pickupLogs->get($report->id, collect());
                    $startLog = $logs->first(
                        fn (object $log): bool => $log->status === 'in_process'
                            || $log->status === 'completed'
                    );

                    if (in_array($report->status, ['approved', 'in_process', 'completed', 'rejected'], true)) {
                        if ($report->status === 'approved') {
                            $events[] = [
                                'status' => 'approved',
                                'created_at' => $report->tanggal_update_status
                                    ?? $report->updated_at
                                    ?? $report->created_at,
                            ];
                        } elseif ($report->status === 'in_process' || $report->status === 'completed') {
                            $events[] = [
                                'status' => 'in_process',
                                'created_at' => $startLog?->pickup_time
                                    ?? $report->tanggal_update_status
                                    ?? $report->updated_at
                                    ?? $report->created_at,
                            ];

                            if ($report->status === 'completed') {
                                $completedLog = $logs->first(
                                    fn (object $log): bool => $log->status === 'completed'
                                );

                                $events[] = [
                                    'status' => 'completed',
                                    'created_at' => $completedLog?->updated_at
                                        ?? $report->tanggal_update_status
                                        ?? $report->updated_at
                                        ?? $report->created_at,
                                ];
                            }
                        } else {
                            $events[] = [
                                'status' => 'rejected',
                                'created_at' => $report->tanggal_update_status
                                    ?? $report->updated_at
                                    ?? $report->created_at,
                            ];
                        }
                    }

                    foreach ($events as $event) {
                        DB::table('waste_report_status_histories')->insert([
                            'waste_report_id' => $report->id,
                            'status' => $event['status'],
                            'note' => $event['status'] === $report->status
                                ? $report->catatan_petugas
                                : null,
                            'created_at' => $event['created_at'],
                            'updated_at' => $event['created_at'],
                        ]);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_report_status_histories');
    }
};
