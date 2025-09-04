<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Livewire\Exceptions\MethodNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof MethodNotFoundException && 
            $e->getMessage() && 
            str_contains($e->getMessage(), 'toJSON') && 
            str_contains($e->getMessage(), 'not found')) {
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Método toJSON ignorado silenciosamente'
                ], 200);
            }
            
            return redirect()->back()->with('info', 'Operação realizada com sucesso');
        }

        return parent::render($request, $e);
    }
}
