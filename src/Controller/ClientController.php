<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api')]
class ClientController extends AbstractController
{
    /**
     * Récupère tous les clients associés à l'entreprise de l'utilisateur actuellement authentifié.
     */
    #[Route('/clients', name: 'api_clients', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'All clients list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Client::class, groups: ['clients:index']))
        )
    )]
    #[OA\Tag(name: 'clients')]
    #[Security(name: 'Bearer')]
    #[Cache(public: true, maxage: 3600, mustRevalidate: true)]
    public function clientAll(
        ClientRepository $clientRepository,
        #[MapQueryParameter(name: 'page')] int $page = 1,
        #[MapQueryParameter(name: 'limit')] int $limit = 1,
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();

        $offset = ($page - 1) * $limit;

        $clients = $clientRepository->findBy(['company' => $user], [], $limit, $offset);

        return $this->json(
            [
                $clients
            ],
            Response::HTTP_OK,
            [],
            [
                'groups' => 'clients:index',
            ],
        );
    }

    /**
     * Récupère les détails d'un client spécifique associé à l'entreprise de l'utilisateur authentifié.
     */
    #[Route('/client/{id}/detail', name: 'api_client_detail', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Detail of client',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Client::class, groups: ['client:detail']))
        )
    )]
    #[OA\Tag(name: 'clients')]
    #[Security(name: 'Bearer')]
    #[Cache(public: true, maxage: 3600, mustRevalidate: true)]
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
                $client
            ],
            Response::HTTP_OK,
            [],
            [
                'groups' => 'client:detail',
            ],
        );
    }

    /**
     * Ajoute un nouveau client associé à l'entreprise de l'utilisateur authentifié.
     */
    #[Route('/client/add/', name: 'api_client_add', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Return id of the new client in case of success',
        content: new OA\JsonContent(
            type: 'success',
        )
    )]
    #[OA\Tag(name: 'clients')]
    #[Security(name: 'Bearer')]
    public function addClient(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        /** @var Client $client */
        $client = $serializer->deserialize($request->getContent(), Client::class, 'json');
        /** @var User $this->getUser() */
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
                    'id' => $client->getId()
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Supprime un client associé à l'entreprise de l'utilisateur authentifié.
     */
    #[Route('/client/{id}', name: 'api_client_remove', methods: ['DELETE'])]
    #[OA\Tag(name: 'clients')]
    #[Security(name: 'Bearer')]
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

    /**
     * Return an array of violation messages
     * 
     * @param ConstraintViolationListInterface $errors
     *
     * @return array<string|Stringable>
     */
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
