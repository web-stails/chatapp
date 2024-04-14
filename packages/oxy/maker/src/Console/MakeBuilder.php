<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeBuilder extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:builder {model} {name}';

    protected $description = 'Создать новый builder';

    protected function getStub()
    {
        return __DIR__ . '/stubs/builder.stub';
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Builders';
    }

    public function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $className = Str::of($this->argument('name'))->explode('/')->last();

        $replace = [
            'DummyClass'          => $this->argument('name'),
            'DummyResource'       => Str::of($className)->replace('Collection', 'Resource'),
            'DummyModelNamespace' => Str::of($this->argument('model'))->replace('/', '\\'),
            'DummyModel'          => Str::of($this->argument('model'))->explode('/')->last(),
        ];

        return str_replace(array_keys($replace), array_values($replace), $stub);
    }
}
