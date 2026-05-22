<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the categories table with default IT asset categories.
     */
    public function run(): void
    {
        $categories = [
            ['category_code' => 'NTB', 'category_name' => 'Notebook / Laptop', 'description' => 'Laptop, notebook, dan ultrabook'],
            ['category_code' => 'DKT', 'category_name' => 'Desktop PC', 'description' => 'Komputer desktop dan workstation'],
            ['category_code' => 'PRN', 'category_name' => 'Printer', 'description' => 'Printer inkjet, laserjet, dan dot matrix'],
            ['category_code' => 'MON', 'category_name' => 'Monitor', 'description' => 'Monitor LCD, LED, dan display'],
            ['category_code' => 'UPS', 'category_name' => 'UPS', 'description' => 'Uninterruptible Power Supply'],
            ['category_code' => 'SRV', 'category_name' => 'Server', 'description' => 'Server rack dan tower'],
            ['category_code' => 'SWT', 'category_name' => 'Switch / Hub', 'description' => 'Network switch dan hub'],
            ['category_code' => 'RTR', 'category_name' => 'Router', 'description' => 'Router dan access point'],
            ['category_code' => 'CTV', 'category_name' => 'CCTV', 'description' => 'Kamera CCTV dan DVR/NVR'],
            ['category_code' => 'SCN', 'category_name' => 'Scanner', 'description' => 'Scanner dokumen dan barcode'],
            ['category_code' => 'PRJ', 'category_name' => 'Projector', 'description' => 'Projector dan layar presentasi'],
            ['category_code' => 'OTH', 'category_name' => 'Lainnya', 'description' => 'Perangkat IT lainnya'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
