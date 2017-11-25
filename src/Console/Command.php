<?php

namespace Thytanium\Migrations\Console;

use Illuminate\Console\Command as BaseCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

abstract class Command extends BaseCommand
{
    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * New Command instance.
     * 
     * @param Filesystem $files
     * @param Composer   $composer
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        $this->files = $files;
        $this->composer = $composer;

        parent::__construct();
    }

    /**
     * Run migrations command.
     * 
     * @return void
     */
    public function handle()
    {
        $this->migration();
    }

    /**
     * Get path where migration stub is located.
     *
     * @return  string
     */
    abstract protected function stubPath();

    /**
     * Get a name for this migration.
     *
     * @return  string
     */
    abstract protected function migrationName();

    /**
     * Create database migration.
     * 
     * @return void
     */
    protected function migration()
    {
        $stub = $this->stubPath();
        $fullPath = $this->createBaseMigration();

        $this->files->put($fullPath, $this->files->get($stub));

        $this->info('Migration created successfully!');

        $this->composer->dumpAutoloads();
    }

    /**
     * Create a base migration file for the session.
     *
     * @return string
     */
    protected function createBaseMigration()
    {
        $name = $this->migrationName();
        $path = $this->laravel->databasePath().'/migrations';

        return $this->laravel['migration.creator']->create($name, $path);
    }
}
