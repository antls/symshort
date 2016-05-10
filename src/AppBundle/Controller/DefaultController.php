<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Link;
use AppBundle\Form\LinkType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $link = new Link();

        $form = $this->createForm(LinkType::class, $link);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $link = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($link);
            $em->flush();

            $this->addFlash(
                'link',
                $this->generateUrl('redirect', ['code' => $link->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{code}", name="redirect")
     *
     * @param Request $request
     * @param $code
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function redirectAction(Request $request, $code)
    {
        /** @var Link $link */
        $link = $this->getDoctrine()
            ->getRepository('AppBundle:Link')
            ->find($code);

        if (!$link) {
            throw $this->createNotFoundException(
                'No link found for code ' . $code
            );
        }

        return $this->redirect($link->getUrl());
    }
}
