<?php

class Gear_Timer {

    private $start;
    private $pause_time;

    public function __construct($start = 0) {
        if ($start) {
            $this->start();
        }
    }

    private function start() {
        $this->start = $this->get_time();
        $this->pause_time = 0;
    }

    public function pause() {
        $this->pause_time = $this->get_time();
    }

    public function unpause() {
        $this->start += ($this->get_time() - $this->pause_time);
        $this->pause_time = 0;
    }

    public function get($decimals = 8) {
        return round(($this->get_time() - $this->start), $decimals);
    }

    public function get_time() {
        list($usec, $sec) = explode(' ', microtime());
        return ((float) $usec + (float) $sec);
    }

}

?>
