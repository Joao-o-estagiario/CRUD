<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Blog\Entity\Post;
use Blog\Form\PostForm;
use Blog\Form\CommentForm;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;


class BlogController extends AbstractActionController
{
  /**
   * Entity manager.
   * @var Doctrine\ORM\EntityManager 
   */
  private $entityManager;

  /**
   * Post manager.
   * @var Blog\Service\PostManager 
   */
  private $postManager;

  public function __construct($entityManager, $postManager)
  {
    $this->entityManager = $entityManager;
    $this->postManager = $postManager;
  }

  // public function indexAction() 
  //   {
  //       $page = $this->params()->fromQuery('page', 1);
  //       $tagFilter = $this->params()->fromQuery('tag', null);
        
  //       if ($tagFilter) {
         
  //           // Filter posts by tag
  //           $query = $this->entityManager->getRepository(Post::class)
  //                   ->findPostsByTag($tagFilter);
            
  //       } else {
  //           // Get recent posts
  //           $query = $this->entityManager->getRepository(Post::class)
  //                   ->findPublishedPosts();
  //       }
        
  //       $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
  //       $paginator = new Paginator($adapter);
  //       $paginator->setDefaultItemCountPerPage(1);        
  //       $paginator->setCurrentPageNumber($page);
                       
  //       // Get popular tags.
  //       $tagCloud = $this->postManager->getTagCloud();
        
  //       // Render the view template.
  //       return new ViewModel([
  //           'posts' => $paginator,
  //           'postManager' => $this->postManager,
  //           'tagCloud' => $tagCloud
  //       ]);
  //   }

  public function addAction()
  {
    $form = new PostForm();

    if ($this->getRequest()->getMethod() == Request::METHOD_POST) {

      $data = $this->params()->fromPost();

      $form->setData($data);
      if ($form->isValid()) {

        $data = $form->getData();

        $this->postManager->addNewPost($data);

        return $this->redirect()->toRoute('blog');
      }
    }

    return new ViewModel([
      'form' => $form
    ]);
  }

  public function editAction()
  {
    //cria o form
    $form = new PostForm();

    //captura o id    
    $postId = $this->params()->fromRoute('id', -1);

    //encontra o registro na db    
    $post = $this->entityManager->getRepository(Post::class)
      ->findOneById($postId);
    if ($post == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }

    //é enviado por post?
    if ($this->getRequest()->getMethod() == Request::METHOD_POST) {

      $data = $this->params()->fromPost();

      $form->setData($data);
      if ($form->isValid()) {

        $data = $form->getData();

        //usa postManager para adicionar um novo registro (não insere até o flush)                
        $this->postManager->updatePost($post, $data);

        //redireciona para a page admin
        return $this->redirect()->toRoute('posts', ['action' => 'admin']);
      }
    } else {
      $data = [
        'title' => $post->getTitle(),
        'conten' => $post->getConten(),
        'tags' => $this->postManager->convertTagsToString($post),
        'status' => $post->getStatus()
      ];

      $form->setData($data);
    }

    return new ViewModel([
      'form' => $form,
      'post' => $post
    ]);
  }

  public function deleteAction()
  {
    $postId = $this->params()->fromRoute('id', -1);

    $post = $this->entityManager->getRepository(Post::class)
      ->findOneById($postId);
    if ($post == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $this->postManager->removePost($post);

    return $this->redirect()->toRoute('posts', ['action' => 'admin']);
  }

  public function viewAction()
  {
    $postId = $this->params()->fromRoute('id', -1);

    $post = $this->entityManager->getRepository(Post::class)
      ->findOneById($postId);

    if ($post == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $commentCount = $this->postManager->getCommentCountStr($post);

    // Create the form.
    $form = new CommentForm();

    // Check whether this post is a POST request.
    if ($this->getRequest()->getMethod() == Request::METHOD_POST) {

      // Get POST data.
      $data = $this->params()->fromPost();

      // Fill form with data.
      $form->setData($data);
      if ($form->isValid()) {

        // Get validated form data.
        $data = $form->getData();

        // Use post manager service to add new comment to post.
        $this->postManager->addCommentToPost($post, $data);

        // Redirect the user again to "view" page.
        return $this->redirect()->toRoute('posts', ['action' => 'view', 'id' => $postId]);
      }
    }

    // Render the view template.
    return new ViewModel([
      'post' => $post,
      'commentCount' => $commentCount,
      'form' => $form,
      'postManager' => $this->postManager
    ]);
  }

  public function adminAction()
  {
    //captura os posts
    $posts = $this->entityManager->getRepository(Post::class)
      ->findBy([], ['dateCreated' => 'DESC']);

    return new ViewModel([
      'posts' => $posts,
      'postManager' => $this->postManager
    ]);
  }
}
