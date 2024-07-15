<?php

namespace CodedMonkey\Conductor\Controller\Dashboard;

use CodedMonkey\Conductor\Attribute\IsGrantedAccess;
use CodedMonkey\Conductor\Doctrine\Entity\AccessToken;
use CodedMonkey\Conductor\Doctrine\Entity\Credentials;
use CodedMonkey\Conductor\Doctrine\Entity\Registry;
use CodedMonkey\Conductor\Doctrine\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardRootController extends AbstractDashboardController
{
    public function __construct(
        #[Autowire(param: 'conductor.title')]
        private readonly string $title,
        #[Autowire(param: 'conductor.security.registration_enabled')]
        private readonly bool $registrationEnabled,
    ) {
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle($this->title);
    }

    public function configureMenuItems(): iterable
    {
        $user = $this->getUser();

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Packages', 'fa fa-cubes', 'dashboard_packages');

        yield MenuItem::section('Personal');
        if ($user) {
            yield MenuItem::linkToRoute('Account', 'fa fa-id-card', 'dashboard_account');
            yield MenuItem::linkToCrud('Access tokens', 'fa fa-key', AccessToken::class);
            yield MenuItem::linkToLogout('Sign out', 'fa fa-user');
        } else {
            yield MenuItem::linkToRoute('Sign in', 'fa fa-user', 'dashboard_login');

            if ($this->registrationEnabled) {
                yield MenuItem::linkToRoute('Register', 'fa fa-user-plus', 'dashboard_register');
            }
        }

        if ($user?->isAdmin()) {
            yield MenuItem::section('Administration');
            yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);
            yield MenuItem::linkToCrud('Registries', 'fa fa-server', Registry::class);
            yield MenuItem::linkToCrud('Credentials', 'fa fa-lock-open', Credentials::class);
        }

        yield MenuItem::section('Documentation');
        yield MenuItem::linkToRoute('Usage', 'fa fa-file', 'dashboard_docs');
        yield MenuItem::linkToRoute('Administration', 'fa fa-file', 'dashboard_admin_docs')
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('Credits', 'fa fa-file', 'dashboard_credits');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getUserIdentifier())
            ->setGravatarEmail($user->getEmail())
            ->addMenuItems([
                MenuItem::linkToRoute('Account', 'fa fa-id-card', 'dashboard_account'),
            ]);
    }

    #[Route('/', name: 'dashboard')]
    #[IsGrantedAccess]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }

    #[Route('/dashboard/docs', name: 'dashboard_docs')]
    #[IsGrantedAccess]
    public function docs(): Response
    {
        return $this->render('dashboard/docs/index.html.twig');
    }

    #[Route('/dashboard/docs/admin', name: 'dashboard_admin_docs')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminDocs(): Response
    {
        return $this->render('dashboard/docs/admin.html.twig');
    }

    #[Route('/dashboard/credits', name: 'dashboard_credits')]
    public function credits(): Response
    {
        return $this->render('dashboard/credits.html.twig');
    }
}
