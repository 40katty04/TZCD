<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Link;
use Carbon\Carbon;

class LinkControllerTest extends TestCase
{
use DatabaseTransactions, WithFaker;

    public function testCreate(): void
    {
        $response = $this->get(route('link.create'));

        $response->assertOk();
        $response->assertViewIs('link.create');
    }

    public function testStore(): void
    {
        $expiresIn = $this->faker->numberBetween(0, 1440);
        $maxClicks = $this->faker->numberBetween(0, 999999);
        $url = 'google.com';

        $response = $this->post(route('link.store'), [
            'url' => $url,
            'max_clicks' => $maxClicks,
            'expires_in' => $expiresIn,
        ]);

        $response->assertRedirect(route('link.create'));

        $url = (str_starts_with($url, 'https://')) ? $url : 'https://' . $url;

        $this->assertDatabaseHas('links', [
            'url' => $url,
            'clicks' => 0,
            'max_clicks' => $maxClicks,
        ]);

    }

    public function testShow(): void
    {
        $link = Link::factory()->create();

        $response = $this->get(route('link.show', $link->token));

        $response->assertRedirect($link->url);

        $link->refresh();

        $this->assertSame($link->clicks, 1);

        // Check expired link
        $link = Link::factory()->new()->expired()->create();

        $response = $this->get(route('link.show', $link->token));

        $response->assertStatus(404);

        //Check link has max clicks
        $link = Link::factory()->new()->hasMaxClicks()->create();

        $response = $this->get(route('link.show', $link->token));

        $response->assertStatus(404);


        //Check expired link has max clicks
        $link = Link::factory()->hasMaxClicks()->expired()->create();

        $response = $this->get(route('link.show', $link->token));

        $response->assertStatus(404);
    }
}
