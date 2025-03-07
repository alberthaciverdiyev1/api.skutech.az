<?php

namespace App\Services;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CrudService
{
    /**
     * Store a newly created resource in storage.
     */
    public function store($request, $callingClass, $callback = null): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            if (property_exists($callingClass, 'translatable')) {
                foreach ($callingClass->translatable as $field) {
                    if (isset($data[$field]) && is_array($data[$field])) {
                        $data[$field] = array_filter($data[$field], function ($value) {
                            return !empty($value) && $value !== null;
                        });
                    }
                }
            }

            $model = $callingClass::create($data);

            if ($callback !== null && is_callable($callback)) {
                try {
                    $callback($model);
                } catch (Exception $exception) {
                    DB::rollBack();
                    return response()->json($exception->getMessage(), 500);
                }
            }

            DB::commit();
            return response()->json(trans('Data successfully created!'), 201);

        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($request, $model, $callback = null): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Получаем данные запроса
            $data = $request->validated();

            // Удаляем пустые или null значения из переводимых полей
            foreach ($model->translatable as $field) {
                if (isset($data[$field]) && is_array($data[$field])) {
                    $data[$field] = array_filter($data[$field], function ($value) {
                        return !empty($value) && $value !== null;
                    });
                }
            }

            // Обновляем модель с очищенными данными
            $model->update($data);

            if ($callback !== null && is_callable($callback)) {
                try {
                    $callback($model);
                } catch (Exception $exception) {
                    DB::rollBack();
                    return response()->json($exception->getMessage(), 500);
                }
            }

            DB::commit();
            return response()->json(trans('Data successfully updated!'), 200);

        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($model, $callback = null): JsonResponse
    {
        DB::beginTransaction();

        try {
            if ($callback !== null && is_callable($callback)) {
                try {
                    $callback($model);
                } catch (Exception $exception) {
                    DB::rollBack();
                    return response()->json($exception->getMessage(), 500);
                }
            }

            $model->delete();

            DB::commit();
            return response()->json(trans('Data successfully deleted!'), 200);

        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
