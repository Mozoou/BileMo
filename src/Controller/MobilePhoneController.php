<?php

namespace App\Controller;

use App\Entity\MobilePhone;
use App\Repository\MobilePhoneRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class MobilePhoneController extends AbstractController
{
    /**
     * Récupère tous les téléphones mobiles disponibles.
     */
    #[Route('/mobilephones', name: 'api_mobile_phones', methods:['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List all mobile phones',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: MobilePhone::class, groups: ['products:index']))
        )
    )]
    #[OA\Tag(name: 'mobilephone')]
    #[Security(name: 'Bearer')]
    public function mobilePhoneAll(MobilePhoneRepository $mobilePhoneRepository): JsonResponse
    {
        return $this->json(
            [
                'status' => 'success',
                'products' => $mobilePhoneRepository->findAll(),
            ],
            Response::HTTP_OK,
            [],
            [
                'groups' => 'products:index',
            ],
        );
    }

    /**
     *  Récupère les détails d'un téléphone mobile spécifique.
     */
    #[Route('/mobilephone/detail/{id}', name: 'api_mobile_phone_detail', methods:['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Detail of mobile phone',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: MobilePhone::class, groups: ['product:detail']))
        )
    )]
    #[OA\Tag(name: 'mobilephone')]
    #[Security(name: 'Bearer')]
    public function mobilePhoneDetail(MobilePhone $mobilePhone): JsonResponse
    {
        return $this->json(
            [
                'status' => 'success',
                'mobilePhone' => $mobilePhone,
            ],
            Response::HTTP_OK,
            [],
            [
                'groups' => 'product:detail',
            ],
        );
    }
}
