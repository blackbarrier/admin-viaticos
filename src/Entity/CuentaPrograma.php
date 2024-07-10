<?php

namespace App\Entity;

use App\Entity\InfoReparticion;
use App\Repository\CuentaProgramaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CuentaProgramaRepository::class)]
class CuentaPrograma
{
    
    public const COLORES = array( 
        '#67001f',
        '#b2182b',
        '#d6604d',
        '#f4a582',
        '#fddbc7',
        '#f7f7f7',
        '#d1e5f0',
        '#92c5de',
        '#4393c3',
        '#2166ac',
        '#053061');
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?InfoReparticion $reparticion = null;


    #[ORM\Column(length: 50)]
    private ?string $programa = null;

    #[ORM\Column(length: 50)]
    private ?string $subcodigo = null;

    #[ORM\Column(length: 100)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaInicio = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaFin = null;

    #[ORM\Column(nullable: true)]
    private ?float $cupoMensual = null;

    #[ORM\Column(length: 50)]
    private ?string $codBAPRO = null;

    #[ORM\Column(length: 50)]
    private ?string $nombreArchivoDeposito = null;

    #[ORM\Column(length: 50)]
    private ?string $CBU1 = null;

    #[ORM\Column(length: 50)]
    private ?string $CBU2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReparticion(): ?Reparticion
    {
        return $this->reparticion;
    }

    public function setReparticion(?Reparticion $r): static
    {
        $this->reparticion = $r;
        return $this;
    }
    
    public function getPrograma(): ?string
    {
        return $this->programa;
    }

    public function setPrograma(string $programa): static
    {
        $this->programa = $programa;

        return $this;
    }

    public function getSubcodigo(): ?string
    {
        return $this->subcodigo;
    }

    public function setSubcodigo(string $subcodigo): static
    {
        $this->subcodigo = $subcodigo;

        return $this;
    }


    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTimeInterface $fecha): static
    {
        $this->fechaInicio = $fecha;
        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(\DateTimeInterface $fecha): static
    {
        $this->fechaFin = $fecha;
        return $this;
    }
    
    
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getCupoMensual(): ?float
    {
        return $this->cupoMensual;
    }

    public function setCupoMensual(?float $importe): static
    {
        $this->cupoMensual = $importe;
        return $this;
    }

    public function getCodBAPRO(): ?string
    {
        return $this->codBAPRO;
    }

    public function setCodBAPRO(string $cod): static
    {
        $this->codBAPRO = $cod;
        return $this;
    }

    public function getNombreArchivoDeposito(): ?string
    {
        return $this->nombreArchivoDeposito;
    }

    public function setNombreArchivoDeposito(string $valor): static
    {
        $this->nombreArchivoDeposito = $valor;
        return $this;
    }

    public function getCBU1(): ?string
    {
        return $this->CBU1;
    }

    public function setCBU1(string $valor): static
    {
        $this->CBU1 = $valor;
        return $this;
    }

    public function getCBU2(): ?string
    {
        return $this->CBU2;
    }

    public function setCBU2(string $valor): static
    {
        $this->CBU2 = $valor;
        return $this;
    }


}
