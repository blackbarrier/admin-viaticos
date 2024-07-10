<?php

namespace App\Entity;

use App\Repository\ActividadRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActividadRepository::class)]
class Actividad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Estado::class)]
    #[ORM\JoinColumn(name: 'estado_origen_id', referencedColumnName: 'id', nullable: true)]
    private ?Estado $estadoOrigen = null;

    #[ORM\ManyToOne(targetEntity: Estado::class)]
    #[ORM\JoinColumn(name: 'estado_destino_id', referencedColumnName: 'id', nullable: true)]
    private ?Estado $estadoDestino = null;


    #[ORM\ManyToOne]
    private ?TipoActividad $tipo = null;

    #[ORM\Column(length: 255)]
    private ?string $controller = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(length: 255)]
    private ?string $accion = null;

    #[ORM\Column(nullable: true)]
    private ?array $detalles = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tags = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?TipoActividad
    {
        return $this->tipo;
    }

    public function setTipo(?TipoActividad $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getEstadoOrigen(): ?Estado
    {
        return $this->estadoOrigen;
    }

    public function setEstadoOrigen(?Estado $estado): static
    {
        $this->estadoOrigen = $estado;
        return $this;
    }

    public function getEstadoDestino(): ?Estado
    {
        return $this->estadoDestino;
    }

    public function setEstadoDestino(?Estado $estado): static
    {
        $this->estadoDestino = $estado;
        return $this;
    }

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function setController(string $controller): static
    {
        $this->controller = $controller;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getAccion(): ?string
    {
        return $this->accion;
    }

    public function setAccion(string $accion): static
    {
        $this->accion = $accion;

        return $this;
    }

    public function getDetalles(): ?array
    {
        return $this->detalles;
    }

    public function setDetalles(?array $detalles): static
    {
        $this->detalles = $detalles;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): static
    {
        $this->tags = $tags;

        return $this;
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
}
