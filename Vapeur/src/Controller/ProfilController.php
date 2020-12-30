<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="profil")
     */
    public function profil(User $user = null, PostRepository $repo)
    {
        $posts = $repo->findBy(array('user' => $user->getId()), array('date' => 'DESC'));
        return $this->render('profil/profile.html.twig', [
            'posts' => $posts
        ]);
    }


    /**
     * @Route("/profil/{id}/edit", name="profil_edit")
     */
    public function edit(User $user = null, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('profil', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
