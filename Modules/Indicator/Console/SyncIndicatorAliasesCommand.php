<?php

namespace Modules\Indicator\Console;

use Illuminate\Console\Command;
use Modules\Directory\Entities\CatalogIndicator;
use Modules\Directory\Jobs\CreateIndicatorAlias;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SyncIndicatorAliasesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'alias:indicators-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sync indicators aliases';

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
        $indicators = CatalogIndicator::all();

        foreach ($indicators as $indicator) {
            $translations = $indicator->getTranslations();

            foreach ($translations['name'] as $locale => $translation) {
                CreateIndicatorAlias::dispatch($translation, $indicator->id)
                    ->onQueue(config('services.alias.create_indicator_alias_queue_name'));
            }
        }
    }
}
