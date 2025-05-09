<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $imageData = [];
        
        // Define the base image names we'll reuse
        $baseImages = [
            'sedacia-suprava-oslo.jpg',
            'postel-malmo.jpg',
            'stol-bergen.jpg', 
            'pisaci-stol-alfa.jpg',
            'detska-postel-lili.jpg'
        ];
        
        // Add primary images for all 16 products
        for ($productId = 1; $productId <= 16; $productId++) {
            // Use modulo to cycle through the available base images
            $imageIndex = ($productId - 1) % count($baseImages);
            $imageName = $baseImages[$imageIndex];
            
            $imageData[] = [
                'product_id' => $productId,
                'image_url' => 'storage/products/' . $imageName,
                'is_primary' => true,
                'sort_order' => 1,
                'alt_text' => 'Product image ' . $productId,
            ];
            
            // Add a secondary image for each product
            $secondaryImageIndex = ($imageIndex + 1) % count($baseImages);
            $secondaryImageName = $baseImages[$secondaryImageIndex];
            
            $imageData[] = [
                'product_id' => $productId,
                'image_url' => 'storage/products/' . $secondaryImageName,
                'is_primary' => false,
                'sort_order' => 2,
                'alt_text' => 'Product image ' . $productId . ' (alternate view)',
            ];
        }
        
        // Insert all image data
        DB::table('product_images')->insert($imageData);
    }
}
