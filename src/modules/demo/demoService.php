<?php
namespace demo\modules\demo;

class DemoService {
    private $demoRepository;

    public function __construct(DemoRepository $demoRepository) {
        $this->demoRepository = $demoRepository;
    }

    public function getUsers() {
        return $this->demoRepository->findAll();
    }
}