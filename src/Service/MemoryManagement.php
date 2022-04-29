<?php

namespace App\Service;

use App\Entity\Memory;
use App\Repository\MemoryRepository;

class MemoryManagement
{
    private MemoryRepository $memoryRepository;

    public function __construct(MemoryRepository $memoryRepository)
    {
        $this->memoryRepository = $memoryRepository;
    }

    public function createMemory($memoryDTO)
    {
        $memory = new Memory();
        $memory->setMemoryCapacity($memoryDTO->memoryCapacity);

        $this->memoryRepository->add($memory);
    }
}