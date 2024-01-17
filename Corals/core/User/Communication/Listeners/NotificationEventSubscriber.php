<?php

namespace Corals\User\Communication\Listeners;


use Corals\User\Communication\Facades\CoralsNotification;
use Corals\User\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class NotificationEventSubscriber
{

    public function handleNotificationEvent($eventName, $data)
    {
        $events = CoralsNotification::getEventByEventName($eventName);

        foreach ($events as $event) {
            $notificationClass = app($event['notificationClass']);

            $notificationClass->initNotification($event['name'], $event['event_name'], $data);

            $notificationTemplate = $notificationClass->getNotificationTemplate();

            $notifiables = $notificationClass->getNotifiables();

            $notifiables = ($notifiables instanceof Collection) ? $notifiables : (is_array($notifiables) ? collect($notifiables) : collect([$notifiables]));

            // check if notificationTemplate has bcc roles
            $bcc_roles = ($notificationTemplate->extras['bcc_roles'] ?? null);

            if (!empty($bcc_roles)) {
                $bcc_roles_users = User::query()->whereHas('roles', function ($query) use ($bcc_roles) {
                    $query->whereIn('id', $bcc_roles);
                })->select('users.*')->get();

                $notifiables = $notifiables->merge($bcc_roles_users);
            }

            // check if notificationTemplate has bcc users
            $bcc_users_ids = ($notificationTemplate->extras['bcc_users'] ?? null);

            if (!empty($bcc_users_ids)) {
                $bcc_users = User::query()->whereIn('id', $bcc_users_ids)->select('users.*')->get();
                $notifiables = $notifiables->merge($bcc_users);
            }

            // get unique notifiables after merge
            $notifiables = $notifiables->unique(function ($item) {
                return class_basename($item) . $item['id'];
            });

            Notification::send($notifiables, $notificationClass);

            // check if notificationTemplate has to channels Custom
            $channelsCustom = ($notificationTemplate->extras['custom'] ?? null);
            $this->sendOnDemandNotifiables($channelsCustom, $notificationClass);

            $onDemandNotifiables = $notificationClass->getOnDemandNotificationNotifiables();
            $this->sendOnDemandNotifiables($onDemandNotifiables, $notificationClass);
        }
    }

    protected function sendOnDemandNotifiables($onDemandNotifiables, $notificationClass)
    {
        if (!empty($onDemandNotifiables) && is_array($onDemandNotifiables)) {
            foreach ($onDemandNotifiables as $channel => $values) {
                if (is_array($values)) {
                    foreach ($values as $value) {
                        Notification::route($channel, $value)
                            ->notify($notificationClass);
                    }
                } else {
                    Notification::route($channel, $values)
                        ->notify($notificationClass);
                }
            }
        }
    }

    public function subscribe($events)
    {
        //listen for every event in the system
        $events->listen('notifications.*',
            'Corals\User\Communication\Listeners\NotificationEventSubscriber@handleNotificationEvent'
        );
    }
}
