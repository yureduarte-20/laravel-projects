<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class NotificationService
{
    public function writeSuccessNotification(string $content)
    {

        Session::flash('notification', ['type' => 'success', 'content' => $content]);
    }

    public function writeInfoNotification(string $content)
    {
        Session::flash('notification', ['type' => 'info', 'content' => $content]);

    }
    public function writeDangerNotification(string $content)
    {
        Session::flash('notification', ['type' => 'danger', 'content' => $content]);
    }

}
