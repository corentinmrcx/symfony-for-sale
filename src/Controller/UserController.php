<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user/{id}/advertisements', name: 'app_user_advertisements', requirements: ['id' => '\d+'])]
    public function advertisements(User $user, UserRepository $userRepository, PaginatorInterface $paginator, #[MapQueryParameter] int $page = 1): Response
    {
        $query = $userRepository->queryUserAdvertisements($user);

        $pagination = $paginator->paginate(
            $query,
            $page,
            15
        );

        return $this->render('user/advertisements.html.twig', [
            'user' => $user,
            'advertisements' => $pagination,
        ]);
    }
}
