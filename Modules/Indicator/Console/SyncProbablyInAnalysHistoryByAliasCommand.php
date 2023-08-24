<?php

namespace Modules\Indicator\Console;

use Illuminate\Console\Command;
use Modules\Indicator\Entities\AnalysHistory;
use Modules\Indicator\Entities\IndicatorHistory;
use Modules\Indicator\Jobs\GetProbablyAnalysisByAlias;
use Modules\Indicator\Jobs\GetProbablyIndicatorsByAlias;
use Modules\Indicator\Jobs\GetProbablyMeasureTypesByAlias;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SyncProbablyInAnalysHistoryByAliasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'alias:probably-in-analys-history-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sync probably analysis by analys alias';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $analysisHistory = AnalysHistory::where(['analys_id' => null])->get();

        foreach ($analysisHistory as $analysHistory) {

            if ($analysHistory->original_analys_name !== null) {
                GetProbablyAnalysisByAlias::dispatch($analysHistory->original_analys_name, $analysHistory->id)
                    ->onQueue(config('services.alias.get_probably_analysis_by_alias_queue_name'));
            }
        }
    }
}
