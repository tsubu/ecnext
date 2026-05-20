<?php

namespace App\Services\Media;

use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    /**
     * Upload an image and create a database record.
     */
    public function uploadProductImage(UploadedFile $file, int $productId, ?int $variantId = null, int $sortOrder = 0): ProductImage
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("products/{$productId}", $filename, 'public');

        return ProductImage::create([
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'file_path' => Storage::url($path),
            'alt_text' => $file->getClientOriginalName(),
            'sort_order' => $sortOrder,
        ]);
    }

    /**
     * Delete an image from storage and database.
     */
    public function deleteImage(ProductImage $image): void
    {
        // Convert URL to relative path for storage
        $relativePath = str_replace(Storage::url(''), '', $image->file_path);
        Storage::disk('public')->delete($relativePath);
        
        $image->delete();
    }
}
