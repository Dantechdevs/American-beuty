<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. All permissions — names match EXACTLY what's in routes/web.php ──

        $permissions = [

            // Dashboard
            ['name' => 'dashboard.view',            'display_name' => 'View Dashboard',                'group' => 'Dashboard'],

            // Products
            ['name' => 'products.view',             'display_name' => 'View Products',                 'group' => 'Products'],
            ['name' => 'products.create',           'display_name' => 'Create Products',               'group' => 'Products'],
            ['name' => 'products.edit',             'display_name' => 'Edit Products',                 'group' => 'Products'],
            ['name' => 'products.delete',           'display_name' => 'Delete Products',               'group' => 'Products'],

            // Orders
            ['name' => 'orders.view',               'display_name' => 'View Orders & Returns',         'group' => 'Orders'],
            ['name' => 'orders.manage',             'display_name' => 'Manage Orders & Returns',       'group' => 'Orders'],

            // Categories
            ['name' => 'categories.view',           'display_name' => 'View Categories',               'group' => 'Categories'],
            ['name' => 'categories.create',         'display_name' => 'Create Categories',             'group' => 'Categories'],
            ['name' => 'categories.edit',           'display_name' => 'Edit Categories',               'group' => 'Categories'],
            ['name' => 'categories.delete',         'display_name' => 'Delete Categories',             'group' => 'Categories'],

            // Users
            ['name' => 'users.view',                'display_name' => 'View Users',                    'group' => 'Users & Roles'],
            ['name' => 'users.create',              'display_name' => 'Create Users',                  'group' => 'Users & Roles'],
            ['name' => 'users.edit',                'display_name' => 'Edit Users',                    'group' => 'Users & Roles'],
            ['name' => 'users.delete',              'display_name' => 'Delete Users',                  'group' => 'Users & Roles'],
            ['name' => 'roles.manage',              'display_name' => 'Manage Roles & Permissions',    'group' => 'Users & Roles'],

            // Settings
            ['name' => 'settings.view',             'display_name' => 'View Settings',                 'group' => 'Settings'],
            ['name' => 'settings.edit',             'display_name' => 'Edit Settings',                 'group' => 'Settings'],
            ['name' => 'settings.payment',          'display_name' => 'Manage Payment Gateways (M-PESA)', 'group' => 'Settings'],

            // Coupons
            ['name' => 'coupons.view',              'display_name' => 'View Coupons',                  'group' => 'Coupons & Promotions'],
            ['name' => 'coupons.manage',            'display_name' => 'Manage Coupons',                'group' => 'Coupons & Promotions'],

            // Promotions
            ['name' => 'promotions.view',           'display_name' => 'View Promotions',               'group' => 'Coupons & Promotions'],
            ['name' => 'promotions.manage',         'display_name' => 'Manage Promotions',             'group' => 'Coupons & Promotions'],

            // Transactions
            ['name' => 'transactions.view',         'display_name' => 'View Transactions',             'group' => 'Transactions'],
            ['name' => 'transactions.export',       'display_name' => 'Export Transactions',           'group' => 'Transactions'],
            ['name' => 'transactions.manage',       'display_name' => 'Update Transaction Status',     'group' => 'Transactions'],

            // POS
            ['name' => 'pos.access',                'display_name' => 'Access POS Terminal',           'group' => 'POS'],

            // Purchases
            ['name' => 'purchases.view',            'display_name' => 'View Purchases',                'group' => 'Purchases'],
            ['name' => 'purchases.create',          'display_name' => 'Create Purchases',              'group' => 'Purchases'],
            ['name' => 'purchases.edit',            'display_name' => 'Edit Purchases',                'group' => 'Purchases'],
            ['name' => 'purchases.delete',          'display_name' => 'Delete Purchases',              'group' => 'Purchases'],
            ['name' => 'purchases.return',          'display_name' => 'Process Purchase Returns',      'group' => 'Purchases'],

            // Suppliers
            ['name' => 'suppliers.view',            'display_name' => 'View Suppliers',                'group' => 'Suppliers'],
            ['name' => 'suppliers.create',          'display_name' => 'Create Suppliers',              'group' => 'Suppliers'],
            ['name' => 'suppliers.edit',            'display_name' => 'Edit Suppliers',                'group' => 'Suppliers'],
            ['name' => 'suppliers.delete',          'display_name' => 'Delete Suppliers',              'group' => 'Suppliers'],

            // Stock
            ['name' => 'stock.view',                'display_name' => 'View Stock & History',          'group' => 'Stock'],
            ['name' => 'stock.adjust',              'display_name' => 'Adjust Stock & Set Alerts',     'group' => 'Stock'],

            // Employees
            ['name' => 'employees.view',            'display_name' => 'View Employees',                'group' => 'Employees'],
            ['name' => 'employees.create',          'display_name' => 'Create Employees',              'group' => 'Employees'],
            ['name' => 'employees.edit',            'display_name' => 'Edit Employees',                'group' => 'Employees'],
            ['name' => 'employees.delete',          'display_name' => 'Delete Employees',              'group' => 'Employees'],

            // Shifts
            ['name' => 'shifts.view',               'display_name' => 'View Shifts',                   'group' => 'Attendance & Shifts'],
            ['name' => 'shifts.manage',             'display_name' => 'Create & Manage Shifts',        'group' => 'Attendance & Shifts'],

            // Attendance
            ['name' => 'attendance.view',           'display_name' => 'View Attendance & Reports',     'group' => 'Attendance & Shifts'],
            ['name' => 'attendance.terminal',       'display_name' => 'Use Clock-In/Out Terminal',     'group' => 'Attendance & Shifts'],
            ['name' => 'attendance.manage',         'display_name' => 'Override & Export Attendance',  'group' => 'Attendance & Shifts'],

            // Reports
            ['name' => 'reports.sales',             'display_name' => 'View Sales Reports',            'group' => 'Reports'],
            ['name' => 'reports.products',          'display_name' => 'View Product Reports',          'group' => 'Reports'],

            // Notifications
            ['name' => 'notifications.manage',      'display_name' => 'Send & Schedule Notifications', 'group' => 'Notifications & Subscribers'],

            // Subscribers
            ['name' => 'subscribers.view',          'display_name' => 'View Subscribers',              'group' => 'Notifications & Subscribers'],
            ['name' => 'subscribers.manage',        'display_name' => 'Manage & Message Subscribers',  'group' => 'Notifications & Subscribers'],

            // Appointments / Bookings
            ['name' => 'appointments.view',         'display_name' => 'View Bookings & Appointments',  'group' => 'Appointments'],
            ['name' => 'appointments.manage',       'display_name' => 'Manage Appointment Status',     'group' => 'Appointments'],

            // Logs
            ['name' => 'logs.view',                 'display_name' => 'View Activity Logs',            'group' => 'Logs'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        $all = Permission::pluck('name')->toArray();

        // ─── 2. Roles ────────────────────────────────────────────────────────

        // SUPER ADMIN — full access; HasRoles::can() also bypasses all checks for this role
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin'], [
            'display_name' => 'Super Admin',
            'description'  => 'Unrestricted access to everything.',
            'color'        => '#dc2626',
        ]);
        $superAdmin->syncPermissions($all);

        // ADMIN — everything except managing roles
        $admin = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Admin',
            'description'  => 'Full access except role/permission management.',
            'color'        => '#7c3aed',
        ]);
        $admin->syncPermissions(array_values(array_filter($all, fn($p) => $p !== 'roles.manage')));

        // MANAGER — operations: products, orders, stock, reports, employees. No users/roles/settings/logs.
        $manager = Role::firstOrCreate(['name' => 'manager'], [
            'display_name' => 'Manager',
            'description'  => 'Manages products, orders, stock, reports, and employees.',
            'color'        => '#2563eb',
        ]);
        $manager->syncPermissions([
            'dashboard.view',
            'products.view', 'products.create', 'products.edit',
            'orders.view', 'orders.manage',
            'categories.view', 'categories.create', 'categories.edit',
            'coupons.view', 'coupons.manage',
            'promotions.view', 'promotions.manage',
            'transactions.view', 'transactions.export',
            'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.return',
            'suppliers.view', 'suppliers.create', 'suppliers.edit',
            'stock.view', 'stock.adjust',
            'employees.view', 'employees.create', 'employees.edit',
            'shifts.view', 'shifts.manage',
            'attendance.view', 'attendance.manage',
            'reports.sales', 'reports.products',
            'subscribers.view',
            'appointments.view', 'appointments.manage',
            'notifications.manage',
        ]);

        // POS OPERATOR — POS, sales, order lookups, attendance terminal
        $posOperator = Role::firstOrCreate(['name' => 'pos-operator'], [
            'display_name' => 'POS Operator',
            'description'  => 'POS sales, order management, and clock-in/out.',
            'color'        => '#0891b2',
        ]);
        $posOperator->syncPermissions([
            'dashboard.view',
            'products.view',
            'pos.access',
            'orders.view', 'orders.manage',
            'transactions.view',
            'attendance.terminal',
        ]);

        // STOCK KEEPER — inventory, purchases, suppliers
        $stockKeeper = Role::firstOrCreate(['name' => 'stock-keeper'], [
            'display_name' => 'Stock Keeper',
            'description'  => 'Manages stock levels, purchases, and suppliers.',
            'color'        => '#d97706',
        ]);
        $stockKeeper->syncPermissions([
            'dashboard.view',
            'products.view',
            'stock.view', 'stock.adjust',
            'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.return',
            'suppliers.view', 'suppliers.create', 'suppliers.edit',
            'reports.products',
        ]);

        // HR — employees, shifts, attendance only
        $hr = Role::firstOrCreate(['name' => 'hr'], [
            'display_name' => 'HR',
            'description'  => 'Manages employees, shifts, and attendance records.',
            'color'        => '#be185d',
        ]);
        $hr->syncPermissions([
            'dashboard.view',
            'employees.view', 'employees.create', 'employees.edit',
            'shifts.view', 'shifts.manage',
            'attendance.view', 'attendance.terminal', 'attendance.manage',
        ]);

        // ─── 3. Assign super-admin to first user ─────────────────────────────
        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->assignRole('super-admin');
            $this->command->info("✅ Super-admin assigned to: {$firstUser->email}");
        }

        // ─── 4. Summary ──────────────────────────────────────────────────────
        $this->command->info("\n✅ Seeded " . count($permissions) . " permissions across " . Role::count() . " roles.\n");
        $this->command->table(
            ['Role', 'Permissions'],
            Role::withCount('permissions')->get()
                ->map(fn($r) => [$r->display_name, $r->permissions_count])
                ->toArray()
        );
    }
}