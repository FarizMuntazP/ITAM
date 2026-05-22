<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Seed the stores table with real store data.
     */
    public function run(): void
    {
        $stores = [
            ['code' => 'D01', 'name' => 'HQ KUTAI'],
            ['code' => 'D02', 'name' => 'GUDANG PUSAT PANDAN'],
            ['code' => '01', 'name' => 'PLAZA GADGET'],
            ['code' => '02', 'name' => 'PLAZA KOMPUTER'],
            ['code' => '03', 'name' => 'MEGASTORE'],
            ['code' => '04', 'name' => 'EXPRESS CARUBAN'],
            ['code' => '05', 'name' => 'OKAZ'],
            ['code' => '06', 'name' => 'NGAWI'],
            ['code' => '07', 'name' => 'SES'],
            ['code' => '09', 'name' => 'MES'],
            ['code' => '10', 'name' => 'NGANJUK'],
            ['code' => '11', 'name' => 'EXPRESS PACITAN'],
            ['code' => '12', 'name' => 'EXPRESS MAGETAN'],
            ['code' => '13', 'name' => 'EXPRESS SRAGEN'],
            ['code' => '14', 'name' => 'MI SHOP MADIUN'],
            ['code' => '16', 'name' => 'SULTAN'],
            ['code' => '18', 'name' => 'OPPO KEDIRI'],
            ['code' => '19', 'name' => 'KEDIRI'],
            ['code' => '20', 'name' => 'TULUNGAGUNG'],
            ['code' => '21', 'name' => 'WARUJAYENG'],
            ['code' => '22', 'name' => 'BARAT'],
            ['code' => '23', 'name' => 'SUNCITY SPS'],
            ['code' => '24', 'name' => 'MAGETAN SPS'],
            ['code' => '25', 'name' => 'HOS COKRO'],
            ['code' => '26', 'name' => 'EBIKE BOGOWONTO'],
            ['code' => '27', 'name' => 'NEW CARUBAN'],
            ['code' => '28', 'name' => 'OES KEDIRI'],
            ['code' => '29', 'name' => 'NEW MEGASTORE'],
            ['code' => '30', 'name' => 'DOLOPO'],
            ['code' => '31', 'name' => 'KARANGJATI'],
            ['code' => '32', 'name' => 'PARE'],
            ['code' => '33', 'name' => 'SPS PARE'],
            ['code' => '34', 'name' => 'EBIKE WARUJAYENG'],
            ['code' => '35', 'name' => 'JOGOROGO'],
            ['code' => '36', 'name' => 'LOROK'],
            ['code' => '37', 'name' => 'EBIKE MAGETAN'],
            ['code' => '39', 'name' => 'EBIKE NGANJUK'],
            ['code' => '40', 'name' => 'EBIKE KERTOSONO'],
            ['code' => '43', 'name' => 'WALIKUKUN'],
            ['code' => '44', 'name' => 'SUMOROTO'],
            ['code' => '45', 'name' => 'NEW KARTO'],
            ['code' => '46', 'name' => 'BERBEK'],
            ['code' => '47', 'name' => 'NEW TULUNGAGUNG'],
            ['code' => '48', 'name' => 'GORANG GARENG'],
            ['code' => '49', 'name' => 'EBIKE MANISREJO UWINFLY'],
            ['code' => '50', 'name' => 'EBIKE PASAR PON'],
            ['code' => '51', 'name' => 'SUNCITY MALL'],
            ['code' => '52', 'name' => 'EBIKE NGAWI'],
            ['code' => '53', 'name' => 'HUAWEI EXPERIENCE STORE'],
            ['code' => '54', 'name' => 'EBIKE KEDIRI'],
            ['code' => '55', 'name' => 'MISHOP KEDIRI'],
            ['code' => '56', 'name' => 'MISHOP TULUNGAGUNG'],
            ['code' => '57', 'name' => 'NEW EBIKE GORANG-GARENG'],
            ['code' => '58', 'name' => 'MISHOP NGANJUK'],
            ['code' => '59', 'name' => 'SURABAYA GAYUNGAN'],
            ['code' => '60', 'name' => 'MISHOP KAPAS KERAMPUNG'],
            ['code' => '61', 'name' => 'MISHOP MANUKAN SURABAYA'],
            ['code' => '62', 'name' => 'APPLE TULUNGAGUNG'],
            ['code' => '63', 'name' => 'EBIKE YADEA'],
            ['code' => 'REALME', 'name' => 'REALME OKAZ'],
            ['code' => 'VIVO', 'name' => 'VIVO OKAZ'],
            ['code' => 'SC01', 'name' => 'SERVICE CENTER'],
        ];

        foreach ($stores as $store) {
            Store::create([
                'store_code' => $store['code'],
                'store_name' => $store['name'],
                'location' => $store['name'],
                'region' => null,
            ]);
        }
    }
}
