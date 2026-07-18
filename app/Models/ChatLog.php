<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatLog extends Model
{
    protected $table = 'chat_logs';

    protected $fillable = [
        'session_id',
        'user_message',
        'found_data',
        'prompt_sent',
        'ai_response',
        'response_time_ms',
        'locale',
        'success',
        'error_message',
    ];

    protected $casts = [
        'success' => 'boolean',
        'response_time_ms' => 'integer',
    ];

    /**
     * Get the found data as array (from JSON).
     */
    public function getFoundDataArray(): ?array
    {
        if (!$this->found_data) {
            return null;
        }
        return json_decode($this->found_data, true);
    }
}