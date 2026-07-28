<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Bus;

class BatchController extends Controller
{
    public function status(string $batchId)
    {
        $batch = Bus::findBatch($batchId);

        if (! $batch) {
            return response()->json([
                'success' => false,
                'message' => 'Batch tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success'   => true,
            'batch_id'  => $batch->id,
            'total'     => $batch->totalJobs,
            'processed' => $batch->processedJobs(),
            'pending'   => $batch->pendingJobs,
            'failed'    => $batch->failedJobs,
            'finished'  => $batch->finished(),
            'cancelled' => $batch->cancelled(),
            'percent'   => $batch->progress(),
        ]);
    }

    public function cancel(string $batchId)
    {
        $batch = Bus::findBatch($batchId);

        if (! $batch) {
            return response()->json([
                'success' => false,
                'message' => 'Batch tidak ditemukan.',
            ], 404);
        }

        $batch->cancel();

        return response()->json([
            'success' => true,
            'message' => 'Batch berhasil dibatalkan.',
        ]);
    }
}