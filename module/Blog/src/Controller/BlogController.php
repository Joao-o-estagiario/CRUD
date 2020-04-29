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
    
    public function __construct($entityManager, $postManager){
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
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
}