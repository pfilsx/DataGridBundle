DataColumn Class Reference
============================

Usage
-----

.. code-block:: php

    $builder->addColumn(self::DATA_COLUMN, [
        'attribute' => 'entity_attribute'
    ]);

Configuration
-------------

attribute - string(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Entity attribute.

attributes - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
html attributes to be applied to the tag <td>.

filter - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~
Filter configuration. See filters docs.
$builder->addColumn(self::DATA_COLUMN, [
        ...
        'filter' => [
            'class' => self::FILTER_TEXT
        ]
    ]);

format - string(default: 'raw')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Format of the output data(raw|html).

value - callable(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Can be used to override default output generation.

.. code-block:: php

    $builder->addColumn(self::DATA_COLUMN, [
        'value' => function($entity) {
            return $entity->isEnabled ? 'enabled' : 'disabled';
        }
    ]);