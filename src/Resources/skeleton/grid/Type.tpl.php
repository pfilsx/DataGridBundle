<?= "<?php\n" ?>

namespace <?= $namespace ?>;

<?php if ($bounded_full_class_name): ?>
use <?= $bounded_full_class_name ?>;
<?php endif ?>
use Pfilsx\DataGrid\Grid\AbstractGridType;
use Pfilsx\DataGrid\Grid\DataGridBuilderInterface;
use Pfilsx\DataGrid\Grid\DataGridFiltersBuilderInterface;

class <?= $class_name ?> extends AbstractGridType
{
    public function buildGrid(DataGridBuilderInterface $builder) : void
    {
        $builder
<?php foreach ($grid_fields as $grid_field => $field_type): ?>
            ->addColumn('<?= $grid_field ?>')
<?php endforeach; ?>
        ;
    }

    public function handleFilters(DataGridFiltersBuilderInterface $builder, array $filters): void
    {
        // Configure your filter options here
    }
}