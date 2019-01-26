<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /**
     * @Route("/video", name="video")
     */
    public function index(Request $request, VideoRepository $videoRepository)
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $video->setUser($this->getUser());
            $entityManager->persist($video);
            $entityManager->flush();
            $this->addFlash('success', 'Votre video a bien ete mise en ligne !');
            return $this->redirectToRoute('home');
        }
        $videos = $videoRepository->findAll();

        $videoPublished = $videoRepository->findBy(['published' => true]);
        $videoNotPublished = $videoRepository->findBy(['published' => false]);


        return $this->render('video/index.html.twig', array(
            'videos' => $videos,
            'videoPublished' => $videoPublished,
            'videoNotPublished' => $videoNotPublished,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/video/{id}", name="detail_video")
     * @ParamConverter("video", options={"mapping"={"id"="id"}})
     */
    public function video(Video $video)
    {
        return $this->render('video/detail.html.twig', array(
            'video' => $video,
        ));
    }

    /**
     * @Route("/video/remove/{id}", name="remove_video")
     * @ParamConverter("video", options={"mapping"={"id"="id"}})
     */
    public function remove(Video $video, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($video);
        $entityManager->flush();
        $this->addFlash('notice', 'Element supprimer !');
        return $this->redirectToRoute('home');
    }
}