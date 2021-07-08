<?php

namespace Tests\Feature\Admin;

use App\Models\Denomination;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Concerns\HandleAuthentication;
use Tests\Feature\Concerns\HandleDataTables;
use Tests\Feature\TestCase;

class DenominationTest extends TestCase
{
    use HandleAuthentication, HandleDataTables;

    public function test_assert_index()
    {
        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $this->actingAs($staff, 'web')
            ->get(route('admin.denomination.index'))
            ->assertOk();
    }

    public function test_assert_datatable()
    {
        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $this->actingAs($staff, 'web')
            ->post(route('admin.denomination.datatable'))
            ->assertOk()
            ->assertJsonStructure($this->getDataTablesFormat());
    }

    public function test_assert_create()
    {
        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $this->actingAs($staff, 'web')
            ->get(route('admin.denomination.create'))
            ->assertOk();
    }

    public function test_assert_store()
    {
        Storage::fake();

        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $this->actingAs($staff, 'web')
            ->post(route('admin.denomination.store'), $data = Denomination::factory()->raw())
            ->assertRedirect(route('admin.denomination.index'));

        $this->assertDatabaseHas(Denomination::class, Arr::except($data, 'image'));

        Storage::assertExists(Denomination::IMAGE_PATH . '/' . $data['image']->getClientOriginalName());
    }

    public function test_assert_edit()
    {
        Storage::fake();

        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $denomination = $this->createFromFactory();

        $this->actingAs($staff, 'web')
            ->get(route('admin.denomination.edit', $denomination))
            ->assertOk();
    }

    public function test_assert_update()
    {
        Storage::fake();

        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $denomination = $this->createFromFactory();

        $this->actingAs($staff, 'web')
            ->put(route('admin.denomination.update', $denomination), $data = Denomination::factory()->raw())
            ->assertRedirect(route('admin.denomination.index'));

        $this->assertDatabaseHas(Denomination::class, Arr::except($data, 'image'));

        Storage::assertMissing(Denomination::IMAGE_PATH . '/' . $denomination->getRawOriginal('image'));
        Storage::assertExists(Denomination::IMAGE_PATH . '/' . $data['image']->getClientOriginalName());
    }

    public function test_assert_destroy()
    {
        Storage::fake();

        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        $denomination = $this->createFromFactory();

        $this->actingAs($staff, 'web')
            ->delete(route('admin.denomination.destroy', $denomination))
            ->assertRedirect(route('admin.denomination.index'));

        $this->assertDatabaseMissing(Denomination::class, $denomination->only('id'));

        Storage::assertMissing(Denomination::IMAGE_PATH . '/' . $denomination->getRawOriginal('image'));
    }

    public function test_assert_destroy_multiple()
    {
        Storage::fake();

        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\Denomination> $denominations */
        $denominations = Collection::times(3, fn () => $this->createFromFactory());

        $this->actingAs($staff, 'web')
            ->delete(route('admin.denomination.destroy-multiple', [
                'checkbox' => $denominations->pluck('id')->toArray(),
            ]))
            ->assertRedirect(route('admin.denomination.index'));

        foreach ($denominations as $denomination) {
            $this->assertDatabaseMissing(Denomination::class, $denomination->only('id'));

            Storage::assertMissing(Denomination::IMAGE_PATH . '/' . $denomination->getRawOriginal('image'));
        }
    }

    public function test_assert_destroy_image()
    {
        Storage::fake();

        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_STAFF);

        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\Denomination> $denominations */
        $denominations = Collection::times(3, fn () => $this->createFromFactory());

        foreach ($denominations as $denomination) {
            $this->actingAs($staff, 'web')
                ->delete(route('admin.denomination.destroy-image', $denomination))
                ->assertRedirect(route('admin.denomination.edit', $denomination));

            $this->assertDatabaseMissing(Denomination::class, $denomination->getAttributes());

            Storage::assertMissing(Denomination::IMAGE_PATH . '/' . $denomination->getRawOriginal('image'));
        }
    }

    /**
     * Create a new resource from factory and store the given image.
     *
     * @return \App\Models\Denomination
     */
    protected function createFromFactory(): Denomination
    {
        $factory = Denomination::factory()->raw();

        /**
         * If the denomination factory data already exists in storage,
         * then retry the create factory process.
         */
        if (Denomination::where('value', $factory['value'])->count() > 0) {
            return $this->createFromFactory();
        }

        /** @var \App\Models\Denomination $denomination */
        $denomination = Denomination::make(Arr::except($factory, 'image'));

        Storage::putFileAs(
            Denomination::IMAGE_PATH,
            $factory['image'],
            $filename = $factory['image']->getClientOriginalName()
        );

        $denomination->setAttribute('image', $filename)->save();

        Storage::assertExists(Denomination::IMAGE_PATH . '/' . $denomination->getRawOriginal('image'));

        return $denomination;
    }
}
