<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakePermission extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:permission {name}';

    protected $description = 'Создать новый permission';

    public function handle()
    {
        $this->call('oxy:maker:permission-group');

        return parent::handle();
    }

    protected function getStub()
    {
        return __DIR__ . '/stubs/permission.stub';
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Enums\Permissions';
    }

    public function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace("DummyClass", $this->argument('name'), $stub);
    }
}
