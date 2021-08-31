<?php

namespace App\Tests;

use App\Entity\Category;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    
    public function testMustCreateACategory(): void
    {
        $category = new Category('title', 'color');

        $this->assertEquals('title', $category->getTitle());
        $this->assertEquals('color', $category->getColor());
    }

    public function testMustUpdateACategory()
    {
        $category = new Category('title', 'color');

        $category->setTitle('New title');
        $category->setColor('red');

        $this->assertEquals('New title', $category->getTitle());
        $this->assertEquals('red', $category->getColor());
    }

    public function testMustFetchOneCategory()
    {
        $category = new Category('title', 'color');

        $this->entityManager->persist($category);
        $this->entityManager->flush();
        
        $categoryRecord = $this->entityManager
            ->getRepository(Category::class)
            ->find($category->getId());

        $this->assertSame($category->getTitle(), $categoryRecord->getTitle());
        $this->assertSame($category->getColor(), $categoryRecord->getColor());
    }

    public function testMustFetchAllCategories()
    {
        $category = new Category('title', 'color');
        $category2 = new Category('title2', 'color2');
        $category3 = new Category('title3', 'color3');
        $category4 = new Category('title4', 'color4');
        $category5 = new Category('title5', 'color5');
        $category6 = new Category('title6', 'color6');

        $this->entityManager->persist($category);
        $this->entityManager->persist($category2);
        $this->entityManager->persist($category3);
        $this->entityManager->persist($category4);
        $this->entityManager->persist($category5);
        $this->entityManager->persist($category6);

        $this->entityManager->flush();

        $categoryRecords = $this->entityManager
            ->getRepository(Category::class)
            ->findAll();

        $this->assertCount(6, $categoryRecords);
        $this->assertEquals($category->getTitle(), $categoryRecords[0]->getTitle());
        $this->assertEquals($category->getColor(), $categoryRecords[0]->getColor());
        $this->assertEquals($category2->getTitle(), $categoryRecords[1]->getTitle());
        $this->assertEquals($category2->getColor(), $categoryRecords[1]->getColor());
        $this->assertEquals($category3->getTitle(), $categoryRecords[2]->getTitle());
        $this->assertEquals($category3->getColor(), $categoryRecords[2]->getColor());
        $this->assertEquals($category4->getTitle(), $categoryRecords[3]->getTitle());
        $this->assertEquals($category4->getColor(), $categoryRecords[3]->getColor());
        $this->assertEquals($category5->getTitle(), $categoryRecords[4]->getTitle());
        $this->assertEquals($category5->getColor(), $categoryRecords[4]->getColor());
        $this->assertEquals($category6->getTitle(), $categoryRecords[5]->getTitle());
        $this->assertEquals($category6->getColor(), $categoryRecords[5]->getColor());
    }

    public function testMustDeleteACategory()
    {
        $category = new Category('title to be deleted', 'color to be deleted');

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        $categoryRecord = $this->entityManager
        ->getRepository(Category::class)
        ->findOneBy(['title' => $category->getTitle()]);

        $this->assertNull($categoryRecord);

    }

    public function testMustStoreACategoryInDatabase()
    {
        $category = new Category('title', 'color');

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $categoryRecord = $this->entityManager
            ->getRepository(Category::class)
            ->find($category->getId());

        $this->assertEquals('title', $categoryRecord->getTitle());
        $this->assertEquals('color', $categoryRecord->getColor());
    }

    public function testMustSetCategoryUpdatesInDatabase()
    {
        $category = new Category('title', 'color');

        $this->entityManager->persist($category);
        $this->entityManager->flush();
        
        $category->setTitle("New title");
        $category->setColor("New color");
        
        $this->entityManager->flush();

        $categoryRecord = $this->entityManager
        ->getRepository(Category::class)
        ->find($category->getId());
        

        $this->assertEquals('New title', $categoryRecord->getTitle());
        $this->assertEquals('New color', $categoryRecord->getColor());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
    
}
