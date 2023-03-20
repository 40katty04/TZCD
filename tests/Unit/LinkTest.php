<?php

namespace Tests\Feature;

use App\Models\Link;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function testUrl(): void
    {
        $link = Link::factory()->create(['url' => 'google.com']);

        $this->assertEquals('https://google.com', $link->url);

        $link = Link::factory()->create(['url' => 'https://google.com']);

        $this->assertEquals('https://google.com', $link->url);

    }

    public function testIsExpired(): void
    {
        $link = Link::factory()->create(['expires_at' => null]);

        $this->assertEquals($link->isExpired(), false);

        $link = Link::factory()->create(['expires_at' => now()->addMinutes(random_int(1,1440))]);

        $this->assertEquals($link->isExpired(), false);

        $link = Link::factory()->create(['expires_at' => now()->addMinutes( -random_int(1,1440))]);

        $this->assertEquals($link->isExpired(), true);
    }

    public function testHasMaxClicks(): void
    {
        $link = Link::factory()->create(['max_clicks' => 0]);

        $this->assertEquals($link->hasMaxClicks(), false);

        $link = Link::factory()->create([
            'max_clicks' => $max = $this->faker->numberBetween(1),
            'clicks' => $max - 1
        ]);

        $this->assertEquals($link->hasMaxClicks(), false);

        $link = Link::factory()->create([
            'max_clicks' => $max = $this->faker->numberBetween(1),
            'clicks' => $max
        ]);

        $this->assertEquals($link->hasMaxClicks(), true);

    }

    public function testIncrementClicks(): void
    {
        $clicks = $this->faker->randomNumber();
        $link = Link::factory()->create(['clicks' => $clicks]);

        $link->incrementClicks();

        $link->refresh();

        $this->assertEquals(++$clicks, $link->clicks);
    }

    public function testGenerateToken(): void
    {
        $token = Link::generateToken();

        $this->assertDatabaseMissing('links', [
            'token' => $token
        ]);


    }
}
