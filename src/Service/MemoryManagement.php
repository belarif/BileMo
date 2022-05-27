<?php

namespace App\Service;

use App\Entity\Memory;
use App\Exception\MemoryException;
use App\Repository\MemoryRepository;

class MemoryManagement
{
    private MemoryRepository $memoryRepository;

    public function __construct(MemoryRepository $memoryRepository)
    {
        $this->memoryRepository = $memoryRepository;
    }

    /**
     * @throws MemoryException
     */
    public function createMemory($memoryDTO): Memory
    {
        if($this->memoryRepository->findBy(['memoryCapacity' => $memoryDTO->memoryCapacity])) {
            throw MemoryException::memoryExists($memoryDTO->memoryCapacity);
        }

        $memory = new Memory();
        $memory->setMemoryCapacity($memoryDTO->memoryCapacity);

        return $this->memoryRepository->add($memory);
    }

    public function memoriesList()
    {
        return $this->memoryRepository->findAll();
    }

    public function updateMemory($memory, $memoryDTO): Memory
    {
        if($this->memoryRepository->findBy(['memoryCapacity' => $memoryDTO->memoryCapacity])) {
            throw MemoryException::memoryExists($memoryDTO->memoryCapacity);
        }

        return $this->memoryRepository->add($memory->setMemoryCapacity($memoryDTO->memoryCapacity));
    }

    public function deleteMemory($memory)
    {
        $this->memoryRepository->remove($memory);
    }
}

