<?php

namespace App\Controller;

use App\Entity\Nationnalite;
use App\Form\NationnaliteType;
use App\Repository\NationnaliteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/nationnalite")
 */
class NationnaliteController extends AbstractController
{
    /**
     * @Route("/", name="nationnalite_index", methods={"GET"})
     */
    public function index(NationnaliteRepository $nationnaliteRepository): Response
    {
        return $this->render('nationnalite/index.html.twig', [
            'nationnalites' => $nationnaliteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="nationnalite_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $nationnalite = new Nationnalite();
        $form = $this->createForm(NationnaliteType::class, $nationnalite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($nationnalite);
            $entityManager->flush();

            return $this->redirectToRoute('nationnalite_index');
        }

        return $this->render('nationnalite/new.html.twig', [
            'nationnalite' => $nationnalite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nationnalite_show", methods={"GET"})
     */
    public function show(Nationnalite $nationnalite): Response
    {
        return $this->render('nationnalite/show.html.twig', [
            'nationnalite' => $nationnalite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="nationnalite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Nationnalite $nationnalite): Response
    {
        $form = $this->createForm(NationnaliteType::class, $nationnalite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('nationnalite_index');
        }

        return $this->render('nationnalite/edit.html.twig', [
            'nationnalite' => $nationnalite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nationnalite_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Nationnalite $nationnalite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nationnalite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($nationnalite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('nationnalite_index');
    }
}
