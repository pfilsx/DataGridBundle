DateFilter Class Reference
============================

Usage
-----

.. code-block:: php

    $builder->addColumn(self::DATE_COLUMN, [
        'attribute' => 'creationDate',
        'filter' => [
            'class' => self::FILTER_DATE,
            'minDate' => '1990-01-01',
            'maxDate' => '2100-01-01'
        ]
    ]);

Output
------

.. code-block:: html

    <input type="date" min="1990-01-01" max="2100-01-01" class="data_grid_filter" name="creationDate" />

Configuration
-------------

minDate - string(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Min available date.

maxDate - string(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Max available date.

options - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Html attributes to be applied to the tag <td>.