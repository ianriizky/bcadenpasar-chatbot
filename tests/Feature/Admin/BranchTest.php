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
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $this->actingAs($user, 'web')
            ->get(route('admin.branch.index'))
            ->assertOk();
    }

    public function test_assert_datatable()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $this->actingAs($user, 'web')
            ->post(route('admin.branch.datatable'))
            ->assertOk()
            ->assertJsonStructure($this->getDataTablesFormat());
    }

    public function test_assert_create()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $this->actingAs($user, 'web')
            ->get(route('admin.branch.create'))
            ->assertOk();
    }

    public function test_assert_store()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $this->actingAs($user, 'web')
            ->post(route('admin.branch.store'), $data = Branch::factory()->raw())
            ->assertRedirect(route('admin.branch.index'));

        $this->assertDatabaseHas(Branch::class, $data);
    }

    public function test_assert_edit()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $role = Branch::factory()->create();

        $this->actingAs($user, 'web')
            ->get(route('admin.branch.edit', $role))
            ->assertOk();
    }

    public function test_assert_update()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $role = Branch::factory()->create();

        $this->actingAs($user, 'web')
            ->put(route('admin.branch.update', $role), $data = Branch::factory()->raw())
            ->assertRedirect(route('admin.branch.index'));

        $this->assertDatabaseHas(Branch::class, $data);
    }

    public function test_assert_destroy()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $role = Branch::factory()->create();

        $this->actingAs($user, 'web')
            ->delete(route('admin.branch.destroy', $role))
            ->assertRedirect(route('admin.branch.index'));

        $this->assertDatabaseMissing(Branch::class, $role->only('id'));
    }

    public function test_assert_destroy_multiple()
    {
        $user = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $roles = Branch::factory()->count(3)->create();

        $this->actingAs($user, 'web')
            ->delete(route('admin.branch.destroy-multiple', [
                'checkbox' => $roles->pluck('id')->toArray(),
            ]))
            ->assertRedirect(route('admin.branch.index'));

        foreach ($roles as $role) {
            $this->assertDatabaseMissing(Branch::class, $role->only('id'));
        }
    }
}
