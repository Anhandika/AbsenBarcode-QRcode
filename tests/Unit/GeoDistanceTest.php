<?php

namespace Tests\Unit;

use App\Support\GeoDistance;
use PHPUnit\Framework\TestCase;

class GeoDistanceTest extends TestCase
{
    public function test_same_coordinate_has_zero_distance(): void
    {
        $this->assertEqualsWithDelta(
            0,
            GeoDistance::meters(-6.9182, 110.2056, -6.9182, 110.2056),
            0.01,
        );
    }

    public function test_distance_is_measured_in_meters(): void
    {
        $distance = GeoDistance::meters(-6.9182, 110.2056, -6.9182, 110.2066);

        $this->assertGreaterThan(100, $distance);
        $this->assertLessThan(120, $distance);
    }
}
