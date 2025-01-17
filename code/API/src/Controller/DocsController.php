<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class DocsController extends AbstractController
{
    #[Route('/docs', name: 'app_docs', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $routes = [
            'Pays' => [
                'GET /pays' => 'Get all Pays records',
                'POST /pays' => 'Create a new Pays record',
                'DELETE /pays/{id}' => 'Delete a Pays record by ID'
            ],
            'Virus' => [
                'GET /virus' => 'Get all Virus records',
                'POST /virus' => 'Create a new Virus record',
                'DELETE /virus/{id}' => 'Delete a Virus record by ID'
            ],
            'DonneesVirus' => [
                'GET /donnees-virus' => 'Get all DonneesVirus records',
                'POST /donnees-virus' => 'Create a new DonneesVirus record',
                'DELETE /donnees-virus/{id}' => 'Delete a DonneesVirus record by ID'
            ]
        ];

        return new JsonResponse($routes, Response::HTTP_OK);
    }
}
