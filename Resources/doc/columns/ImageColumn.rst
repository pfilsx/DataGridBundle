ImageColumn Class Reference
============================

Usage
-----

.. code-block:: php

    $builder->addColumn(self::IMAGE_COLUMN, [
        'attribute' => 'entity_attribute'
    ]);

Configuration
-------------

alt - string(default: '')
~~~~~~~~~~~~~~~~~~~~~~~~~
Alt attribute value.

attribute - string(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Entity attribute.

attributes - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
html attributes to be applied to the tag <td>.

format - string(default: 'html')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Format of the output data(raw|html).

height - integer(default: 25)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Height of output image.

noImageMessage - string(default: '-')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Empty value message.

value - callable(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Can be used to override default output generation.

.. code-block:: php

    $builder->addColumn(self::IMAGE_COLUMN, [
        'value' => function($entity) {
            return $entity->getImgTag();
        }
    ]);

width - integer(default: 25)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Width of output image.