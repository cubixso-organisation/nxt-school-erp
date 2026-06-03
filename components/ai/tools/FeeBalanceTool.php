<?php
namespace app\components\ai\tools;

use Yii;

class FeeBalanceTool extends AbstractTool
{
    public function name(): string { return 'fee_balance'; }

    public function description(): string
    {
        return 'Look up the outstanding fee balance and payment history for a single student. Accepts either student_id (int) or admission_number (string). Returns class/section, total due, total paid, outstanding balance, and per-fee breakdown.';
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'student_id'       => ['type' => 'integer', 'description' => 'Internal student_details.id'],
                'admission_number' => ['type' => 'string',  'description' => 'Student admission number'],
            ],
            'oneOf' => [
                ['required' => ['student_id']],
                ['required' => ['admission_number']],
            ],
        ];
    }

    public function execute(array $input, array $context = []): array
    {
        $db = Yii::$app->db;
        $studentId = isset($input['student_id']) ? (int)$input['student_id'] : null;
        $admission = $input['admission_number'] ?? null;

        if (!$studentId && !$admission) {
            throw new \InvalidArgumentException('Provide student_id or admission_number');
        }

        $where = $studentId ? ['id' => $studentId] : ['admission_number' => $admission];
        $student = (new \yii\db\Query())
            ->select(['id','student_name','admission_number','campus_id','student_class_id','section_id','academic_year_id','parent_id','status'])
            ->from('student_details')
            ->where($where)
            ->limit(1)
            ->one($db);

        if (!$student) {
            return ['found' => false, 'message' => 'Student not found'];
        }

        $sid = (int)$student['id'];

        $fees = (new \yii\db\Query())
            ->select(['pf.id','pf.fee_structures_id','pf.fees_cut','pf.balance_fee','pf.due_date','pf.status','pf.academic_year_id'])
            ->from(['pf' => 'payi_fees'])
            ->where(['pf.student_id' => $sid])
            ->orderBy(['pf.due_date' => SORT_ASC])
            ->all($db);

        $totalDue       = 0.0;
        $totalOutstanding = 0.0;
        $breakdown = [];
        foreach ($fees as $f) {
            $totalDue        += (float)$f['fees_cut'];
            $totalOutstanding += (float)$f['balance_fee'];
            $breakdown[] = [
                'pay_fee_id'        => (int)$f['id'],
                'fee_structure_id'  => (int)$f['fee_structures_id'],
                'amount_due'        => (float)$f['fees_cut'],
                'balance'           => (float)$f['balance_fee'],
                'due_date'          => $f['due_date'],
                'status'            => (int)$f['status'],
            ];
        }

        $payments = (new \yii\db\Query())
            ->select(['paid_amount','payment_mode','created_on','fee_receipt'])
            ->from('payment_detailis')
            ->where(['student_id' => $sid])
            ->orderBy(['created_on' => SORT_DESC])
            ->limit(20)
            ->all($db);

        $totalPaid = 0.0;
        foreach ($payments as $p) { $totalPaid += (float)$p['paid_amount']; }

        $className = null;
        if (!empty($student['student_class_id'])) {
            $className = (new \yii\db\Query())
                ->select('title')->from('student_class')
                ->where(['id' => $student['student_class_id']])->scalar($db);
        }
        $sectionName = null;
        if (!empty($student['section_id'])) {
            $sectionName = (new \yii\db\Query())
                ->select('section_name')->from('class_sections')
                ->where(['id' => $student['section_id']])->scalar($db);
        }

        return [
            'found'              => true,
            'student' => [
                'id'                => $sid,
                'name'              => $student['student_name'],
                'admission_number'  => $student['admission_number'],
                'class'             => $className,
                'section'           => $sectionName,
                'campus_id'         => (int)$student['campus_id'],
            ],
            'totals' => [
                'total_due'           => round($totalDue, 2),
                'total_paid'          => round($totalPaid, 2),
                'outstanding_balance' => round($totalOutstanding, 2),
            ],
            'breakdown'        => $breakdown,
            'recent_payments'  => $payments,
            'last_payment_date' => $payments[0]['created_on'] ?? null,
        ];
    }
}
