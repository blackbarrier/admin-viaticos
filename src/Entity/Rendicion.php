<?php

namespace App\Entity;

use App\Repository\RendicionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendicionRepository::class)]
class Rendicion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tipo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column]
    private ?float $porcentaje = null;


    #[ORM\Column(length: 255)]
    private ?string $destino = null;

    #[ORM\ManyToOne(targetEntity: Viatico::class, inversedBy: 'rendiciones')]
    #[ORM\JoinColumn(name: 'viatico_id', referencedColumnName: 'id')]
    private Viatico|null $viatico = null;

    #[ORM\Column(nullable: true)]
    private ?float $modulo = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): static
    {
        $this->tipo = $tipo;

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

    public function getPorcentaje(): ?float
    {
        return $this->porcentaje;
    }

    public function setPorcentaje(float $porcentaje): static
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }


    public function getDestino(): ?string
    {
        return $this->destino;
    }

    public function setDestino(string $destino): static
    {
        $this->destino = $destino;

        return $this;
    }

    public function getViatico(){
        return $this->viatico;
    }
    public function setViatico($viatico){
        $this->viatico = $viatico;
    }

    public function getModulo(): ?float
    {
        return $this->modulo;
    }

    public function setModulo(?float $modulo): static
    {
        $this->modulo = $modulo;

        return $this;
    }   
}
