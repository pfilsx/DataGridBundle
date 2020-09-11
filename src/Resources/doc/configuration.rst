Configuration Reference
=======================

.. configuration-block::

    .. code-block:: yaml

        data_grid:
            instances:
                # template used for rendering grid blocks
                template: 'grid/grid.blocks.html.twig'
                # message used in case of no data found
                noDataMessage: 'No records found'
                # pagination configuration
                pagination_enabled: true
                # num of data rows on each page
                pagination_limit: 10