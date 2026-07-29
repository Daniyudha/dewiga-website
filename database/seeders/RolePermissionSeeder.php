<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Dashboard
            ['name' => 'Lihat Dashboard', 'slug' => 'dashboard.view', 'group' => 'Dashboard'],
            
            // Bookings
            ['name' => 'Lihat Booking', 'slug' => 'bookings.view', 'group' => 'Booking'],
            ['name' => 'Tambah Booking', 'slug' => 'bookings.create', 'group' => 'Booking'],
            ['name' => 'Edit Booking', 'slug' => 'bookings.edit', 'group' => 'Booking'],
            ['name' => 'Hapus Booking', 'slug' => 'bookings.delete', 'group' => 'Booking'],
            ['name' => 'Konfirmasi Booking', 'slug' => 'bookings.confirm', 'group' => 'Booking'],
            
            // Schedules
            ['name' => 'Lihat Jadwal', 'slug' => 'schedules.view', 'group' => 'Jadwal'],
            ['name' => 'Tambah Jadwal', 'slug' => 'schedules.create', 'group' => 'Jadwal'],
            ['name' => 'Edit Jadwal', 'slug' => 'schedules.edit', 'group' => 'Jadwal'],
            ['name' => 'Hapus Jadwal', 'slug' => 'schedules.delete', 'group' => 'Jadwal'],
            
            // Open Trips
            ['name' => 'Lihat Open Trip', 'slug' => 'open_trips.view', 'group' => 'Open Trip'],
            ['name' => 'Tambah Open Trip', 'slug' => 'open_trips.create', 'group' => 'Open Trip'],
            ['name' => 'Edit Open Trip', 'slug' => 'open_trips.edit', 'group' => 'Open Trip'],
            
            // Travel Packages
            ['name' => 'Lihat Paket', 'slug' => 'packages.view', 'group' => 'Paket Wisata'],
            ['name' => 'Tambah Paket', 'slug' => 'packages.create', 'group' => 'Paket Wisata'],
            ['name' => 'Edit Paket', 'slug' => 'packages.edit', 'group' => 'Paket Wisata'],
            ['name' => 'Hapus Paket', 'slug' => 'packages.delete', 'group' => 'Paket Wisata'],
            
            // Proposals
            ['name' => 'Lihat Proposal', 'slug' => 'proposals.view', 'group' => 'Proposal'],
            ['name' => 'Tambah Proposal', 'slug' => 'proposals.create', 'group' => 'Proposal'],
            ['name' => 'Edit Proposal', 'slug' => 'proposals.edit', 'group' => 'Proposal'],
            ['name' => 'Hapus Proposal', 'slug' => 'proposals.delete', 'group' => 'Proposal'],
            
            // Transactions
            ['name' => 'Lihat Transaksi', 'slug' => 'transactions.view', 'group' => 'Keuangan'],
            ['name' => 'Tambah Transaksi', 'slug' => 'transactions.create', 'group' => 'Keuangan'],
            ['name' => 'Edit Transaksi', 'slug' => 'transactions.edit', 'group' => 'Keuangan'],
            ['name' => 'Hapus Transaksi', 'slug' => 'transactions.delete', 'group' => 'Keuangan'],
            
            // Guests
            ['name' => 'Lihat Tamu', 'slug' => 'guests.view', 'group' => 'Database Tamu'],
            ['name' => 'Tambah Tamu', 'slug' => 'guests.create', 'group' => 'Database Tamu'],
            ['name' => 'Edit Tamu', 'slug' => 'guests.edit', 'group' => 'Database Tamu'],
            ['name' => 'Hapus Tamu', 'slug' => 'guests.delete', 'group' => 'Database Tamu'],
            
            // Content Management
            ['name' => 'Kelola Blog', 'slug' => 'blogs.manage', 'group' => 'Konten'],
            ['name' => 'Kelola Galeri', 'slug' => 'galleries.manage', 'group' => 'Konten'],
            ['name' => 'Kelola Testimoni', 'slug' => 'testimonials.manage', 'group' => 'Konten'],
            ['name' => 'Kelola Hero', 'slug' => 'hero.manage', 'group' => 'Konten'],
            ['name' => 'Kelola Partner Logo', 'slug' => 'partners.manage', 'group' => 'Konten'],
            ['name' => 'Kelola Kategori', 'slug' => 'categories.manage', 'group' => 'Konten'],
            
            // Users & Roles
            ['name' => 'Lihat Pengguna', 'slug' => 'users.view', 'group' => 'Pengguna'],
            ['name' => 'Tambah Pengguna', 'slug' => 'users.create', 'group' => 'Pengguna'],
            ['name' => 'Edit Pengguna', 'slug' => 'users.edit', 'group' => 'Pengguna'],
            ['name' => 'Hapus Pengguna', 'slug' => 'users.delete', 'group' => 'Pengguna'],
            ['name' => 'Kelola Role', 'slug' => 'roles.manage', 'group' => 'Pengguna'],
        ];

        $permissionModels = [];
        foreach ($permissions as $perm) {
            $permissionModels[$perm['slug']] = Permission::firstOrCreate(
                ['slug' => $perm['slug']],
                $perm
            );
        }

        // Create Roles
        $superAdmin = Role::firstOrCreate(
            ['slug' => 'super_admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Akses penuh ke semua fitur',
                'is_default' => false,
            ]
        );

        $admin = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Admin',
                'description' => 'Akses ke fitur operasional dan konten',
                'is_default' => false,
            ]
        );

        $finance = Role::firstOrCreate(
            ['slug' => 'finance'],
            [
                'name' => 'Finance',
                'description' => 'Akses ke fitur transaksi dan keuangan',
                'is_default' => false,
            ]
        );

        $contentManager = Role::firstOrCreate(
            ['slug' => 'content_manager'],
            [
                'name' => 'Content Manager',
                'description' => 'Mengelola konten website',
                'is_default' => false,
            ]
        );

        // Super Admin gets all permissions
        $superAdmin->permissions()->sync(Permission::pluck('id'));

        // Admin permissions (operational + content)
        $admin->syncPermissions([
            'dashboard.view',
            'bookings.view', 'bookings.create', 'bookings.edit', 'bookings.confirm',
            'schedules.view', 'schedules.create', 'schedules.edit',
            'open_trips.view', 'open_trips.create', 'open_trips.edit',
            'packages.view', 'packages.create', 'packages.edit',
            'proposals.view', 'proposals.create', 'proposals.edit',
            'guests.view', 'guests.create', 'guests.edit',
            'blogs.manage', 'galleries.manage', 'testimonials.manage',
            'hero.manage', 'partners.manage', 'categories.manage',
            'transactions.view',
        ]);

        // Finance permissions
        $finance->syncPermissions([
            'dashboard.view',
            'transactions.view', 'transactions.create', 'transactions.edit',
            'bookings.view',
            'guests.view',
        ]);

        // Content Manager permissions
        $contentManager->syncPermissions([
            'dashboard.view',
            'blogs.manage', 'galleries.manage', 'testimonials.manage',
            'hero.manage', 'partners.manage', 'categories.manage',
            'packages.view', 'packages.create', 'packages.edit',
        ]);

        // Assign Super Admin role to existing admin users
        $adminUser = User::where('email', 'admin@dewiga.com')->first();
        if ($adminUser) {
            $adminUser->roles()->sync([$superAdmin->id]);
        }
    }
}