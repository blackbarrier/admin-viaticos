<?php

namespace App\Entity;

use App\Repository\SolicitudRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SolicitudRepository::class)]
class Solicitud
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nroGdeba = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metodoRendicion = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $agenteSolicitante = null;

    #[ORM\Column]
    private ?float $importeTotal = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Estado $estado = null;

    #[ORM\Column]
    private ?int $mes = null;

    #[ORM\Column]
    private ?int $anio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reparticion = null;

    #[ORM\OneToMany(mappedBy: 'solicitud', targetEntity: Viatico::class)]
    private Collection $viaticos;

    public function __construct()
    {
        $this->viaticos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getNroGdeba(): ?string
    {
        return $this->nroGdeba;
    }

    public function setNroGdeba(?string $nroGdeba): static
    {
        $this->nroGdeba = $nroGdeba;

        return $this;
    }

    public function getMetodoRendicion(): ?string
    {
        return $this->metodoRendicion;
    }

    public function setMetodoRendicion(?string $metodoRendicion): static
    {
        $this->metodoRendicion = $metodoRendicion;

        return $this;
    }

    /**
     * @return Collection<int, Viatico>
     */
    public function getViaticos(): Collection
    {
        return $this->viaticos;
    }

    public function addViatico(Viatico $viatico): static
    {
        if (!$this->viaticos->contains($viatico)) {
            $this->viaticos->add($viatico);
        }

        return $this;
    }

    public function removeViatico(Viatico $viatico): static
    {
        $this->viaticos->removeElement($viatico);

        return $this;
    }

    public function getAgenteSolicitante(): ?Usuario
    {
        return $this->agenteSolicitante;
    }

    public function setAgenteSolicitante(?Usuario $agenteSolicitante): static
    {
        $this->agenteSolicitante = $agenteSolicitante;

        return $this;
    }

    public function getImporteTotal(): ?float
    {
        return $this->importeTotal;
    }

    public function setImporteTotal(float $importeTotal): static
    {
        $this->importeTotal = $importeTotal;

        return $this;
    }

    public function getEstado(): ?Estado
    {
        return $this->estado;
    }

    public function setEstado(?Estado $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getMes(): ?int
    {
        return $this->mes;
    }

    public function setMes(int $mes): static
    {
        $this->mes = $mes;

        return $this;
    }

    public function getAnio(): ?int
    {
        return $this->anio;
    }

    public function setAnio(int $anio): static
    {
        $this->anio = $anio;

        return $this;
    }

    public function getReparticion(): ?string
    {
        return $this->reparticion;
    }

    public function setReparticion(?string $reparticion): static
    {
        $this->reparticion = $reparticion;

        return $this;
    }

   
}
