<?php

use App\Http\Controllers\CategoryController;
use App\Jobs\PushNotificationJob;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Support\Facades\Route;

Route::get('/',[CategoryController::class,'index'])->name('welcome');
Route::get('test', function () {
   $deviceTokens =\App\Models\User::whereDay('created_at',now()->format('d'))->pluck('device_token')->toArray();
//    $notify =Notification::create(
//        [
//            'topic' => 'test',
//            'title' => 'Push Notification',
//            'body' => 'Chúc mừng bạn đã push được Notification',
//        ]
//    );
    PushNotificationJob::dispatch('sendBatchNotification', [
        $deviceTokens,
        [
//            'topicName' => $notify->topic,
//            'title' => $notify->title,
//            'body' => $notify->body,
            'topicName' => 'test',
            'title' => 'Push Notification',
            'body' => 'Chúc mừng bạn đã push được Notification',
        ],
    ]);
});
