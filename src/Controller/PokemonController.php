<?php

namespace App\Controller;

use App\Entity\Pokemon;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Classe des articles
 */
class PokemonController extends AbstractController
{

	/**
	 * Récupère deux catégories et articles aléatoire
	 */
	public static function readRandom(ManagerRegistry $doctrine): array
	{
		$repository = $doctrine->getRepository(Pokemon::class)->findRandom();

		dump($repository);

		return $repository;
	}

	public function readAllCategories(): Response
	{
		return $this->render('pokemon/categories.html.twig');
	}

	/**
	 * Récupère et affiche tous les pokemons d'une catégorie
	 * @Route("/pokemon/{category}", name="pokemon_category")
	 * @return Response
	 */
	public function readAll(): Response
	{
		return $this->render('pokemon/category.html.twig');
	}

	/**
	 * Création d'un article
	 * @Route("/pokemon/create", name="pokemon_create")
	 * @return Response
	 */
	public function create(): Response
	{
		return $this->render('pokemon/create.html.twig');
	}

	/**
	 * Récupère et affiche un article
	 * @Route("/pokemon/{category}/{id}", name="pokemon_create")
	 * @return Response
	 */
	public function read(): Response
	{
		return $this->render('pokemon/read.html.twig');
	}

	/**
	 * Met à jour l'article
	 * @Route("/pokemon/{category}/{id}/update", name="pokemon_update")
	 * @return Response
	 */
	public function update(): Response
	{
		return $this->render('pokemon/update.html.twig');
	}

	/**
	 * Supprime un pokemon
	 * @Route("/pokemon/{category}/{id}", name="pokemon_delete")
	 */
	public function delete()
	{

	}
}