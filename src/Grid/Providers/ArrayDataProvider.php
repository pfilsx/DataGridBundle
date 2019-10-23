<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use DateTime;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Items\ArrayGridItem;
use Pfilsx\DataGrid\Grid\Items\DataGridItem;
use Pfilsx\DataGrid\Grid\Items\EntityGridItem;

class ArrayDataProvider extends DataProvider
{
    protected $data;

    public function __construct(array $data, ManagerRegistry $registry = null)
    {
        parent::__construct($registry);
        if (!empty($data)){
            $firstItem = $data[0];
            if (is_array($firstItem)){
                $this->data = array_map(function($row){
                    return new ArrayGridItem($row, array_key_exists('id', $row) ? 'id' : null);
                }, $data);
            } elseif (is_object($firstItem)){
                $identifier = $this->getEntityIdentifier(get_class($firstItem));
                $this->data = array_map(function($item) use ($identifier) {
                    return new EntityGridItem($item, $identifier);
                }, $data);
            } else {
                throw new DataGridException('Each item of ArrayDataProvider data must be an array or object. '
                    . gettype($firstItem).' given.'
                );
            }
        } else {
            $this->data = $data;
        }
    }

    public function getItems(): array
    {
        if ($this->getPager()->isEnabled()){
            $this->data = array_slice($this->data, $this->getPager()->getFirst(), $this->getPager()->getLimit());
        }
        return $this->data;
    }

    public function getTotalCount(): int
    {
        return count($this->data);
    }

    public function setSort(array $sort): DataProviderInterface
    {
        if (!empty($this->data)) {
            foreach ($sort as $attribute => $order) {
                usort($this->data, $this->buildCompare($attribute, $order));
            }

        }
        return $this;
    }

    public function addEqualFilter(string $attribute, $value): DataProviderInterface
    {
        $this->data = array_filter($this->data, function (DataGridItem $item) use ($attribute, $value) {
            if (!$item->has($attribute)) {
                return false;
            }
            return $item[$attribute] == $value;
        });
        return $this;
    }

    public function addLikeFilter(string $attribute, $value): DataProviderInterface
    {
        $this->data = array_filter($this->data, function (DataGridItem $item) use ($attribute, $value) {
            if (!$item->has($attribute)) {
                return false;
            }
            return mb_strpos(mb_strtolower($item[$attribute]), mb_strtolower($value)) !== false;
        });
        return $this;
    }

    public function addCustomFilter(string $attribute, $value, callable $callback): DataProviderInterface
    {
        $this->data = array_filter($this->data, function (DataGridItem $item) use ($attribute, $value, $callback) {
            return call_user_func_array($callback, [$item, $attribute, $value]);
        });
        return $this;
    }

    protected function buildCompare($attribute, $order)
    {
        return function (DataGridItem $a, DataGridItem $b) use ($attribute, $order) {
            if (!$a->has($attribute) || !$b->has($attribute)) {
                return 0;
            }
            $attrValueA = $a[$attribute];
            $attrValueB = $b[$attribute];
            if ($attrValueA == $attrValueB) {
                return 0;
            }
            if (($type1 = gettype($attrValueA)) != gettype($attrValueB)) {
                return 0;
            }
            if ($type1 == 'string') {
                return $order == 'ASC' ? strcmp($attrValueA, $attrValueB) : -strcmp($attrValueA, $attrValueB);
            }
            return $order == 'ASC' ? $attrValueA <=> $attrValueB : $attrValueB <=> $attrValueA;
        };
    }

    protected function equalDate($attribute, $value): void
    {
        $date = new DateTime($value);
        $this->data = array_filter($this->data, function (DataGridItem $item) use ($attribute, $date) {
            if (!$item->has($attribute)) {
                return false;
            }
            $attrValue = $item[$attribute];
            if ($attrValue instanceof DateTime) {
                return date('d.m.Y', $attrValue->getTimestamp()) == date('d.m.Y', $date->getTimestamp());
            }
            if (is_string($attrValue)) {
                $attrDate = new DateTime($attrValue);
                return date('d.m.Y', $attrDate->getTimestamp()) == date('d.m.Y', $date->getTimestamp());
            }
            return false;
        });
    }

    protected function ltDate($attribute, $value): void
    {
        $date = new DateTime($value);
        $this->data = array_filter($this->data, function (DataGridItem $item) use ($attribute, $date) {
            if (!$item->has($attribute)) {
                return false;
            }
            $attrValue = $item[$attribute];
            if ($attrValue instanceof DateTime) {
                return $attrValue < $date;
            }
            if (is_string($attrValue)) {
                $attrDate = new DateTime($attrValue);
                return $attrDate < $date;
            }
            return false;
        });
    }

    protected function lteDate($attribute, $value): void
    {
        $date = (new DateTime($value))->modify('+1 day');
        $this->data = array_filter($this->data, function (DataGridItem $item) use ($attribute, $date) {
            if (!$item->has($attribute)) {
                return false;
            }
            $attrValue = $item[$attribute];
            if ($attrValue instanceof DateTime) {
                return $attrValue < $date;
            }
            if (is_string($attrValue)) {
                $attrDate = new DateTime($attrValue);
                return $attrDate < $date;
            }
            return false;
        });
    }

    protected function gtDate($attribute, $value): void
    {
        $date = (new DateTime($value))->modify('+1 day');
        $this->data = array_filter($this->data, function (DataGridItem $item) use ($attribute, $date) {
            if (!$item->has($attribute)) {
                return false;
            }
            $attrValue = $item[$attribute];
            if ($attrValue instanceof DateTime) {
                return $attrValue >= $date;
            }
            if (is_string($attrValue)) {
                $attrDate = new DateTime($attrValue);
                return $attrDate >= $date;
            }
            return false;
        });
    }

    protected function gteDate($attribute, $value): void
    {
        $date = new DateTime($value);
        $this->data = array_filter($this->data, function (DataGridItem $item) use ($attribute, $date) {
            if (!$item->has($attribute)) {
                return false;
            }
            $attrValue = $item[$attribute];
            if ($attrValue instanceof DateTime) {
                return $attrValue >= $date;
            }
            if (is_string($attrValue)) {
                $attrDate = new DateTime($attrValue);
                return $attrDate >= $date;
            }
            return false;
        });
    }
}
