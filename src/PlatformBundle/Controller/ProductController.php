<?php

namespace PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use PlatformBundle\Entity\Product;
use PlatformBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{

    /**
     * @Route("/addProduct", name="addProduct")
     */
    public function addProductAction(Request $request)
    {
        // On crée un objet Advert
        $product = new Product();

        // On crée le FormBuilder grâce au service form factory
        $form = $this->get('form.factory')->create(ProductType::class, $product);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le produit "' . $product->getDesignation() . '" a bien été enregistré !');

            return $this->redirectToRoute('app');
        }

        return $this->render('@Platform/Form/product.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'form' => $form->createView()
        ));
    }
}
