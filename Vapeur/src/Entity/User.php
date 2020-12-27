<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $Username;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $Password;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="User", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, mappedBy="Download")
     */
    private $download_post;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->download_post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): self
    {
        $this->Username = $Username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getDownloadPost(): Collection
    {
        return $this->download_post;
    }

    public function addDownloadPost(Post $downloadPost): self
    {
        if (!$this->download_post->contains($downloadPost)) {
            $this->download_post[] = $downloadPost;
            $downloadPost->addDownload($this);
        }

        return $this;
    }

    public function removeDownloadPost(Post $downloadPost): self
    {
        if ($this->download_post->removeElement($downloadPost)) {
            $downloadPost->removeDownload($this);
        }

        return $this;
    }
}
