<?php

namespace Tests\Unit;

use Tests\TestCase;

class SimpleTest extends TestCase
{
    /** @test */
    public function it_can_run_basic_test(): void
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_access_application(): void
    {
        $this->assertNotNull($this->app);
    }

    /** @test */
    public function it_has_correct_environment(): void
    {
        $this->assertEquals('testing', $this->app->environment());
    }
}