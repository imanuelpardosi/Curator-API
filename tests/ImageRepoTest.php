<?php

class ImageRepoTest{

    protected $image_factory;

    public function __construct(ImageFactoryTest $image_factory)
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

    private function is_base64($base64_res)
    {
        if (base64_encode(base64_decode($base64_res, true)) === $base64_res) {
            return true;
        }
        return false;
    }

}