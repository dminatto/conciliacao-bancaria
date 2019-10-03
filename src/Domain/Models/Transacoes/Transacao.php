<?php

class Transacao
{
    private $data;
    private $tipo;
    /**
     * @var Valor
     */
    private $valor;
    private $codigo;
    private $protocolo;
    private $descricao;
    private $conciliado;
    private $transacaoId;
    private $referenciaId;

    /**
     * Transacao constructor.
     * @param $tipo
     * @param DateTime $data
     * @param $valor
     * @param $transacaoId
     * @param $protocolo
     * @param $descricao
     * @param $referenciaId
     */
    public function __construct($tipo, DateTime $data, $valor, $transacaoId, $protocolo, $descricao, $referenciaId = null)
    {
        $this->tipo = $tipo;
        $this->data = $data;
        $this->valor = $valor;
        $this->transacaoId = $transacaoId;
        $this->protocolo = $protocolo;
        $this->descricao = $descricao;
        $this->referenciaId = $referenciaId;
        $this->conciliado = false;
    }

    /**
     * @return string
     */
    public function tipo()
    {
        return $this->tipo;
    }

    /**
     * @return DateTime
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return Valor
     */
    public function valor()
    {
        return $this->valor;
    }

    /**
     * @return string
     */
    public function transacaoId()
    {
        return $this->transacaoId;
    }

    /**
     * @return string
     */
    public function protocolo()
    {
        return $this->protocolo;
    }

    /**
     * @return string
     */
    public function descricao()
    {
        return $this->descricao;
    }

    /**
     * @return string|null
     */
    public function referenciaId()
    {
        return $this->referenciaId;
    }

    /**
     * @return bool
     */
    public function isConciliado()
    {
        return $this->conciliado;
    }

    /**
     * @param $conciliado
     */
    public function setConciliado($conciliado)
    {
        $this->conciliado = ($conciliado == 0 ? false : true);
    }

    /**
     * @return mixed
     */
    public function codigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function toArray()
    {
        return [
            'data' => $this->data()->format('d/m/Y'),
            'tipo' => $this->tipo(),
            'valor' => $this->valor()->modulo(),
            'codigo' => $this->codigo(),
            'protocolo' => $this->protocolo(),
            'descricao' => $this->descricao(),
            'conciliacao' => $this->isConciliado(),
            'transacaoId' => $this->transacaoId(),
            'referencia' => $this->referenciaId()

        ];
    }
}
