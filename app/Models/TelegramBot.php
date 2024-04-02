<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use  DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramBot extends TelegraphBot
{
    protected $fillable = [
        'token',
        'name',
        'form_id'
    ];

    protected $table = "telegraph_bots";

    public function aiFrom(): BelongsTo
    {
        return $this->belongsTo(AiForm::class, 'form_id', 'id');
    }

}
