<?php

namespace Tests\Feature\Admin;

use App\Models\Customer;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Concerns\HandleAuthentication;
use Tests\Feature\Concerns\HandleDataTables;
use Tests\Feature\TestCase;

class CustomerTest extends TestCase
{
    use HandleAuthentication, HandleDataTables;

    public function test_assert_index()
    {
        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($staff, 'web')
            ->get(route('admin.customer.index'))
            ->assertOk();
    }

    public function test_assert_datatable()
    {
        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($staff, 'web')
            ->post(route('admin.customer.datatable'))
            ->assertOk()
            ->assertJsonStructure($this->getDataTablesFormat());
    }

    public function test_assert_create()
    {
        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($staff, 'web')
            ->get(route('admin.customer.create'))
            ->assertOk();
    }

    public function test_assert_store()
    {
        Storage::fake();
        Event::fake();

        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($staff, 'web')
            ->post(route('admin.customer.store'), $data = Customer::factory()->raw())
            ->assertRedirect(route('admin.customer.index'));

        $this->assertDatabaseHas(Customer::class, Arr::only($data, 'username'));

        Storage::assertExists(Customer::IDENTITYCARD_IMAGE_PATH . '/' . Customer::firstWhere('username', $data['username'])->getRawOriginal('identitycard_image'));
    }

    public function test_assert_edit()
    {
        Storage::fake();

        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $customer = $this->createFromFactory();

        $this->actingAs($staff, 'web')
            ->get(route('admin.customer.edit', $customer))
            ->assertOk();
    }

    public function test_assert_update()
    {
        Storage::fake();

        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $customer = $this->createFromFactory();

        $this->actingAs($staff, 'web')
            ->put(route('admin.customer.update', $customer), $data = Customer::factory()->raw())
            ->assertRedirect(route('admin.customer.edit', Customer::firstWhere('username', $data['username'])));

        $this->assertDatabaseHas(Customer::class, Arr::only($data, 'username'));

        Storage::assertMissing(Customer::IDENTITYCARD_IMAGE_PATH . '/' . $customer->getRawOriginal('identitycard_image'));
        Storage::assertExists(Customer::IDENTITYCARD_IMAGE_PATH . '/' . Customer::firstWhere('username', $data['username'])->getRawOriginal('identitycard_image'));
    }

    public function test_assert_destroy()
    {
        Storage::fake();

        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $customer = $this->createFromFactory();

        $this->actingAs($staff, 'web')
            ->delete(route('admin.customer.destroy', $customer))
            ->assertRedirect(route('admin.customer.index'));

        $this->assertDatabaseMissing(Customer::class, $customer->only('id'));

        Storage::assertMissing(Customer::IDENTITYCARD_IMAGE_PATH . '/' . $customer->getRawOriginal('identitycard_image'));
    }

    public function test_assert_destroy_multiple()
    {
        Storage::fake();

        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\Customer> $customers */
        $customers = Collection::times(3, fn () => $this->createFromFactory());

        $this->actingAs($staff, 'web')
            ->delete(route('admin.customer.destroy-multiple', [
                'checkbox' => $customers->pluck('id')->toArray(),
            ]))
            ->assertRedirect(route('admin.customer.index'));

        foreach ($customers as $customer) {
            $this->assertDatabaseMissing(Customer::class, $customer->only('id'));

            Storage::assertMissing(Customer::IDENTITYCARD_IMAGE_PATH . '/' . $customer->getRawOriginal('identitycard_image'));
        }
    }

    public function test_assert_destroy_image()
    {
        Storage::fake();

        $staff = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\Customer> $customers */
        $customers = Collection::times(3, fn () => $this->createFromFactory());

        foreach ($customers as $customer) {
            $this->actingAs($staff, 'web')
                ->delete(route('admin.customer.destroy-identitycard_image', $customer))
                ->assertRedirect(route('admin.customer.edit', $customer));

            $this->assertDatabaseMissing(Customer::class, $customer->getAttributes());

            Storage::assertMissing(Customer::IDENTITYCARD_IMAGE_PATH . '/' . $customer->getRawOriginal('identitycard_image'));
        }
    }

    /**
     * Create a new resource from factory and store the given image.
     *
     * @return \App\Models\Customer
     */
    protected function createFromFactory(): Customer
    {
        $factory = Customer::factory()->raw();

        /**
         * If the customer factory data already exists in storage,
         * then retry the create factory process.
         */
        if (Customer::orWhere([
            ['email', $factory['email']],
            ['phone', $factory['phone']],
            ['whatsapp_phone', $factory['whatsapp_phone']],
        ])->count() > 0) {
            return $this->createFromFactory();
        }

        /** @var \App\Models\Customer $customer */
        $customer = Customer::make(Arr::except($factory, 'identitycard_image'));

        Storage::putFileAs(
            Customer::IDENTITYCARD_IMAGE_PATH,
            $factory['identitycard_image'],
            $filename = $factory['identitycard_image']->getClientOriginalName()
        );

        $customer->setAttribute('identitycard_image', $filename)->save();

        Storage::assertExists(Customer::IDENTITYCARD_IMAGE_PATH . '/' . $customer->getRawOriginal('image'));

        return $customer;
    }
}
