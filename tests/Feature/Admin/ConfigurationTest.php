<?php

namespace Tests\Feature\Admin;

use App\Models\Configuration;
use App\Models\Role;
use Tests\Feature\Concerns\HandleAuthentication;
use Tests\Feature\Concerns\HandleDataTables;
use Tests\Feature\TestCase;

class ConfigurationTest extends TestCase
{
    use HandleAuthentication, HandleDataTables;

    public function test_assert_index()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($admin, 'web')
            ->get(route('admin.configuration.index'))
            ->assertOk();
    }

    public function test_assert_datatable()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($admin, 'web')
            ->post(route('admin.configuration.datatable'))
            ->assertOk()
            ->assertJsonStructure($this->getDataTablesFormat());
    }

    public function test_assert_create()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($admin, 'web')
            ->get(route('admin.configuration.create'))
            ->assertOk();
    }

    public function test_assert_store()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($admin, 'web')
            ->post(route('admin.configuration.store'), $data = Configuration::factory()->raw())
            ->assertRedirect(route('admin.configuration.index'));

        $this->assertDatabaseHas(Configuration::class, $data);
    }

    public function test_assert_edit()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $configuration = Configuration::factory()->create();

        $this->actingAs($admin, 'web')
            ->get(route('admin.configuration.edit', $configuration))
            ->assertOk();
    }

    public function test_assert_update()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $configuration = Configuration::factory()->create();

        $this->actingAs($admin, 'web')
            ->put(route('admin.configuration.update', $configuration), $data = Configuration::factory()->raw())
            ->assertRedirect(route('admin.configuration.index'));

        $this->assertDatabaseHas(Configuration::class, $data);
    }

    public function test_assert_destroy()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $configuration = Configuration::factory()->create();

        $this->actingAs($admin, 'web')
            ->delete(route('admin.configuration.destroy', $configuration))
            ->assertRedirect(route('admin.configuration.index'));

        $this->assertDatabaseMissing(Configuration::class, $configuration->only('id'));
    }

    public function test_assert_destroy_multiple()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $configurations = Configuration::factory()->count(3)->create();

        $this->actingAs($admin, 'web')
            ->delete(route('admin.configuration.destroy-multiple', [
                'checkbox' => $configurations->pluck('id')->toArray(),
            ]))
            ->assertRedirect(route('admin.configuration.index'));

        foreach ($configurations as $configuration) {
            $this->assertDatabaseMissing(Configuration::class, $configuration->only('id'));
        }
    }
}
