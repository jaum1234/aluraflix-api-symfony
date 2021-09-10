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

    public function testMustAddACategoryToRepository()
    {
        //Arrange
        $category = Category::build('title', 'color');
        $categoryRepository = $this->entityManager->getRepository(Category::class);
    
        //Act
        $categoryRepository->add($category);
        $id = $category->getId();
        $categoryRecord = $categoryRepository->find($id);
    
        //Assert
        $this->assertEquals('title', $categoryRecord->getTitle());
        $this->assertEquals('color', $categoryRecord->getColor());
    }
    
    public function testMustlistAllCategories()
    {
        //Arrange
        $category = Category::build('title', 'color');
        $category2 = Category::build('title2', 'color2');
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $categoryRepository->add($category);
        $categoryRepository->add($category2);

        //Act
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
        $category = Category::build('title', 'color');
        $category2 = Category::build('title2', 'color2');
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $categoryRepository->add($category);
        $categoryRepository->add($category2);

        $id = $category->getId();
        $id2 = $category2->getId();

        //Act
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
        $category = Category::build('title', 'color');
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $categoryRepository->add($category);
               
        //Act
        $category = $categoryRepository->findByQueryParameter('title');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
