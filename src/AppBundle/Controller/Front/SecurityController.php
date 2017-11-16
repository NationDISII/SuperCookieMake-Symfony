<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller {

    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('front/default/user/login.html.twig', [
            'error' => $error,
            'lastUsername' => $lastUsername
        ]);
    }

    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->set('success', "Just login to make some cookies");

            return $this->redirectToRoute('user_login');
        }

        return $this->render('front/default/user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function logoutAction() { }

    public function editAccountAction()
    {
        return $this->render('front/default/user/edit.html.twig');
    }
}