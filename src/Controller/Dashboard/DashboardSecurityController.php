<?php

namespace CodedMonkey\Conductor\Controller\Dashboard;

use CodedMonkey\Conductor\Doctrine\Entity\User;
use CodedMonkey\Conductor\Doctrine\Repository\UserRepository;
use CodedMonkey\Conductor\Form\RegistrationFormType;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DashboardSecurityController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    #[Route('/login', name: 'dashboard_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('@EasyAdmin/page/login.html.twig', [
            'action' => $this->generateUrl('dashboard_login'),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername(),
            'username_label' => 'Email',
        ]);
    }

    #[Route('/register', name: 'dashboard_register')]
    public function register(Request $request, AdminUrlGenerator $adminUrlGenerator, #[Autowire(param: 'conductor.security.registration_enabled')] bool $registrationEnabled): Response
    {
        $userCount = $this->userRepository->count([]);

        if (!$registrationEnabled && 0 !== $userCount) {
            return $this->redirectToRoute('dashboard');
        }

        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (0 === $userCount) {
                $user->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_USER']);
            }

            $this->userRepository->save($user, true);

            return $this->redirect($adminUrlGenerator->setRoute('dashboard_login'));
        }

        return $this->render('dashboard/security/register.html.twig', [
            'form' => $form,
        ]);
    }
}
