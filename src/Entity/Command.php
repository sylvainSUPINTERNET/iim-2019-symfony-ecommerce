<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Command
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Many Commands have Many Products.
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="commands")
     * @ORM\JoinTable(name="commands_products")
     */
    private $products;

    /**
     * One Command has One Charge.
     * @ORM\OneToOne(targetEntity="Charge", mappedBy="command")
     */
    private $charge;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;



    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="commands")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }


    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }

    public function getCreatedAtValue()
    {
        return $this->createdAt;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }



    public function getCharge(): ?Charge
    {
        return $this->charge;
    }



    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setCharge(?Charge $charge): self
    {
        $this->charge = $charge;

        // set (or unset) the owning side of the relation if necessary
        $newCommand = $charge === null ? null : $this;
        if ($newCommand !== $charge->getCommand()) {
            $charge->setCommand($newCommand);
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }



}
