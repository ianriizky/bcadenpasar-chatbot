<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NodeModulesLink extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'node_modules:link {--force : Recreate existing symbolic links}';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Create the symbolic links configured for the node_modules directory';

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function handle()
    {
        $link = public_path('node_modules');
        $target = base_path('node_modules');

        if (file_exists($link) && ! $this->isRemovableSymlink($link, $this->option('force'))) {
            return $this->error("The [$link] link already exists.");
        }

        if (is_link($link)) {
            $this->laravel->make('files')->delete($link);
        }

        $this->laravel->make('files')->link($target, $link);

        $this->info("The [$link] link has been connected to [$target].");
    }

    /**
     * Determine if the provided path is a symlink that can be removed.
     *
     * @param  string  $link
     * @param  bool  $force
     * @return bool
     */
    protected function isRemovableSymlink(string $link, bool $force): bool
    {
        return is_link($link) && $force;
    }
}
