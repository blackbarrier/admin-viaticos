<?php

namespace App\Entity;

use App\Repository\EstadoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstadoRepository::class)]
class Estado
{

    public const SOLICITADO = 1;
    public const RENDIDO = 2;
    public const APROBADO = 3;
    public const CADUCADO = 4;
    
    /**
     * Colores [background, foreground, label]
     * 
     * 
     * #f7fcfd #6e016b SOLICITADO
     * #e0ecf4 #6e016b RENDIDO
     * #bfd3e6
     * #9ebcda
     * #8c96c6
     * #8c6bb1 #f7fcfd APROBADO
     * #88419d  
     * #6e016b #f7fcfd CADUCADO 
     */

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(nullable: true)]
    private ?bool $publico = null;

    #[ORM\Column(length: 255)]
    private ?string $short = null;

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

    public function isPublico(): ?bool
    {
        return $this->publico;
    }

    public function setPublico(?bool $publico): static
    {
        $this->publico = $publico;

        return $this;
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function setShort(string $short): static
    {
        $this->short = $short;

        return $this;
    }
}
