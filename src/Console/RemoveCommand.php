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
        $modules = [
            'roles',
            'permissions',
            'pages',
            'teams',
            'blogs',
            'enquiries',
            'tickets',
        ];

        $selected = $this->choice('Please select module to remove', $modules, null, null, true);

        foreach ($modules as $module) {
            if (in_array($module, $selected)) {
                call_user_func([$this, str()->camel('remove-'.$module)]);
                $this->markModuleDisabled($module);
            }
        }

        $this->newLine();
        $this->info('All done!');
        $this->newLine();
    }

    /**
     * Mark module as disabled
     */
    private function markModuleDisabled($module)
    {
        $query = DB::table('site_settings')->where('name', 'modules');
        $enabled = collect(json_decode($query->first()->value))->reject(fn($val) => $val === $module);
        $value = $enabled->values()->all();

        $query->update(['value' => $value ? json_encode($value) : null]);
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
     * Remove permissions
     */
    private function removePermissions()
    {
        $this->newLine();
        $this->info('Removing permissions...');

        Schema::dropIfExists('users_permissions');
        $this->line('Dropped users_permissions table.');

        Schema::dropIfExists('roles_permissions');
        $this->line('Dropped roles_permissions table.');
    }

    /**
     * Remove roles
     */
    private function removeRoles()
    {
        $this->newLine();
        $this->info('Removing roles...');

        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function($table) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            });
            $this->line('Dropped role_id column from users table.');
        }

        Schema::dropIfExists('roles_permissions');
        $this->line('Dropped roles_permissions table.');

        Schema::dropIfExists('roles');
        $this->line('Dropped roles table.');
    }
}