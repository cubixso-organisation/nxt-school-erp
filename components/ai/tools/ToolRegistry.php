<?php
namespace app\components\ai\tools;

class ToolRegistry
{
    private array $tools = [];

    public function __construct()
    {
        $this->register(new FeeBalanceTool());
    }

    public function register(AbstractTool $tool): void
    {
        $this->tools[$tool->name()] = $tool;
    }

    public function get(string $name): ?AbstractTool
    {
        return $this->tools[$name] ?? null;
    }

    public function all(): array
    {
        return $this->tools;
    }

    public function asAnthropicSchemas(): array
    {
        $out = [];
        foreach ($this->tools as $t) {
            $out[] = [
                'name'         => $t->name(),
                'description'  => $t->description(),
                'input_schema' => $t->schema(),
            ];
        }
        return $out;
    }
}
