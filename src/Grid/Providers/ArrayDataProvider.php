<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use DateTime;
use Pfilsx\DataGrid\Grid\DataGridItem;

class ArrayDataProvider extends DataProvider
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getItems(): array
    {
        return array_map(function ($row) {
            $item = new DataGridItem();
            $item->setRow($row);
            return $item;
        }, $this->data);
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
        $this->data = array_filter($this->data, function ($row) use ($attribute, $value) {
            if (!array_key_exists($attribute, $row)) {
                return false;
            }
            return $row[$attribute] == $value;
        });
        return $this;
    }

    public function addLikeFilter(string $attribute, $value): DataProviderInterface
    {
        $this->data = array_filter($this->data, function ($row) use ($attribute, $value) {
            if (!array_key_exists($attribute, $row)) {
                return false;
            }
            return mb_strpos(mb_strtolower($row[$attribute]), mb_strtolower($value)) !== false;
        });
        return $this;
    }

    public function addCustomFilter(string $attribute, $value, callable $callback): DataProviderInterface
    {
        $data = $this->data;
        call_user_func_array($callback, [&$data, $attribute, $value]);
        return $this;
    }

    protected function buildCompare($attribute, $order)
    {
        return function ($a, $b) use ($attribute, $order) {
            if (!array_key_exists($attribute, $a) || !array_key_exists($attribute, $b)) {
                return 0;
            }
            $attrValueA = $a[$attribute];
            $attrValueB = $b[$attribute];
            if ($attrValueA == $attrValueB) {
                return 0;
            }
            if (($type1 = gettype($attrValueA)) != ($type2 = gettype($attrValueB))) {
                return 0;
            }
            if ($type1 == 'string') {
                return strcmp($attrValueA, $attrValueB);
            }
            return $attrValueA < $attrValueB ? -1 : 1;
        };
    }

    protected function equalDate($attribute, $value): void
    {
        $date = new DateTime($value);
        $this->data = array_filter($this->data, function ($row) use ($attribute, $date) {
            if (!array_key_exists($attribute, $row)) {
                return false;
            }
            $attrValue = $row[$attribute];
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
        $this->data = array_filter($this->data, function ($row) use ($attribute, $date) {
            if (!array_key_exists($attribute, $row)) {
                return false;
            }
            $attrValue = $row[$attribute];
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
        $this->data = array_filter($this->data, function ($row) use ($attribute, $date) {
            if (!array_key_exists($attribute, $row)) {
                return false;
            }
            $attrValue = $row[$attribute];
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
        $this->data = array_filter($this->data, function ($row) use ($attribute, $date) {
            if (!array_key_exists($attribute, $row)) {
                return false;
            }
            $attrValue = $row[$attribute];
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
        $this->data = array_filter($this->data, function ($row) use ($attribute, $date) {
            if (!array_key_exists($attribute, $row)) {
                return false;
            }
            $attrValue = $row[$attribute];
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
