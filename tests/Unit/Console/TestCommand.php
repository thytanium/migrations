<?php

namespace Tests\Unit\Console;

use Thytanium\Migrations\Console\Command;

class TestCommand extends Command
{
    public $signature = 'thytanium:test-command';
    public $description = 'Thytanium migrations test command';

    /**
     * Path to migration stub.
     * 
     * @return string
     */
    public function stubPath()
    {
        return 'stub_path';
    }

    /**
     * Migration name.
     * 
     * @return string
     */
    public function migrationName()
    {
        return 'create_thytanium_table';
    }
}
