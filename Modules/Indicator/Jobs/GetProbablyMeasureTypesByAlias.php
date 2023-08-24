<?php

namespace Modules\Indicator\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Directory\Services\MeasureTypeAliasServerService;

class GetProbablyMeasureTypesByAlias implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $alias;
    private $indicator_history_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $alias, int $indicatorHistoryId)
    {
        $this->alias = $alias;
        $this->indicator_history_id = $indicatorHistoryId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MeasureTypeAliasServerService $aliasService)
    {       
        $aliasService->getProbablyMeasureTypesByAlias($this->alias, $this->indicator_history_id);
    }
}
