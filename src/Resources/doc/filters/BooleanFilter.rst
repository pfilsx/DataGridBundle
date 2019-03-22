BooleanFilter Class Reference
=============================

Usage
-----

.. code-block:: php

    $builder->addColumn(self::BOOLEAN_COLUMN, [
        'attribute' => 'isEnabled',
        'filter' => [
            'class' => self::FILTER_BOOLEAN,
            'trueChoice' => 'Enabled',
            'falseChoice' => 'Disabled'
        ]
    ]);

Output
------

.. code-block:: html

    <select name="isEnabled" class="data_grid_filter">
        <option value></option>
        <option value="1">Enabled</option>
        <option value="0">Disabled</option>
    </select>

Configuration
-------------

trueChoice - string(default: 'Yes')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Label of true choice in filter.

falseChoice - string(default: 'No')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Label of false choice in filter.

options - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Html attributes to be applied to the tag <td>.