<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['3/4" CPVC Fittings', '3-4-cpvc', '3/4" CPVC Pipe|3/4" 90° Elbow|3/4" 45° Elbow|3/4" Tee|3/4" Cross Tee|3/4" Socket / Coupler|3/4" Union|3/4" End Cap|3/4" Reducer|3/4" × 1/2" Reducer Bush|3/4" × 1/2" Brass Elbow|3/4" × 1/2" Brass Tee|3/4" × 1/2" Brass Socket|3/4" × 1/2" Brass Union|3/4" Ball Valve|3/4" Stop Valve|3/4" CPVC Flange|3/4" CPVC Male Adapter|3/4" CPVC Female Adapter|3/4" × 1/2" Male Adapter|3/4" × 1/2" Female Adapter|3/4" Pipe Clamp|3/4" Transition Fitting|3/4" Repair Coupler'],
            ['110 mm Drainage Fittings', '110mm-drainage', '110 mm Pipe|110 mm 90° Elbow|110 mm 45° Elbow|110 mm Door Elbow|110 mm Tee|110 mm Y Fitting|110 mm Socket / Coupler|110 mm Reducer|110 mm End Cap|110 mm Clean-out / Door Tee|110 mm Pipe Clamp|110 mm Repair Coupler|110 mm Floor Trap|110 mm P-Trap|110 mm S-Trap|110 mm Bottle Trap|110 mm WC Pan Connector|110 mm Access Plug / Clean-out Plug'],
            ['1" CPVC Fittings', '1-inch-cpvc', '1" CPVC Pipe|1" 90° Elbow|1" 45° Elbow|1" Tee|1" Cross Tee|1" Socket / Coupler|1" Union|1" End Cap|1" Reducer|1" × 3/4" Reducer Bush|1" CPVC Male Adapter|1" CPVC Female Adapter|1" × 3/4" Male Adapter|1" × 3/4" Female Adapter|1" Pipe Clamp|1" Transition Fitting|1" Repair Coupler|1" Tank Nipple'],
            ['1½" CPVC Fittings', 'one-and-half-inch-cpvc', '1½" CPVC Pipe|1½" 90° Elbow|1½" 45° Elbow|1½" Tee|1½" Cross Tee|1½" Socket / Coupler|1½" Union|1½" End Cap|1½" Reducer|1½" × 1" Reducer Bush|1½" CPVC Male Adapter|1½" CPVC Female Adapter|1½" × 1" Male Adapter|1½" × 1" Female Adapter|1½" Pipe Clamp|1½" Transition Fitting|1½" Repair Coupler|1½" Tank Nipple'],
            ['Bathroom / Sanitary Fittings', 'bathroom-sanitary', 'Wash Basin|Basin Mixer|Angle Cock|Health Faucet / Jet Spray|Shower|Shower Arm|Diverter|Bath Filler|Western Commode / WC|Flush Tank / Cistern|Flush Plate|Bottle Trap|Waste Coupling|Connection Pipe|CP Nipple|CP Flange|Bite Nipple|White Cement|Waste Pipe|Flexible Hose|Mirror|Towel Rod|Towel Ring|Robe Hook|Soap Dish|Toilet Paper Holder|Brush Holder|Glass Shelf|Drain Grating|Hanger / Hook'],
        ];

        foreach ($groups as $i => [$name, $slug, $items]) {
            $category = ProductCategory::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'sort_order' => $i]
            );

            foreach (explode('|', $items) as $n => $product) {
                $category->products()->firstOrCreate(
                    ['name' => $product],
                    ['sort_order' => $n]
                );
            }
        }
    }
}
