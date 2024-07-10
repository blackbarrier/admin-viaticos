<?php

namespace App\Entity;

use App\Repository\ViaticoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ViaticoRepository::class)]
class Viatico
{


    public const RENDICION_FINAL = FALSE;
    public const ANTICIPO = TRUE;   


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaPedido = null;

    #[ORM\Column(nullable: true)]
    private ?float $modulo = null;

    #[ORM\Column(nullable: true)]
    private ?float $valorMovilidad = null;

    #[ORM\Column(nullable: true)]
    private ?int $dias150 = null;

    #[ORM\Column(nullable: true)]
    private ?int $dias100 = null;

    #[ORM\Column(nullable: true)]
    private ?int $dias70 = null;

    #[ORM\Column(nullable: true)]
    private ?int $dias50 = null;

    #[ORM\Column(nullable: true)]
    private ?int $dias40 = null;

    #[ORM\Column(nullable: true)]
    private ?int $dias20 = null;

    #[ORM\Column(nullable: true)]
    private ?int $diasmov = null;

    #[ORM\Column(nullable: true)]
    private ?float $importe = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agente $agente = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $operador = null;

    #[ORM\Column(length: 255)]
    private ?string $cuil = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ordenPago = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Estado $estado = null;

    #[ORM\Column]
    private ?int $mes = null;

    #[ORM\Column]
    private ?int $anio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reparticion = null;

    #[ORM\OneToMany(targetEntity: Rendicion::class, mappedBy: 'viatico')]
    private Collection $rendiciones;

    #[ORM\Column]
    private ?bool $anticipo = null;

    #[ORM\Column(length: 255)]
    private ?string $dependencia = null;

    #[ORM\ManyToOne]
    private ?InfoReparticion $reparticionReferencia = null;

    #[ORM\ManyToOne]
    private ?InfoDependencia $dependenciaReferencia = null;

    #[ORM\ManyToOne(inversedBy: 'viaticos')]
    private ?Solicitud $solicitud = null;

   
    public function __construct()
    {
        $this->rendiciones = new ArrayCollection();
    }

    public function getAnticipo() 
    {
        return $this->anticipo;
    }

    public function setAnticipo($anticipo)
    {
        $this->anticipo = $anticipo;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImporte(): ?float
    {
        return $this->importe;
    }

    public function setImporte(?float $importe): static
    {
        $this->importe = $importe;

        return $this;
    }

    public function getFechaPedido(): ?\DateTimeInterface
    {
        return $this->fechaPedido;
    }

    public function setFechaPedido(\DateTimeInterface $fechaPedido): static
    {
        $this->fechaPedido = $fechaPedido;

        return $this;
    }


    public function getAgente(): ?Agente
    {
        return $this->agente;
    }

    public function setAgente(?Agente $agente): static
    {
        $this->agente = $agente;

        return $this;
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

    public function getDias150(): ?int
    {
        return $this->dias150;
    }

    public function setDias150(?int $dias150): static
    {
        $this->dias150 = $dias150;

        return $this;
    }

    public function getDias100(): ?int
    {
        return $this->dias100;
    }

    public function setDias100(?int $dias100): static
    {
        $this->dias100 = $dias100;

        return $this;
    }

    public function getDias70(): ?int
    {
        return $this->dias70;
    }

    public function setDias70(?int $dias70): static
    {
        $this->dias70 = $dias70;

        return $this;
    }

    public function getDias50(): ?int
    {
        return $this->dias50;
    }

    public function setDias50(?int $dias50): static
    {
        $this->dias50 = $dias50;

        return $this;
    }

    public function getDias40(): ?int
    {
        return $this->dias40;
    }

    public function setDias40(?int $dias40): static
    {
        $this->dias40 = $dias40;

        return $this;
    }

    public function getDias20(): ?int
    {
        return $this->dias20;
    }

    public function setDias20(?int $dias20): static
    {
        $this->dias20 = $dias20;

        return $this;
    }

    public function getOperador(): ?Usuario
    {
        return $this->operador;
    }

    public function setOperador(?Usuario $operador): static
    {
        $this->operador = $operador;

        return $this;
    }

    public function getValorMovilidad(): ?float
    {
        return $this->valorMovilidad;
    }

    public function setValorMovilidad(?float $valorMovilidad): static
    {
        $this->valorMovilidad = $valorMovilidad;

        return $this;
    }

    public function getDiasmov(): ?int
    {
        return $this->diasmov;
    }

    public function setDiasmov(?int $diasmov): static
    {
        $this->diasmov = $diasmov;

        return $this;
    }

    public function getCuil(): ?string
    {
        return $this->cuil;
    }

    public function setCuil(string $cuil): static
    {
        $this->cuil = $cuil;

        return $this;
    }

    public function getOrdenPago(): ?string
    {
        return $this->ordenPago;
    }

    public function setOrdenPago(?string $ordenPago): static
    {
        $this->ordenPago = $ordenPago;

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

    /**
     * @return Collection<int, Rendicion>
     */
    public function getRendiciones(): Collection
    {
        return $this->rendiciones;
    }

    public function addRendicione(Rendicion $rendicione): static
    {
        if (!$this->rendiciones->contains($rendicione)) {
            $this->rendiciones->add($rendicione);
            $rendicione->setViatico($this);
        }

        return $this;
    }

    public function removeRendicione(Rendicion $rendicione): static
    {
        if ($this->rendiciones->removeElement($rendicione)) {
            // set the owning side to null (unless already changed)
            if ($rendicione->getViatico() === $this) {
                $rendicione->setViatico(null);
            }
        }

        return $this;
    }

    public function getDependencia(): ?string
    {
        return $this->dependencia;
    }

    public function setDependencia(string $dependencia): static
    {
        $this->dependencia = $dependencia;

        return $this;
    }

    public function getReparticionRefencia(): ?InfoReparticion
    {
        return $this->reparticionReferencia;
    }

    public function setReparticionRefencia(?InfoReparticion $reparticionRefencia): static
    {
        $this->reparticionReferencia = $reparticionRefencia;

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

    public function getSolicitud(): ?Solicitud
    {
        return $this->solicitud;
    }

    public function setSolicitud(?Solicitud $solicitud): static
    {
        $this->solicitud = $solicitud;

        return $this;
    }
    
}