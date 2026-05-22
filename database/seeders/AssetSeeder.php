<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $stores = Store::all();

        if ($categories->isEmpty() || $stores->isEmpty()) {
            $this->command->error('Please run CategorySeeder and StoreSeeder first.');
            return;
        }

        // Mapping categories to realistic brands & models
        $brandModels = [
            'NTB' => [
                'brands' => ['Apple', 'Lenovo', 'Dell', 'HP', 'ASUS'],
                'models' => [
                    'Apple' => ['MacBook Pro M2 14"', 'MacBook Pro M3 16"', 'MacBook Air M2 13"'],
                    'Lenovo' => ['ThinkPad T14 Gen 4', 'ThinkPad X1 Carbon Gen 11', 'IdeaPad Slim 5'],
                    'Dell' => ['Latitude 5440', 'XPS 13 9315', 'Vostro 3430'],
                    'HP' => ['EliteBook 840 G10', 'ProBook 440 G10', 'Pavilion 14'],
                    'ASUS' => ['Zenbook 14 OLED', 'ExpertBook B1402', 'Vivobook Go 14'],
                ]
            ],
            'DKT' => [
                'brands' => ['Dell', 'HP', 'Lenovo', 'ASUS'],
                'models' => [
                    'Dell' => ['OptiPlex 7010 Tower', 'OptiPlex 3000 Micro', 'Precision 3660 Workstation'],
                    'HP' => ['ProDesk 400 G9', 'EliteDesk 800 G9', 'Z2 Mini G9 Workstation'],
                    'Lenovo' => ['ThinkCentre M70q Gen 3', 'ThinkCentre M90t Gen 3', 'IdeaCentre 3'],
                    'ASUS' => ['ExpertCenter D7', 'ROG Strix G15', 'AIO A3402'],
                ]
            ],
            'PRN' => [
                'brands' => ['Epson', 'Canon', 'HP', 'Brother'],
                'models' => [
                    'Epson' => ['L3210 EcoTank', 'L5290 EcoTank', 'LQ-310 Dot Matrix'],
                    'Canon' => ['Pixma G3010', 'LBP6030 Laser', 'imageRUNNER 2006i'],
                    'HP' => ['LaserJet Pro M404dn', 'Smart Tank 515', 'OfficeJet Pro 8023'],
                    'Brother' => ['HL-L2360DN Laser', 'DCP-T420W Ink Benefit', 'MFC-L2700DW'],
                ]
            ],
            'MON' => [
                'brands' => ['Dell', 'LG', 'Samsung', 'ASUS', 'AOC'],
                'models' => [
                    'Dell' => ['UltraSharp U2422H 24"', 'P2422H Professional 24"', 'E2222H 21.5"'],
                    'LG' => ['24MK600M 24"', '27UP850N-W 27" 4K', '22MP410-B 21.5"'],
                    'Samsung' => ['Essential Monitor S3 24"', 'Odyssey G3 24"', 'Curved CF390 24"'],
                    'ASUS' => ['ProArt PA248QV 24"', 'VZ249HE-W 23.8"', 'TUF Gaming VG249Q 23.8"'],
                    'AOC' => ['24B2H2 23.8"', '27B2H 27"', 'Q27G2S 27"'],
                ]
            ],
            'UPS' => [
                'brands' => ['APC', 'ICA', 'SFC', 'Protek'],
                'models' => [
                    'APC' => ['Back-UPS 700VA BX700U-MS', 'Smart-UPS 1500VA SMC1500I', 'Easy UPS 1000VA BV1000I-MS'],
                    'ICA' => ['CP700 700VA', 'CT1082B 1000VA', 'SE1000 1000VA'],
                    'SFC' => ['600VA', '1200VA'],
                    'Protek' => ['1000VA', '2000VA'],
                ]
            ],
            'SRV' => [
                'brands' => ['Dell', 'HPE', 'Lenovo'],
                'models' => [
                    'Dell' => ['PowerEdge R750 Rack Server', 'PowerEdge T350 Tower Server', 'PowerEdge R450 1U Rack'],
                    'HPE' => ['ProLiant DL380 Gen10 Rack', 'ProLiant ML350 Gen10 Tower', 'ProLiant DL360 Gen10'],
                    'Lenovo' => ['ThinkSystem SR650 Rack', 'ThinkSystem ST250 Tower', 'ThinkSystem SR550'],
                ]
            ],
            'SWT' => [
                'brands' => ['Cisco', 'MikroTik', 'TP-Link', 'Ruijie'],
                'models' => [
                    'Cisco' => ['Catalyst 2960L 24-Port', 'Catalyst 9200L 48-Port', 'Business 250 24-Port'],
                    'MikroTik' => ['CRS326-24G-2S+RM', 'CRS354-48G-4S+2Q+RM', 'CSS326-24G-2S+RM'],
                    'TP-Link' => ['TL-SG1024D 24-Port Gigabit', 'TL-SG3428 JetStream 28-Port', 'TL-SF1008D 8-Port'],
                    'Ruijie' => ['Reyee RG-ES226GC 24-Port', 'RG-NBS3100-24GT4SFP', 'Reyee RG-ES108GD'],
                ]
            ],
            'RTR' => [
                'brands' => ['MikroTik', 'Cisco', 'Ubiquiti', 'Ruijie'],
                'models' => [
                    'MikroTik' => ['RB4011iGS+RM', 'hEX gr3 (RB750Gr3)', 'CCR2004-16G-2S+'],
                    'Cisco' => ['RV340 Dual WAN VPN', 'ISR 1100 Series', 'RV160 VPN Router'],
                    'Ubiquiti' => ['EdgeRouter 4 ER-4', 'UniFi Dream Machine Pro', 'EdgeRouter X ER-X'],
                    'Ruijie' => ['Reyee RG-EG210G-E', 'Reyee RG-EG105G-V2', 'Reyee RG-EG310GH'],
                ]
            ],
            'CTV' => [
                'brands' => ['Hikvision', 'Dahua', 'Ezviz', 'Xiaomi'],
                'models' => [
                    'Hikvision' => ['DS-2CD1023G0-I 2MP Dome', 'DS-7208HUHI-K1 8-Ch DVR', 'DS-2CD2143G0-I 4MP Dome'],
                    'Dahua' => ['DH-HAC-HFW1200T 2MP Bullet', 'DH-XVR5108HS-X 8-Ch XVR', 'DH-IPC-HFW1230S-S2 2MP'],
                    'Ezviz' => ['C6N 1080p Pan & Tilt', 'C3W Color Night Vision', 'TY1 1080p Smart Wi-Fi'],
                    'Xiaomi' => ['Smart Camera C300 2K', 'Smart Camera C200 1080p', 'Outdoor Camera AW200'],
                ]
            ],
            'SCN' => [
                'brands' => ['Fujitsu', 'Canon', 'Epson', 'Honeywell'],
                'models' => [
                    'Fujitsu' => ['ScanSnap iX1600 Document', 'fi-7160 Workgroup', 'fi-800R Sheetfed'],
                    'Canon' => ['imageFORMULA DR-C225 II', 'CanoScan LiDE 300 Flatbed', 'imageFORMULA DR-C240'],
                    'Epson' => ['WorkForce DS-530 II', 'Perfection V39II Flatbed', 'WorkForce ES-60W'],
                    'Honeywell' => ['Voyager 1250g Barcode', 'Orbit 7120 Omnidirectional', 'Xenon Extreme Performance 1950g'],
                ]
            ],
            'PRJ' => [
                'brands' => ['Epson', 'BenQ', 'InFocus', 'ViewSonic'],
                'models' => [
                    'Epson' => ['EB-E500 3300 Lumens', 'EB-X500 3600 Lumens', 'CO-FH02 Smart Full HD'],
                    'BenQ' => ['MX560 4000 Lumens', 'MS550 SVGA', 'GV30 Portable Projector'],
                    'InFocus' => ['IN112xv SVGA', 'IN114xv XGA', 'IN118bb 1080p'],
                    'ViewSonic' => ['PA503X XGA', 'M1 Pro Smart Portable', 'PX701-4K Gaming'],
                ]
            ],
            'OTH' => [
                'brands' => ['Logitech', 'Sandisk', 'WD', 'TP-Link'],
                'models' => [
                    'Logitech' => ['Wireless Keyboard & Mouse MK220', 'C922 Pro HD Webcam', 'MX Master 3S Mouse'],
                    'Sandisk' => ['Ultra Dual Drive Luxe 64GB', 'Extreme Portable SSD 1TB', 'Cruzer Glide 32GB'],
                    'WD' => ['My Passport 1TB External HDD', 'Blue SN570 500GB NVMe SSD', 'Red Plus 4TB NAS HDD'],
                    'TP-Link' => ['Bluetooth 5.0 Adapter UB500', 'USB Hub UH700 7-Port', 'Archer T3U Plus Wi-Fi USB'],
                ]
            ],
        ];

        // Ensure QR code directory exists
        if (!Storage::disk('public')->exists('qrcodes')) {
            Storage::disk('public')->makeDirectory('qrcodes');
        }

        // Count per category to generate sequential asset IDs
        $categoryCounters = [];
        foreach ($categories as $cat) {
            $categoryCounters[$cat->id] = 0;
        }

        $this->command->info('Seeding 300 assets...');

        for ($i = 1; $i <= 300; $i++) {
            $category = $categories->random();
            $store = $stores->random();

            $catCode = $category->category_code;
            $mapping = $brandModels[$catCode] ?? $brandModels['OTH'];

            $brand = $mapping['brands'][array_rand($mapping['brands'])];
            $modelList = $mapping['models'][$brand] ?? ['Standard Item'];
            $model = $modelList[array_rand($modelList)];

            $assetName = $brand . ' ' . $model;

            // Generate asset_id
            $categoryCounters[$category->id]++;
            $seqNumber = str_pad($categoryCounters[$category->id], 4, '0', STR_PAD_LEFT);
            $assetId = 'ITAM-' . $catCode . '-' . $seqNumber;

            // Condition distribution: 70% good, 18% fair, 8% poor, 4% damaged
            $randCond = rand(1, 100);
            if ($randCond <= 70) {
                $condition = 'good';
            } elseif ($randCond <= 88) {
                $condition = 'fair';
            } elseif ($randCond <= 96) {
                $condition = 'poor';
            } else {
                $condition = 'damaged';
            }

            // Status distribution: 80% active, 10% maintenance, 5% inactive, 5% disposed
            $randStatus = rand(1, 100);
            if ($randStatus <= 80) {
                $status = 'active';
            } elseif ($randStatus <= 90) {
                $status = 'maintenance';
            } elseif ($randStatus <= 95) {
                $status = 'inactive';
            } else {
                $status = 'disposed';
            }

            // Added date: last 4 years
            $addedAt = now()->subDays(rand(0, 1460));
            $purchaseDate = $addedAt->copy()->subDays(rand(0, 30));
            $warrantyUntil = $purchaseDate->copy()->addYears(rand(1, 3));

            // Price range: 300k to 25 million
            $priceRange = [
                'NTB' => [8000000, 25000000],
                'DKT' => [6000000, 20000000],
                'PRN' => [1500000, 8000000],
                'MON' => [1000000, 6000000],
                'UPS' => [500000, 4000000],
                'SRV' => [20000000, 80000000],
                'SWT' => [500000, 5000000],
                'RTR' => [300000, 4000000],
                'CTV' => [300000, 1500000],
                'SCN' => [500000, 10000000],
                'PRJ' => [4000000, 15000000],
                'OTH' => [100000, 2000000],
            ];
            $range = $priceRange[$catCode] ?? [100000, 5000000];
            $purchasePrice = rand($range[0], $range[1]);

            // Create asset
            $asset = Asset::create([
                'asset_id' => $assetId,
                'asset_name' => $assetName,
                'category_id' => $category->id,
                'store_id' => $store->id,
                'brand' => $brand,
                'model' => $model,
                'serial_number' => strtoupper(substr($catCode, 0, 3)) . rand(10000000, 99999999),
                'specs' => 'Spesifikasi standar untuk ' . $assetName,
                'condition' => $condition,
                'status' => $status,
                'purchase_date' => $purchaseDate->format('Y-m-d'),
                'warranty_until' => $warrantyUntil->format('Y-m-d'),
                'purchase_price' => $purchasePrice,
                'location_detail' => 'Ruang IT / Area Kerja',
                'notes' => 'Di-seed otomatis oleh AssetSeeder',
                'added_at' => $addedAt,
            ]);

            // Generate QR Code file
            $qrData = [
                'asset_id' => $asset->asset_id,
                'asset_name' => $asset->asset_name,
                'category' => $category->category_name,
                'brand' => $asset->brand,
                'model' => $asset->model,
                'serial_number' => $asset->serial_number,
                'store' => $store->store_name,
                'condition' => ucfirst($asset->condition),
                'status' => ucfirst($asset->status),
                'added_at' => $asset->added_at->format('Y-m-d'),
            ];

            $jsonStr = json_encode($qrData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $filename = 'qr_' . $asset->asset_id . '_' . time() . '_' . $i . '.svg';
            $path = 'qrcodes/' . $filename;

            $qrCodeContent = QrCode::format('svg')
                ->size(250)
                ->margin(1)
                ->generate($jsonStr);

            Storage::disk('public')->put($path, $qrCodeContent);
            $asset->update(['qr_code_path' => $path]);
        }

        $this->command->info('Seeding 300 assets completed successfully!');
    }
}
