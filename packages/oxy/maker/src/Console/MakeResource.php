<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeResource extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:resource {name} {model}';

    protected $description = 'Создать новый resource';

    protected function getStub()
    {
        return __DIR__ . '/stubs/resource.stub';
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Resources';
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
