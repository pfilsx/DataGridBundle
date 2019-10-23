<?php


namespace Pfilsx\DataGrid\Config;

/**
 * Interface DataGridConfigurationInterface
 * @package Pfilsx\DataGrid\Config
 * @internal
 */
interface ConfigurationInterface
{
    public function __construct(array $config = []);

    public function getTemplate(): ?string;

    public function setTemplate(string $template): void;

    public function getNoDataMessage(): ?string;

    public function setNoDataMessage(string $message): void;

    public function getPaginationLimit(): ?int;

    public function setPaginationLimit(int $limit): void;

    public function getPaginationEnabled(): ?bool;

    public function setPaginationEnabled(bool $value): void;

    public function getTranslationDomain(): ?string;

    public function setTranslationDomain(string $domain): void;

    public function getConfigsArray(): array;

    public function merge(ConfigurationInterface $configuration): self;
}
