<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }public function test_authenticated_users_can_visit_the_dashboard()
{
    // Create a user with the 'admin' role
    $user = User::factory()->create([
        'role' => 'admin', // must match your admin middleware check
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200); // should pass now
}

}
