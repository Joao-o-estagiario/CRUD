<?php
namespace Blog\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Blog\Service\PostManager;
use Blog\Controller\BlogController;


class PostControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, 
                           $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $postManager = $container->get(PostManager::class);
        
        // Instantiate the controller and inject dependencies
        return new BlogController($entityManager, $postManager);
    }
}