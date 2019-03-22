ChoiceFilter Class Reference
============================

Usage
-----

.. code-block:: php

    $builder->addColumn(self::BOOLEAN_COLUMN, [
        'attribute' => 'isEnabled',
        'filter' => [
            'class' => self::FILTER_CHOICE,
            'choices' => [
                0 => 'Disabled',
                1 => 'Enabled'
            ]
        ]
    ]);

Output
------

.. code-block:: html

    <select name="isEnabled" class="data_grid_filter">
        <option value></option>
        <option value="0">Disabled</option>
        <option value="1">Enabled</option>
    </select>

Configuration
-------------

choices - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
List of choices in filter.

options - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Html attributes to be applied to the tag <td>.