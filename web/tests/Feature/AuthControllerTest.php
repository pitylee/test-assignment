<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions; // This will rollback any database changes made during testing.

    /**
     * Test the login method.
     *
     * @return void
     */
    public function testLogin()
    {
        // Create a user for testing.
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Hash the password for testing.
        ]);

        // Make a POST request to the login endpoint.
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Assert that the response is successful and contains expected data.
        $response->assertStatus(200)
            ->assertJsonStructure([
                'me',
                'auth',
                'token',
            ]);
    }

    /**
     * Test the me method.
     *
     * @return void
     */
    public function testMe()
    {
        // Create a user for testing.
        $user = User::factory()->create();

        // Act: Make a GET request to the 'me' endpoint.
        $response = $this->actingAs($user)->get('/api/me');

        // Assert that the response is successful and contains expected data.
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    // Include other fields you expect in the response.
                ],
            ]);
    }
}
