<?php

namespace App\Repository;

use App\Entity\ChatBotConversationMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChatBotConversationMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatBotConversationMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatBotConversationMessage[]    findAll()
 * @method ChatBotConversationMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatBotConversationMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatBotConversationMessage::class);
    }

    // /**
    //  * @return ChatBotSessionMessage[] Returns an array of ChatBotSessionMessage objects
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
    public function findOneBySomeField($value): ?ChatBotSessionMessage
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
