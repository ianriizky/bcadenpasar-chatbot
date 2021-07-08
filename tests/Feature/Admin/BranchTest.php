<?php

namespace Tests\Feature\Admin;

use App\Models\Branch;
use App\Models\Role;
use Tests\Feature\Concerns\HandleAuthentication;
use Tests\Feature\Concerns\HandleDataTables;
use Tests\Feature\TestCase;

class BranchTest extends TestCase
{
    use HandleAuthentication, HandleDataTables;

    public function test_assert_index()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $this->actingAs($admin, 'web')
            ->get(route('admin.branch.index'))
            ->assertOk();
    }

    public function test_assert_datatable()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $this->actingAs($admin, 'web')
            ->post(route('admin.branch.datatable'))
            ->assertOk()
            ->assertJsonStructure($this->getDataTablesFormat());
    }

    public function test_assert_create()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $this->actingAs($admin, 'web')
            ->get(route('admin.branch.create'))
            ->assertOk();
    }

    public function test_assert_store()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $this->actingAs($admin, 'web')
            ->post(route('admin.branch.store'), $data = Branch::factory()->raw())
            ->assertRedirect(route('admin.branch.index'));

        $this->assertDatabaseHas(Branch::class, $data);
    }

    public function test_assert_edit()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $role = Branch::factory()->create();

        $this->actingAs($admin, 'web')
            ->get(route('admin.branch.edit', $role))
            ->assertOk();
    }

    public function test_assert_update()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $role = Branch::factory()->create();

        $this->actingAs($admin, 'web')
            ->put(route('admin.branch.update', $role), $data = Branch::factory()->raw())
            ->assertRedirect(route('admin.branch.index'));

        $this->assertDatabaseHas(Branch::class, $data);
    }

    public function test_assert_destroy()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $role = Branch::factory()->create();

        $this->actingAs($admin, 'web')
            ->delete(route('admin.branch.destroy', $role))
            ->assertRedirect(route('admin.branch.index'));

        $this->assertDatabaseMissing(Branch::class, $role->only('id'));
    }

    public function test_assert_destroy_multiple()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $roles = Branch::factory()->count(3)->create();

        $this->actingAs($admin, 'web')
            ->delete(route('admin.branch.destroy-multiple', [
                'checkbox' => $roles->pluck('id')->toArray(),
            ]))
            ->assertRedirect(route('admin.branch.index'));

        foreach ($roles as $role) {
            $this->assertDatabaseMissing(Branch::class, $role->only('id'));
        }
    }
}
