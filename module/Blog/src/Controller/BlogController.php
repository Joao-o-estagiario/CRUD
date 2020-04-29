<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Blog\Entity\Post;
use Blog\Form\PostForm;


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
    
    public function __construct($entityManager){
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $posts = $this->entityManager->getRepository(Post::class)
                        ->findBy(['status'=>Post::STATUS_PUBLISHED],
                                 ['dateCreated'=>'DESC']);
        return new ViewModel([
            'posts' => $posts
        ]);
    }
    public function addAction() 
    {     
        // Create the form.
        $form = new PostForm();
        
        // Check whether this post is a POST request.
        if ($this->getRequest() == Request::METHOD_POST) {
            
            // Get POST data.
            $data = $this->params()->fromPost();
            
            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {
                                
                // Get validated form data.
                $data = $form->getData();
                
                // Use post manager service to add new post to database.                
                $this->postManager->addNewPost($data);
                
                // Redirect the user to "index" page.
                return $this->redirect()->toRoute('blog');
            }
        }
        
        // Render the view template.
        return new ViewModel([
            'form' => $form
        ]);
    }   
}