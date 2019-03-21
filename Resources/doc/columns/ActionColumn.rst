ActionColumn Class Reference
============================

Usage
-----

.. codeblock:: php

    $builder->addColumn(self::ACTION_COLUMN, [
        'pathPrefix' => 'path_prefix'
    ]);

Configuration
-------------

attributes - array
~~~~~~~~~~~~~~~~~~
html attributes to be applied to the tag <td>.

buttonsTemplate - string(default: '{show} {edit} {delete}')
~~~~~~~~~~~~~~~~~~~~~~~~
buttons rendering template.

buttons - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Can be used to override default button rendering.

.. codeblock:: php

    $builder->addColumn(self::ACTION_COLUMN, [
        'buttons' => [
            'show' => function(Object $entity, string $url){
                return '<a href="'.$url.'?id='.$entity->id.'">Show</a>';
            }
        ]
    ]);

buttonsVisibility - array(default: ['show' => true, 'edit' => true, 'delete' => true])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Can be used to set visibility of each button.

pathPrefix - string(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Path prefix would be used in default url generation

visible - boolean(default: true)
~~~~~~~~~~~~~~~~~
whether to display a column

urlGenerator - callable(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Can be used to override default url generation function.
.. codeblock:: php

    $builder->addColumn(self::ACTION_COLUMN, [
        'buttons' => [
            'urlGenerator' => function($entity, string $action, RouterInterface $router){
                return $router->generate('entity-'.$action, ['guid' => $entity->getId()]);
            }
        ]
    ]);


