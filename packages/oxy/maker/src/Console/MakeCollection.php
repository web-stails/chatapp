<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeCollection extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:collection {name}';

    protected $description = 'Создать новый resource collection';

    protected function getStub()
    {
        return __DIR__ . '/stubs/collection.stub';
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Resources';
    }

    public function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $className = Str::of($this->argument('name'))->explode('/')->last();

        $replace = [
            'DummyClass'    => $this->argument('name'),
            'DummyResource' => Str::of($className)->replace('Collection', 'Resource'),
        ];

        return str_replace(array_keys($replace), array_values($replace), $stub);
    }
}
