<?php

namespace App\Traits;

trait NotificationHandlerTrait
{
    protected function successNotification($message, $title = 'Berhasil!')
    {
        return [
            'type' => 'success',
            'title' => $title,
            'message' => $message,
            'duration' => 5000 // durasi dalam milidetik
        ];
    }

    protected function errorNotification($message, $title = 'Gagal!')
    {
        return [
            'type' => 'error',
            'title' => $title,
            'message' => $message,
            'duration' => 5000
        ];
    }

    protected function infoNotification($message, $title = 'Informasi')
    {
        return [
            'type' => 'info',
            'title' => $title,
            'message' => $message,
            'duration' => 5000
        ];
    }
}

