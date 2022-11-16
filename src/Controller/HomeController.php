<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Accueil
 */
class HomeController extends AbstractController
{
	/**
	 * @Route("/", name="home")
	 */
	public function index(ManagerRegistry $doctrine): Response
	{

		// Affichage des deux catégories et articles aléatoires
		$randomArticle = PokemonController::readRandom($doctrine);

		// commentaire

		return $this->render('home/index.html.twig', [
			'randomArticle' => $randomArticle
		]);
	}
}
