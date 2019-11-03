<?php

namespace PlatformBundle\Controller;

use PlatformBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PlatformBundle\Form\CategoryType;
use PlatformBundle\Form\EditFormularyType;
use PlatformBundle\Form\ProductInCommandType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use PlatformBundle\Entity\Command;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use PlatformBundle\Repository\ProductRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


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
     * @ParamConverter("category", options={"mapping": {"id": "id"}})
     */
    public function ViewFormularyAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        // $category = $em->getRepository('PlatformBundle:Category')->findOneById($id);

        $command = new Command();

        // $form = $this->get('form.factory')->create(ProductInCommandType::class, [
        //     'category' => $category // send this data in option
        // ]);

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $command);

        $formBuilder
            ->add('products', CollectionType::class, [
                'entry_type'   => ProductInCommandType::class,

                // [
                //     'category' => $category // send this data in option
                // ],
                'entry_options' => ['label' => false],
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'required'     => false,
                'by_reference' => true,
                'attr'         => [
                    'class' => "command-collection",
                    'label' => false,
                ],
            ])
            ->add('Valider', SubmitType::class)
            ->getForm();
        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();


        // return $this->render('@Platform/Default/viewFormulary.html.twig', array(
        return $this->render('@Platform/Form/command.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'category' => $category,
            // 'data' => $command,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/addFormulary", name="addFormulary")
     */
    public function addFormularyAction(Request $request)
    {
        $category = new Category();

        $form = $this->get('form.factory')->create(CategoryType::class, $category);

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

    /**
     * @Route("/editFormulary/{id}", name="editFormulary", requirements={"id"="\d+"})
     * @ParamConverter("category", options={"mapping": {"id": "id"}})
     */
    public function editFormularyAction(Category $category, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // $category = $em->getRepository('PlatformBundle:Category')->findOneById($id);

        $originalProducts = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($category->getProducts() as $product) {
            $originalProducts->add($product);
        }
        $form = $this->get('form.factory')->create(EditFormularyType::class, $category);
        $form->handleRequest($request);

        if ($form->isValid()) {

            foreach ($originalProducts as $product) {
                if (false === $category->getProducts()->contains($product)) {

                    $em->persist($product);
                    $em->remove($product);

                    // if ($em->persist($product)) {
                    //     $request->getSession()->getFlashBag()->add('success', 'Le produit "' . $product->getDesignation() . '" a bien été enregistré !');
                    // } elseif ($em->remove($product)) {
                    //     $request->getSession()->getFlashBag()->add('alert', 'Le produit "' . $product->getDesignation() . '" a bien été supprimé !');
                    // }
                }
            }
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('app');
        }

        return $this->render('@Platform/Form/editFormulary.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'category' => $category,
            'form' => $form->createView()
        ));
    }
}
