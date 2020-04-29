<?php
namespace Blog\Service;

use Blog\Entity\Post;
use Blog\Controller\BlogController;
use Blog\Entity\Tag;
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
}