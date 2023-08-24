<?php

namespace Modules\Indicator\Console;

use Illuminate\Console\Command;
use Modules\Indicator\Entities\IndicatorHistory;
use Modules\Indicator\Jobs\GetProbablyIndicatorsByAlias;
use Modules\Indicator\Jobs\GetProbablyMeasureTypesByAlias;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SyncProbablyInIndicatorHistoryByAliasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'alias:probably-in-indicator-history-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sync probably indicators and measure types by idicator alias';

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
        $indicatorsHistory = IndicatorHistory::where(['validated_at' => null])->get();

        foreach ($indicatorsHistory as $indicatorHistory) {

            if ($indicatorHistory->original_indicator_name !== null) {
                GetProbablyIndicatorsByAlias::dispatch($indicatorHistory->original_indicator_name, $indicatorHistory->id)
                    ->onQueue(config('services.alias.get_probably_indicators_by_alias_queue_name'));
            }

            if ($indicatorHistory->original_measure_type !== null) {
                GetProbablyMeasureTypesByAlias::dispatch($indicatorHistory->original_measure_type, $indicatorHistory->id)
                    ->onQueue(config('services.alias.get_probably_measure_types_by_alias_queue_name'));
            }
            
        }
    }
}
