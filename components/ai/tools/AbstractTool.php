<?php
namespace app\components\ai\tools;

abstract class AbstractTool
{
    abstract public function name(): string;
    abstract public function description(): string;
    abstract public function schema(): array;
    abstract public function execute(array $input, array $context = []): array;

    protected function require(array $input, array $keys): void
    {
        foreach ($keys as $k) {
            if (!isset($input[$k]) || $input[$k] === '' || $input[$k] === null) {
                throw new \InvalidArgumentException("Missing required parameter: {$k}");
            }
        }
    }
}
