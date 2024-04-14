<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\GeneratorCommand;

class MakeAction extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:action {name}';

    protected $description = 'Создать новый action';

    protected function getStub()
    {
        return __DIR__ . '/stubs/action.stub';
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Actions';
    }

    public function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace("DummyClass", $this->argument('name'), $stub);
    }
}
