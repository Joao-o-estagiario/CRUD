<?php

// namespace Blog\Controller;

// use Zend\Mvc\Controller\AbstractActionController;
// use Zend\View\Model\ViewModel;
// use Zend\Http\Request;
// use Blog\Form\PostForm;


// class PostController extends AbstractActionController 
// {
//     /**
//      * Entity manager.
//      * @var Doctrine\ORM\EntityManager 
//      */
//     private $entityManager;
    
//     /**
//      * Post manager.
//      * @var Blog\Service\PostManager 
//      */
//     private $postManager;
    
//     /**
//      * Constructor is used for injecting dependencies into the controller.
//      */
//     public function __construct($entityManager, $postManager) 
//     {
//         $this->entityManager = $entityManager;
//         $this->postManager = $postManager;
//     }
//     public function addAction() 
//     {     
//         // Create the form.
//         $form = new PostForm();
        
//         // Check whether this post is a POST request.
//         if ($this->getRequest() == Request::METHOD_POST) {
            
//             // Get POST data.
//             $data = $this->params()->fromPost();
            
//             // Fill form with data.
//             $form->setData($data);
//             if ($form->isValid()) {
                                
//                 // Get validated form data.
//                 $data = $form->getData();
                
//                 // Use post manager service to add new post to database.                
//                 $this->postManager->addNewPost($data);
                
//                 // Redirect the user to "index" page.
//                 return $this->redirect()->toRoute('blog');
//             }
//         }
        
//         // Render the view template.
//         return new ViewModel([
//             'form' => $form
//         ]);
//     }   
// }