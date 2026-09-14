<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_dashboard_but_scanner_roles_cannot(): void
    {
        $this->seed();

        $this->actingAs(User::where('role', UserRole::ADMIN_SEKOLAH->value)->first())
            ->get(route('dashboard'))
            ->assertOk();

        $this->actingAs(User::where('role', UserRole::SISWA->value)->first())
            ->get(route('dashboard'))
            ->assertForbidden();
    }
}
