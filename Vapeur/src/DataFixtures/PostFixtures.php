<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Comment;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 3; $i++)
        {
            $category = new Category();
            $category->setTitle("Catégorie n°$i");

            $manager->persist($category);

            for ($j = 1; $j <= mt_rand(4,6); $j++)
            {
                $post= new Post();
                $temp = $j*$i;

                $post->setTitle("Titre du post n°$temp")
                    ->setContent("Contenu du post n°$temp")
                    ->setLink("http://placehold.it/350x10$temp")
                    ->setDate(new \DateTime())
                    ->setCategory($category);

                $manager->persist($post);

                for ($k = 1; $k <= mt_rand(4,10); $k++)
                {
                    $comment = new Comment();

                    $temp2=$i*$j*$k;

                    $comment->setAuthor("Author n°$temp2")
                            ->setContent("Contenu du commentaire n°$temp2")
                            ->setDate(new \DateTime())
                            ->setPost($post);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
