<?php


namespace Pfilsx\DataGrid\Grid\Filters;


class DateFilter extends AbstractFilter
{

    protected $minDate = null;

    protected $maxDate = null;

    /**
     * @return mixed
     */
    public function getMinDate(): ?string
    {
        return $this->minDate;
    }

    /**
     * @param mixed $minDate
     */
    protected function setMinDate(?string $minDate): void
    {
        $this->minDate = $minDate;
    }

    /**
     * @return mixed
     */
    public function getMaxDate(): ?string
    {
        return $this->maxDate;
    }

    /**
     * @param mixed $maxDate
     */
    protected function setMaxDate(?string $maxDate): void
    {
        $this->maxDate = $maxDate;
    }

    protected function getParams(): array
    {
        return [
            'min' => $this->minDate,
            'max' => $this->maxDate
        ];
    }

    public function getBlockName(): ?string
    {
        return 'date_filter';
    }
}
