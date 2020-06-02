<?php

namespace Blog;

use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;

// use Zend\Form\View\Helper\FormElementErrors;
// use Zend\ServiceManager\Factory\InvokableFactory;
// use Blog\Factories\FormElementsErrorsFactory;


return [
    'controllers' => [
        'factories' => [
            Controller\BlogController::class => 
                Factories\BlogControllerFactory::class,
                Controller\BlogController::class => 
                           Controller\Factory\PostControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'rest' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/rest/blog[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\BlogController::class,
                        'action'     => 'rest-admin',
                    ],
                ],
            ],
            'posts' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/posts[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\BlogController::class,
                        'action'        => 'add',
                    ],
                ],
            ],
            'post' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/post[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\BlogController::class,
                        'action'        => 'admin',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'blog' => __DIR__ .'/../view', 
        ],
        'strategies' => [
            'ViewJsonStrategy'
        ]
    ],
    
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ],
            ],
        ],
    ],
    'service_manager' => [
        //...
        'factories' => [
            Service\PostManager::class => Service\Factory\PostManagerFactory::class,
        ],
    ],
    // Add the Doctrine integration modules.
    'DoctrineModule',
    'DoctrineORMModule',      
    //...
  
];  