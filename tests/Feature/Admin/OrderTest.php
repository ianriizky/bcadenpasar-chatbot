<?php

namespace Tests\Feature\Admin;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Role;
use Illuminate\Support\Arr;
use Tests\Feature\Concerns\HandleAuthentication;
use Tests\Feature\Concerns\HandleDataTables;
use Tests\Feature\TestCase;

class OrderTest extends TestCase
{
    use HandleAuthentication, HandleDataTables;

    public function test_assert_index()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($admin, 'web')
            ->get(route('admin.order.index'))
            ->assertOk();
    }

    public function test_assert_datatable()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $this->actingAs($admin, 'web')
            ->post(route('admin.order.datatable'))
            ->assertOk()
            ->assertJsonStructure($this->getDataTablesFormat());
    }

    // public function test_assert_create()
    // {
    //     $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

    //     $this->actingAs($admin, 'web')
    //         ->get(route('admin.order.create'))
    //         ->assertOk();
    // }

    // public function test_assert_store()
    // {
    //     $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

    //     $data = Order::factory()
    //         ->for(Customer::factory())
    //         ->raw([
    //             'order_status' => OrderStatus::factory()->draft()->raw(),
    //         ]);

    //     foreach (Item::factory()->count(3)->raw() as $key => $item) {
    //         foreach ($item as $attribute => $value) {
    //             data_set($data, 'items.' . $key . '.' . $attribute, $value);
    //         }
    //     }

    //     $this->actingAs($admin, 'web')
    //         ->post(route('admin.order.store'), $data)
    //         ->assertRedirect(route('admin.order.index'));

    //     $this->assertDatabaseHas(Order::class, Arr::only($data, 'schedule_date'));
    // }

    // public function test_assert_edit()
    // {
    //     $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

    //     $order = Order::factory()
    //         ->for($customer = Customer::factory())
    //         ->has(Item::factory()->count(3))
    //         ->has(OrderStatus::factory()->for($customer, 'issuerable')->draft(), 'statuses')
    //         ->create();

    //     $this->actingAs($admin, 'web')
    //         ->get(route('admin.order.show', $order))
    //         ->assertOk();
    // }

    // public function test_assert_update()
    // {
    //     $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

    //     $order = Order::factory()->create();

    //     $this->actingAs($admin, 'web')
    //         ->put(route('admin.order.???', $order), $data = Order::factory()->raw())
    //         ->assertRedirect(route('admin.order.show', Order::firstWhere('code', $data['code'])));

    //     $this->assertDatabaseHas(Order::class, $data);
    // }

    public function test_assert_destroy()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $order = Order::factory()
            ->for($customer = Customer::factory())
            ->has(Item::factory()->count(3))
            ->has(OrderStatus::factory()->for($customer, 'issuerable')->draft(), 'statuses')
            ->create();

        $this->actingAs($admin, 'web')
            ->delete(route('admin.order.destroy', $order))
            ->assertRedirect(route('admin.order.index'));

        $this->assertDatabaseMissing(Order::class, $order->only('id'));
    }

    public function test_assert_destroy_multiple()
    {
        $admin = $this->createUserFromFactory()->syncRoles(Role::ROLE_ADMIN);

        $orders = Order::factory()->count(3)
            ->for($customer = Customer::factory())
            ->has(Item::factory()->count(3))
            ->has(OrderStatus::factory()->for($customer, 'issuerable')->draft(), 'statuses')
            ->create();

        $this->actingAs($admin, 'web')
            ->delete(route('admin.order.destroy-multiple', [
                'checkbox' => $orders->pluck('id')->toArray(),
            ]))
            ->assertRedirect(route('admin.order.index'));

        foreach ($orders as $order) {
            $this->assertDatabaseMissing(Order::class, $order->only('id'));
        }
    }
}
