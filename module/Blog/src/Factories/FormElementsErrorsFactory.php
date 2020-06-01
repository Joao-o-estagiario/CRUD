<?php

// namespace Core\Factories;

// use Interop\Container\ContainerInterface;
// use Zend\Form\View\Helper\FormElementErrors;
// use Zend\ServiceManager\Factory\FactoryInterface;

// class FormElementsErrorsFactory implements FactoryInterface
// {

//     public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
//     {
//         $helper = new FormElementErrors();

//         $config = $container->get('config');
//         if(isset($config['view_helper_config']['form_element_errors'])){
//             $configHelper = $config['view_helper_config']['form_element_errors'];
//             if(isset($configHelper['message_open_format'])){
//                 $helper->setMessageOpenFormat($configHelper['message_open_format']);
//             }
//             if(isset($configHelper['message_close_string'])){
//                 $helper->setMessageCloseString($configHelper['message_close_string']);
//             }
//             if(isset($configHelper['message_separator_string'])){
//                 $helper->setMessageSeparatorString($configHelper['message_close_string']);
//             }
//         }

//         return $helper;
//     }
// }