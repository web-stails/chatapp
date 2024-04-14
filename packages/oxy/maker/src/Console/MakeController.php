<?php

namespace Oxy\Maker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeController extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oxy:maker:controller {name} {model} {builder} {collection} {resource} {service?} {request.store?} {request.update?} {prefix?} {--F|full}';

    protected $description = 'Создать новый controller';

    protected function getStub()
    {
        $stub = __DIR__ . '/stubs/controller.stub';

        if ($this->option('full')) {
            $stub = __DIR__ . '/stubs/controller.full.stub';
        }

        return $stub;
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers';
    }

    public function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);


        $replace = [
            'DummyClass' => $this->argument('name'),

            'DummyModelNamespace'  => $this->getResourceName('model'),
            'DummyModelVariable'   => Str::of($this->getResourceName('model'))->camel(),
            'DummyModelRouteParam' => Str::of($this->getResourceName('model'))->headline()->snake(),
            'DummyModel'           => $this->getResourceNamespace('model'),

            'DummyBuilderNamespace' => $this->getResourceNamespace('builder'),
            'DummyBuilder'          => $this->getResourceName('builder'),

            'DummyCollectionNamespace' => $this->getResourceNamespace('collection'),
            'DummyCollection'          => $this->getResourceName('collection'),

            'DummyResourceNamespace' => $this->getResourceNamespace('resource'),
            'DummyResource'          => $this->getResourceName('resource'),

            'DummyTag' => $this->argument('prefix') . $this->getResourceNamespace('model')
        ];

        if ($this->option('full')) {
            $replace = array_merge($replace, [
                'DummyServiceNamespace' => $this->getResourceNamespace('service'),
                'DummyService'          => $this->getResourceName('service'),

                'DummyStoreRequestNamespace' => $this->getResourceNamespace('request.store'),
                'DummyStoreRequest'          => $this->getResourceName('request.store'),

                'DummyUpdateRequestNamespace' => $this->getResourceNamespace('request.update'),
                'DummyUpdateRequest'          => $this->getResourceName('request.update'),
            ]);
        }

        return str_replace(array_keys($replace), array_values($replace), $stub);
    }

    protected function getResourceNamespace(string $key): string
    {
        return Str::of($this->argument($key))->replace('/', '\\');
    }

    protected function getResourceName(string $key): string
    {
        return Str::of($this->argument($key))->explode('/')->last();
    }
}
