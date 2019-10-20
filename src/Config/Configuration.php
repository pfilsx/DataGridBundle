<?php


namespace Pfilsx\DataGrid\Config;

/**
 * Class DataGridConfiguration
 * @package Pfilsx\DataGrid\Config
 * @internal
 */
class Configuration implements ConfigurationInterface
{
    protected $template;

    protected $noDataMessage;

    protected $paginationLimit;

    protected $paginationEnabled;

    protected $showTitles;

    protected $translationDomain;

    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            $key = str_replace('_', '', $key);
            $setter = 'set' . $key;
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function getNoDataMessage(): ?string
    {
        return $this->noDataMessage;
    }

    public function setNoDataMessage(string $message): void
    {
        $this->noDataMessage = $message;
    }

    public function setPaginationEnabled(bool $value): void
    {
        $this->paginationEnabled = $value;
    }

    public function setPaginationLimit(int $limit): void
    {
        $this->paginationLimit = $limit;
    }

    public function setShowTitles(bool $showTitles): void
    {
        $this->showTitles = $showTitles;
    }

    public function setTranslationDomain(?string $domain): void
    {
        $this->translationDomain = $domain;
    }

    public function getConfigsArray(): array
    {
        return [
            'template' => $this->getTemplate(),
            'paginationEnabled' => $this->getPaginationEnabled(),
            'paginationLimit' => $this->getPaginationLimit(),
            'noDataMessage' => $this->getNoDataMessage(),
            'showTitles' => $this->getShowTitles(),
            'translationDomain' => $this->getTranslationDomain()
        ];
    }

    public function merge(ConfigurationInterface $configuration): ConfigurationInterface
    {
        $result = clone $this;
        foreach ($configuration->getConfigsArray() as $key => $value) {
            if (!empty($value)) {
                $setter = 'set' . ucfirst($key);
                $this->$setter($value);
            }
        }
        return $result;
    }

    public function getPaginationLimit(): ?int
    {
        return $this->paginationLimit;
    }

    public function getPaginationEnabled(): ?bool
    {
        return $this->paginationEnabled;
    }

    public function getShowTitles(): ?bool
    {
        return $this->showTitles;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }
}
