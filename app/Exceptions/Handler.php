<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * Une liste des types d'exception qui ne sont pas déclarés.
     *
     * @var array
     */
    protected $dontReport = [
    ];

    /**
     * Une liste des entrées qui ne sont jamais flashées pour les exceptions de validation.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Signalez ou enregistrez une exception.
     *
     * C'est un endroit idéal pour envoyer des exceptions à Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     */
    public function report(Exception $exception)
    {
        // https://github.com/getsentry/sentry-laravel/issues/64
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Rendre une exception dans une réponse HTTP.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
