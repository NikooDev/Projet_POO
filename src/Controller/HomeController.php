<?php

namespace App\Controller;

use App\Entity\Pokemon;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gestion de la page d'accueil
 */
class HomeController extends AbstractController
{
	/**
	 * Retourne et affiche deux catégories et articles aléatoire
	 * @Route("/", name="home")
	 * @param ManagerRegistry $doctrine
	 * @return Response
	 */
	public function index(ManagerRegistry $doctrine): Response
	{
		// Récupère 2 catégories et 4 articles aléatoires avec findRandom()
		$randomArticle = $doctrine->getRepository(Pokemon::class)->findRandom($doctrine);

		return $this->render('home/index.html.twig', [
			'randomArticle' => $randomArticle
		]);
	}
}
