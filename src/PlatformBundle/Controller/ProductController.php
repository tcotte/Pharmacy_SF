<?php

namespace PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use PlatformBundle\Entity\Product;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{

    /**
     * @Route("/addProduct", name="addProduct")
     */
    public function addProductAction()
    {
        // On crée un objet Advert
        $product = new Product();

        // On crée le FormBuilder grâce au service form factory
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $product);

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('designation',      TextType::class)
            ->add('code',     TextType::class)
            ->add('reference',   TextType::class)
            ->add('supplier',    TextType::class)
            ->add('market', TextType::class)
            ->add('price', MoneyType::class)
            ->add('cdt', NumberType::class)
            ->add('save',      SubmitType::class);
        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('@Platform/Form/product.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'form' => $form->createView(),
        ));
    }
}
