<?php

namespace App\Logging;

use App\Models\ActivityLog;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Monolog\Level;
use Exception;

class DatabaseLogHandler extends AbstractProcessingHandler
{
    public function __construct($level = Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        try {
            $context = $record->context ?? [];
            
            $logData = [
                'level' => strtolower($record->level->name),
                'message' => $record->message,
                'context' => $context,
                'created_at' => $record->datetime,
            ];

            if (isset($context['user_id'])) {
                $logData['user_id'] = $context['user_id'];
                unset($context['user_id']);
            }

            if (isset($context['ip'])) {
                $logData['ip_address'] = $context['ip'];
                unset($context['ip']);
            }

            if (isset($context['action'])) {
                $logData['action'] = $context['action'];
                unset($context['action']);
            }

            if (isset($context['model_type'])) {
                $logData['model_type'] = $context['model_type'];
                unset($context['model_type']);
            }

            if (isset($context['model_id'])) {
                $logData['model_id'] = $context['model_id'];
                unset($context['model_id']);
            }

            if (isset($context['old_values'])) {
                $logData['old_values'] = $context['old_values'];
                unset($context['old_values']);
            }

            if (isset($context['new_values'])) {
                $logData['new_values'] = $context['new_values'];
                unset($context['new_values']);
            }

            if (function_exists('request') && request()) {
                $logData['ip_address'] = $logData['ip_address'] ?? request()->ip();
                $logData['user_agent'] = request()->userAgent();
                $logData['url'] = request()->fullUrl();
                $logData['method'] = request()->method();
                $logData['session_id'] = session()->getId();

                // Se não temos user_id no contexto, tentar pegar do auth
                if (!isset($logData['user_id']) && auth()->check()) {
                    $user = auth()->user();
                    $logData['user_id'] = $user->id;
                    $logData['user_name'] = $user->name;
                    $logData['user_email'] = $user->email;
                }
            }

            $logData['context'] = $context;

            ActivityLog::create($logData);

        } catch (Exception $e) {
       
            error_log("Erro ao salvar log no banco: " . $e->getMessage());
        }
    }
} 