<?php
namespace Blog\Service;

use Blog\Entity\Post;
use Blog\Entity\Tag;
use Blog\Entity\Comment;
use Zend\Filter\StaticFilter;

class PostManager 
{
  /**
   * Doctrine entity manager.
   * @var Doctrine\ORM\EntityManager
   */
  private $entityManager;
  
  public function __construct($entityManager)
  {
    $this->entityManager = $entityManager;
  }
    
  // This method adds a new post.
  public function addNewPost($data) 
  {
    
    $post = new Post();
    $post->setTitle($data['title']);
    $post->setConten($data['conten']);
    $post->setStatus($data['status']);
    $currentDate = date('Y-m-d H:i:s');
    $post->setDateCreated($currentDate);        
    $this->entityManager->persist($post);
        
    $this->addTagsToPost($data['tags'], $post);
        
    $this->entityManager->flush();
  }
  
  private function addTagsToPost($tagsStr, $post) 
  {
    $tags = $post->getTags();
    foreach ($tags as $tag) {            
      $post->removeTagAssociation($tag);
    }
        
    $tags = explode(',', $tagsStr);
    foreach ($tags as $tagName) {
            
      $tagName = StaticFilter::execute($tagName, 'StringTrim');
      if (empty($tagName)) {
        continue; 
      }
            
      $tag = $this->entityManager->getRepository(Tag::class)
                 ->findOneByName($tagName);
      if ($tag == null)
        $tag = new Tag();
      $tag->setName($tagName);
      $tag->addPosts($post);
            
      $this->entityManager->persist($tag);
            
      $post->addTag($tag);
    }
  }
  
  public function updatePost($post, $data) 
    {
        $post->setTitle($data['title']);
        $post->setConten($data['conten']);
        $post->setStatus($data['status']);
        
        //add tags para um post
        $this->addTagsToPost($data['tags'], $post);
        
        // flush envia pro banco
        $this->entityManager->flush();
    }
        
    //faz uma lista separada por virgulas das tags que foram atribuidas ao post
    public function convertTagsToString($post) 
    {
        $tags = $post->getTags();
        $tagCount = count($tags);
        $tagsStr = '';
        $i = 0;
        foreach ($tags as $tag) {
            $i ++;
            $tagsStr .= $tag->getName();
            if ($i < $tagCount) 
                $tagsStr .= ', ';
        }
        
        return $tagsStr;
    }
    public function removePost($post) 
  {
    // romeve os coimentarios associados a um psot
    $comments = $post->getComments();
    foreach ($comments as $comment) {
      $this->entityManager->remove($comment);
    }
        
    // e se houver tags associadas, removem elas tbm
    $tags = $post->getTags();
    foreach ($tags as $tag) {
      $post->removeTagAssociation($tag);
    }
        
    $this->entityManager->remove($post);
        
    $this->entityManager->flush();
  }

  public function getCommentCountStr($post)
    {
        $commentCount = count($post->getComments());
        if ($commentCount == 0)
            return 'No comments';
        else if ($commentCount == 1) 
            return '1 comment';
        else
            return $commentCount . ' comments';
    }


    public function addCommentToPost($post, $data) 
    {
        $comment = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($data['author']);
        $comment->setConten($data['comment']);        
        $currentDate = date('Y-m-d H:i:s');
        $comment->setDateCreated($currentDate);

        $this->entityManager->persist($comment);

        $this->entityManager->flush();
    }

  public function getPostStatusAsString($post) 
  {
    switch ($post->getStatus()) {
        case Post::STATUS_DRAFT: return 'Draft';
        case Post::STATUS_PUBLISHED: return 'Published';
    }
    
    return 'Unknown';
  }
}