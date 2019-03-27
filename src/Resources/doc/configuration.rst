Configuration Reference
=======================

.. configuration-block::

    .. code-block:: yaml

        data_grid:
            # template used for rendering grid blocks
            template: 'grid/grid.blocks.html.twig'
            # message used in case of no data found
            noDataMessage: 'Записей не найдено'
            # pagination configuration
            pagination:
                # num of data rows on each page
                limit: 10
