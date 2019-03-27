EntityFilter Class Reference
============================

Usage
-----

.. code-block:: php

    $builder->addColumn(self::DATA_COLUMN, [
        'attribute' => 'fidCategory',
        'filter' => [
            'class' => self::FILTER_ENTITY,
            'entityClass' => 'App\Entity\Category',
            'label' => 'title'
        ]
    ]);

Output
------

.. code-block:: html

    <select name="fidCategory" class="data_grid_filter">
        <option value></option>
        <option value="1">First Category</option>
        <option value="2">Second Category</option>
        ...
    </select>

Configuration
-------------

entityClass - string(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
FQN of related entity.

label - string(default: null)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Attribute name of entity what should be used for options label.

options - array(default: [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Html attributes to be applied to the tag <td>.
