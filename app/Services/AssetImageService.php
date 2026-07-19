<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AssetImageService
{
    /**
     * Store a compressed asset photo and a lightweight thumbnail.
     *
     * @return array{photo: string, photo_thumbnail: string|null}
     */
    public function storeAssetPhoto(UploadedFile $file, string $assetId): array
    {
        $filename = $assetId . '_' . time() . '_' . uniqid() . '.jpg';
        $photoPath = trim(config('itam_images.asset_photo.directory'), '/') . '/' . $filename;
        $thumbnailPath = trim(config('itam_images.asset_photo.thumbnail_directory'), '/') . '/' . $filename;

        if (!extension_loaded('gd')) {
            return [
                'photo' => $file->storeAs(
                    config('itam_images.asset_photo.directory'),
                    $filename,
                    config('itam_images.asset_photo.disk')
                ),
                'photo_thumbnail' => null,
            ];
        }

        $this->writeJpeg(
            $file,
            $photoPath,
            (int) config('itam_images.asset_photo.max_width'),
            (int) config('itam_images.asset_photo.quality')
        );

        $this->writeJpeg(
            $file,
            $thumbnailPath,
            (int) config('itam_images.asset_photo.thumbnail_width'),
            (int) config('itam_images.asset_photo.thumbnail_quality')
        );

        return [
            'photo' => $photoPath,
            'photo_thumbnail' => $thumbnailPath,
        ];
    }

    public function deleteAssetPhoto(?string $photo, ?string $thumbnail): void
    {
        $paths = array_values(array_filter([$photo, $thumbnail]));

        if ($paths !== []) {
            Storage::disk(config('itam_images.asset_photo.disk'))->delete($paths);
        }
    }

    private function writeJpeg(UploadedFile $file, string $targetPath, int $maxWidth, int $quality): void
    {
        $sourceImage = $this->createSourceImage($file);

        if (!$sourceImage) {
            Storage::disk(config('itam_images.asset_photo.disk'))->put(
                $targetPath,
                file_get_contents($file->getRealPath())
            );
            return;
        }

        [$width, $height] = getimagesize($file->getRealPath());

        $newWidth = $width;
        $newHeight = $height;

        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) ($height * ($maxWidth / $width));
        }

        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        $white = imagecolorallocate($newImage, 255, 255, 255);
        imagefill($newImage, 0, 0, $white);

        imagecopyresampled(
            $newImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        ob_start();
        imagejpeg($newImage, null, $quality);
        $compressedData = ob_get_clean();

        Storage::disk(config('itam_images.asset_photo.disk'))->put($targetPath, $compressedData);

        imagedestroy($sourceImage);
        imagedestroy($newImage);
    }

    private function createSourceImage(UploadedFile $file)
    {
        $type = getimagesize($file->getRealPath())[2] ?? null;

        return match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($file->getRealPath()),
            IMAGETYPE_PNG => @imagecreatefrompng($file->getRealPath()),
            IMAGETYPE_WEBP => @imagecreatefromwebp($file->getRealPath()),
            IMAGETYPE_GIF => @imagecreatefromgif($file->getRealPath()),
            default => false,
        };
    }
}
