DataColumn Class Reference
============================

Usage
-----

.. code-block:: php

    $builder->addDataColumn('entity_attribute');

Configuration
-------------

attributes - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
html attributes to be applied to the tag <td>.

filter - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~
Filter configuration. See filters docs.

.. code-block:: php

    $builder->addDataColumn('entity_attribute', [
        ...
        'filter' => [
            'class' => self::FILTER_TEXT
        ]
    ]);

format - string(default: 'raw')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Format of the output data(raw|html).

label - string(default: attribute name|'')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Label of column.

value - callable(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Can be used to override default output generation.

.. code-block:: php

    $builder->addColumn('entity_attribute', self::DATA_COLUMN, [
        'value' => function($entity) {
            return $entity->isEnabled ? 'enabled' : 'disabled';
        }
    ]);
