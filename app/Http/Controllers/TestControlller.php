<?php

interface Workable
{
    public function work();
}

class Developer implements Workable
{
    public function work() {
        echo 'work developer';
    }
}

class Designer implements Workable
{
    public function work() {
        echo 'work designer';
    }
}
class Person
{
    public function process(Workable $work)
    {
        echo $work->work();
    }
}
