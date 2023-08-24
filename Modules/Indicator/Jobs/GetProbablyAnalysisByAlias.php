<?php

namespace Modules\Indicator\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Directory\Services\AnalysAliasServerService;

class GetProbablyAnalysisByAlias implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $alias;
    private $analys_history_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $alias, int $analysHistoryId)
    {
        $this->alias = $alias;
        $this->analys_history_id = $analysHistoryId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AnalysAliasServerService $aliasService)
    {       
        $aliasService->getProbablyAnalysisByAlias($this->alias, $this->analys_history_id);
    }
}
