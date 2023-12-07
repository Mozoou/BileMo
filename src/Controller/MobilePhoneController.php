<?php

namespace App\Controller;

use App\Entity\MobilePhone;
use App\Repository\MobilePhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class MobilePhoneController extends AbstractController
{
    #[Route('/mobilephones', name: 'api_mobile_phones')]
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

    #[Route('/mobilephone/detail/{id}', name: 'api_mobile_phone_detail')]
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
