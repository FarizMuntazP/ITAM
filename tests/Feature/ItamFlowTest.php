<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Store;
use App\Models\Asset;
use App\Models\AssetLoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItamFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_flow(): void
    {
        // 1. Unauthenticated redirect
        $response = $this->get('/');
        $response->assertRedirect('/login');

        // 2. Seed default admin user
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        // 3. Login submit
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_asset_creation_generates_qr_code(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        $response = $this->actingAs($user)->post('/assets', [
            'asset_name' => 'MacBook Pro M3',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'brand' => 'Apple',
            'model' => 'M3 16 Inch',
            'serial_number' => 'APPLE123456',
            'condition' => 'good',
            'status' => 'active',
        ]);

        $asset = Asset::first();

        if (!$asset || !$asset->qr_code_path) {
            dd($response->getContent() ?: session('errors')?->all());
        }
        $this->assertNotNull($asset);
        $this->assertEquals('ITAM-NTB-0001', $asset->asset_id);
        $this->assertNotNull($asset->qr_code_path);
        
        Storage::disk('public')->assertExists($asset->qr_code_path);
    }

    public function test_asset_detail_shows_combined_media_card(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        $asset = Asset::create([
            'asset_id' => 'ITAM-NTB-2026',
            'asset_name' => 'Lenovo Yoga Media',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'added_at' => now(),
            'qr_code_path' => 'qrcodes/qr_ITAM-NTB-2026.svg',
        ]);

        $response = $this->actingAs($user)->get("/assets/{$asset->id}");

        $response->assertStatus(200);
        $response->assertSee('Media Aset');
        $response->assertSee('QR Code');
        $response->assertSee('Scan untuk melihat data aset');
        $response->assertDontSee('<h3 class="text-sm font-semibold text-[var(--color-text-secondary)] uppercase tracking-wider mb-3">Foto Aset</h3>', false);
    }

    public function test_asset_detail_layout_aligns_assignment_media_and_notes_cards(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        $asset = Asset::create([
            'asset_id' => 'ITAM-NTB-2027',
            'asset_name' => 'Lenovo Yoga Layout',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'added_at' => now(),
            'notes' => 'Catatan layout aset',
            'qr_code_path' => 'qrcodes/qr_ITAM-NTB-2027.svg',
        ]);

        $response = $this->actingAs($user)->get("/assets/{$asset->id}");

        $response->assertStatus(200);
        $response->assertSee('lg:col-span-2 flex h-full flex-col gap-6', false);
        $response->assertSee('card flex-1', false);
        $response->assertDontSee('lg:col-span-2 space-y-6', false);
    }

    public function test_export_and_template_routes(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        // Access template
        $response = $this->actingAs($user)->get('/assets/template');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Access export
        $response = $this->actingAs($user)->get('/assets/export');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_store_crud_and_validation(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        // 1. Create Store
        $response = $this->actingAs($user)->post('/stores', [
            'store_code' => 'STR-999',
            'store_name' => 'Test Store',
            'location' => 'Test Location',
            'region' => 'Test Region',
        ]);
        $response->assertRedirect('/stores');
        $this->assertDatabaseHas('stores', ['store_code' => 'STR-999']);

        $store = Store::where('store_code', 'STR-999')->first();

        // 2. Edit Store
        $response = $this->actingAs($user)->put("/stores/{$store->id}", [
            'store_code' => 'STR-999',
            'store_name' => 'Updated Store Name',
            'location' => 'Test Location',
            'region' => 'Test Region',
        ]);
        $response->assertRedirect('/stores');
        $this->assertDatabaseHas('stores', ['store_name' => 'Updated Store Name']);

        // 3. Delete Store
        $response = $this->actingAs($user)->delete("/stores/{$store->id}");
        $response->assertRedirect('/stores');
        $this->assertDatabaseMissing('stores', ['id' => $store->id]);
    }

    public function test_category_crud_and_validation(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        // 1. Create Category
        $response = $this->actingAs($user)->post('/categories', [
            'category_code' => 'MON',
            'category_name' => 'Monitor',
            'description' => 'Display screens',
        ]);
        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['category_code' => 'MON']);

        $category = Category::where('category_code', 'MON')->first();

        // 2. Edit Category
        $response = $this->actingAs($user)->put("/categories/{$category->id}", [
            'category_code' => 'MON',
            'category_name' => 'Updated Monitor Name',
            'description' => 'Display screens updated',
        ]);
        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['category_name' => 'Updated Monitor Name']);

        // 3. Delete Category
        $response = $this->actingAs($user)->delete("/categories/{$category->id}");
        $response->assertRedirect('/categories');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_delete_protection_for_store_and_category(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        $asset = Asset::create([
            'asset_id' => 'ITAM-NTB-0001',
            'asset_name' => 'MacBook Pro M3',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'added_at' => now(),
        ]);

        // Attempt delete store (should fail validation/protection)
        $response = $this->actingAs($user)->delete("/stores/{$store->id}");
        $response->assertRedirect('/stores');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('stores', ['id' => $store->id]);

        // Attempt delete category (should fail validation/protection)
        $response = $this->actingAs($user)->delete("/categories/{$category->id}");
        $response->assertRedirect('/categories');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_excel_import_logic(): void
    {
        Storage::fake('public');

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        $import = new \App\Imports\AssetsImport();
        $import->collection(collect([
            [
                'asset_name' => 'MacBook Air M2',
                'category_code' => 'NTB',
                'store_code' => 'STR-001',
                'brand' => 'Apple',
                'model' => 'M2 13 Inch',
                'serial_number' => 'AIR123456',
                'condition' => 'good',
                'status' => 'active',
                'purchase_date' => '2023-05-10',
                'purchase_price' => '15000000',
            ]
        ]));

        $this->assertEquals(1, $import->getSuccessCount());
        $this->assertEquals(0, $import->getFailedCount());
        $this->assertCount(0, $import->getErrors());

        $asset = Asset::where('serial_number', 'AIR123456')->first();
        $this->assertNotNull($asset);
        $this->assertEquals('ITAM-NTB-0001', $asset->asset_id);
        $this->assertNotNull($asset->qr_code_path);
        
        Storage::disk('public')->assertExists($asset->qr_code_path);
    }

    public function test_dashboard_analytics_data(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        Asset::create([
            'asset_id' => 'ITAM-NTB-0001',
            'asset_name' => 'MacBook Pro M3',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'added_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertViewHas('conditionsData');
        $response->assertViewHas('statusesData');
        $response->assertViewHas('topCategories');
        $response->assertViewHas('topStores');

        $viewConditions = $response->viewData('conditionsData');
        $this->assertEquals(1, $viewConditions['good']);
        $this->assertEquals(0, $viewConditions['damaged']);

        $viewStatuses = $response->viewData('statusesData');
        $this->assertEquals(1, $viewStatuses['active']);
        $this->assertEquals(0, $viewStatuses['disposed']);
    }

    public function test_sidebar_does_not_show_asset_import_export_links(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Audit');
        $response->assertDontSee('Tools');
        $response->assertDontSee('Import Excel');
        $response->assertDontSee('Export Excel');
    }

    public function test_uploaded_photo_is_compressed_and_resized(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        // Create a large fake image (e.g. 2000x1500 px)
        $largePhoto = \Illuminate\Http\UploadedFile::fake()->image('my_notebook.png', 2000, 1500);

        $response = $this->actingAs($user)->post('/assets', [
            'asset_name' => 'Dell Latitude 5420',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'brand' => 'Dell',
            'model' => 'Latitude 5420',
            'serial_number' => 'DELL7890',
            'condition' => 'good',
            'status' => 'active',
            'photo' => $largePhoto,
        ]);

        $asset = Asset::where('serial_number', 'DELL7890')->first();
        $this->assertNotNull($asset);
        $this->assertNotNull($asset->photo);
        
        // Assert filename extension is .jpg
        $this->assertStringEndsWith('.jpg', $asset->photo);

        // Assert file exists on storage
        Storage::disk('public')->assertExists($asset->photo);

        // Assert size/dimension is resized (max width 1200px, aspect ratio 4:3 means 1200x900)
        $filePath = Storage::disk('public')->path($asset->photo);
        list($width, $height) = getimagesize($filePath);

        $this->assertEquals(1200, $width);
        $this->assertEquals(900, $height);

        // --- Test Update Photo ---
        $newPhoto = \Illuminate\Http\UploadedFile::fake()->image('updated_notebook.png', 1800, 1800);
        $oldPhotoPath = $asset->photo;

        $response = $this->actingAs($user)->put('/assets/' . $asset->id, [
            'asset_name' => 'Dell Latitude 5420 Updated',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'brand' => 'Dell',
            'model' => 'Latitude 5420',
            'serial_number' => 'DELL7890',
            'condition' => 'good',
            'status' => 'active',
            'photo' => $newPhoto,
        ]);

        $asset->refresh();

        // Old photo should be deleted from storage
        Storage::disk('public')->assertMissing($oldPhotoPath);

        // New photo should exist and be compressed/resized
        $this->assertNotNull($asset->photo);
        $this->assertStringEndsWith('.jpg', $asset->photo);
        Storage::disk('public')->assertExists($asset->photo);

        $newFilePath = Storage::disk('public')->path($asset->photo);
        list($newWidth, $newHeight) = getimagesize($newFilePath);
        $this->assertEquals(1200, $newWidth);
        $this->assertEquals(1200, $newHeight);
    }

    public function test_employee_crud_and_validation(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        // 1. Create Employee
        $response = $this->actingAs($user)->post('/employees', [
            'name' => 'Budi Santoso',
            'email' => 'budi@company.com',
            'department' => 'IT Support',
            'phone' => '081234567890',
        ]);
        $response->assertRedirect('/employees');
        $this->assertDatabaseHas('employees', [
            'name' => 'Budi Santoso',
            'email' => 'budi@company.com',
        ]);

        $employee = \App\Models\Employee::where('email', 'budi@company.com')->first();
        $this->assertNotNull($employee->employee_code);
        $this->assertStringStartsWith('EMP-', $employee->employee_code);

        // Create asset and active loan for testing employee edit specifications display
        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        $asset = Asset::create([
            'asset_id' => 'ITAM-NTB-9999',
            'asset_name' => 'Lenovo ThinkPad X1',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'brand' => 'Lenovo',
            'model' => 'X1 Carbon Gen 11',
            'serial_number' => 'SN-THINKPAD-9999',
            'specs' => 'RAM 32GB, SSD 1TB, Intel Core i7',
            'condition' => 'good',
            'status' => 'active',
            'current_employee_id' => $employee->id,
        ]);

        $loan = AssetLoan::create([
            'asset_id' => $asset->id,
            'employee_id' => $employee->id,
            'loaned_by' => $user->id,
            'loan_date' => now(),
            'status' => 'active',
        ]);

        // Access edit view and verify specifications display
        $response = $this->actingAs($user)->get("/employees/{$employee->id}/edit");
        $response->assertStatus(200);
        $response->assertSee('Lenovo ThinkPad X1');
        $response->assertSee('X1 Carbon Gen 11');
        $response->assertSee('SN-THINKPAD-9999');
        $response->assertSee('RAM 32GB, SSD 1TB, Intel Core i7');
        $response->assertSee(now()->format('d M Y'));

        // 2. Edit Employee
        $response = $this->actingAs($user)->put("/employees/{$employee->id}", [
            'name' => 'Budi Santoso Updated',
            'email' => 'budi.updated@company.com',
            'department' => 'IT Ops',
            'phone' => '081234567899',
        ]);
        $response->assertRedirect('/employees');
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => 'Budi Santoso Updated',
            'email' => 'budi.updated@company.com',
        ]);

        // Clean up loan and asset before delete
        $loan->delete();
        $asset->delete();

        // 3. Delete Employee
        $response = $this->actingAs($user)->delete("/employees/{$employee->id}");
        $response->assertRedirect('/employees');
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_employee_index_sorting(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        // Create 3 employees with different department and asset counts
        $empA = \App\Models\Employee::create([
            'name' => 'Alpha Employee',
            'email' => 'alpha@company.com',
            'department' => 'Finance',
        ]);

        $empB = \App\Models\Employee::create([
            'name' => 'Beta Employee',
            'email' => 'beta@company.com',
            'department' => 'Analytics',
        ]);

        $empC = \App\Models\Employee::create([
            'name' => 'Gamma Employee',
            'email' => 'gamma@company.com',
            'department' => 'Customer Service',
        ]);

        // Assign 2 assets to Beta (empB)
        Asset::create([
            'asset_id' => 'ITAM-NTB-8801',
            'asset_name' => 'Laptop Beta 1',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'current_employee_id' => $empB->id,
        ]);

        Asset::create([
            'asset_id' => 'ITAM-NTB-8802',
            'asset_name' => 'Laptop Beta 2',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'current_employee_id' => $empB->id,
        ]);

        // Assign 1 asset to Gamma (empC)
        Asset::create([
            'asset_id' => 'ITAM-NTB-8803',
            'asset_name' => 'Laptop Gamma 1',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'current_employee_id' => $empC->id,
        ]);

        // 1. Sort by department ASC (Analytics, Customer Service, Finance)
        $response = $this->actingAs($user)->get('/employees?sort=department&direction=asc');
        $response->assertStatus(200);
        $content = $response->getContent();
        $posB = strpos($content, 'Beta Employee');
        $posC = strpos($content, 'Gamma Employee');
        $posA = strpos($content, 'Alpha Employee');
        $this->assertTrue($posB < $posC);
        $this->assertTrue($posC < $posA);

        // 2. Sort by department DESC (Finance, Customer Service, Analytics)
        $response = $this->actingAs($user)->get('/employees?sort=department&direction=desc');
        $response->assertStatus(200);
        $content = $response->getContent();
        $posB = strpos($content, 'Beta Employee');
        $posC = strpos($content, 'Gamma Employee');
        $posA = strpos($content, 'Alpha Employee');
        $this->assertTrue($posA < $posC);
        $this->assertTrue($posC < $posB);

        // 3. Sort by assets_count ASC (Alpha [0], Gamma [1], Beta [2])
        $response = $this->actingAs($user)->get('/employees?sort=assets_count&direction=asc');
        $response->assertStatus(200);
        $content = $response->getContent();
        $posB = strpos($content, 'Beta Employee');
        $posC = strpos($content, 'Gamma Employee');
        $posA = strpos($content, 'Alpha Employee');
        $this->assertTrue($posA < $posC);
        $this->assertTrue($posC < $posB);

        // 4. Sort by assets_count DESC (Beta [2], Gamma [1], Alpha [0])
        $response = $this->actingAs($user)->get('/employees?sort=assets_count&direction=desc');
        $response->assertStatus(200);
        $content = $response->getContent();
        $posB = strpos($content, 'Beta Employee');
        $posC = strpos($content, 'Gamma Employee');
        $posA = strpos($content, 'Alpha Employee');
        $this->assertTrue($posB < $posC);
        $this->assertTrue($posC < $posA);
    }

    public function test_asset_checkout_and_checkin(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        $asset = Asset::create([
            'asset_id' => 'ITAM-NTB-0001',
            'asset_name' => 'MacBook Pro M3',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'added_at' => now(),
        ]);

        $employee = \App\Models\Employee::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@company.com',
            'department' => 'IT Support',
        ]);

        // 1. Checkout (Assign Asset)
        $response = $this->actingAs($user)->post("/assets/{$asset->id}/checkout", [
            'employee_id' => $employee->id,
            'notes' => 'Serah terima laptop baru',
        ]);
        $response->assertRedirect();
        
        $asset->refresh();
        $this->assertEquals($employee->id, $asset->current_employee_id);
        $this->assertDatabaseHas('asset_loans', [
            'asset_id' => $asset->id,
            'employee_id' => $employee->id,
            'status' => 'active',
            'notes' => 'Serah terima laptop baru',
        ]);

        // 2. Checkin (Return Asset)
        $response = $this->actingAs($user)->post("/assets/{$asset->id}/checkin", [
            'notes' => 'Dikembalikan dengan mulus',
        ]);
        $response->assertRedirect();

        $asset->refresh();
        $this->assertNull($asset->current_employee_id);
        $this->assertDatabaseHas('asset_loans', [
            'asset_id' => $asset->id,
            'employee_id' => $employee->id,
            'status' => 'returned',
        ]);
    }

    public function test_employee_loan_history_displayed_on_edit_page(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        $employee = \App\Models\Employee::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.history@company.com',
            'department' => 'IT Support',
        ]);

        $asset = Asset::create([
            'asset_id' => 'ITAM-NTB-7701',
            'asset_name' => 'Dell Latitude History',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'added_at' => now(),
        ]);

        $this->actingAs($user)->post("/assets/{$asset->id}/checkout", [
            'employee_id' => $employee->id,
            'notes' => 'Dipinjam untuk onboarding staff baru',
        ])->assertRedirect();

        $this->actingAs($user)->post("/assets/{$asset->id}/checkin", [
            'notes' => 'Dikembalikan setelah onboarding selesai',
        ])->assertRedirect();

        $response = $this->actingAs($user)->get("/employees/{$employee->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Riwayat Penugasan Aset');
        $response->assertSee('ITAM-NTB-7701');
        $response->assertSee('Dell Latitude History');
        $response->assertSee('Notebook');
        $response->assertSee(now()->format('d M Y'));
        $response->assertSee('Kembali');
        $response->assertSee('Admin ITAM');
        $response->assertSee('Dipinjam untuk onboarding staff baru');
        $response->assertSee('Dikembalikan setelah onboarding selesai');
    }

    public function test_asset_and_employee_loan_history_show_duration(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        $employee = \App\Models\Employee::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.duration@company.com',
            'department' => 'IT Support',
        ]);

        $asset = Asset::create([
            'asset_id' => 'ITAM-NTB-7702',
            'asset_name' => 'Dell Latitude Duration',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'added_at' => now(),
        ]);

        AssetLoan::create([
            'asset_id' => $asset->id,
            'employee_id' => $employee->id,
            'loaned_by' => $user->id,
            'returned_by' => $user->id,
            'loan_date' => now()->subDays(3)->toDateString(),
            'return_date' => now()->toDateString(),
            'status' => 'returned',
            'notes' => 'Durasi tiga hari',
        ]);

        $assetResponse = $this->actingAs($user)->get("/assets/{$asset->id}");
        $assetResponse->assertStatus(200);
        $assetResponse->assertSee('Durasi');
        $assetResponse->assertSee('3 hari');

        $employeeResponse = $this->actingAs($user)->get("/employees/{$employee->id}/edit");
        $employeeResponse->assertStatus(200);
        $employeeResponse->assertSee('Durasi');
        $employeeResponse->assertSee('3 hari');
    }

    public function test_asset_observer_logs_activities(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        $this->actingAs($user);

        // This triggers observer's 'created' event
        $asset = Asset::create([
            'asset_id' => 'ITAM-NTB-0001',
            'asset_name' => 'MacBook Pro M3',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'notes' => 'Catatan awal',
            'specs' => 'RAM 8GB',
            'purchase_price' => 15000000,
            'added_at' => now(),
        ]);

        $this->assertDatabaseHas('asset_activities', [
            'asset_id' => $asset->id,
            'action' => 'created',
        ]);

        // This triggers observer's 'updating' event
        $response = $this->actingAs($user)->put("/assets/{$asset->id}", [
            'asset_name' => 'MacBook Pro M3 Pro',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'poor',
            'status' => 'active',
            'notes' => 'Catatan baru diubah oleh admin',
            'specs' => 'RAM 16GB',
            'purchase_price' => 18000000,
        ]);

        $this->assertDatabaseHas('asset_activities', [
            'asset_id' => $asset->id,
            'action' => 'updated',
        ]);

        $latestActivity = \App\Models\AssetActivity::where('asset_id', $asset->id)
            ->where('action', 'updated')
            ->first();

        $this->assertNotNull($latestActivity);
        $this->assertStringContainsString("Catatan diubah menjadi 'Catatan baru diubah oleh admin'", $latestActivity->description);
        $this->assertStringContainsString("Spesifikasi diubah menjadi 'RAM 16GB'", $latestActivity->description);
        $this->assertStringContainsString("Harga pembelian diubah dari 'Rp 15.000.000' ke 'Rp 18.000.000'", $latestActivity->description);
    }


    public function test_asset_maintenance_flow(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        $asset = Asset::create([
            'asset_id' => 'ITAM-NTB-0001',
            'asset_name' => 'MacBook Pro M3',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'added_at' => now(),
        ]);

        // 1. Add maintenance log and set asset status to maintenance
        $response = $this->actingAs($user)->post("/assets/{$asset->id}/maintenances", [
            'issue' => 'Keyboard double-typing on spaces',
            'start_date' => '2026-05-22',
            'status' => 'in_progress',
            'change_asset_status' => 'true',
        ]);

        $response->assertRedirect(route('assets.show', $asset));
        
        $this->assertDatabaseHas('asset_maintenances', [
            'asset_id' => $asset->id,
            'issue' => 'Keyboard double-typing on spaces',
            'status' => 'in_progress',
        ]);

        $asset->refresh();
        $this->assertEquals('maintenance', $asset->status);

        $maintenance = $asset->maintenances()->first();
        $this->assertNotNull($maintenance);

        // 2. Complete maintenance and restore asset status to active
        $response = $this->actingAs($user)->put("/assets/maintenances/{$maintenance->id}", [
            'issue' => 'Keyboard double-typing on spaces (updated)',
            'start_date' => '2026-05-22',
            'end_date' => '2026-05-23',
            'status' => 'completed',
            'cost' => '1200000',
            'performed_by' => 'Apple Service Vendor',
            'solution' => 'Replaced keyboard topcase',
            'restore_asset_status' => 'true',
        ]);

        $response->assertRedirect(route('assets.show', $asset));

        $this->assertDatabaseHas('asset_maintenances', [
            'id' => $maintenance->id,
            'status' => 'completed',
            'cost' => 1200000.00,
            'performed_by' => 'Apple Service Vendor',
            'solution' => 'Replaced keyboard topcase',
        ]);

        $asset->refresh();
        $this->assertEquals('active', $asset->status);

        // 3. Delete maintenance log
        $response = $this->actingAs($user)->delete("/assets/maintenances/{$maintenance->id}");
        $response->assertRedirect(route('assets.show', $asset));

        $this->assertDatabaseMissing('asset_maintenances', [
            'id' => $maintenance->id,
        ]);
    }

    public function test_asset_index_sorting(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        // Create 3 assets with different added_at dates
        // Asset A: Oldest (1 year ago)
        $assetA = Asset::create([
            'asset_id' => 'ITAM-NTB-0001',
            'asset_name' => 'Laptop A Oldest',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'added_at' => now()->subYear(),
        ]);

        // Asset B: Middle (1 month ago)
        $assetB = Asset::create([
            'asset_id' => 'ITAM-NTB-0002',
            'asset_name' => 'Laptop B Middle',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'added_at' => now()->subMonth(),
        ]);

        // Asset C: Youngest (1 day ago)
        $assetC = Asset::create([
            'asset_id' => 'ITAM-NTB-0003',
            'asset_name' => 'Laptop C Youngest',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'added_at' => now()->subDay(),
        ]);

        // 1. Sort by added_at desc (youngest first: C, B, A)
        $response = $this->actingAs($user)->get('/assets?sort=added_at&direction=desc');
        $response->assertStatus(200);
        $content = $response->getContent();
        $posC = strpos($content, 'Laptop C Youngest');
        $posB = strpos($content, 'Laptop B Middle');
        $posA = strpos($content, 'Laptop A Oldest');
        $this->assertTrue($posC < $posB);
        $this->assertTrue($posB < $posA);

        // 2. Sort by added_at asc (oldest first: A, B, C)
        $response = $this->actingAs($user)->get('/assets?sort=added_at&direction=asc');
        $response->assertStatus(200);
        $content = $response->getContent();
        $posC = strpos($content, 'Laptop C Youngest');
        $posB = strpos($content, 'Laptop B Middle');
        $posA = strpos($content, 'Laptop A Oldest');
        $this->assertTrue($posA < $posB);
        $this->assertTrue($posB < $posC);
    }

    public function test_global_activity_logs_page(): void
    {
        $user = User::create([
            'name' => 'Admin ITAM',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'category_code' => 'NTB',
            'category_name' => 'Notebook',
        ]);

        $store = Store::create([
            'store_code' => 'STR-001',
            'store_name' => 'Store Jakarta',
            'location' => 'Jakarta',
        ]);

        // Creating an asset will trigger a "created" log in AssetObserver
        $asset = Asset::create([
            'asset_id' => 'ITAM-NTB-5555',
            'asset_name' => 'Unique Asset Name For Log Testing',
            'category_id' => $category->id,
            'store_id' => $store->id,
            'condition' => 'good',
            'status' => 'active',
            'added_at' => now(),
        ]);

        // 1. Unauthenticated redirect
        $response = $this->get('/logs');
        $response->assertRedirect('/login');

        // 2. Authenticated access
        $response = $this->actingAs($user)->get('/logs');
        $response->assertStatus(200);
        $response->assertSee('ITAM-NTB-5555');
        $response->assertSee('Unique Asset Name For Log Testing');
        $response->assertSee('Aset pertama kali didaftarkan dengan status');

        // 3. Search filter
        $response = $this->actingAs($user)->get('/logs?search=Unique Asset Name For Log Testing');
        $response->assertStatus(200);
        $response->assertSee('ITAM-NTB-5555');

        $response = $this->actingAs($user)->get('/logs?search=NonExistentLogSearchString');
        $response->assertStatus(200);
        $response->assertDontSee('ITAM-NTB-5555');
        $response->assertSee('Tidak ada log aktivitas ditemukan.');

        // 4. Test pagination options (10/25/50)
        // We have 1 log so far. Let's create 15 more assets to get total 16 logs.
        for ($i = 1; $i <= 15; $i++) {
            Asset::create([
                'asset_id' => "ITAM-NTB-500{$i}",
                'asset_name' => "Asset Pagination Test {$i}",
                'category_id' => $category->id,
                'store_id' => $store->id,
                'condition' => 'good',
                'status' => 'active',
                'added_at' => now(),
            ]);
        }

        // Default should be 50 per page, so we see all of them (16)
        $response = $this->actingAs($user)->get('/logs');
        $response->assertStatus(200);
        $this->assertCount(16, $response->viewData('activities'));

        // per_page=10
        $response = $this->actingAs($user)->get('/logs?per_page=10');
        $response->assertStatus(200);
        $this->assertCount(10, $response->viewData('activities'));

        // per_page=25
        $response = $this->actingAs($user)->get('/logs?per_page=25');
        $response->assertStatus(200);
        $this->assertCount(16, $response->viewData('activities'));
    }

}

