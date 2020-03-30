<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

trait ExceptionTrait
{
    public function apiException($exception,$request)
    {
        if ($this->isModel($exception)) {
            return $this->modelResponse($exception);
        }

        if ($this->isHttp($exception)) {
            return $this->httpResponse();
        }

        // if ($this->isDbQuery($exception)){
        //     return $this->dbQueryResponse();
        // }

        if ($this->isMeal($exception)) {
            return $this->mealResponse($exception);
        }

        return parent::render($request, $exception);
    }

    protected function modelResponse($exception)
    {
        $model = $exception->getModel();
        $className = last(explode('\\', $model));

        return response()->json([
            'errors' => "{$className} not found."
        ],Response::HTTP_NOT_FOUND);
    }

    protected function httpResponse()
    {
        return response()->json([
            'errors' => 'Incorrect route.'
        ],Response::HTTP_NOT_FOUND);
    }

    protected function dbQueryResponse()
    {
        return response()->json([
            'errors' => 'Unable to write into database.',
        ],Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function mealResponse($exception)
    {
        return response()->json([
            'errors' => $exception->message,
        ],Response::HTTP_NOT_FOUND);
    }

    protected function otherResponse($exception)
    {
        return response()->json($exception);
    }

    protected function isModel($exception)
    {
        return $exception instanceOf ModelNotFoundException;
    }

    protected function isHttp($exception)
    {
        return $exception instanceOf NotFoundHttpException;
    }

    protected function isDbQuery($exception)
    {
        return $exception instanceOf QueryException;
    }

    protected function isMeal($exception)
    {
        return $exception instanceOf MealNotFoundException;
    }

}