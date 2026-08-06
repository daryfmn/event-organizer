<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_guests_are_redirected_from_root_to_login(): void
    {
        $this->get('/')
            ->assertRedirect(route('dashboard'));
    }

    public function test_login_page_is_accessible(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('nevermind.hcn');
    }
}
