EntityFilter Class Reference
============================

Usage
-----

.. code-block:: php

    $builder->addColumn(self::DATA_COLUMN, [
        'attribute' => 'fidCategory',
        'filter' => [
            'class' => self::FILTER_TEXT
        ]
    ]);

Output
------

.. code-block:: html

    <input type="text" class="data_grid_filter" name="fidCategory" />

Configuration
-------------

options - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Html attributes to be applied to the tag <td>.