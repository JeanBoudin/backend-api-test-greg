<?php

namespace App\Controller\Admin;

use App\Entity\{Education, Skills, SocialsNetworks, User, Works};

use EasyCorp\Bundle\EasyAdminBundle\Config\{Dashboard, MenuItem};
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Http\Attribute\IsGranted;

// use App\Controller\Admin\UserCrudController;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
        // return 'ouai c\'est greg';
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Portfolio Back');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Compétences', 'fas fa-pen', Skills::class);
        yield MenuItem::linkToCrud('Projets', 'fas fa-list-check', Works::class);
        yield MenuItem::linkToCrud('Diplôme', 'fas fa-diploma', Education::class);
        yield MenuItem::linkToCrud('Réseau sociaux', 'fas fa-file-certificate', SocialsNetworks::class);
    }
}
