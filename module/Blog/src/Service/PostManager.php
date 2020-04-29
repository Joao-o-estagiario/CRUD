<?php
namespace Blog\Service;

use Blog\Entity\Post;
use Blog\Entity\Comment;
use Blog\Entity\Tag;
use Zend\Filter\StaticFilter;

// The PostManager service is responsible for adding new posts.
class PostManager 
{
  /**
   * Doctrine entity manager.
   * @var Doctrine\ORM\EntityManager
   */
  private $entityManager;
  
  // Constructor is used to inject dependencies into the service.
  public function __construct($entityManager)
  {
    $this->entityManager = $entityManager;
  }
    
  // This method adds a new post.
  public function addNewPost($data) 
  {
    // Create new Post entity.
    $post = new Post();
    $post->setTitle($data['title']);
    $post->setConten($data['conten']);
    $post->setStatus($data['status']);
    $currentDate = date('Y-m-d H:i:s');
    $post->setDateCreated($currentDate);        
        
    // Add the entity to entity manager.
    $this->entityManager->persist($post);
        
    // Add tags to post
    $this->addTagsToPost($data['tags'], $post);
        
    // Apply changes to database.
    $this->entityManager->flush();
  }
  
  // Adds/updates tags in the given post.
  private function addTagsToPost($tagsStr, $post) 
  {
    // Remove tag associations (if any)
    $tags = $post->getTags();
    foreach ($tags as $tag) {            
      $post->removeTagAssociation($tag);
    }
        
    // Add tags to post
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
      $tag->addPost($post);
            
      $this->entityManager->persist($tag);
            
      $post->addTag($tag);
    }
  }    
}