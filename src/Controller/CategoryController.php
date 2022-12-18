<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
	/**
	 * Retourne toute les catégories
	 * @Route("/categories", name="category_readall")
	 * @param CategoryRepository $categoryRepository
	 * @param PokemonRepository $pokemonRepository
	 * @return Response
	 */
	public function readAll(CategoryRepository $categoryRepository, PokemonRepository $pokemonRepository): Response
	{
		$datas = array();
		$categories = $categoryRepository->findBy([], ['name' => 'ASC']);

		// Pour chaque catégorie, on compte le nombre de pokemons associés
		foreach ($categories as $category) {
			$count = $pokemonRepository->createQueryBuilder('p')
				->select('count(p) as count')
				->where('p.category = :id')
				->setParameter('id', $category->getId())
				->getQuery()
				->getResult();

			// Insertion des résultats dans un tableau indexé
			$datas[$category->getId()] = $count;
		}

		return $this->render('category/read.all.html.twig', [
			'categories' => $categories,
			'count' => $datas
		]);
	}

	/**
	 * Retourne tous les pokemons d'une catégorie
	 * @Route("/categories/{category}", name="category_readby")
	 * @param Request $request
	 * @param CategoryRepository $categoryRepository
	 * @param PokemonRepository $pokemonRepository
	 * @param string $category
	 * @return Response
	 */
	public function readBy(Request $request, CategoryRepository $categoryRepository, PokemonRepository $pokemonRepository, string $category): Response
	{
		$categories = $categoryRepository->findOneBy(['url' => $category]);

		// Message d'erreur et redirection si la catégorie est null
		if (!$categories) {
			$request->getSession()->getFlashBag()->add('error', 'Cette catégorie n\'existe pas');
			return $this->redirectToRoute('category_readall');
		}

		$categoryName = $categories->getName();
		$pokemons = $pokemonRepository->findBy(['type' => $categoryName], ['name' => 'ASC']);

		return $this->render('category/read.pokemon.html.twig', [
			'categorie' => $categoryName,
			'pokemons' => $pokemons
		]);
	}
}
