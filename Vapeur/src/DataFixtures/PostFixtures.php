<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Post;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++)
        {
            $post= new Post();
            $post->setTitle("Titre du post n°$i")
                ->setContent("<p>Contenu du post n°$i</p>")
                ->setLink("http://palcehold.it/350x150")
                ->setDate(new \DateTime());

            $manager->persist($post);
        }
        $manager->flush();
    }
}
