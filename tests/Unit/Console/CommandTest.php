<?php

namespace Tests\Unit\Console;

use Illuminate\Contracts\Console\Kernel;
use Mockery as m;
use Thytanium\Tests\TestCase;

class CommandTest extends TestCase
{
    /**
     * Test command normal flow.
     * 
     * @return void
     */
    public function test_normal_flow()
    {
        $this->migration();

        $this->artisan('thytanium:test-command');
    }

    /**
     * Migration flow for command.
     * 
     * @return void
     */
    protected function migration()
    {
        $outputPath = 'output_path';

        // Mocks
        $files = static::filesystemMock('stub_path', 'migration_contents', $outputPath);
        $composer = static::composerMock($files);

        // Command mock
        $this->commandMock($outputPath, $composer, $files);
    }

    /**
     * Create mock for Tests\Unit\Console\TestCommand class.
     * 
     * @param  string $outputPath
     * @param  string $stubPath
     * @param  string $migrationName
     * @return Tests\Unit\Console\TestCommand
     */
    protected function commandMock($outputPath, $composer, $files)
    {
        $command = m::mock(
            'Tests\Unit\Console\TestCommand[createBaseMigration]',
            [$files, $composer]
        )->shouldAllowMockingProtectedMethods();
        
        $command->shouldReceive('createBaseMigration')
            ->once()
            ->andReturn($outputPath);

        $this->app[Kernel::class]->registerCommand($command);
    }

    /**
     * Create mock Illuminate\Filesystem\Filesystem class.
     * 
     * @param  string $stubPath
     * @param  string $migrationContents
     * @param  string $outputPath
     * @return Illuminate\Filesystem\Filesystem
     */
    protected static function filesystemMock($stubPath, $migrationContents, $outputPath)
    {
        $files = m::mock('Illuminate\Filesystem\Filesystem[get,put]');
        $files->shouldReceive('get')
            ->once()
            ->with($stubPath)
            ->andReturn($migrationContents);
        $files->shouldReceive('put')
            ->once()
            ->with($outputPath, $migrationContents);

        return $files;
    }

    /**
     * Create mock for Illuminate\Support\Composer class.
     * 
     * @return Illuminate\Support\Composer
     */
    protected static function composerMock($files)
    {
        $composer = m::mock('Illuminate\Support\Composer[dumpAutoloads]', [$files]);
        $composer->shouldReceive('dumpAutoloads')->once();
        return $composer;
    }
}
