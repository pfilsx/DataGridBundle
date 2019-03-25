CustomFilter Class Reference
============================

Usage
-----

.. code-block:: php

    $builder->addColumn(self::DATA_COLUMN, [
        'attribute' => 'isEnabled',
        'filter' => [
            'class' => self::FILTER_CUSTOM,
            'value' => function($attribute, $value, $options){
                return '<input type="text" class="data_grid_filter" name="'.$attribute.'" />';
            }
        ]
    ]);

Output
------

.. code-block:: html

    <input type="text" class="data_grid_filter" name="isEnabled" />

Configuration
-------------

value - callable(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Callback to render filter content.
