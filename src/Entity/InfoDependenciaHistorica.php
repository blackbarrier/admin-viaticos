<?php

namespace App\Entity;

use App\Repository\InfoDependenciaHistoricaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfoDependenciaHistoricaRepository::class )]
class InfoDependenciaHistorica 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $abreviatura = null;

    #[ORM\Column(nullable: true)]
    private ?int $dependenciaRenaperId = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $codigoGdeba = null;

    #[ORM\Column(nullable: true)]
    private ?int $tipoDependenciaId = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $tipoDelegacion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $clase = null;

    #[ORM\Column(nullable: true)]
    private ?int $partidoId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaVigenciaDesde = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaVigenciaHasta = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaCarga = null;

    #[ORM\Column(nullable: true)]
    private ?int $usuarioId = null;

    #[ORM\Column(nullable: true)]
    private ?int $estadoDependenciaId = null;

    #[ORM\ManyToOne]
    private ?InfoReparticion $reparticion = null;

    #[ORM\Column(nullable: true)]
    private ?bool $externa = null;

    #[ORM\ManyToOne(targetEntity:InfoDependencia::class)]
    private ?InfoDependencia $dependenciaReferencia = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getAbreviatura(): ?string
    {
        return $this->abreviatura;
    }

    public function setAbreviatura(?string $abreviatura): static
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    public function getDependenciaRenaperId(): ?int
    {
        return $this->dependenciaRenaperId;
    }

    public function setDependenciaRenaperId(?int $dependenciaRenaperId): static
    {
        $this->dependenciaRenaperId = $dependenciaRenaperId;

        return $this;
    }

    public function getCodigoGdeba(): ?string
    {
        return $this->codigoGdeba;
    }

    public function setCodigoGdeba(?string $codigoGdeba): static
    {
        $this->codigoGdeba = $codigoGdeba;

        return $this;
    }

    public function getTipoDependenciaId(): ?int
    {
        return $this->tipoDependenciaId;
    }

    public function setTipoDependenciaId(?int $tipoDependenciaId): static
    {
        $this->tipoDependenciaId = $tipoDependenciaId;

        return $this;
    }

    public function getTipoDelegacion(): ?string
    {
        return $this->tipoDelegacion;
    }

    public function setTipoDelegacion(?string $tipoDelegacion): static
    {
        $this->tipoDelegacion = $tipoDelegacion;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getClase(): ?string
    {
        return $this->clase;
    }

    public function setClase(?string $clase): static
    {
        $this->clase = $clase;

        return $this;
    }

    public function getPartidoId(): ?int
    {
        return $this->partidoId;
    }

    public function setPartidoId(?int $partidoId): static
    {
        $this->partidoId = $partidoId;

        return $this;
    }

    public function getFechaVigenciaDesde(): ?\DateTimeInterface
    {
        return $this->fechaVigenciaDesde;
    }

    public function setFechaVigenciaDesde(?\DateTimeInterface $fechaVigenciaDesde): static
    {
        $this->fechaVigenciaDesde = $fechaVigenciaDesde;

        return $this;
    }

    public function getFechaVigenciaHasta(): ?\DateTimeInterface
    {
        return $this->fechaVigenciaHasta;
    }

    public function setFechaVigenciaHasta(?\DateTimeInterface $fechaVigenciaHasta): static
    {
        $this->fechaVigenciaHasta = $fechaVigenciaHasta;

        return $this;
    }

    public function getFechaCarga(): ?\DateTimeInterface
    {
        return $this->fechaCarga;
    }

    public function setFechaCarga(?\DateTimeInterface $fechaCarga): static
    {
        $this->fechaCarga = $fechaCarga;

        return $this;
    }

    public function getUsuarioId(): ?int
    {
        return $this->usuarioId;
    }

    public function setUsuarioId(?int $usuarioId): static
    {
        $this->usuarioId = $usuarioId;

        return $this;
    }

    public function getEstadoDependenciaId(): ?int
    {
        return $this->estadoDependenciaId;
    }

    public function setEstadoDependenciaId(?int $estadoDependenciaId): static
    {
        $this->estadoDependenciaId = $estadoDependenciaId;

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


    public function isExterna(): ?bool
    {
        return $this->externa;
    }

    public function setExterna(?bool $externa): static
    {
        $this->externa = $externa;

        return $this;
    }

    public function getDependenciaReferencia(): ?InfoDependencia
    {
        return $this->dependenciaReferencia;
    }

    public function setDependenciaReferencia(?InfoDependencia $dependenciaReferencia): static
    {
        $this->dependenciaReferencia = $dependenciaReferencia;

        return $this;
    }
}
