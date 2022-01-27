<?php

namespace Jiannius\Atom\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class ViewsCommand extends Command
{
    protected $signature = 'atom:views 
                            {feature? : Specify which feature\'s views to publish}
                            {--list : List all features}
                            {--force : Force publishing}';
    protected $description = 'Publish Atom\'s views';

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
        if ($this->option('list')) {
            $features = array_keys(config('atom.features'));

            $this->newLine();
            $this->info('Available features:');
            $this->newLine();

            foreach ($features as $feature) {
                if ($feature === 'site_settings') $this->comment($feature);
                else $this->comment(Str::singular($feature));
            }

            $this->newLine();
        }
        else if ($feature = $this->argument('feature')) {
            $this->call('vendor:publish', [
                '--provider' => 'Jiannius\Atom\AtomServiceProvider',
                '--tag' => 'atom-views-' . $feature,
                '--force' => $this->option('force'),
            ]);
        }
        else $this->error('Please specify a feature to publish');



        // dd($this->argument('feature'));
    }
}