<?php

namespace Modules\Indicator\Console;

use Illuminate\Console\Command;
use Modules\Directory\Entities\CatalogAnalys;
use Modules\Directory\Jobs\CreateAnalysAlias;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SyncAnalysAliasesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'alias:analysis-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sync analysis aliases';

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
        $analysis = CatalogAnalys::all();

        foreach ($analysis as $analys) {
            $translations = $analys->getTranslations();

            foreach ($translations['name'] as $locale => $translation) {
                CreateAnalysAlias::dispatch($translation, $analys->id)
                    ->onQueue(config('services.alias.create_analys_alias_queue_name'));
            }
        }
    }
}
