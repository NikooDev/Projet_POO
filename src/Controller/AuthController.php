<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Authentification
 */
class AuthController extends AbstractController
{
	/**
	 * Inscription
	 * @Route("/signup", name="auth_signup")
	 * @param Request $request
	 * @param UserPasswordHasherInterface $userPasswordHasher
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	public function signup(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
	{
		$user = new User();
		$form = $this->createForm(RegistrationFormType::class, $user);
		$form->handleRequest($request);

		// Hash du password si le formulaire est soumis
		if ($form->isSubmitted() && $form->isValid()) {
			$user->setPassword(
				$userPasswordHasher->hashPassword(
					$user,
					$form->get('plainPassword')->getData()
				)
			);

			// Ajout du rôle USER par défaut
			$user->setRoles(array('ROLE_USER'));

			// Persistence BDD
			$entityManager->persist($user);
			$entityManager->flush();

			// Redirection vers /login
			return $this->redirectToRoute('auth_login');
		}

		return $this->render('auth/signup.html.twig', [
			'registrationForm' => $form->createView(),
		]);
	}

	/**
	 * Connexion
	 * @Route("/login", name="auth_login")
	 * @param AuthenticationUtils $authenticationUtils
	 * @return Response
	 */
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('auth/login.html.twig', [
			'last_username' => $lastUsername,
			'error' => $error
		]);
	}
}
