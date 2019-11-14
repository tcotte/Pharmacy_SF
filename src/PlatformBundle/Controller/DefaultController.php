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
     * @Route("/app", name="app")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $listCommand = $em->getRepository(Command::class)->findByUserOrderDate($user);

        return $this->render('@Platform/Default/viewCommandUser.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'listCommand'=> $listCommand,
        ));
    }

    /**
     * @Route("/command", name="viewCommand")
     * @Security("has_role('ROLE_PHARMA')")
     */
    public function viewCommandAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listCommand = $em->getRepository('PlatformBundle:Command')->findByTreatmentOrderDate();

        return $this->render('@Platform/Default/viewCommand.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'listCommand' => $listCommand,
        ));
    }

    /**
     * @Route("/commandDetails/{id}", name="commandDetails", requirements={"id"="\d+"})
     * * @ParamConverter("command", options={"mapping": {"id": "id"}})
     * @Security("has_role('ROLE_BLOC')")
     * @param Command $command
     * @return Response
     */
    public function showDetailsAction(Command $command)
    {
        $em = $this->getDoctrine()->getManager();
        $totalPrice = 0;
        $listProduct = $command->getCommandProducts();
        foreach ($listProduct as $product){
            $price = $product->getProduct()->getPrice();
            $quantity = $product->getQuantity();
            $totalPrice += $price*$quantity;
        }

        return $this->render('@Platform/Default/commandDetails.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'command'=> $command,
            'totalPrice'=>$totalPrice,
        ));
    }

    /**
     * @Route("/command/treated/{id}", name="treatCommand", requirements={"id"="\d+"}, options = { "expose" = true })
     * @Security("has_role('ROLE_PHARMA')")
     */
    public function treatCommandAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $listCommand = $em->getRepository('PlatformBundle:Command')->findAll();
        $command = $em->getRepository('PlatformBundle:Command')->find($id);
        $command->setTreat(true);
        $command->setTreatmentDate(new \DateTime('now'));
        $em->persist($command);
        $em->flush();

        return new Response("La commande a été traitée");
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
