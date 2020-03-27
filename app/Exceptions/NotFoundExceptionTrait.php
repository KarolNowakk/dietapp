<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
use Symfony\Component\HttpFoundation\Response;

trait NotFoundExceptionTrait
{
    public function apiException($exception,$request)
    {
        if ($this->isModel($exception)) {
            return $this->modelResponse($exception);
        }

        if ($this->isHttp($exception)) {
            return $this->httpResponse();
        }
        return parent::render($request, $exception);
        //return $this->otherResponse($exception);
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

}