<?php

namespace App\Controller\API;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\{Request, Response};

use App\Repository\{UserRepository, SkillsRepository, WorksRepository, SocialsNetworksRepository, EducationRepository};

class generalController extends AbstractController
{
    #[Route("/api/alldata", methods: ["GET"])]
    public function index(Request $request, UserRepository $userRepository, SkillsRepository $skillsRepository, WorksRepository $worksRepository, SocialsNetworksRepository $socialsNetworksRepository, EducationRepository $educationRepository) {
        $user = $userRepository->findby(['email' => 'greg.dilon@gmail.com'])[0];
        $skill = $skillsRepository->findAll();
        $works = $worksRepository->findAll();
        $social = $socialsNetworksRepository->findAll();
        $education = $educationRepository->findAll();

        return $this->json(compact('user', 'skill', 'works', 'social', 'education'), 200, [], [
            'groups' => ['api.general']
        ]);
    }
}
