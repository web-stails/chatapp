<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class AbstractModelService
{
    /**
     * Получить одну запись модели.
     *
     * @param int $id
     *
     * @return Model
     */
    public function getById(int $id): Model
    {
        return $this->model()::findOrFail($id);
    }

    /**
     * Создание модели.
     *
     * @param array $fields
     *
     * @return Model
     */
    public function create(array $fields): Model
    {
        $res = null;

        DB::transaction(function () use (&$res, $fields) {
            $newFields = $this->preProcessing('create', $fields);

            $res = $this->postProcessing(
                typePostProcessing: 'create',
                newFields: $newFields,
                oldFields: $fields,
                model: $this->model()::create($newFields)
            );
        });

        return $res;
    }

    /**
     * @return Model
     */
    abstract public function model(): Model;

    /**
     * Обновление модели.
     *
     * @param Model $item
     * @param array $fields
     * @param Model $model
     *
     * @throws \Exception
     */
    public function update(Model $model, array $fields): array|Model|null
    {
        $res = null;

        DB::transaction(function () use (&$res, &$model, $fields) {
            $newFields = $this->preProcessing('update', $fields, $model);

            if (!$model->updateOrFail($newFields)) {
                throw new \Exception('Не удалось обновить instance: ' . $model->getTable());
            }

            $res = $this->postProcessing(
                typePostProcessing: 'update',
                newFields: $newFields,
                oldFields: $fields,
                model: $model
            );
        });

        return $res ?? $model->unsetRelations();
    }

    /**
     * Удаление модели.
     *
     * @param Model $item
     * @param Model $model
     *
     * @throws \Exception
     */
    public function delete(Model $model): void
    {
        DB::transaction(function () use (&$model) {
            $this->preProcessing('delete', [], $model);

            $oldModel = clone $model;

            $model->deleteOrFail();

            $this->postProcessing(
                typePostProcessing: 'delete',
                newFields: [],
                oldFields: [],
                model: $oldModel
            );
        });
    }

    /**
     * Загрузить отношения для модели.
     *
     * @param Model $model
     *
     * @return Model
     */
    public static function loadRelation(Model $model): Model
    {
        return !empty(static::RELATION_LIST)
            ? $model->load(static::RELATION_LIST ?? [])
            : $model
        ;
    }

    /**
     * Получить $this class.
     *
     * @param int $permissionId
     *
     * @return static
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * Отфильтровываем поля которые не нужно передавать модели для сохранения.
     *
     * @param array $attributes
     * @param array $filter
     *
     * @return array
     */
    protected function filterFieldsModel(array $attributes, array $filter = []): array
    {
        return array_filter($attributes, fn ($item) => !in_array($item, $filter), ARRAY_FILTER_USE_KEY);
    }

    /**
     * Предварительная обработка параметров.
     *
     * @param string     $typePreProcessing
     * @param array      $fields
     * @param Model|null $model
     *
     * @return array
     */
    private function preProcessing(string $typePreProcessing, array $fields, ?Model $model = null): ?array
    {
        $methodPreProcessing = match ($typePreProcessing) {
            'create' => function () use ($fields) {
                if (method_exists($this, 'beforeCreate')) {
                    return $this->beforeCreate($fields) ?? $fields;
                }

                return $fields;
            },
            'update' => function () use ($fields, $model) {
                if (method_exists($this, 'beforeUpdate')) {
                    return $this->beforeUpdate($fields, $model) ?? $fields;
                }

                return $fields;
            },
            'delete' => function () use ($model) {
                if (method_exists($this, 'beforeDelete')) {
                    return $this->beforeDelete($model) ?? null;
                }

                return null;
            }
        };

        return $methodPreProcessing();
    }

    /**
     * Пост обработка.
     *
     * @param string $typePostProcessing
     * @param array  $fields
     * @param Model  $model
     * @param array  $newFields
     * @param array  $oldFields
     *
     * @return mixed
     */
    private function postProcessing(string $typePostProcessing, array $newFields, array $oldFields, Model $model): mixed
    {
        $methodPostProcessing = match ($typePostProcessing) {
            'create' => function () use ($model, $oldFields, $newFields) {
                if (method_exists($this, 'afterCreate')) {
                    return $this->afterCreate($model, $oldFields, $newFields) ?? $model;
                }

                return $model;
            },
            'update' => function () use ($model, $oldFields, $newFields) {
                if (method_exists($this, 'afterUpdate')) {
                    return $this->afterUpdate($model, $oldFields, $newFields);
                }

                return null;
            },
            'delete' => function () use ($model) {
                if (method_exists($this, 'afterDelete')) {
                    $this->afterDelete($model);
                }

                return null;
            }
        };

        return $methodPostProcessing();
    }
}
