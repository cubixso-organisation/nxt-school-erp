<?php
namespace app\components\ai;

use app\components\ai\tools\ToolRegistry;
use yii\base\Component;

class AIClient extends Component
{
    public string $apiKey = '';
    public string $model = 'claude-sonnet-4-6';
    public string $apiBase = 'https://api.anthropic.com/v1/messages';
    public string $apiVersion = '2023-06-01';
    public int $maxTokens = 1024;

    private ?ToolRegistry $registry = null;

    public function init()
    {
        parent::init();
        if ($this->apiKey === '') {
            $this->apiKey = getenv('ANTHROPIC_API_KEY') ?: '';
        }
        $envModel = getenv('ANTHROPIC_MODEL');
        if ($envModel) {
            $this->model = $envModel;
        }
    }

    public function tools(): ToolRegistry
    {
        if ($this->registry === null) {
            $this->registry = new ToolRegistry();
        }
        return $this->registry;
    }

    public function callTool(string $toolName, array $input, array $context = []): array
    {
        $tool = $this->tools()->get($toolName);
        if (!$tool) {
            throw new \RuntimeException("Unknown tool: {$toolName}");
        }
        $userId      = $context['user_id']      ?? null;
        $instituteId = $context['institute_id'] ?? null;
        $campusId    = $context['campus_id']    ?? null;

        $invId = AuditLogger::start($toolName, $userId, $instituteId, $campusId, ['input' => $input], null);
        $t0 = microtime(true);
        try {
            $result = $tool->execute($input, $context);
            $latency = (int)((microtime(true) - $t0) * 1000);
            AuditLogger::finish($invId, $result, null, null, $latency, 'success');
            return ['invocation_id' => $invId, 'result' => $result];
        } catch (\Throwable $e) {
            $latency = (int)((microtime(true) - $t0) * 1000);
            AuditLogger::finish($invId, ['error' => $e->getMessage()], null, null, $latency, 'error', $e->getMessage());
            throw $e;
        }
    }

    public function chat(array $messages, ?string $systemPrompt = null, bool $allowTools = true, array $context = []): array
    {
        if ($this->apiKey === '') {
            throw new \RuntimeException('ANTHROPIC_API_KEY is not set');
        }

        $payload = [
            'model'      => $this->model,
            'max_tokens' => $this->maxTokens,
            'messages'   => $messages,
        ];
        if ($systemPrompt) {
            $payload['system'] = $systemPrompt;
        }
        if ($allowTools) {
            $payload['tools'] = $this->tools()->asAnthropicSchemas();
        }

        $t0 = microtime(true);
        $ch = curl_init($this->apiBase);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'content-type: application/json',
                'x-api-key: ' . $this->apiKey,
                'anthropic-version: ' . $this->apiVersion,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT => 60,
        ]);
        $raw = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);
        $latency = (int)((microtime(true) - $t0) * 1000);

        if ($raw === false || $code >= 400) {
            throw new \RuntimeException("Anthropic API error ($code): " . ($err ?: $raw));
        }

        $decoded = json_decode($raw, true);
        return [
            'response'   => $decoded,
            'latency_ms' => $latency,
        ];
    }
}
