<?php

namespace PlatformBundle\Controller;

use PlatformBundle\Entity\Category;
use PlatformBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PlatformBundle\Form\CategoryType;
use PlatformBundle\Form\EditFormularyType;
use PlatformBundle\Form\CommandType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use PlatformBundle\Entity\Command;
use PlatformBundle\Entity\CommandProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Form\ProfileFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{

    /**
     * @Route("/app", name="app", options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $listCommand = $em->getRepository(Command::class)->findByUserOrderDate($user);

        $oldCommand = $this->get('platform.purge');
        $oldCommand->purge();

        return $this->render('@Platform/Default/viewCommandUser.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'listCommand'=> $listCommand,
        ));
    }

    /**
     * @Route("/viewFormulary/{id}", name="viewFormulary", requirements={"id"="\d+"})
     * @ParamConverter("category", options={"mapping": {"id": "id"}})
     * @Security("has_role('ROLE_BLOC')")
     */
    public function ViewFormularyAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $listProduct = $category->getProducts();

        $command = new Command();
        $command->setCategory($category);
        foreach ($listProduct as $product) {
            $commandProduct = new CommandProduct();
            $commandProduct->setProduct($product);
            $commandProduct->setCommand($command);
            $commandProduct->setQuantity(0);
            $command->addCommandProduct($commandProduct);
        }

        $form = $this->createForm(CommandType::class, $command);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Command $command */
            $command = $form->getData();
            $command->setUser($user);
            $em->persist($command);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'La commande "' . $command->getId() . '" a bien été enregistrée !');
            return $this->redirectToRoute('app');
        }

        return $this->render('@Platform/Form/command.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/excel/{id}", name="excel", requirements={"id"="\d+"}, options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function excelAction($id){
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $command = $em->getRepository(Command::class)->findOneById($id);
        $listProduct = $command->getCommandProducts();

        $excel = $this->get('platform.excelService');
        $filename = $excel->generateExcel($id, $user, $listProduct);

        return new Response("Le fichier a été enregristré à ".$filename);
    }

    /**
     * @Method("GET")
     * @Route("/productInfo/{id}", name="getProductInfo", requirements={"id"="\d+"}, options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function getProductInfoAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Product $product */
        $product = $em->getRepository(Product::class)->find($id);

        $serializer = $this->container->get('jms_serializer');
        $productJson = $serializer->serialize($product, 'json');

        return new Response($productJson);
    }

    /**
     * @Route("/addFormulary", name="addFormulary")
     * @Security("has_role('ROLE_PHARMA')")
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
     * @Security("has_role('ROLE_PHARMA')")
     */
    public function editFormularyAction(Category $category, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $originalProducts = new ArrayCollection();

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

    /**
     * @Method("GET")
     * @Route("/deleteFormulary/{id}", name="deleteFormulary", requirements={"id"="\d+"}, options = { "expose" = true })
     * @Security("has_role('ROLE_PHARMA')")
     */
    public function deleteFormularyAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->findOneById($id);
        $em->remove($category);
        $em->flush();
        $this->addFlash('alert', 'Le formulaire '.$category->getName().' a bien été supprimée !');
        
        return new Response("Vous avez supprimé le formulaire ".$category->getName());
    }

}
