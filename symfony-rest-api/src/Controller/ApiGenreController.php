<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genre", name="api_genre", methods={"GET"})
     */
    function list(GenreRepository $repo, SerializerInterface $serializer) {
        $genres = $repo->findAll();
        $resultat = $serializer->serialize(
            $genres,
            'json',
            [
                'groups' => ['listGenreFull']
            ]
        );
        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/genre/{id}", name="api_genre_show", methods={"GET"})
     */
    function show(Genre $genre, SerializerInterface $serializer) {

        $resultat = $serializer->serialize(
            $genre,
            'json',
            [
                'groups' => ['listGenreSimple']
            ]
        );
        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/genre", name="api_genre_create", methods={"POST"})
     */
    function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator) {

        $data=$request->getContent();
        $genre=$serializer->deserialize($data, Genre::class, 'json');

        $errors = $validator->validate($genre);
        if(count($errors)){
            $errorsJson=$serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $manager->persist($genre);
        $manager->flush();
      
        return new JsonResponse(
            "genre à bien été crée", 
            Response::HTTP_CREATED, [
            "location" =>"api/genre/".$genre->getId()], 
            true
        );
    }

    /**
     * @Route("/api/genre/{id}", name="api_genre_update", methods={"PUT"})
     */
    function edit(Genre $genre, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator) {
        $data = $request->getContent();
        $serializer->deserialize($data, Genre::class, 'json',['object_to_populate'=>$genre]);

        $errors = $validator->validate($genre);
        if(count($errors)){
            $errorsJson=$serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $manager->persist($genre);
        $manager->flush();

        return new JsonResponse("le genre à bien été modifié", 200, [], true);
    }

    /**
     * @Route("/api/genre/{id}", name="api_genre_delete", methods={"DELETE"})
     */
    function delete(Genre $genre, EntityManagerInterface $manager) {
       
        $manager->remove($genre);
        $manager->flush();

        return new JsonResponse("le genre à bien été supprimé", 200, []);
    }
}

