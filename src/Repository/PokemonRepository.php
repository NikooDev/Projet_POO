<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
	/**
	 * @var ManagerRegistry
	 */
	protected $em;

	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Pokemon::class);
		$this->em = $registry;
	}

	/**
	 * Retourne 2 catégories et articles aléatoires
	 * @return array
	 */
	public function findRandom(): array
	{
		$datas = array();
		$categories = $this->em->getRepository(Category::class);

		// On récupère 2 catégories aléatoires
		$randomCategories = $categories->createQueryBuilder('c')
			->select('c')
			->orderBy('RANDOM()')
			->setMaxResults(2)
			->getQuery()
			->getResult();

		// Stockage des catégories dans un tableau de données
		$datas['cat'] = $randomCategories;

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
			$datas['cat' . $value->getId()] = $randomPokemons;
		}

		return $datas;
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
