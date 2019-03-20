<?php


namespace Pfilsx\DataGrid\Grid\Filters;


class EntityFilter extends AbstractFilter
{
    protected $label;

    protected $entityClass;

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    protected function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param string $class
     */
    protected function setEntityClass(string $class): void
    {
        $this->entityClass = $class;
    }

    protected function getBlockName(): string
    {
        return 'choice_filter';
    }

    protected function getParams(): array
    {
        $manager = $this->container->get('doctrine')->getRepository($this->entityClass);
        $queryResult = $manager->createQueryBuilder('dgq')
            ->select(['dgq.id', 'dgq.'.$this->label])->orderBy('dgq.id')->getQuery()->getArrayResult();
        $choices = [];
        array_walk($queryResult, function($val) use (&$choices){
            $choices[$val['id']] = $val[$this->label];
        });
        return [
            'choices' => $choices
        ];
    }


}