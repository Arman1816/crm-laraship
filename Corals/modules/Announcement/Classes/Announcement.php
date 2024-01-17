<?php

namespace Corals\Modules\Announcement\Classes;

use Carbon\Carbon;
use Corals\Modules\Announcement\Models\Announcement as AnnouncementModel;
use Corals\User\Models\User;

class Announcement
{
    /**
     * Announcement constructor.
     */
    function __construct()
    {
    }

    /**
     * @param User|null $user
     * @param bool $count
     * @param null $limit
     * @param array $conditions
     * @param array $orConditions
     * @return \Illuminate\Database\Eloquent\Collection|int
     */
    public function unreadAnnouncements(User $user = null, $count = true, $limit = null, $conditions = [], $orConditions = [])
    {
        return $this->getAnnouncements($user, true, $count, $limit, $conditions, $orConditions);
    }

    /**
     * @param User|null $user
     * @param bool $unread
     * @param bool $count
     * @param null $limit
     * @param array $conditions
     * @param array $orConditions
     * @return \Illuminate\Database\Eloquent\Collection|int
     */
    public function getAnnouncements(User $user = null, $unread = true, $count = true, $limit = null, $conditions = [], $orConditions = [])
    {
        //in case user not passed to the method then the default is the logged in user
        if (is_null($user)) {
            $user = user();
        }

        //in case user not logged in
        if (is_null($user)) {
            $conditions['is_public'] = true;
            $roles = [];
        } else {
            $roles = $user->roles()->pluck('id')->toArray();
        }
        $announcements = \DB::table('announcements')
            ->whereDate('starts_at', '<=', Carbon::today())
            ->whereDate('ends_at', '>=', Carbon::today())
            ->orderBy('starts_at', 'desc');

        if (!empty($roles)) {
            $announcements = $announcements->leftJoin('model_has_roles', function ($join) {
                $join->on('model_id', 'announcements.id')
                    ->where('model_type', AnnouncementModel::class);
            })->where(function ($query) use ($roles) {
                $query->whereIn('role_id', $roles)->orWhereNull('role_id');
            });
        }

        $readPublicAnnouncements = \Cookie::get('read_public_announcements', '{}');
        $readPublicAnnouncements = json_decode($readPublicAnnouncements, true);

        if (!empty($conditions) || !empty($orConditions)) {
            $announcements = $announcements->where(function ($query) use ($conditions, $orConditions) {
                if ($conditions) {
                    $query->where($conditions);
                }
                if ($orConditions) {
                    $query->orWhere($orConditions);
                }
                $query->orWhereNull('show_in_url');

            });
        }

        if (!empty($readPublicAnnouncements)) {
            $announcements = $announcements->whereNotIn('announcements.id', $readPublicAnnouncements);
        }

        if ($limit) {
            $announcements = $announcements->take($limit);
        }

        if ($unread && $user) {
            $announcements = $announcements->whereRaw("not exists (
                select * from `announcement_tracking` 
                where `announcements`.`id` = `announcement_tracking`.`announcement_id` and `user_id` = '{$user->id}')");
        }

        $announcements = $announcements->groupBy('announcements.id')
            ->select('announcements.*')->get();

        if ($count) {
            return $announcements->count();
        }

        return AnnouncementModel::hydrate($announcements->toArray());
    }

    public function normalizeAnnouncement($announcement)
    {
        return rescue(function () use ($announcement) {
            if (!($announcement instanceof AnnouncementModel) && is_int($announcement)) {
                $announcement = AnnouncementModel::query()->find($announcement);
            }

            if ($announcement && $announcement->exists) {
                return $announcement;
            }
            return null;
        }, function () {
            return null;
        });

    }

    /**
     * @param AnnouncementModel $announcement
     */
    public function doRead(AnnouncementModel $announcement)
    {
        if (user() && !$announcement->isRead()) {
            $announcement->markAsRead();
            $this->setReadCookie($announcement);
        } elseif (!user()) {
            $this->setReadCookie($announcement);
        }
    }

    /**
     * @param AnnouncementModel $announcement
     */
    public function setReadCookie(AnnouncementModel $announcement)
    {
        //store for a week
        $readPublicAnnouncements = \Cookie::get('read_public_announcements', '{}');
        $readPublicAnnouncements = json_decode($readPublicAnnouncements, true);

        if (!$readPublicAnnouncements) {
            $readPublicAnnouncements = [];
        }

        if (!in_array($announcement->id, $readPublicAnnouncements)) {
            array_push($readPublicAnnouncements, $announcement->id);
        }

        \Cookie::queue('read_public_announcements', json_encode($readPublicAnnouncements), 10080);
    }
}
