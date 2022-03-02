<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Jiannius\Atom\AtomServiceProvider;

class PublishCommand extends Command
{
    protected $signature = 'atom:publish
                            {modules? : Modules to be published. Separate multiple modules with comma.}
                            {--force : Force publishing.}
                            {--static : Publish static site.}';

    protected $description = 'Publish Atom\'s modules views.';

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
        if ($this->option('static')) {
            $this->publish('atom-install-static');
        }
        else {
            $modules = $this->argument('modules')
                ? explode(',', $this->argument('modules'))
                : $this->choice('Please select modules to publish', [
                    'base',
                    'web',
                    'auth',
                    'user',
                    'role',
                    'team',
                    'file',
                    'permission',
                    'site_settings',
                    'label',
                    'page',
                    'blog',
                    'enquiry',
                    'ticket',
                    'plan',
                ], null, null, true);

            $this->newLine();

            foreach ($modules as $module) {
                $this->warn('Publishing '.$module.'...');

                if ($module === 'base') $this->publish('atom-base');
                else $this->publish('atom-views-'.$module);

                $this->newLine();
            }

            $this->newLine();
            $this->info('All done!');
            $this->newLine();
        }
    }

    /**
     * Publishing files
     */
    private function publish($tag)
    {
        $this->call('vendor:publish', [
            '--provider' => AtomServiceProvider::class,
            '--tag' => $tag,
            '--force' => $this->option('force'),
        ]);
    }
}