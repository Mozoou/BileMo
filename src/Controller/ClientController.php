<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api')]
class ClientController extends AbstractController
{
    #[Route('/clients', name: 'api_clients', methods:['GET'])]
    public function clientAll(ClientRepository $clientRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json(
            [
                'status' => 'success',
                'clients' => $clientRepository->findBy(['company' => $user]),
            ],
            Response::HTTP_OK,
            [],
            [
                'groups' => 'clients:index',
            ],
        );
    }

    #[Route('/client/detail/{id}', name: 'api_client_detail', methods:['GET'])]
    public function clientDetail(Client $client): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($client->getCompany()->getId() !== $user->getId()) {
            return $this->json(
                [
                    'status' => 'error',
                    'message' => 'Ce client n\'est pas rattaché à votre compte'
                ],
                Response::HTTP_UNAUTHORIZED,
                []
            );
        }

        return $this->json(
            [
                'status' => 'success',
                'client' => $client,
            ],
            Response::HTTP_OK,
            [],
            [
                'groups' => 'client:detail',
            ],
        );
    }

    #[Route('/client/add/', name: 'api_client_add', methods:['POST'])]
    public function addClient(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        /** @var Client $client */
        $client = $serializer->deserialize($request->getContent(), Client::class, 'json');
        $client->setCompany($this->getUser());

        $errors = $validator->validate($client);

        if (0 < count($errors)) {
            return $this->json(
                [
                    'status' => 'error',
                    'messages' => [$this->getViolationMessages($errors)],
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $em->persist($client);
            $em->flush();
            return $this->json(
                [
                    'status' => 'success',
                    'id' => $client->getId()
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    #[Route('/client/remove/{id}', name: 'api_client_remove', methods:['POST'])]
    public function removeClient(Client $client, EntityManagerInterface $em): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($client->getCompany()->getId() !== $user->getId()) {
            return $this->json(
                [
                    'status' => 'error',
                    'message' => 'Ce client n\'est pas rattaché à votre compte'
                ],
                Response::HTTP_UNAUTHORIZED,
                []
            );
        }

        try {
            $user->removeClient($client);
            $em->remove($client);
            $em->flush();
            return $this->json(
                [
                    'status' => 'success',
                    'message' => 'Le client a bien été supprimé'
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    private function getViolationMessages(ConstraintViolationListInterface $errors): array
    {
        $messages = [];

        foreach ($errors as $error) {
            /** @var ConstraintViolation $error */
            $messages[$error->getPropertyPath()] = $error->getMessage();
        }

        return $messages;
    }
}
