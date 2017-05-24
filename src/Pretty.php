<?php
namespace Pyaesone17\LaravelPrettyHandler;

trait Pretty 
{
    public $prettyRules;

    public $prettyDefaultView;

    public function setUpPretty()
    {
        $this->prettyDefaultView = 'errors.503';
        $this->prettyRules = [
            ['url' => 'backend/*','view' => 'errors.back'],
            ['url' => 'frontend/*','view' => 'errors.frontend'],
            ['url' => '/', 'view' => 'errors.503']
        ];
    }
}
