DateColumn Class Reference
============================

Usage
-----

.. code-block:: php

    $builder->addColumn(self::DATE_COLUMN, [
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

dateFormat - string(default: 'd.m.Y')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Date format for output. See `PHP_Date`_

filter - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~
Filter configuration. See filters docs.

.. code-block:: php

    $builder->addColumn(self::DATE_COLUMN, [
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

    $builder->addColumn(self::DATE_COLUMN, [
        'value' => function($entity) {
            return $entity->getCreationDate()->format('d.m.Y');
        }
    ]);

.. _`PHP_Date`: http://php.net/manual/ru/function.date.php
