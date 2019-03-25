<?php


namespace Pfilsx\DataGrid\Config;


class DataGridConfiguration implements DataGridConfigurationInterface
{
    protected $template;

    protected $noDataMessage;

    protected $pagination;

    protected $paginationOptions;

    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    protected function setTemplate(string $template)
    {
        $this->template = $template;
    }

    protected function setNoDataMessage(string $message)
    {
        $this->noDataMessage = $message;
    }

    protected function setPagination(array $options)
    {
        if (!empty($options)) {
            $this->pagination = true;
            $this->paginationOptions = $options;
        } else {
            $this->pagination = false;
        }
    }

    public function getConfigs(): array
    {
        return [
            'template' => $this->template,
            'pagination' => ['enabled' => $this->pagination, 'options' => $this->paginationOptions],
            'noDataMessage' => $this->noDataMessage
        ];
    }
}
