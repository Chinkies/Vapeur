<?php

namespace App\Controller;

use App\Form\RegistrationType;
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
     * @Route("/profil", name="profil")
     */
    public function profil()
    {
        return $this->render('profil/profile.html.twig');
    }


    /**
     * @Route("/profil/{id}/edit", name="profil_edit")
     */
    public function edit(User $user = null, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(RegistrationType::class, $user);
        dump($user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('profil');
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
