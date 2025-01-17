<?php

namespace App\Controller;

use App\Repository\VirusRepository;
use App\Entity\Virus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class VirusController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/virus', name: 'app_get_virus', methods: ['GET'])]
    public function index(VirusRepository $virusRepository): JsonResponse
    {
        $data = $virusRepository->findAll();
        $jsonData = $this->serializer->serialize($data, 'json');
        return new JsonResponse(
            $jsonData, Response::HTTP_OK, [], true
        );
    }

    #[Route('/virus', name: 'app_create_virus', methods: ['POST'])]
    public function create(Request $request, VirusRepository $virusRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $virus = new Virus();
        $virus->setLibelle($data['libelle']);
        $virus->setDateApparition(new \DateTime($data['date_apparition']));
        $virus->setDateFin(new \DateTime($data['date_fin']));
        $virus->setDescription($data['description']);
        $virusRepository->save($virus, true);
        return new JsonResponse(['status' => 'Virus created!'], Response::HTTP_CREATED);
    }

    #[Route('/virus/{id}', name: 'app_delete_virus', methods: ['DELETE'])]
    public function delete(int $id, VirusRepository $virusRepository): JsonResponse
    {
        $virus = $virusRepository->find($id);
        if ($virus) {
            $virusRepository->remove($virus, true);
            return new JsonResponse(['status' => 'Virus deleted!'], Response::HTTP_OK);
        }
        return new JsonResponse(['status' => 'Virus not found!'], Response::HTTP_NOT_FOUND);
    }
}
