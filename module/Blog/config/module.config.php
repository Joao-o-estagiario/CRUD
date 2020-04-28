<?php

namespace Blog;

use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
// use Zend\Form\View\Helper\FormElementErrors;
// use Zend\ServiceManager\Factory\InvokableFactory;
// use Blog\Factories\FormElementsErrorsFactory;


return [
    'controllers' => [
        'factories' => [
            Controller\BlogController::class => 
                Factories\BlogControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'blog' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/blog[/:action]',
                    'defaults' => [
                        'controller' => Controller\BlogController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'blog' => __DIR__ .'/../view', 
        ],
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
    // Add the Doctrine integration modules.
    'DoctrineModule',
    'DoctrineORMModule',      
    //...
  
];  