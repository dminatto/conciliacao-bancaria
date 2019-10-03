<?php

class Valor
{
    private $valor;

    /**
     * Valor constructor.
     * @param $valor
     */
    public function __construct($valor)
    {
        $this->valor = $valor;
    }

    public function modulo()
    {
        return abs($this->valor);
    }

    public function toString()
    {
        return number_format((float)$this->valor, 2, ',', '.');
    }
}
