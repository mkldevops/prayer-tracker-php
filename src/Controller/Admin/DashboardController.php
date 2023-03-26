<?php

namespace App\Controller\Admin;

use Iterator;
use App\Entity\Objective;
use App\Entity\Prayer;
use App\Entity\PrayerName;
use App\Entity\Program;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', []);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('<strong>ADMIN PRAYER PROGRAM</strong>');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()->setDateFormat('dd/MM/yyyy')->setDateTimeFormat('dd/MM/yyyy h:mm A zzzz');
    }

    public function configureMenuItems(): Iterator
    {
        yield MenuItem::linktoRoute('Homepage', 'fas fa-home', 'app_home');
        yield MenuItem::linkToCrud('Prayer', 'fas fa-folder-open', Prayer::class);
        yield MenuItem::linkToCrud('Program', 'fas fa-folder-open', Program::class);
        yield MenuItem::linkToCrud('Objective', 'fas fa-folder-open', Objective::class);
        yield MenuItem::linkToCrud('PrayerName', 'fas fa-folder-open', PrayerName::class);
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
    }
}
