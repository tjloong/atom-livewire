<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveCommand extends Command
{
    protected $signature = 'atom:remove';

    protected $description = 'Remove installed modules.';

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
        $modules = $this->choice('Please select module to remove', [
            'abilities',
            'labels',
            'pages',
            'teams',
            'blogs',
            'enquiries',
            'tickets',
        ], null, null, true);

        if (in_array('abilities', $modules)) $this->removeAbilities();
        if (in_array('labels', $modules)) $this->removeLabels();
        if (in_array('pages', $modules)) $this->removePages();
        if (in_array('teams', $modules)) $this->removeTeams();
        if (in_array('blogs', $modules)) $this->removeBlogs();
        if (in_array('enquiries', $modules)) $this->removeEnquiries();
        if (in_array('tickets', $modules)) $this->removeTickets();

        $this->newLine();
        $this->info('All done!');
        $this->newLine();
    }

    /**
     * Remvoe tickets
     */
    private function removeTickets()
    {
        $this->newLine();
        $this->info('Removing tickets...');

        Schema::dropIfExists('tickets_comments');
        $this->line('Dropped tickets_comments table.');

        Schema::dropIfExists('tickets');
        $this->line('Dropped tickets table.');
    }

    /**
     * Remove enquiries
     */
    private function removeEnquiries()
    {
        $this->newLine();
        $this->info('Removing enquiries...');

        Schema::dropIfExists('enquiries');
        $this->line('Dropped enquiries table.'); 
    }

    /**
     * Remove blogs
     */
    private function removeBlogs()
    {
        $this->newLine();
        $this->info('Removing blogs...');

        Schema::dropIfExists('blogs_labels');
        $this->line('Dropped blogs_labels table.');

        Schema::dropIfExists('blogs');
        $this->line('Dropped blogs table.');
    }

    /**
     * Remvoe teams
     */
    private function removeTeams()
    {
        $this->newLine();
        $this->info('Removing teams...');

        Schema::dropIfExists('teams_users');
        $this->line('Dropped teams_users table.');

        Schema::dropIfExists('teams');
        $this->line('Dropped teams table.');
    }

    /**
     * Remove pages
     */
    private function removePages()
    {
        $this->newLine();
        $this->info('Removing pages...');

        Schema::dropIfExists('pages');
        $this->line('Dropped pages table.');

        foreach ([
            'company',
            'phone',
            'email',
            'address',
            'facebook',
            'instagram',
            'twitter',
            'linkedin',
            'youtube',
            'spotify',
            'tiktok',
            'seo_title',
            'seo_description',
            'seo_image',
            'ga_id',
            'gtm_id',
            'fbpixel_id',
            'whatsapp',
            'whatsapp_bubble',
            'whatsapp_text',
        ] as $setting) {
            DB::table('site_settings')->where('name', $setting)->delete();
        }

        $this->line('Deleted site settings for pages.');
    }

    /**
     * Remove labels
     */
    private function removeLabels()
    {
        $this->newLine();
        $this->info('Removing labels...');

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('labels');
        Schema::enableForeignKeyConstraints();

        $this->line('Dropped labels table.');
    }

    /**
     * Remove abilities
     */
    private function removeAbilities()
    {
        $this->newLine();
        $this->info('Removing abilities...');

        Schema::dropIfExists('abilities_users');
        $this->line('Dropped abilities_users table.');

        Schema::dropIfExists('abilities_roles');
        $this->line('Dropped abilities_roles table.');

        Schema::dropIfExists('abilities');
        $this->line('Dropped abilities table.');
    }
}