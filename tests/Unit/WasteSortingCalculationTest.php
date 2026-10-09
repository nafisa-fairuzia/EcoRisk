<?php

namespace Tests\Unit;

use App\Models\WasteSorting;
use Tests\TestCase;

class WasteSortingCalculationTest extends TestCase
{
    public function test_prevented_emissions_use_total_sorted_weight(): void
    {
        $wasteSorting = new WasteSorting([
            'organic_weight_kg' => 10,
            'inorganic_weight_kg' => 5,
            'paper_weight_kg' => 3,
        ]);

        $this->assertSame(27.0, $wasteSorting->calculatePreventedEmissions());
    }
}
