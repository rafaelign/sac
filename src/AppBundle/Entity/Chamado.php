<?php

namespace AppBundle\Entity;

/**
 * Chamado
 */
class Chamado
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $titulo;

    /**
     * @var string
     */
    private $observacao;

    /**
     * @var int
     */
    private $clienteId;

    /**
     * @var int
     */
    private $pedidoId;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Pedido", inversedBy="chamados")
     * @ORM\JoinColumn(name="pedido_numero", referencedColumnName="numero")
     */
    private $pedido;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="chamados")
     * @ORM\JoinColumn(name="clienteId", referencedColumnName="id")
     */
    private $cliente;

    /**
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Chamado
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set observacao
     *
     * @param string $observacao
     *
     * @return Chamado
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;

        return $this;
    }

    /**
     * Get observacao
     *
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * Set clienteId
     *
     * @param integer $clienteId
     *
     * @return Chamado
     */
    public function setClienteId($clienteId)
    {
        $this->clienteId = $clienteId;

        return $this;
    }

    /**
     * Get clienteId
     *
     * @return int
     */
    public function getClienteId()
    {
        return $this->clienteId;
    }

    /**
     * Set pedidoId
     *
     * @param integer $pedidoId
     *
     * @return Chamado
     */
    public function setPedidoId($pedidoId)
    {
        $this->pedidoId = $pedidoId;

        return $this;
    }

    /**
     * Get pedidoId
     *
     * @return int
     */
    public function getPedidoId()
    {
        return $this->pedidoId;
    }
}
