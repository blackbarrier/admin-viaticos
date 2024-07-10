<?php

namespace App\Entity;

use App\Repository\AgenteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgenteRepository::class)]
class Agente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $apellido = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $legajo = null;

    #[ORM\Column]
    private ?int $numeroDocumento = null;

    #[ORM\ManyToOne]
    private ?Genero $genero = null;

    #[ORM\Column]
    private ?int $cuil = null;

    #[ORM\Column]
    private ?int $categoria = null;

    #[ORM\Column]
    private ?int $externo = null;

    #[ORM\ManyToOne]
    private ?InfoDependencia $dependencia = null;

    #[ORM\ManyToOne]
    private ?InfoReparticion $reparticion = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroCuenta = null;

    #[ORM\Column(length: 255)]
    private ?string $sucursal = null;

    #[ORM\Column]
    private ?int $digito = null;

    #[ORM\Column(length: 255)]
    private ?string $cbu1 = null;

    #[ORM\Column(length: 255)]
    private ?string $cbu2 = null;

    #[ORM\Column(length: 255)]
    private ?string $tag = null;

    #[ORM\Column]
    private ?int $activo = null;

    #[ORM\Column]
    private ?int $borrado = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaAlta = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaAct = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
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

    public function getLegajo(): ?string
    {
        return $this->legajo;
    }

    public function setLegajo(string $legajo): static
    {
        $this->legajo = $legajo;

        return $this;
    }

    public function getNumeroDocumento(): ?int
    {
        return $this->numeroDocumento;
    }

    public function setNumeroDocumento(int $numeroDocumento): static
    {
        $this->numeroDocumento = $numeroDocumento;

        return $this;
    }
    public function getGenero(): ?Genero
    {
        return $this->genero;
    }

    public function setGenero(?Genero $genero): static
    {
        $this->genero = $genero;

        return $this;
    }
    public function getCuil(): ?int
    {
        return $this->cuil;
    }

    public function setCuil(int $cuil): static
    {
        $this->cuil = $cuil;

        return $this;
    }

    public function getCategoria(): ?int
    {
        return $this->categoria;
    }

    public function setCategoria(int $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getExterno(): ?int
    {
        return $this->externo;
    }

    public function setExterno(int $externo): static
    {
        $this->externo = $externo;

        return $this;
    }

    public function getDependencia(): ?InfoDependencia
    {
        return $this->dependencia;
    }

    public function setDependencia(InfoDependencia $dependencia): static
    {
        $this->dependencia = $dependencia;

        return $this;
    }

    public function getReparticion(): ?InfoReparticion
    {
        return $this->reparticion;
    }

    public function setReparticion(InfoReparticion $reparticion): static
    {
        $this->reparticion = $reparticion;

        return $this;
    }

    public function getNumeroCuenta(): ?string
    {
        return $this->numeroCuenta;
    }

    public function setNumeroCuenta(string $numeroCuenta): static
    {
        $this->numeroCuenta = $numeroCuenta;

        return $this;
    }

    public function getSucursal(): ?string
    {
        return $this->sucursal;
    }

    public function setSucursal(string $sucursal): static
    {
        $this->sucursal = $sucursal;

        return $this;
    }

    public function getDigito(): ?int
    {
        return $this->digito;
    }

    public function setDigito(int $digito): static
    {
        $this->digito = $digito;

        return $this;
    }

    public function getCbu1(): ?string
    {
        return $this->cbu1;
    }

    public function setCbu1(string $Cbu1): static
    {
        $this->cbu1 = $Cbu1;

        return $this;
    }

    public function getCbu2(): ?string
    {
        return $this->cbu2;
    }

    public function setCbu2(string $cbu2): static
    {
        $this->cbu2 = $cbu2;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    public function getActivo(): ?int
    {
        return $this->activo;
    }

    public function setActivo(int $activo): static
    {
        $this->activo = $activo;

        return $this;
    }

    public function getBorrado(): ?int
    {
        return $this->borrado;
    }

    public function setBorrado(int $borrado): static
    {
        $this->borrado = $borrado;

        return $this;
    }

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(\DateTimeInterface $fechaAlta): static
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    public function getFechaAct(): ?\DateTimeInterface
    {
        return $this->fechaAct;
    }

    public function setFechaAct(\DateTimeInterface $fechaAct): static
    {
        $this->fechaAct = $fechaAct;

        return $this;
    }

    
}
