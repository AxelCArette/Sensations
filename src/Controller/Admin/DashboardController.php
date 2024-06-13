<?php

namespace App\Controller\Admin;

use App\Entity\ArticleTag;
use App\Entity\Formations;
use App\Entity\RedactionArticles;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/admin.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sensation')
            ->setFaviconPath('favicon.ico')
            ->setTranslationDomain('admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Gestion des contenus');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', Utilisateur::class);
        yield MenuItem::linkToCrud('Tag des articles','fa-solid fa-tags', ArticleTag::class);
        yield MenuItem::linkToCrud('Rédaction d\'articles', 'fas fa-newspaper', RedactionArticles::class);
        yield MenuItem::linkToCrud('Formations','fa fa-graduation-cap', Formations::class);
        
    }
}
