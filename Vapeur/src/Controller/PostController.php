<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\AST\HavingClause;
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
     * @Route("/", name="home")
     */
    public function index(PostRepository $repo)
    {
        $posts = $repo->findBy(array(), array('date' => 'DESC'));

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/research", name="research")
     */
    public function research(Request $request, CategoryRepository $repoC, PostRepository $repoP, EntityManagerInterface $manager, UserRepository $repoU)
    {
        $categories = $repoC->findAll();
        if ($request->request->all())
        {
            if ($request->request->get('_username'))
            {
                if($repoU->findBy(array('username' => $request->request->get('_username'))) != null)
                {
                    $username = $repoU->findBy(array('username' => $request->request->get('_username')))[0]->getId();
                }
                else
                {
                    dump($repoU->findBy(array('username' => $request->request->get('_username'))));
                    $username = PHP_INT_MAX;
                    dump($username);
                }
            }
            else
            {
                $username=null;
            }

            $posts = $repoP->findPost($request->request->get('_name'), $request->request->get('post', 0), $username);
            return $this->render('post/research.html.twig', [
                'posts' => $posts,
                'categories' => $categories
            ]);
        }

        return $this->render('post/research.html.twig', [
            'posts' => null,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/post/new", name="post_create")
     * @Route("/post/{id}/edit", name="post_edit")
     */
    public function form(Post $post = null, Request $request, EntityManagerInterface $manager) {

        if(!$post)
        {
            $post = new Post();
            $post->setUser($this->container->get('security.token_storage')->getToken()->getUser());
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
            'mode' => $post->getId()!== null,
            'post' => $post
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_show")
     */
    public function show(Post $post, Request $request, EntityManagerInterface $manager, PostRepository $repo)
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
