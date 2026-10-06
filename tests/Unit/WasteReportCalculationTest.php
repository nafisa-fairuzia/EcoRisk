<?php

namespace Tests\Unit;

use App\Models\WasteReport;
use Tests\TestCase;

class WasteReportCalculationTest extends TestCase
{
    public function test_risk_score_includes_days_and_location_factors(): void
    {
        $this->travelTo('2026-10-04 12:00:00');

        $wasteReport = new WasteReport([
            'is_near_river' => true,
            'is_dense_populated' => true,
            'history_burned' => true,
        ]);
        $wasteReport->created_at = now()->subDays(2);

        $this->assertSame(95, $wasteReport->calculateRiskScore());
        $this->assertSame('critical', $wasteReport->determinePriorityLevel(95));
    }

    public function test_priority_level_matches_risk_score_thresholds(): void
    {
        $wasteReport = new WasteReport;

        $this->assertSame('low', $wasteReport->determinePriorityLevel(29));
        $this->assertSame('medium', $wasteReport->determinePriorityLevel(30));
        $this->assertSame('medium', $wasteReport->determinePriorityLevel(59));
        $this->assertSame('high', $wasteReport->determinePriorityLevel(60));
        $this->assertSame('high', $wasteReport->determinePriorityLevel(84));
        $this->assertSame('critical', $wasteReport->determinePriorityLevel(85));
    }
}
