<?php

namespace App\Repository;

use App\Entity\ChatBotConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChatBotConversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatBotConversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatBotConversation[]    findAll()
 * @method ChatBotConversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatBotConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatBotConversation::class);
    }

    // /**
    //  * @return ChatBotSession[] Returns an array of ChatBotSession objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChatBotSession
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
