<?php

namespace App\Entity;

use App\Repository\InfoReparticionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfoReparticionRepository::class)]
class InfoReparticion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $nombre = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $abreviatura = null;

    #[ORM\Column(length: 11, nullable: true)]
    private ?string $cuit = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $rotulo = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nombreBapro = null;

    #[ORM\Column(length: 4, nullable: true)]
    private ?string $sucursal = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $numeroCuenta = null;

    #[ORM\Column(length: 1, nullable: true)]
    private ?string $digito = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $cbu = null;

    #[ORM\Column(nullable: true)]
    private ?int $estadoReparticionId = null;

    #[ORM\ManyToOne]
    private ?Reparticion $reparticionReferencia = null;

    #[ORM\Column(nullable: true)]
    private ?bool $externa = null;

    #[ORM\ManyToMany(targetEntity: InfoDependencia::class)]
    private Collection $dependencias;

    public function __construct()
    {
        $this->plantillas = new ArrayCollection();
        $this->dependencias = new ArrayCollection();
    }

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

    public function getCuit(): ?string
    {
        return $this->cuit;
    }

    public function setCuit(?string $cuit): static
    {
        $this->cuit = $cuit;

        return $this;
    }

    public function getRotulo(): ?string
    {
        return $this->rotulo;
    }

    public function setRotulo(?string $rotulo): static
    {
        $this->rotulo = $rotulo;

        return $this;
    }

    public function getNombreBapro(): ?string
    {
        return $this->nombreBapro;
    }

    public function setNombreBapro(?string $nombre): static
    {
        $this->nombreBapro = $nombre;
        return $this;
    }

    public function getSucursal(): ?string
    {
        return $this->sucursal;
    }

    public function setSucursal(?string $sucursal): static
    {
        $this->sucursal = $sucursal;

        return $this;
    }

    public function getNumeroCuenta(): ?string
    {
        return $this->numeroCuenta;
    }

    public function setNumeroCuenta(?string $numeroCuenta): static
    {
        $this->numeroCuenta = $numeroCuenta;

        return $this;
    }

    public function getDigito(): ?string
    {
        return $this->digito;
    }

    public function setDigito(?string $digito): static
    {
        $this->digito = $digito;

        return $this;
    }

    public function getCbu(): ?string
    {
        return $this->cbu;
    }

    public function setCbu(?string $cbu): static
    {
        $this->cbu = $cbu;

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

    public function getReparticionReferencia(): ?Reparticion
    {
        return $this->reparticionReferencia;
    }

    public function setReparticionReferencia(?Reparticion $reparticionReferencia): static
    {
        $this->reparticionReferencia = $reparticionReferencia;

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

    public function __tostring(){
        $string= "INFO_REPARTICION => id:".$this->getId().",nombre:".$this->getNombre().",abreviatura:".$this->getAbreviatura().",cuit:".$this->getCuit().",cbu:".$this->getCbu();
        $string .=",digito:".$this->getDigito().",numeroCuenta:".$this->getNumeroCuenta().",sucursal:".$this->getSucursal().",rotulo:".$this->getRotulo().",estadoReparticionId:".$this->getEstadoReparticionId();
        $string.=",externa:".$this->isExterna().",reparticionRefId:".($this->getReparticionReferencia())?$this->getReparticionReferencia()->getId():null;
        return $string;
     }

    /**
     * @return Collection<int, InfoDependencia>
     */
    public function getDependencias(): Collection
    {
        return $this->dependencias;
    }

    public function addDependencia(InfoDependencia $dependencia): static
    {
        if (!$this->dependencias->contains($dependencia)) {
            $this->dependencias->add($dependencia);
        }

        return $this;
    }

    public function removeDependencia(InfoDependencia $dependencia): static
    {
        $this->dependencias->removeElement($dependencia);

        return $this;
    }
}
