<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeService extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:service {name} {model}';

    protected $description = 'Создать новый service';

    protected function getStub()
    {
        return __DIR__ . '/stubs/service.stub';
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }

    public function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $replace = [
            'DummyClass'          => $this->argument('name'),
            'DummyModelNamespace' => Str::of($this->argument('model'))->replace('/', '\\'),
            'DummyModel'          => Str::of($this->argument('model'))->explode('/')->last(),
        ];

        return str_replace(array_keys($replace), array_values($replace), $stub);
    }
}
