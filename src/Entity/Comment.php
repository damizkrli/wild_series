<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="integer")
     */
    private $rate;

    /**
<<<<<<< HEAD
     * @ORM\ManyToOne(targetEntity=Episode::class)
     */
    private $episodes;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $users;
=======
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Episode::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $episodes;
>>>>>>> 494c0bc90a6eef499283e38882ca237333bb6195

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

<<<<<<< HEAD
    public function getEpisodes(): ?Episode
    {
        return $this->episodes;
    }

    public function setEpisodes(?Episode $episodes): self
    {
        $this->episodes = $episodes;
=======
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
>>>>>>> 494c0bc90a6eef499283e38882ca237333bb6195

        return $this;
    }

<<<<<<< HEAD
    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;
=======
    public function getEpisodes(): ?Episode
    {
        return $this->episodes;
    }

    public function setEpisodes(?Episode $episodes): self
    {
        $this->episodes = $episodes;
>>>>>>> 494c0bc90a6eef499283e38882ca237333bb6195

        return $this;
    }
}
