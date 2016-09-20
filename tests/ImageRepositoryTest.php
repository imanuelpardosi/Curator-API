<?php

use Mockery as m;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ImageRepositoryTest extends TestCase
{
    use WithoutMiddleware;
    protected $fake_image_repository;
    protected $fake_image;
    protected $problematic_fake_image;
    protected $problematic_fake_image_repository;

    public function setUp()
    {
        parent::setUp();
        $this->fake_image = new FakeImageFactoryTest();
        $this->fake_image_repository = new ImageRepoTest($this->fake_image);

        $this->problematic_fake_image = new ProblematicImageFactoryTest();
        $this->problematic_fake_image_repository = new ImageRepoTest($this->problematic_fake_image);
    }

    public function testUpload_validBase64_shouldReturnUrl()
    {
        $valid_base64_res = base64_encode("Hello World");
        $url = $this->fake_image_repository->upload($valid_base64_res);

        $this->assertNotNull($url);
    }

    public function testUpload_invalidBase64_shouldReturnNull()
    {
        $invalid_base64_res = "987832ekjahsdkahgdasd";
        $url = $this->fake_image_repository->upload($invalid_base64_res);
        $this->assertNull($url);
    }

    public function testUpload_nullBase64_shouldReturnNull()
    {
        $null_base64_res = null;
        $url = $this->fake_image_repository->upload($null_base64_res);
        $this->assertNull($url);
    }

    public function testUpload_ibizaProblem_shouldThrowException()
    {

        $valid_base64_res = base64_encode("Hello World");
        try {
            $this->problematic_fake_image_repository->upload($valid_base64_res);
            $this->fail("Should failed upload");
        } catch (Exception $e) {
            // succeed
        }
    }
}