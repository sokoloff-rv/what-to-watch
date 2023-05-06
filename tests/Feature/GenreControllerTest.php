<?php

namespace Tests\Feature;

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class GenreControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex(): void
    {
        Genre::factory()->count(3)->create();

        $response = $this->get('/api/genres');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name'
                ]
            ]
        ]);
    }
}
