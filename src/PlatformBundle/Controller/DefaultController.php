<?php

namespace PlatformBundle\Controller;

use PlatformBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PlatformBundle\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/app", name="app")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('PlatformBundle:Category')->findAll();

        return $this->render('@Platform/Default/index.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
        ));
    }

    /**
     * @Route("/viewFormulary/{id}", name="viewFormulary", requirements={"id"="\d+"})
     */
    public function ViewFormularyAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('PlatformBundle:Category')->findOneById($id);

        return $this->render('@Platform/Default/viewFormulary.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'category' => $category
        ));
    }

    /**
     * @Route("/addFormulary", name="addFormulary")
     */
    public function addFormularyAction(Request $request)
    {
        $category = new Category();

        $form   = $this->get('form.factory')->create(CategoryType::class, $category);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le formulaire "' . $category->getName() . '" a bien été enregistré !');

            return $this->redirectToRoute('app');
        }

        return $this->render('@Platform/Form/category.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'form' => $form->createView()
        ));
    }
}
