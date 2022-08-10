<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $user = new User();
        $checkUser = $doctrine->getRepository(User::class)->findAll();

        if (empty($checkUser)) {
            $user
                ->setName('admin')
                ->setLastName("admin")
                ->setEmail('admin@gmail.com')
                ->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        '123456'
                    )
                )
                ->setRoles([
                    "ROLE_ADMIN"
                ]);
            $this->userRepository->add($user, true);
        }


        if ($this->getUser()) {

            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
                return $this->redirectToRoute('admin');
            } else {
                return $this->redirectToRoute('app_home');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
