<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeTestPestController extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:test:pest:controller {name} {model} {permission} {type=default}';

    protected $description = 'Создать новый pest test для controller';

    protected function getStub()
    {
        $stub = match ($this->argument('type')) {
            'admin' => 'test.pest.controller.full.stub',
            'public' => 'test.pest.controller.public.stub',
            default => 'test.pest.controller.stub',
        };

        return __DIR__ . '/stubs/' . $stub;
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    public function getPath($name)
    {
        $path = Str::of($name)->replace('App\\', '')->replace('\\', '/');

        return base_path() . '/tests/Feature/Http/Controllers/' . $path . '.php';
    }

    public function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $prefix = match ($this->argument('type')) {
            'admin' => 'admin.',
            'public' => 'public.',
            default => '',
        };

        $routeGroup = Str::of($this->argument('model'))
            ->replace('/', '-')
            ->headline()
            ->kebab()
            ->prepend($prefix);

        $replace = [
            'DummyClass'               => $this->argument('name'),
            'DummyPermissionNamespace' => Str::of($this->argument('permission'))->replace('/', '\\'),
            'DummyPermission'          => Str::of($this->argument('permission'))->explode('/')->last(),
            'DummyGroupName'           => $routeGroup->replace('.', '-')->headline()->lower(),
            'DummyGroup'               => $routeGroup->replace('.', '-'),
            'DummyRouteGroup'          => $routeGroup->plural(),
            'DummyModelNamespace'      => Str::of($this->argument('model'))->replace('/', '\\'),
            'DummyModel'               => Str::of($this->argument('model'))->explode('/')->last(),
        ];

        return str_replace(array_keys($replace), array_values($replace), $stub);
    }
}
