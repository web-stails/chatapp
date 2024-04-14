<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakePolicy extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:policy {name} {permission}';

    protected $description = 'Создать новый permission';

    protected function getStub()
    {
        return __DIR__ . '/stubs/policy.stub';
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Policies';
    }

    public function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $replace = [
            'DummyClass'               => $this->argument('name'),
            'DummyPermissionNamespace' => Str::of($this->argument('permission'))->replace('/', '\\'),
            'DummyPermission'          => Str::of($this->argument('permission'))->explode('/')->last(),
        ];

        return str_replace(array_keys($replace), array_values($replace), $stub);
    }
}
