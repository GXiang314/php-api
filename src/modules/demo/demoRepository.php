<?php
namespace demo\modules\demo;

class DemoRepository {
    public function getData() {
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