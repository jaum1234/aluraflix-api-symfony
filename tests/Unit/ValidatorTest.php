<?php

namespace App\Tests;

use App\Entity\Video;
use App\Entity\Category;
use App\Service\ResourcesPaginator;
use App\Service\ResourcesValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ValidatorTest extends KernelTestCase
{
    private $paginator;
    private $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testMustValidateACategoryWithoutAnyErrors()
    {
        //Arrange
        $category = Category::build('title', 'color');
        $container = static::getContainer();
        $validator = $container->get(ResourcesValidator::class);

        //act
        $validationData = $validator->validate($category);

        //Assert
        $this->assertTrue($validationData['success']);
        $this->assertArrayNotHasKey('errors', $validationData);
    }

    /**
     * @dataProvider dataForCategoryValidation
     */
    public function testMustValidateACategoryWithErrors(string $title, string $color, int $totalErrors)
    {
        //Arrange
        $category = Category::build($title, $color);
        $container = static::getContainer();
        $validator = $container->get(ResourcesValidator::class);

        //act
        $validationData = $validator->validate($category);

        //Assert
        $this->assertFalse($validationData['success']);
        $this->assertCount($totalErrors, $validationData['errors']);
    }

    public function testMustValidateAVideoWithoutAnyErrors()
    {
        //Arrange
        $category = Category::build('title', 'color');
        $video = Video::build('title', 'description', 'http://url.com', $category);
        $container = static::getContainer();
        $validator = $container->get(ResourcesValidator::class);

        //act
        $validationData = $validator->validate($video);

        //Assert
        $this->assertTrue($validationData['success']);
        $this->assertArrayNotHasKey('errors', $validationData);
    }

    /**
     * @dataProvider dataForVideoValidation
     */
    public function testMustValidateAVideoWithErrors(
        string $title, 
        string $description, 
        string $url, 
        Category $category, 
        int $totalErrors
        ) {
        //Arrange
        $video = Video::build($title, $description, $url, $category);
        $container = static::getContainer();
        $validator = $container->get(ResourcesValidator::class);

        //act
        $validationData = $validator->validate($video);

        //Assert
        $this->assertFalse($validationData['success']);
        $this->assertCount($totalErrors, $validationData['errors']);
    }

    public function dataForCategoryValidation()
    {
        return [
            'Fails when title is missing' => ['', 'color', 1],
            'Fails when color is missing' => ['title', '', 1],
            'Fails when title and color are missing' => ['', '', 2]
        ];
    }

    public function dataForVideoValidation()
    {
        $category = Category::build('title', 'color');

        return [
            'Fails when title is missing' => ['', 'description', 'http://url.com', $category, 1],
            'Fails when description is missing' => ['title', '', 'http://url.com', $category, 1],
            'Fails when url is missing' => ['title', 'description', '', $category, 1],
            'Fails when url is invalid' => ['title', 'description', 'url', $category, 1],
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
