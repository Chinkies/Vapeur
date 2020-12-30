<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setTitle("FPS");
        $manager->persist($category);

        $category = new Category();
        $category->setTitle("MMO");
        $manager->persist($category);

        $category = new Category();
        $category->setTitle("RPG");
        $manager->persist($category);

        $category = new Category();
        $category->setTitle("FPS");
        $manager->persist($category);

        $category = new Category();
        $category->setTitle("Hack-n-Slash");
        $manager->persist($category);

        $category = new Category();
        $category->setTitle("Rythme");
        $manager->persist($category);

        $category = new Category();
        $category->setTitle("MOBA");
        $manager->persist($category);

        $category = new Category();
        $category->setTitle("Simulation");
        $manager->persist($category);

        $category = new Category();
        $category->setTitle("Autre");
        $manager->persist($category);

        $manager->flush();
    }
}
