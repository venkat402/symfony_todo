<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TasksRepository")
 */
class Tasks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $task_name;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $task_status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_of_added;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskName(): ?string
    {
        return $this->task_name;
    }

    public function setTaskName(string $task_name): self
    {
        $this->task_name = $task_name;

        return $this;
    }

    public function getTaskStatus(): ?string
    {
        return $this->task_status;
    }

    public function setTaskStatus(?string $task_status): self
    {
        $this->task_status = $task_status;

        return $this;
    }

    public function getDateOfAdded(): ?\DateTimeInterface
    {
        return $this->date_of_added;
    }

    public function setDateOfAdded(\DateTimeInterface $date_of_added = null): self
    {
        $this->date_of_added = $date_of_added;

        return $this;
    }
}
