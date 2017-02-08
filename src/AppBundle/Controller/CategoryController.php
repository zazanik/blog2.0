<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Category controller.
 * @Route("category")
 */
class CategoryController extends Controller
{
   /**
    * @Route("/new", name="category_new")
    * @Method({"GET", "POST"})
    * @Template()
    *
    * @param $request
    * @return {*}
    */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush($category);

            return $this->redirectToRoute('post_index');
        }

        return array(
            'category' => $category,
            'form' => $form->createView(),
        );
    }
}