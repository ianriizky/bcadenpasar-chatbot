<?php

namespace Tests\Feature\Admin;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Tests\Feature\Concerns\HandleAuthentication;
use Tests\Feature\Concerns\HandleDataTables;
use Tests\Feature\TestCase;

class UserTest extends TestCase
{
    use HandleAuthentication, HandleDataTables;

    public function test_assert_index()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($admin, 'web')
            ->get(route('admin.user.index'))
            ->assertOk();
    }

    public function test_assert_datatable()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($admin, 'web')
            ->post(route('admin.user.datatable'))
            ->assertOk()
            ->assertJsonStructure($this->getDataTablesFormat());
    }

    public function test_assert_create()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($admin, 'web')
            ->get(route('admin.user.create'))
            ->assertOk();
    }

    public function test_assert_store()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $data = User::factory()->activate()->raw();
        $data = array_merge($data, [
            'branch_id' => Branch::value('id'),
            'password_confirmation' => $data['password'],
            'role' => Role::ROLE_STAFF,
        ]);

        $this->actingAs($admin, 'web')
            ->post(route('admin.user.store'), $data)
            ->assertRedirect(route('admin.user.index'));

        $this->assertDatabaseHas(User::class, Arr::only($data, 'id'));
    }

    public function test_assert_edit()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $user = User::factory()->for(Branch::first())->create();

        $this->actingAs($admin, 'web')
            ->get(route('admin.user.edit', $user))
            ->assertOk();
    }

    public function test_assert_update()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $user = User::factory()->for(Branch::first())->create();

        $data = User::factory()->raw();
        $data = array_merge($data, [
            'branch_id' => Branch::value('id'),
            'password_confirmation' => $data['password'],
            'role' => Role::ROLE_STAFF,
        ]);

        $this->actingAs($admin, 'web')
            ->put(route('admin.user.update', $user), $data)
            ->assertRedirect(route('admin.user.index'));

        $this->assertDatabaseHas(User::class, Arr::only($data, 'id'));
    }

    public function test_assert_destroy()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $user = User::factory()->for(Branch::first())->create();

        $this->actingAs($admin, 'web')
            ->delete(route('admin.user.destroy', $user))
            ->assertRedirect(route('admin.user.index'));

        $this->assertDatabaseMissing(User::class, $user->only('id'));
    }

    public function test_assert_destroy_multiple()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $users = User::factory()->for(Branch::first())->count(3)->create();

        $this->actingAs($admin, 'web')
            ->delete(route('admin.user.destroy-multiple', [
                'checkbox' => $users->pluck('id')->toArray(),
            ]))
            ->assertRedirect(route('admin.user.index'));

        foreach ($users as $user) {
            $this->assertDatabaseMissing(User::class, $user->only('id'));
        }
    }
}
