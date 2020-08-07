<?php
 
namespace App\Repository;
 
use App\Entity\Livre;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
 
/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }
 
    /**
     * retourne les 5 livres les plus prisés
     * @return void
     */
    public function TrouveMeilleursLivres()
    {
 
        $query = $this->createQueryBuilder('l')
            ->select('l as livre, count(p.id) as nbprets')
            ->join('l.prets', 'p')
            ->groupBy('l')
            ->orderBy('nbprets', 'DESC')
            ->setMaxResults(5);
        return $query->getQuery()->getResult();
    }
}