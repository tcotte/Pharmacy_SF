<?php

namespace PlatformBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PlatformBundle\Entity\Command;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Form\ProfileFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/command")
 */
class CommandController extends Controller
{

    /**
     * @Route("/viewCommand", name="viewCommand")
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
     * @Route("/oldCommand", name="viewOldCommand")
     * @Security("has_role('ROLE_PHARMA')")
     */
    public function viewOldCommandAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listCommand = $em->getRepository('PlatformBundle:Command')->findByTreatedOrderDate();

        return $this->render('@Platform/Default/viewOldCommand.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'listCommand' => $listCommand,
        ));
    }

    /**
     * @Route("/commandDetails/{id}", name="commandDetails")
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
     * @Route("/command/Treated/{id}", name="treatCommand", requirements={"id"="\d+"}, options = { "expose" = true })
     * @Security("has_role('ROLE_PHARMA')")
     */
    public function treatCommandAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $command = $em->getRepository('PlatformBundle:Command')->find($id);
        $command->setTreat(true);
        $command->setTreatmentDate(new \DateTime('now'));
        $em->persist($command);
        $em->flush();

        return new Response("La commande a été traitée");
    }

}