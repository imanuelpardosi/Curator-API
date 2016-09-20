<?php
include_once('ImageFactoryTest.php');

class ProblematicImageFactoryTest implements  ImageFactoryTest
{
    public function createImageFromBlob($blob_res)
    {
        throw new Exception("Something wrong");
    }
}