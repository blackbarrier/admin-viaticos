<?php

namespace App\Entity;
use App\Entity\Agente;
use App\Repository\PlantillaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlantillaRepository::class)]
class Plantilla
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $identificacion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $ultima_mod = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaCreacion = null;

    #[ORM\ManyToMany(targetEntity: agente::class)]
    private Collection $agentes;

    #[ORM\Column(nullable: true)]
    private ?bool $borrado = null;

    #[ORM\ManyToOne]
    private ?InfoDependencia $dependencia = null;

    #[ORM\ManyToOne(inversedBy: 'plantillas')]
    private ?InfoReparticion $reparticion = null;



    public function __construct()
    {
        $this->agentes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentificacion(): ?string
    {
        return $this->identificacion;
    }

    public function setIdentificacion(string $identificacion): static
    {
        $this->identificacion = $identificacion;

        return $this;
    }

    public function getultimaMod(): ?\DateTimeInterface
    {
        return $this->ultima_mod;
    }

    public function setultimaMod(\DateTimeInterface $ultima_mod): static
    {
        $this->ultima_mod = $ultima_mod;

        return $this;
    }



    /**
     * @return Collection<int, agente>
     */
    public function getAgentes(): Collection
    {
        return $this->agentes;
    }

    public function addAgente(agente $agente): static
    {
        if (!$this->agentes->contains($agente)) {
            $this->agentes->add($agente);
        }

        return $this;
    }

    public function removeAgente(agente $agente): static
    {
        $this->agentes->removeElement($agente);

        return $this;
    }

    public function isBorrado(): ?bool
    {
        return $this->borrado;
    }

    public function setBorrado(?bool $borrado): static
    {
        $this->borrado = $borrado;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fechaCreacion): static
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    public function getDependencia(): ?InfoDependencia
    {
        return $this->dependencia;
    }

    public function setDependencia(?InfoDependencia $dependencia): static
    {
        $this->dependencia = $dependencia;

        return $this;
    }

    public function getReparticion(): ?InfoReparticion
    {
        return $this->reparticion;
    }

    public function setReparticion(?InfoReparticion $reparticion): static
    {
        $this->reparticion = $reparticion;

        return $this;
    }
}
