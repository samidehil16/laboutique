<?php

namespace App\Tests\Unit;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
         self::bootKernel();

         $category = new Category();
         $category->setName("Category1");

        $container = static::getContainer();
        $error= $container->get('validator')->validate($category);

        $this->assertCount(0,$error);
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }
}
