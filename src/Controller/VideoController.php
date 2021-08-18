<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/videos", name="api_")
 */
class VideoController extends AbstractController
{
    /**
     * @Route("", name="videos", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->json([
            'Videos buscados com sucesso'
        ]);
    }

    /**
     * @Route("/{id}", name="video", methods={"GET"})
     */
    public function show(): Response
    {
        return $this->json([
            'Video buscado com sucesso'
        ]);
    }

    public function store(): Response
    {
        return $this->json([
            'Video criado com sucesso'
        ]);
    }
}
