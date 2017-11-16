<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Cookie;
use AppBundle\Form\CookieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CookieController extends Controller {

    public function indexAction()
    {
        $cookies = $this->getDoctrine()->getRepository("AppBundle:Cookie")->findAll();
        $users = $this->getDoctrine()->getRepository("AppBundle:User")->findAll();

        return $this->render('front/default/cookies/list.html.twig', [
            'cookies' => $cookies,
            "users" => $users
        ]);
    }

    public function shareAction(Request $request)
    {
        $cookieId = $request->request->get('_cookieId');
        $cookie = $this->getDoctrine()->getRepository('AppBundle:Cookie')->find($cookieId);

        if($this->isGranted('SHARE', $cookie) && $request->getMethod() == "POST") {
            $toUserId = $request->request->get('toUser');
            $newOwner = $this->getDoctrine()->getRepository('AppBundle:User')->find($toUserId);
            $em = $this->getDoctrine()->getManager();
            $cookie->setOwner($newOwner);
            $em->persist($cookie);
            $em->flush();
            $this->get('session')->getFlashBag()->set("success", "Yayyy");
        } else {
            $this->get('session')->getFlashBag()->set('danger', "Nope !");
        }

        return $this->redirectToRoute('cookies_list');
    }

    public function addAction(Request $request)
    {
        $cookie = new Cookie();
        $form = $this->createForm(CookieType::class, $cookie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $cookie->setOwner($this->getUser());

            $em->persist($cookie);
            $em->flush();

            $this->get('session')->getFlashBag()->set('success', "Yayy a new cookie ! Share it with friend or eat it alone");

            return $this->redirectToRoute('cookies_list');
        }

        return $this->render('front/default/cookies/add.html.twig', [
           'form' => $form->createView()
        ]);
    }

    public function eatAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $cookie = $em->getRepository("AppBundle:Cookie")->find($id);

        if(!$cookie) {
            throw $this->createNotFoundException();
        }

        if($this->isGranted('EAT', $cookie))
        {
            $em->remove($cookie);
            $em->flush();
            $this->get('session')->getFlashBag()->set("success", "That cookie was perfect !");
        } else {
            $this->get('session')->getFlashBag()->set('danger', "It's not your cookie");
        }

        return $this->redirectToRoute("cookies_list");
    }

}