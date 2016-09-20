<?php
include_once('ImageFactoryTest.php');

class FakeImageFactoryTest implements ImageFactoryTest
{
    public function createImageFromBlob($blob_res)
    {
        return new ImageTest();
    }
}