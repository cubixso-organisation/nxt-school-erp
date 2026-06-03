<?php
namespace app\components\ai;

use Yii;

class AuditLogger
{
    public static function start(string $toolName, ?int $userId, ?int $instituteId, ?int $campusId, array $request, ?string $model = null): int
    {
        $db = Yii::$app->db;
        $promptHash = hash('sha256', json_encode($request, JSON_UNESCAPED_UNICODE));
        $db->createCommand()->insert('ai_invocations', [
            'tool_name'       => $toolName,
            'model'           => $model,
            'user_id'         => $userId,
            'institute_id'    => $instituteId,
            'campus_id'       => $campusId,
            'prompt_hash'     => $promptHash,
            'request_payload' => json_encode($request, JSON_UNESCAPED_UNICODE),
            'status'          => 'success',
        ])->execute();
        return (int)$db->getLastInsertID();
    }

    public static function finish(int $id, array $response, ?int $tokensIn, ?int $tokensOut, int $latencyMs, string $status = 'success', ?string $error = null): void
    {
        Yii::$app->db->createCommand()->update('ai_invocations', [
            'response_payload' => json_encode($response, JSON_UNESCAPED_UNICODE),
            'tokens_in'        => $tokensIn,
            'tokens_out'       => $tokensOut,
            'latency_ms'       => $latencyMs,
            'status'           => $status,
            'error_message'    => $error,
        ], ['id' => $id])->execute();
    }

    public static function proposal(int $invocationId, string $targetTable, ?string $targetPk, array $proposedChange, ?string $reasoning = null): int
    {
        $db = Yii::$app->db;
        $db->createCommand()->insert('ai_proposals', [
            'invocation_id'   => $invocationId,
            'target_table'    => $targetTable,
            'target_pk'       => $targetPk,
            'proposed_change' => json_encode($proposedChange, JSON_UNESCAPED_UNICODE),
            'reasoning'       => $reasoning,
            'status'          => 'pending',
        ])->execute();
        return (int)$db->getLastInsertID();
    }
}
