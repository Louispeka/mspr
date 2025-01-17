<?php

namespace App\Controller;

use App\Repository\DonneesVirusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\DonneesVirus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class DonneesVirusController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/donnees-virus', name: 'app_get_donnees_virus', methods: ['GET'])]
    public function index(DonneesVirusRepository $donneesVirusRepository): JsonResponse
    {
        $data = $donneesVirusRepository->findAll();
        $jsonData = $this->serializer->serialize($data, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['__initializer__', '__cloner__', '__isInitialized__'],
            AbstractNormalizer::CALLBACKS => [
                'pays' => function ($pays) {
                    return $pays ? $pays->getLibelle() : null;
                },
                'virus' => function ($virus) {
                    return $virus ? $virus->getLibelle() : null;
                }
            ]
        ]);
        return new JsonResponse(
            $jsonData, Response::HTTP_OK, [], true
        );
    }

    #[Route('/donnees-virus', name: 'app_create_donnees_virus', methods: ['POST'])]
    public function create(Request $request, DonneesVirusRepository $donneesVirusRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $donneesVirus = new DonneesVirus();
        $donneesVirus->setDateDuJour(new \DateTime($data['date_du_jour']));
        $donneesVirus->setTotalCas($data['total_cas']);
        $donneesVirus->setTotalMort($data['total_mort']);
        $donneesVirus->setNouveauCas($data['nouveau_cas']);
        $donneesVirus->setNouveauMort($data['nouveau_mort']);
        $donneesVirus->setPays($data['pays']);
        $donneesVirus->setVirus($data['virus']);
        $donneesVirusRepository->save($donneesVirus, true);
        return new JsonResponse(['status' => 'DonneesVirus created!'], Response::HTTP_CREATED);
    }

    #[Route('/donnees-virus/{id}', name: 'app_delete_donnees_virus', methods: ['DELETE'])]
    public function delete(int $id, DonneesVirusRepository $donneesVirusRepository): JsonResponse
    {
        $donneesVirus = $donneesVirusRepository->find($id);
        if ($donneesVirus) {
            $donneesVirusRepository->remove($donneesVirus, true);
            return new JsonResponse(['status' => 'DonneesVirus deleted!'], Response::HTTP_OK);
        }
        return new JsonResponse(['status' => 'DonneesVirus not found!'], Response::HTTP_NOT_FOUND);
    }

    // Additional methods for creating, updating, and deleting data...
}
