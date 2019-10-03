<?php

abstract class Conversor
{
    protected $banco;
    protected $arquivo;

    /**
     * Conversor constructor.
     * @param $banco
     * @param $url
     */
    public function __construct($banco, $url)
    {
        $this->banco = $banco;
        $this->arquivo = file_get_contents($url);
    }

    abstract public function converteConjuntoDeDados();
}
