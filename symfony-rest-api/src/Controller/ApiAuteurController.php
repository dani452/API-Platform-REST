<?php

namespace App\Controller;

use App\Repository\AuteurRepository;
use App\Entity\Auteur;
use App\Repository\NationnaliteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiAuteurController extends AbstractController
{
    /**
     * @Route("/api/auteur", name="api_auteur", methods={"GET"})
     */
    function list(AuteurRepository $repo, SerializerInterface $serializer) {
        $auteurs = $repo->findAll();
        $resultat = $serializer->serialize(
            $auteurs,
            'json',
            [
                'groups' => ['listAuteurFull']
            ]
        );
        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/auteur/{id}", name="api_auteur_show", methods={"GET"})
     */
    function show(Auteur $auteur, SerializerInterface $serializer) {

        $resultat = $serializer->serialize(
            $auteur,
            'json',
            [
                'groups' => ['listAuteurSimple']
            ]
        );
        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/auteur", name="api_auteur_create", methods={"POST"})
     */
    function create(Request $request, NationnaliteRepository $repoNationalite, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator) {

        $data=$request->getContent();
        $dataTab = json_decode($data, 'json');
        $auteur=new Auteur();
        // Si la nationalité est préenregistrée dans la BDD dans une table, 
        // on la récupère via son ID et on l'ajoute dans l'objet auteur.
        $nationalite = $repoNationalite->find($dataTab['nationnalite']['id']);
        $serializer->deserialize($data, Auteur::class, 'json', ['object_to_populate'=>$auteur]);
        $auteur->setNationnalite($nationalite);

        $errors = $validator->validate($auteur);
        if(count($errors)){
            $errorsJson=$serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $manager->persist($auteur);
        $manager->flush();
      
        return new JsonResponse(
            "auteur à bien été crée", 
            Response::HTTP_CREATED, [
            "location" =>"api/auteur/".$auteur->getId()], 
            true
        );
    }

    /**
     * @Route("/api/auteur/{id}", name="api_auteur_update", methods={"PUT"})
     */
    function edit(Auteur $auteur, Request $request, NationnaliteRepository $repoNationalite, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator) {
        $data = $request->getContent();
        $dataTab = json_decode($data, 'json');
        // Si la nationalité est préenregistrée dans la BDD dans une table, 
        // on la récupère via son ID et on l'ajoute dans l'objet auteur.
        $nationalite = $repoNationalite->find($dataTab['nationnalite']['id']);
        $auteur->setNationnalite($nationalite);
        $auteur->setNom($dataTab['Nom']);
        $auteur->setPrenom($dataTab['prenom']);

        $errors = $validator->validate($auteur);
        if(count($errors)){
            $errorsJson=$serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $manager->persist($auteur);
        $manager->flush();

        return new JsonResponse("l'auteur à bien été modifié", 200, [], true);
    }

    /**
     * @Route("/api/auteur/{id}", name="api_auteur_delete", methods={"DELETE"})
     */
    function delete(Auteur $auteur, EntityManagerInterface $manager) {
       
        $manager->remove($auteur);
        $manager->flush();

        return new JsonResponse("l'auteur à bien été supprimé", 200, []);
    }
}

