<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Form\AdvertisementType;
use App\Repository\AdvertisementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AdvertisementController extends AbstractController
{
    #[Route('/advertisement', name: 'app_advertisement')]
    public function index(AdvertisementRepository $advertisementRepository, PaginatorInterface $paginator, #[MapQueryParameter] int $page = 1, #[MapQueryParameter] string $search = '',
    ): Response {
        $query = $advertisementRepository->queryAllOrderedByDate($search);
        $pagination = $paginator->paginate(
            $query,
            $page,
            15
        );

        return $this->render('advertisement/index.html.twig', [
            'advertisements' => $pagination,
            'search' => $search,
        ]);
    }

    #[Route('/advertisement/{id}', name: 'app_advertisement_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Advertisement $advertisement, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$advertisement->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $entityManager->remove($advertisement);
        $entityManager->flush();

        return $this->redirectToRoute('app_advertisement');
    }

    #[Route('/advertisement/{id}', name: 'app_advertisement_show', requirements: ['id' => '\d+'])]
    public function show(#[MapEntity(expr: 'repository.findWithCategory(id)')] Advertisement $advertisement): Response
    {
        return $this->render('advertisement/show.html.twig', [
            'advertisement' => $advertisement,
            'category' => $advertisement->getCategory(),
        ]);
    }

    #[Route('/advertisement/new', name: 'app_advertisement_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $advertisement = new Advertisement();
        $form = $this->createForm(AdvertisementType::class, $advertisement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_advertisement');
        }

        return $this->render('advertisement/_form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/advertisement/{id}/edit', name: 'app_advertisement_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, #[MapEntity(expr: 'repository.findWithCategory(id)')] Advertisement $advertisement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdvertisementType::class, $advertisement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_advertisement_show', ['id' => $advertisement->getId()]);
        }

        return $this->render('advertisement/_form.html.twig', [
            'form' => $form,
            'advertisement' => $advertisement,
        ]);
    }
}
