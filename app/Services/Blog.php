<?php


namespace App\Services;


use App\Models\Publication;

class Blog
{
    public function getRandomItems()
    {
        return Publication::where('active', 1)
            ->where(function ($q) {
                $q->where('active_from', '<=', date('Y-m-d H:i:s'))->orWhereNull('active_from');
            })
            ->where(function ($q) {
                $q->where('active_to', '>=', date('Y-m-d H:i:s'))->orWhereNull('active_to');
            })->limit(3)->inRandomOrder()->get();
    }

    public function getItems()
    {
        return Publication::where('active', 1)
            ->where(function ($q) {
                $q->where('active_from', '<=', date('Y-m-d H:i:s'))->orWhereNull('active_from');
            })
            ->where(function ($q) {
                $q->where('active_to', '>=', date('Y-m-d H:i:s'))->orWhereNull('active_to');
            })
            ->orderBy('created_at', 'desc')->paginate(3);
    }

    /**
     * @param Publication $publication
     */
    public function getDetail(Publication $publication)
    {

        if ($publication->active != 1 || ($publication->active_from > date('Y-m-d H:i:s') && $publication->active_from) || ($publication->active_to < date('Y-m-d H:i:s') && $publication->active_to)) {
            abort('404');
        }
    }
}
