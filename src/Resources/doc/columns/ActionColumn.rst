ActionColumn Class Reference
============================

Usage
-----

.. code-block:: php

    $builder->addActionColumn([
        'pathPrefix' => 'path_prefix'
    ]);

Configuration
-------------

attributes - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
html attributes to be applied to the tag <td>.

buttonsTemplate - string(default: '{show} {edit} {delete}')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
buttons rendering template.

buttons - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Can be used to override default button rendering.

.. code-block:: php

    $builder->addColumn(self::ACTION_COLUMN, [
        'buttons' => [
            'show' => function(Object $entity, string $url) {
                return '<a href="'.$url.'?id='.$entity->id.'">Show</a>';
            }
        ]
    ]);

buttonsVisibility - array(default: ['show' => true, 'edit' => true, 'delete' => true])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Can be used to set visibility of each button.

format - string(default: 'raw')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Format of the output data(raw|html).

pathPrefix - string(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Path prefix would be used in default url generation.

visible - boolean(default: true)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Whether to display a column.

urlGenerator - callable(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Can be used to override default url generation function.

.. code-block:: php

    $builder->addActionColumn([
        'urlGenerator' => function($entity, string $action, RouterInterface $router) {
                return $router->generate('entity-'.$action, ['guid' => $entity->getId()]);
        }
    ]);

identifier - string(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Can be used to override default identifier field.
