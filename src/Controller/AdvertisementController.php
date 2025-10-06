<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\AdvertisementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class AdvertisementController extends AbstractController
{
    #[Route('/advertisement', name: 'app_advertisement')]
    public function index(AdvertisementRepository $advertisementRepository): Response
    {
        $advertisements = $advertisementRepository->findAllOrderedByDate();

        return $this->render('advertisement/index.html.twig', [
            'advertisements' => $advertisements,
        ]);
    }

    #[Route('/advertisement/{id}', name: 'app_advertisement_show', requirements: ['id' => '\d+'])]
    public function show(Advertisement $advertisement): Response
    {
        return $this->render('advertisement/show.html.twig', [
            'advertisement' => $advertisement,
        ]);
    }

    #[Route('/advertisement/new', name: 'app_advertisement_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $advertisement = new Advertisement();
        $form = $this->createForm(AdvertisementType::class, $advertisement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($advertisement);
            $entityManager->flush();

            return $this->redirectToRoute('app_advertisement');
        }

        return $this->render('advertisement/_form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/advertisement/{id}/edit', name: 'app_advertisement_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, Advertisement $advertisement, EntityManagerInterface $entityManager): Response
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

    #[Route('/advertisement/{id}', name: 'app_advertisement_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Advertisement $advertisement, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $advertisement->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $entityManager->remove($advertisement);
        $entityManager->flush();
        var_dump($advertisement->getId());

        return $this->redirectToRoute('app_advertisement');
    }
}
