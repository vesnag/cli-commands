<?php

namespace AG\RepoStatusBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends AbstractController
{
    #[Route('/repo-status', name: 'repo_status')]
    public function index(): Response
    {
        return new Response('Repo status is healthy!');
    }
}
