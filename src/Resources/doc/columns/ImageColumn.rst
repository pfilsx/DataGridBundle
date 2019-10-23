ImageColumn Class Reference
============================

Usage
-----

.. code-block:: php

    $builder->addColumn('entity_attribute', self::IMAGE_COLUMN);

Configuration
-------------

alt - callable(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~
Alt attribute callback.

.. code-block:: php

    $builder->addColumn('entity_attribute', self::IMAGE_COLUMN, [
        ...
        'alt' => function($entity){
            return $entity->getAlt();
        }
    ]);

attributes - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
html attributes to be applied to the tag <td>.

format - string(default: 'html')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Format of the output data(raw|html).

height - integer(default: 25)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Height of output image.

label - string(default: attribute name|'')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Label of column.

noImageMessage - string(default: '-')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Empty value message.

value - callable(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Can be used to override default output generation.

.. code-block:: php

    $builder->addColumn('entity_attribute', self::IMAGE_COLUMN, [
        'value' => function($entity) {
            return $entity->getImgTag();
        }
    ]);

width - integer(default: 25)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Width of output image.
