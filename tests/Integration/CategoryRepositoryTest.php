<?php

namespace App\Tests;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryRepositoryTest extends KernelTestCase
{
    private $entityManager;

    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
        ->get('doctrine')
        ->getManager();
    }

    public function testMustlistAllCategories()
    {
        //Arrange
        $category = new Category('title', 'color');
        $category2 = new Category('title2', 'color2');
        $this->entityManager->persist($category);
        $this->entityManager->persist($category2);
        $this->entityManager->flush();

        //Act
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        //Assert
        $this->assertEquals('title', $categories[0]->getTitle());
        $this->assertEquals('color', $categories[0]->getColor());
       
        $this->assertEquals('title2', $categories[1]->getTitle());
        $this->assertEquals('color2', $categories[1]->getColor());
    }

    public function testMustFetchOneCategory()
    {
        //Arrange
        $category = new Category('title', 'color');
        $category2 = new Category('title2', 'color2');
        $this->entityManager->persist($category);
        $this->entityManager->persist($category2);
        $this->entityManager->flush();

        $id = $category->getId();
        $id2 = $category2->getId();

        //Act
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $category = $categoryRepository->find($id);
        $category2 = $categoryRepository->find($id2);

        //Assert
        $this->assertEquals('title', $category->getTitle());
        $this->assertEquals('color', $category->getColor());
       
        $this->assertEquals('title2', $category2->getTitle());
        $this->assertEquals('color2', $category2->getColor());
    }

    public function testMustThrowExceptionIfUserTriesToQueryCategoryByQueryParamter()
    {
        //assert
        $this->expectException(\Doctrine\ORM\ORMException::class);
        
        //Arrange
        $category = new Category('title', 'color');
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        
        //act
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $category = $categoryRepository->findByQueryParameter('title');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
