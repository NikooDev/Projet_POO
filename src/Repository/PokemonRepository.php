<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pokemon>
 *
 * @method Pokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokemon[]    findAll()
 * @method Pokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Pokemon::class);
	}

	public function add(Pokemon $entity, bool $flush = false): void
	{
		$this->getEntityManager()->persist($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	public function remove(Pokemon $entity, bool $flush = false): void
	{
		$this->getEntityManager()->remove($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	/**
	 * Utilisation du QueryBuilder pour récupérer les pokemons associés à leur catégorie
	 * Permet de random le résultat grâce à la classe Random dans App\Orm\Random
	 * @param ManagerRegistry $doctrine
	 * @return array
	 */
	public function findRandom(ManagerRegistry $doctrine): array
	{
		$datas = array();
		$categories = $doctrine->getRepository(Category::class);

		// La première méthode ci-dessous fonctionne mais la requête met trop de temps à s'exécuter :
		// return $this->createQueryBuilder('p')
		//	->select('c')
		//	->from('App:Category', 'c')
		//	->leftJoin('c.article', 'r')
		//	->orderBy('RANDOM()')
		//	->getQuery()->getResult();

		// Nous avons préférés utiliser deux requêtes séparées avec moins de données à récupérer :

		// On récupère 2 catégories aléatoires
		$randomCategories = $categories->createQueryBuilder('c')
			->select('c')
			->orderBy('RANDOM()')
			->setMaxResults(2)
			->getQuery()
			->getResult();

		// Stockage des catégories dans un tableau de données
		$datas['cat'] = $randomCategories;

		// Pour chaque catégorie on récupère 3 articles associés
		foreach ($randomCategories as $value) {
			$randomPokemons = $this->createQueryBuilder('p')
				->select(['p.id', 'p.name', 'p.author', 'p.image_url', 'p.type'])
				->andWhere('p.category = :category_id')
				->setParameter('category_id', $value->getId())
				->orderBy('RANDOM()')
				->setMaxResults(4)
				->getQuery()
				->getArrayResult();

			// On stocke le résultat dans le tableau de données avec l'id d'une catégorie en index
			// Cet index permet d'ajouter deux entrées distinctes dans le tableau et de savoir où boucler dans la vue
			$datas['cat'.$value->getId()] = $randomPokemons;
		}

		// On retourne le tableau avec les catégories et les articles
		return $datas;
	}

//    /**
//     * @return Pokemon[] Returns an array of Pokemon objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Pokemon
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}