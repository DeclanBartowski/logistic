<?php


namespace App\Services;


class Commerce
{
    public static $instance;
    private $items;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function getItems()
    {
        if (!$this->items) {
            $this->items = \App\Models\Commerce::where('active', 1)
                ->where(function ($q) {
                    $q->where('active_from', '<=', date('Y-m-d H:i:s'))->orWhereNull('active_from');
                })
                ->where(function ($q) {
                    $q->where('active_to', '>=', date('Y-m-d H:i:s'))->orWhereNull('active_from');
                })->get();
        }
        return $this->items;
    }

}
