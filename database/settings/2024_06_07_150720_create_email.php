<?php

use Illuminate\Support\Str;
use Spatie\LaravelSettings\Migrations\SettingsMigration;
use Illuminate\Support\Facades\Log;

return new class extends SettingsMigration
{
    public function up(): void
    {
        try {
            $this->migrator->add('general.mail_from_name', '');
            $this->migrator->add('general.mail_from_address', '');
            $this->migrator->add('general.mail_encryption', '');
            $this->migrator->add('general.mail_password', '');
            $this->migrator->add('general.mail_username', '');
            $this->migrator->add('general.mail_port', '');
            $this->migrator->add('general.mail_host', '');
            $this->migrator->add('general.mail_mailer', '');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
};
