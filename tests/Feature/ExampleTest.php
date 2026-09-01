<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login page response.
     */
    public function test_login_page_returns_successful_response(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Test role switcher for all 4 roles.
     */
    public function test_switch_role_functionality(): void
    {
        $this->seed();

        foreach (['admin', 'guru', 'industri', 'siswa'] as $role) {
            $response = $this->get('/switch-role/' . $role);
            $response->assertRedirect('/dashboard');
        }
    }
}
