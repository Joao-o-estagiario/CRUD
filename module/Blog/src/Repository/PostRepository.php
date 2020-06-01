<?php
namespace Blog\Repository;

use Doctrine\ORM\EntityRepository;
use Blog\Entity\Post;

class PostRepository extends EntityRepository
{
    //primeiro ele encontra todas as publicações que tenham alguma tag
    public function findPostsHavingAnyTag(){
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('p') //é como se criasse uma var e ela se chamasse p
           ->from(Post::class, 'p')
           ->join('p.tag', 't') //acessa a tabela tag e a chama de t
           ->where('p.status = :string') //?1 poderia ser uma :string / pode ser qualquer nome
           ->orderBy('p.dataCreated', 'DESC')
           ->setParameter('string', Post::STATUS_PUBLISHED);//search valores que tenham STATUS_PUBLISHED
        $posts = $queryBuilder->getQuery()->getResult();

        return $posts;

    }

    //agora ele procura uma tag específica
    public function findPostByTag($tagName){
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('p')
           ->from(Post::class, 'p')
           ->join('p.tags', 't')
           ->where('p.status = :status')
           ->andWhere('p.name = :ptags')
           ->orderBy('p.dateCreated', 'DESC')
           ->setParameter(':status', Post::STATUS_PUBLISHED)
           ->setParameter(':ptags', $tagName);

        return $queryBuilder->getQuery();;
    }

    //acha posagens com status "publicada"
    public function findPublishedPosts()
    {
        $entityManager = $this->getEntityManager();
    
        $queryBuilder = $entityManager->createQueryBuilder();
    
        $queryBuilder->select('p')
           ->from(Post::class, 'p')
           ->where('p.status = :posts')
           ->orderBy('p.dateCreated', 'DESC')
           ->setParameter('posts', Post::STATUS_PUBLISHED);
    
        return $queryBuilder->getQuery();
    }

}