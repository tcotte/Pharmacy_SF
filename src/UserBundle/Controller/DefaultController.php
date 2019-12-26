<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\ProfileFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/user")
 * @Security("has_role('ROLE_ADMIN')")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/addProfile", name="addProfile")
     */
    public function addProfileAction(Request $request)
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $form = $this->createForm(ProfileFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $userManager = $this->get('fos_user.user_manager');
            $userData = $form->getData();
            $email_exist = $userManager->findUserByEmail($userData->getEmail());
            $user = $userManager->createUser();

            if ($email_exist) {
                $this->addFlash('alert', 'L\'addresse e-mail "' . $userData->getEmail() . '" est déjà attribué à un autre utilisateur');
                return ($this->RedirectToRoute("addProfile"));
            }

            if ($form->isValid()) {
                $username = $user->setUsername($userData->getUsername());
                dump($form->getData());
                $user->setEmail($userData->getEmail());
//                $user->setFirstName($form->getData()->getFirstName());
//                $user->setLastName($form->getData()['lastName']);
                $user->setEmailCanonical($userData->getEmail());
                $user->setEnabled(1); // enable the user or enable it later with a confirmation token in the email
                // this method will encrypt the password with the default settings
                $user->setPlainPassword('pharmacie');
                foreach ($userData->getRoles() as $role) {
                    $user->addRole($role);
                }
                $userManager->updateUser($user);

                $request->getSession()->getFlashBag()->add('success', 'Utilisateur "' . $username . '" bien enregistré !');
                return $this->redirectToRoute('userManagement');
            }
        }
        return $this->render('@User/addProfile.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'form' => $form->createView(),
        ));
    }

    /**
 * @Route("/userManagement", name="userManagement")
 */
    public function userManagementAction(Request $request)
    {
        if($this->getUser() == null)
        {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        dump(get_class($paginator));
        $result = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('@User/userManagement.html.twig', array(
           'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'users' => $users,
            'users' => $result,
        ));
    }

    /**
     * @Route("/delete/{id}", name="deleteProfile", requirements={"id" = "\d+"})
     */
    public function deleteProfileAction($id)
    {
        if($this->getUser() == null)
        {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        $username = $user->getUsername();

        $userManager->deleteUser($user);

        $this->addFlash('alert', 'L\'utilisateur ' . $username . ' a bien été supprimé !');

        return $this->redirectToRoute('userManagement');
    }

    /**
     * @Route("/edit/{id}", name="editProfile", requirements={"id" = "\d+"})
     */
    public function editProfileAction($id, Request $request)
    {
        if($this->getUser() == null)
        {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        $username = $user->getUsername();

        $form = $this->createForm(ProfileFormType::class);
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $userManager->updateUser($user);

            $this->addFlash('info', 'L\'utilisateur ' . $username . ' a bien été modifié !');

            return $this->redirectToRoute('userManagement');
        }

        return $this->render('@FOSUser/Profile/edit.html.twig', array(
            'listCategory' => $this->get('app_service.layout_data')->getLayoutData(),
            'form' => $form->createView(),
        ));
    }



}