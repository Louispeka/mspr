<?php

namespace App\Controller;

use App\Repository\PaysRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Pays;

class PaysController extends AbstractController
{

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/pays', name: 'app_get_pays', methods: ['GET'])]
    public function index(PaysRepository $paysRepository): JsonResponse
    {
        $data = $paysRepository->findAll();
        $jsonData = $this->serializer->serialize($data, 'json');
        return new JsonResponse(
            $jsonData, Response::HTTP_OK, [], true
        );
    }

    #[Route('/pays', name: 'app_create_pays', methods: ['POST'])]
    public function create(Request $request, PaysRepository $paysRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $pays = new Pays();
        $pays->setLibelle($data['libelle']);
        $pays->setCodeLettre($data['code_lettre']);
        $pays->setCodeChiffre($data['code_chiffre']);
        $paysRepository->save($pays, true);
        return new JsonResponse(['status' => 'Pays created!'], Response::HTTP_CREATED);
    }

    #[Route('/pays/{id}', name: 'app_delete_pays', methods: ['DELETE'])]
    public function delete(int $id, PaysRepository $paysRepository): JsonResponse
    {
        $pays = $paysRepository->find($id);
        if ($pays) {
            $paysRepository->remove($pays, true);
            return new JsonResponse(['status' => 'Pays deleted!'], Response::HTTP_OK);
        }
        return new JsonResponse(['status' => 'Pays not found!'], Response::HTTP_NOT_FOUND);
    }

}
