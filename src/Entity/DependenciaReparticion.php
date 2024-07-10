<?php

namespace App\Entity;

use App\Repository\DependenciaReparticionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DependenciaReparticionRepository::class)]
class DependenciaReparticion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Dependencia $dependencia = null;

    #[ORM\ManyToOne]
    private ?Reparticion $reparticion = null;

    #[ORM\Column(nullable: true)]
    private ?int $dep_id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observacion = null;

    #[ORM\Column(nullable: true)]
    private ?int $tipoRecorridoId = null;

    #[ORM\Column(nullable: true)]
    private ?int $nodoId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaCarga = null;

    #[ORM\Column(nullable: true)]
    private ?int $usuario_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $estadoReparticionId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaVigenciaDesde = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaVigenciaHasta = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $diasAtencion = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $horarioAtencion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDependencia(): ?Dependencia
    {
        return $this->dependencia;
    }

    public function setDependencia(?Dependencia $dependencia): static
    {
        $this->dependencia = $dependencia;

        return $this;
    }

    public function getReparticion(): ?Reparticion
    {
        return $this->reparticion;
    }

    public function setReparticion(?Reparticion $reparticion): static
    {
        $this->reparticion = $reparticion;

        return $this;
    }

    public function getDepId(): ?int
    {
        return $this->dep_id;
    }

    public function setDepId(?int $dep_id): static
    {
        $this->dep_id = $dep_id;

        return $this;
    }

    public function getObservacion(): ?string
    {
        return $this->observacion;
    }

    public function setObservacion(?string $observacion): static
    {
        $this->observacion = $observacion;

        return $this;
    }

    public function getTipoRecorridoId(): ?int
    {
        return $this->tipoRecorridoId;
    }

    public function setTipoRecorridoId(?int $tipoRecorridoId): static
    {
        $this->tipoRecorridoId = $tipoRecorridoId;

        return $this;
    }

    public function getNodoId(): ?int
    {
        return $this->nodoId;
    }

    public function setNodoId(?int $nodoId): static
    {
        $this->nodoId = $nodoId;

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
        return $this->usuario_id;
    }

    public function setUsuarioId(?int $usuario_id): static
    {
        $this->usuario_id = $usuario_id;

        return $this;
    }

    public function getEstadoReparticionId(): ?int
    {
        return $this->estadoReparticionId;
    }

    public function setEstadoReparticionId(?int $estadoReparticionId): static
    {
        $this->estadoReparticionId = $estadoReparticionId;

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

    public function getDiasAtencion(): ?string
    {
        return $this->diasAtencion;
    }

    public function setDiasAtencion(?string $diasAtencion): static
    {
        $this->diasAtencion = $diasAtencion;

        return $this;
    }

    public function getHorarioAtencion(): ?string
    {
        return $this->horarioAtencion;
    }

    public function setHorarioAtencion(?string $horarioAtencion): static
    {
        $this->horarioAtencion = $horarioAtencion;

        return $this;
    }
}
