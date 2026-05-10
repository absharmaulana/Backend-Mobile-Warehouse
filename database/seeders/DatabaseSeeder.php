<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdmin = User::query()->updateOrCreate(
            ['email' => 'superadmin@warehouse.test'],
            [
                'name' => 'Super Admin',
                'password' => 'password123',
                'role' => User::ROLE_SUPER_ADMIN,
                'is_active' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@warehouse.test'],
            [
                'name' => 'Admin',
                'password' => 'password123',
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'finance@warehouse.test'],
            [
                'name' => 'Finance Officer',
                'password' => 'password123',
                'role' => User::ROLE_FINANCE,
                'is_active' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'pm@warehouse.test'],
            [
                'name' => 'Project Manager',
                'password' => 'password123',
                'role' => User::ROLE_PROJECT_MANAGER,
                'is_active' => true,
            ]
        );

        $item1 = Item::query()->updateOrCreate(
            ['code' => 'ITM-001'],
            [
                'name' => 'Cement 50kg',
                'description' => 'Cement bag 50kg',
                'stock' => 200,
                'unit_price' => 65000,
                'is_active' => true,
            ]
        );

        $item2 = Item::query()->updateOrCreate(
            ['code' => 'ITM-002'],
            [
                'name' => 'Steel Bar 10mm',
                'description' => 'Steel bar for construction',
                'stock' => 150,
                'unit_price' => 120000,
                'is_active' => true,
            ]
        );

        $invoice = Invoice::query()->updateOrCreate(
            ['number' => 'INV-SAMPLE-001'],
            [
                'created_by' => $superAdmin->id,
                'status' => Invoice::STATUS_POSTED,
                'invoice_date' => now()->toDateString(),
                'notes' => 'Sample seeded invoice',
                'subtotal' => 250000,
                'tax_amount' => 25000,
                'total_amount' => 275000,
            ]
        );

        InvoiceItem::query()->updateOrCreate(
            ['invoice_id' => $invoice->id, 'item_id' => $item1->id],
            [
                'quantity' => 2,
                'unit_price' => 65000,
                'line_total' => 130000,
            ]
        );

        InvoiceItem::query()->updateOrCreate(
            ['invoice_id' => $invoice->id, 'item_id' => $item2->id],
            [
                'quantity' => 1,
                'unit_price' => 120000,
                'line_total' => 120000,
            ]
        );
    }
}
