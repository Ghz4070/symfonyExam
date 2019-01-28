<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(UserRepository $userRepository, VideoRepository $videoRepository)
    {
        $videoPublished = $videoRepository->findBy(['published' => true]);
        $videoNotPublished = $videoRepository->findBy(['published' => false]);

        $users = $userRepository->findAll();
        $videos = $videoRepository->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'users' => $users,
            'videos' => $videos,
            'videoPublished' => $videoPublished,
            'videoNotPublished' => $videoNotPublished,
        ]);
    }
}
