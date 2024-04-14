<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeRequest extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:request {name} {model} {type}';

    protected $description = 'Создать новый controller';

    protected function getStub()
    {
        return __DIR__ . '/stubs/request.' . $this->argument('type') . '.stub';;
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Requests';
    }

    public function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $replace = [
            'DummyClass' => $this->argument('name'),
            'DummyModel' => Str::of($this->argument('model'))->explode('/')->last(),
        ];

        return str_replace(array_keys($replace), array_values($replace), $stub);
    }
}
