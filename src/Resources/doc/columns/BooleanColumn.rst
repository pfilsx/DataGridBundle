ActionColumn Class Reference
============================

Usage
-----

.. code-block:: php

    $builder->addColumn('entity_attribute', self::BOOLEAN_COLUMN, [
        'trueValue' => 'yes',
        'falseValue' => 'no'
    ]);

Configuration
-------------

attributes - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
html attributes to be applied to the tag <td>.

filter - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~
Filter configuration. See filters docs.

.. code-block:: php

    $builder->addColumn('entity_attribute', self::BOOLEAN_COLUMN, [
        ...
        'filter' => [
            'class' => self::FILTER_BOOLEAN
        ]
    ]);

format - string(default: 'raw')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Format of the output data(raw|html).

label - string(default: attribute name|'')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Label of column.

trueValue - string(default: 'yes')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Text output for true value.

falseValue - string(default: 'no')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Text output for false value.

value - callable(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Can be used to override default output generation.

.. code-block:: php

    $builder->addColumn('entity_attribute', self::BOOLEAN_COLUMN, [
        ...
        'value' => function($entity) {
            return $entity->isEnabled ? 'enabled' : 'disabled';
        }
    ]);
