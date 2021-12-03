<?php


namespace App\Services;


class Vacancy
{
    /**
     * @param string $q
     * @return mixed
     */
    public function getVacancies($q = '')
    {
        $vacancies = \App\Models\Vacancy::where('active', 1)
            ->where(function ($q) {
                $q->where('active_from', '<=', date('Y-m-d H:i:s'))->orWhereNull('active_from');
            })
            ->where(function ($q) {
                $q->where('active_to', '>=', date('Y-m-d H:i:s'))->orWhereNull('active_to');
            });
        if ($q) {
            $vacancies = $vacancies->where('name', 'like', sprintf('%%%s%%', $q));
        }

        return $vacancies->orderBy('id', 'desc')->paginate(9);
    }

    public function getRandomItems()
    {
        return \App\Models\Vacancy::where('active', 1)
            ->where(function ($q) {
                $q->where('active_from', '<=', date('Y-m-d H:i:s'))->orWhereNull('active_from');
            })
            ->where(function ($q) {
                $q->where('active_to', '>=', date('Y-m-d H:i:s'))->orWhereNull('active_to');
            })->limit(3)->inRandomOrder()->get();
    }

    /**
     * @param \App\Models\Vacancy $vacancy
     */
    public function getDetail(\App\Models\Vacancy &$vacancy)
    {
        if ($vacancy->active != 1 || ($vacancy->active_from > date('Y-m-d H:i:s') && $vacancy->active_from) || ($vacancy->active_to < date('Y-m-d H:i:s') && $vacancy->active_to)) {
            abort('404');
        } else {
            if (isset($vacancy->related) && $vacancy->related) {
                $vacancy->related_items = \App\Models\Vacancy::where('active', 1)
                    ->where(function ($q) {
                        $q->where('active_from', '<=', date('Y-m-d H:i:s'))->orWhereNull('active_from');
                    })
                    ->where(function ($q) {
                        $q->where('active_to', '>=', date('Y-m-d H:i:s'))->orWhereNull('active_from');
                    })
                    ->whereIn('id', $vacancy->related)->get();
            }
        }
    }

}
