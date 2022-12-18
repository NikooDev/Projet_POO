<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonEditFormType;
use App\Form\PokemonFormType;
use App\Repository\CategoryRepository;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PokemonController extends AbstractController
{
	/**
	 * @var EntityManagerInterface
	 */
	protected $em;

	/**
	 * Hydrate l'entityManager
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager) {
		$this->em = $entityManager;
	}

	/**
	 * @Route("/pokemon/create", name="pokemon_create")
	 * @param Request $request
	 * @param CategoryRepository $categoryRepository
	 * @param SluggerInterface $slugger
	 * @param ValidatorInterface $validator
	 * @return Response
	 */
	public function create(
		Request $request,
		CategoryRepository $categoryRepository,
		SluggerInterface $slugger,
		ValidatorInterface $validator): Response
	{
		$errors = array();
		$pokemon = new Pokemon();
		$form = $this->createForm(PokemonFormType::class, $pokemon);
		$form->handleRequest($request);
		$user = $this->getUser();

		if ($form->isSubmitted() && $form->isValid()){
			$pokemon->setAuthor($user->getUserIdentifier());
			$pokemon->setUser($user);

			$type = $form->get('type')->getData();
			$name = $form->get('name')->getData();
			$file = $form->get('image_url')->getData();

			$category = $categoryRepository->findOneBy(['name' => $type]);
			$pokemon->setCategory($category);
			$pokemon->setName(ucfirst($name));

			if ($file) {
				// Upload nouvelle image
				$projectDir = $this->getParameter('kernel.project_dir');
				$Filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
				$safeFilename = $slugger->slug($Filename);
				$newFilename = $safeFilename.'.'.$file->guessExtension();
				$file->move($projectDir.'/public/static/uploads/pokemons', $newFilename);
				$pokemon->setImageUrl($newFilename);
			}

			$this->em->persist($pokemon);
			$this->em->flush();

			return $this->redirectToRoute('pokemon_user', ['username' => $user->getUserIdentifier()]);
		} else {
			$errorsForm = $validator->validate($form);
			foreach ($errorsForm as $error) {
				$errors[$error->getPropertyPath()] = [
					'field' => $error->getPropertyPath(),
					'message' => $error->getMessage()
				];
			}
		}

		return $this->render('pokemon/create.html.twig', [
			'createForm' => $form->createView(),
			'errors' => $errors
		]);
	}

	/**
	 * @Route("/pokemon/{name}", name="pokemon_read")
	 * @param Request $request
	 * @param PokemonRepository $pokemonRepository
	 * @param string $name
	 * @return Response
	 */
	public function read(Request $request, PokemonRepository $pokemonRepository, string $name): Response
	{
		$pokemon = $pokemonRepository->findOneBy(['name' => ucfirst($name)]);

		if (!$pokemon) {
			$request->getSession()->getFlashBag()->add('error', 'Ce pokemon n\'existe pas');
			return $this->redirectToRoute('category_readall');
		}

		return $this->render('pokemon/read.html.twig', [
			'pokemon' => $pokemon
		]);
	}

	/**
	 * @Route("/pokemon/{name}/update", name="pokemon_update")
	 * @param Request $request
	 * @param PokemonRepository $pokemonRepository
	 * @param CategoryRepository $categoryRepository
	 * @param EntityManagerInterface $entityManager
	 * @param ValidatorInterface $validator
	 * @param SluggerInterface $slugger
	 * @param string $name
	 * @return Response
	 */
	public function update(
		Request $request,
		PokemonRepository $pokemonRepository,
		CategoryRepository $categoryRepository,
		EntityManagerInterface $entityManager,
		ValidatorInterface $validator,
		SluggerInterface $slugger,
		string $name): Response
	{
		$errors = array();
		$pokemons = $pokemonRepository->findOneBy(['name' => ucfirst($name)]);
		$form = $this->createForm(PokemonEditFormType::class, $pokemons);
		$user = $this->getUser();

		$form->handleRequest($request);

		// Message et redirection si le pokémon n'existe pas
		if (!$pokemons) {
			$request->getSession()->getFlashBag()->add('error', 'Ce pokemon n\'existe pas');
			return $this->redirectToRoute('pokemon_user', ['username' => $user->getUserIdentifier()]);
		}

		// Vérification de l'identité de l'utilisateur
		if ($pokemons->getUser()->getUserIdentifier() !== $user->getUserIdentifier()) {
			$request->getSession()->getFlashBag()->add('error', 'Vous ne pouvez pas modifier ce pokemon');
			return $this->redirectToRoute('pokemon_user', ['username' => $user->getUserIdentifier()]);
		}

		// Gestion de l'upload
		$file = $form->get('image_url')->getData();
		$projectDir = $this->getParameter('kernel.project_dir');

		// Si modification du fichier
		if ($file) {
			// Supprime l'ancienne image
			$filesystem = new Filesystem();
			$nameFile = $pokemons->getImageUrl();
			$filesystem->remove($projectDir.'/public/static/uploads/pokemons/'.$nameFile);

			// Upload nouvelle image
			$Filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
			$safeFilename = $slugger->slug($Filename);
			$newFilename = $safeFilename.'.'.$file->guessExtension();
			$file->move($projectDir.'/public/static/uploads/pokemons', $newFilename);
			$pokemons->setImageUrl($newFilename);
		} else {
			// Si aucune modification, on garde l'image
			$pokemons->setImageUrl($pokemons->getImageUrl());
		}

		if($form->isSubmitted() && $form->isValid()){
			$type = $form->get('type')->getData();
			$name = $form->get('name')->getData();

			$category = $categoryRepository->findOneBy(['name' => $type]);
			$pokemons->setCategory($category);
			$pokemons->setName(ucfirst($name));

			$entityManager->persist($pokemons);
			$entityManager->flush();

			return $this->redirectToRoute('pokemon_user', ['username' => $user->getUserIdentifier()]);
		} else {
			$errorsForm = $validator->validate($form);
			foreach ($errorsForm as $error) {
				$errors[$error->getPropertyPath()] = [
					'field' => $error->getPropertyPath(),
					'message' => $error->getMessage(),
				];
			}
		}

		return $this->render('pokemon/update.html.twig', [
			'updateForm' => $form->createView(),
			'pokemon' => $pokemons,
			'errors' => $errors
		]);
	}

	/**
	 * @Route("/pokemon/{id}/delete", name="pokemon_delete")
	 * @param Request $request
	 * @param PokemonRepository $pokemonRepository
	 * @param int $id
	 * @return RedirectResponse
	 */
	public function delete(Request $request, PokemonRepository $pokemonRepository, int $id): RedirectResponse
	{
		$pokemon = $pokemonRepository->find($id);
		$user = $this->getUser();

		// Vérification de l'identité de l'utilisateur
		if ($pokemon->getUser()->getUserIdentifier() !== $user->getUserIdentifier()) {
			$request->getSession()->getFlashBag()->add('error', 'Vous ne pouvez pas supprimer ce pokemon');
			return $this->redirectToRoute('pokemon_user', ['username' => $user->getUserIdentifier()]);
		}

		$this->em->remove($pokemon);
		$this->em->flush();

		$filesystem = new Filesystem();
		$nameFile = $pokemon->getImageUrl();
		$projectDir = $this->getParameter('kernel.project_dir');
		$filesystem->remove($projectDir.'/public/static/uploads/pokemons/'.$nameFile);

		return $this->redirectToRoute('pokemon_user', ['username' => $user->getUserIdentifier()]);
	}

	/**
	 * @Route("/user/{username}", name="pokemon_user")
	 * @param Request $request
	 * @param string $username
	 * @param PokemonRepository $pokemonRepository
	 * @return Response
	 */
	public function readPrivate(Request $request, string $username, PokemonRepository $pokemonRepository): Response
	{
		$user = $this->getUser()->getUserIdentifier();

		// Si l'utilisateur ne correspond à aucun username on redirige vers l'utilisateur courant'
		if ($username !== $user) {
			$request->getSession()->getFlashBag()->add('error', 'Cet utilisateur est introuvable');
			return $this->redirectToRoute('pokemon_user', ['username' => $user]);
		}

		// Récupération des pokemons de l'utilisateur
		$pokemons = $pokemonRepository->findBy(['user' => $this->getUser()], ['name' => 'ASC']);

		return $this->render('user/read.pokemon.html.twig', [
			'pokemons' => $pokemons
		]);
	}
}
