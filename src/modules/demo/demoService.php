<?php
namespace demo\modules\demo;

class DemoService {
    private $demoRepository;

    public function __construct(DemoRepository $demoRepository) {
        $this->demoRepository = $demoRepository;
    }

    public function getData() {
        return $this->demoRepository->getData();
    }
}