<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'main_')]
class MainController extends AbstractController
{
    #[Route(name: "home", methods: ['GET'])]
    public function home()
    {
        return $this->render('main/home.html.twig');
    }

    #[Route("/about-us", name: "about_us", methods: ['GET'])]
    public function aboutUs()
    {
        return $this->render('main/about-us.html.twig');
    }
}