<?php

namespace SerterSerbest\LTO\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateDTOCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {name} {--validatable}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a DTO class';


    protected function getStub(): string
    {
        if ($this->option('validatable'))
            return __DIR__ . '/../../stubs/validatable.stub';

        return __DIR__ . '/../../stubs/plain.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\\DTOs';
    }
}
