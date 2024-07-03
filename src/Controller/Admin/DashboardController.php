<?php

namespace App\Controller\Admin;

use App\Entity\ArticleTag;
use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Entity\EmailNewsletter;
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
            ->setTitle('Sensations')
            ->setFaviconPath('favicon.ico')
            ->setTranslationDomain('admin');
    }

    public function configureMenuItems(): iterable
    {
        
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');
        yield MenuItem::linkToRoute('Retour sur le site', 'fa fa-arrow-left', 'app_accueil');

        yield MenuItem::section('Utilisateur');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', Utilisateur::class);
        yield MenuItem::linkToCrud('Email Newsletter', 'fa fa-envelope', EmailNewsletter::class);

        yield MenuItem::section('Formations en ligne');
        yield MenuItem::linkToCrud('Formations', 'fa fa-graduation-cap', Formations::class);
        yield MenuItem::linkToCrud('Commande Détail', 'fa fa-shopping-cart', Commande::class);

        yield MenuItem::section('Rédaction d articles');
        yield MenuItem::linkToCrud('Tag des articles', 'fa-solid fa-tags', ArticleTag::class);
        yield MenuItem::linkToCrud('Rédaction d\'articles', 'fas fa-newspaper', RedactionArticles::class);
        yield MenuItem::linkToRoute('Voir les résumés d\'articles', 'fa fa-arrow-left', 'app_resumer_article');

    }
    
}
