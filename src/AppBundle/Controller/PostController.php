<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\PostRepository;

class PostController extends Controller
{
    /**
     * Lists all post entities.
     *
     * @Route("/", name="post_index")
     * @Method("GET")
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $thisPage = $request->query->get('page');

        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository(Post::class)->getAllPosts($thisPage, $this->getParameter('app.pgManager.limit'));

        $pagesParameters = $this->get('app.pgManager')->paginate($thisPage, $posts);

        return array(
            'posts' => $posts,
            'maxPages' => $pagesParameters[0],
            'thisPage' => $pagesParameters[1]
        );
    }

    /**
     * Creates a new post entity.
     *
     * @Route("post/new", name="post_new")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm('AppBundle\Form\PostType', $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $file = $post->getImage();
            $fileName = $this->get('app.image_uploader')->upload($file);

            $post->setImage($fileName);

            $em->persist($post);
            $em->flush($post);

            return $this->redirectToRoute('post_index');
        }

        return array(
            'post' => $post,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a post entity.
     *
     * @Route("post/{id}", name="post_show")
     * @Method("GET")
     * @Template()
     *
     * @param Post $post
     *
     * @return array
     */
    public function showAction(Post $post)
    {
        return array(
            'post' => $post,
        );
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("post/{id}/edit", name="post_edit")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * @param Request $request
     *
     * @param Post $post
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, Post $post)
    {
        $post->setImage(
            new File($this->getParameter('images_directory').'/'.$post->getImage())
        );

        $editForm = $this->createForm('AppBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
        }

        return array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a category entity.
     *
     * @Route("post/{id}/delete", name="post_delete")
     * @Method({"GET", "POST"})
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);

        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('post_index');
    }
}
