<?php

namespace Project\Model\GoRush;

class Notifications
{
    /**
     * @var array
     */
    protected array $notifications = [];

    /**
     * @return array
     */
    public function getNotifications(): array
    {
        return $this->notifications;
    }

    /**
     * @param array $notifications
     */
    public function setNotifications(array $notifications): void
    {
        $this->notifications = $notifications;
    }

    /**
     * @param array $notifications
     */
    public function addNotifications(array $notifications): void
    {
        $this->notifications[] = $notifications;
    }

    public function toArray(): array
    {
        return [
            'notifications' => $this->getNotifications()
        ];
    }
}