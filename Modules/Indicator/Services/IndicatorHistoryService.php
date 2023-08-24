<?php

namespace Modules\Indicator\Services;

use App\Models\Indicator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Modules\Indicator\Entities\IndicatorHistory;

class IndicatorHistoryService
{
    public function getInstance()
    {
        return IndicatorHistory::with([]);
    }

    public function validate(FormRequest $request, IndicatorHistory $history)
    {
        if ($history->isValidated()) {
            abort(422, 'This Indicator has already been validated');
        }

        if ($history->indicator_id === null and $request->indicator_id === null) {
            abort(422, 'This Indicator need confirmed indicator_id before validated');
        }

        DB::transaction(function () use ($request, $history) {
            $history->update([
                'indicator_id' => $request->indicator_id,
                'measure_type_id' => $request->measure_type_id ?? null,
                'norm_flag' => $request->norm_flag ?? null,
                'validated_at' => date('Y-m-d H:i:s'),
                'validated_who' => \Auth::id()
            ]);
        });
    }
}
