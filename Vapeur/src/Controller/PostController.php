<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Form\PostType;
use App\Form\CommentType;
use App\Entity\Comment;
use App\Entity\User;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index(PostRepository $repoP, UserRepository $repoU)
    {
        $posts = $repoP->findAll();
        $users = $repoU->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'users' => $users
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('post/home.html.twig');
    }

    /**
     * @Route("/post/new", name="post_create")
     * @Route("/post/{id}/edit", name="post_edit")
     */
    public function form(Post $post = null, Request $request, EntityManagerInterface $manager) {

        if(!$post)
        {
            $post = new Post();
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if (!$post->getId())
            {
                $post->setDate(new \DateTime)
                    ->setUser($this->container->get('security.token_storage')->getToken()->getUser());
            }
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/create.html.twig', [
            'formPost' => $form->createView(),
            'mode' => $post->getId()!== null
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_show")
     */
    public function show(Post $post, Request $request, EntityManagerInterface $manager)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setDate(new \DateTime())
                    ->setPost($post)
                    ->setAuthor($this->container->get('security.token_storage')->getToken()->getUser()->getUsername());

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'commentForm' => $form->createView()
        ]);
    }
}
