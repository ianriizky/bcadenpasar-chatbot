<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use Tests\Feature\Concerns\HandleAuthentication;
use Tests\Feature\Concerns\HandleDataTables;
use Tests\Feature\TestCase;

class RoleTest extends TestCase
{
    use HandleAuthentication, HandleDataTables;

    public function test_assert_index()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($user, 'web')
            ->get(route('admin.role.index'))
            ->assertOk();
    }

    public function test_assert_datatable()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($user, 'web')
            ->post(route('admin.role.datatable'))
            ->assertOk()
            ->assertJsonStructure($this->getDataTablesFormat());
    }

    public function test_assert_create()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($user, 'web')
            ->get(route('admin.role.create'))
            ->assertOk();
    }

    public function test_assert_store()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($user, 'web')
            ->post(route('admin.role.store'), $data = Role::factory()->raw())
            ->assertRedirect(route('admin.role.index'));

        $this->assertDatabaseHas(Role::class, $data);
    }

    public function test_assert_edit()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $role = Role::factory()->create();

        $this->actingAs($user, 'web')
            ->get(route('admin.role.edit', $role))
            ->assertOk();
    }

    public function test_assert_update()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $role = Role::factory()->create();

        $this->actingAs($user, 'web')
            ->patch(route('admin.role.update', $role), $data = Role::factory()->raw())
            ->assertRedirect(route('admin.role.index'));

        $this->assertDatabaseHas(Role::class, $data);
    }

    public function test_assert_destroy()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $role = Role::factory()->create();

        $this->actingAs($user, 'web')
            ->delete(route('admin.role.destroy', $role))
            ->assertRedirect(route('admin.role.index'));

        $this->assertDatabaseMissing(Role::class, $role->only('id'));
    }

    public function test_assert_destroy_multiple()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $roles = Role::factory()->count(3)->create();

        $this->actingAs($user, 'web')
            ->delete(route('admin.role.destroy-multiple', [
                'checkbox' => $roles->pluck('id')->toArray(),
            ]))
            ->assertRedirect(route('admin.role.index'));

        foreach ($roles as $role) {
            $this->assertDatabaseMissing(Role::class, $role->only('id'));
        }
    }
}
