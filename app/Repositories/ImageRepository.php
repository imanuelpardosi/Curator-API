<?php
namespace App\Repositories;

use Kurio\Ibiza\ImageFactory;

class ImageRepository
{

    protected $image_factory;

    public function __construct(ImageFactory $image_factory)
    {
        $this->image_factory = $image_factory;

    }

    public function upload($base64_res)
    {
         if ($base64_res && $this->is_base64($base64_res)) {
            $blob_res = base64_decode($base64_res);
            try {
                $image = $this->image_factory->createImageFromBlob($blob_res)->toArray();
            } catch (Exception $e) {
                return null;
            }
            return $image['url'];
        }
        return null;
    }

    public function is_base64($str)
    {
        if (base64_encode(base64_decode($str, true)) === $str) {
            return true;

        }
        return false;

    }

}