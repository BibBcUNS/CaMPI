<?php

namespace campi\Module\Configuration;

$config = [
    'controllers' => [
        'factories' => [
            'my-research' => 'campi\Controller\Factory::getMyResearchController',
            'record' => 'campi\Controller\Factory::getRecordController',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'VuFind\AuthManager' => 'campi\Auth\Factory::getManager',
        ],
    ],
    'vufind' => [
        'plugin_managers' => [
          'ils_driver' => [
                'factories' => [
                    //campi
                    'campi' => 'campi\ILS\Driver\Factory::getCampi',
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
          'myresearch-activar' => [
            'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
            'options' => [
              'route' => '/MyResearch/Activar',
              'defaults' => [
                'controller' => 'MyResearch',
                'action' => 'Activar',
              ],
            ],
          ],
          'myresearch-ecu' => [
            'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
            'options' => [
              'route' => '/MyResearch/Ecu',
              'defaults' => [
                'controller' => 'MyResearch',
                'action' => 'Ecu',
              ],
            ],
          ],
          'myresearch-reservar' => [
            'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
            'options' => [
              'route' => '/MyResearch/Reservar',
              'defaults' => [
                'controller' => 'MyResearch',
                'action' => 'Reservar',
              ],
            ],
          ],
          'myresearch-renovar' => [
            'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
            'options' => [
              'route' => '/MyResearch/Renovar',
              'defaults' => [
                'controller' => 'MyResearch',
                'action' => 'Renovar',
              ],
            ],
          ],
          'record-view' => [
            'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
            'options' => [
              'route' => '/Record/View',
              'defaults' => [
                'controller' => 'Record',
                'action' => 'View',
              ],
            ],
          ],
        ],
      ],
];

return $config;
