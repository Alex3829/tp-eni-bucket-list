<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wish', name: 'wish_')]
final class WishController extends AbstractController
{
    #[Route('/list', name: "list", methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('wish/list.html.twig');
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id): Response
    {
        return $this->render('wish/detail.html.twig');
    }
}