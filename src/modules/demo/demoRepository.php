<?php
namespace demo\modules\demo;

class DemoRepository {
    public function findAll() {
        return [
            [
                "id" => 1,
                "name"=> "John",
            ],
            [
                "id"=> 2,
                "name"=> "Allen",
            ],
        ];
    }
}