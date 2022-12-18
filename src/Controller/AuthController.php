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
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
	/**
	 * @Route("/signup", name="auth_signup")
	 * @param Request $request
	 * @param UserPasswordHasherInterface $userPasswordHasher
	 * @param EntityManagerInterface $entityManager
	 * @param ValidatorInterface $validator
	 * @return Response
	 */
	public function signup(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
	{
		if ($this->getUser()) {
			return $this->redirectToRoute('pokemon_user', ['username' => $this->getUser()->getUserIdentifier()]);
		}

		$errors = array();
		$user = new User();
		$form = $this->createForm(RegistrationFormType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// Encodage du password
			$user->setPassword(
				$userPasswordHasher->hashPassword(
					$user,
					$form->get('plainPassword')->getData()
				)
			);

			// Ajout du rôle USER par défaut
			$user->setRoles(array('ROLE_USER'));

			$entityManager->persist($user);
			$entityManager->flush();

			return $this->redirectToRoute('auth_login');
		} else {
			$errorsForm = $validator->validate($form);
			foreach ($errorsForm as $error) {
				$errors[$error->getPropertyPath()] = [
					'field' => $error->getPropertyPath(),
					'message' => $error->getMessage()
				];
			}
		}

		return $this->render('auth/signup.html.twig', [
			'registrationForm' => $form->createView(),
			'errors' => $errors
		]);
	}

	/**
	 * @Route("/login", name="auth_login")
	 * @param AuthenticationUtils $authenticationUtils
	 * @return Response
	 */
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		if ($this->getUser()) {
			return $this->redirectToRoute('pokemon_user', ['username' => $this->getUser()->getUserIdentifier()]);
		}

		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('auth/login.html.twig', [
			'last_username' => $lastUsername,
			'error' => $error
		]);
	}
}
