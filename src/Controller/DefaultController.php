<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/list', name: 'app_default')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('default/index.html.twig', [
            'users' => $userRepository->findAll(),
            "title" => "Liste"
        ]);
    }
}