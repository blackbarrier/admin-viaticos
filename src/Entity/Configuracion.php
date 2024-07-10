<?php

namespace App\Entity;

use App\Repository\ConfiguracionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfiguracionRepository::class)]
class Configuracion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $valor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $leyenda = null;

    #[ORM\Column( nullable: true)]
    private ?bool $borrado = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getValor(): ?string
    {
        return $this->valor;
    }

    public function setValor(?string $valor): static
    {
        $this->valor = $valor;

        return $this;
    }

    public function getLeyenda(): ?string
    {
        return $this->leyenda;
    }

    public function setLeyenda(?string $leyenda): static
    {
        $this->leyenda = $leyenda;

        return $this;
    }

    public function isBorrado(): ?bool
    {
        return $this->borrado;
    }

    public function setBorrado(bool $borrado): static
    {
        $this->borrado = $borrado;

        return $this;
    }
}
