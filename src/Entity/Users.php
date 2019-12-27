<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 */
class Users
{

    /**
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $user_email;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $user_password;

    /**
     *
     * @ORM\Column(type="date")
     */
    private $date_of_added;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserEmail(): ?string
    {
        return $this->user_email;
    }

    public function setUserEmail(string $user_email): self
    {
        $this->user_email = $user_email;

        return $this;
    }

    public function getUserPassword(): ?string
    {
        return $this->user_password;
    }

    public function setUserPassword(string $user_password): self
    {
        $this->user_password = $user_password;

        return $this;
    }

    public function getDateOfAdded(): ?\DateTimeInterface
    {
        return $this->date_of_added;
    }

    public function setDateOfAdded(\DateTimeInterface $date_of_added): self
    {
        $this->date_of_added = $date_of_added;

        return $this;
    }
}
