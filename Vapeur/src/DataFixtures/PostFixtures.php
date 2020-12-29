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
        $temp = 1;
        for ($i = 1; $i <= 3; $i++)
        {
            $category = new Category();
            $category->setTitle("Catégorie n°$i");

            $manager->persist($category);

            $user = new User();
            $user->setUsername("User $i")
                ->setPassword("password$i");

            $manager->persist($user);

            for ($j = 1; $j <= mt_rand(1,2); $j++)
            {
                $post= new Post();

                $post->setTitle("Titre du post n°$temp")
                    ->setContent("Contenu du post n°$temp")
                    ->setLink("http://placehold.it/350x10$temp")
                    ->setDate(new \DateTime())
                    ->setCategory($category)
                    ->setUser($user);

                $manager->persist($post);

                $temp++;
            }
        }

        $manager->flush();
    }
}
