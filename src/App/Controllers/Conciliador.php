<?php


class Conciliador
{
    private $arquivo;

    /**
     * Conciliador constructor.
     */
    public function __construct($arquivo)
    {
        $this->arquivo = $arquivo;
    }

    /**
     * @param $tipoBanco
     * @throws Exception
     */
    public function importaExtratoBancario($tipoBanco)
    {
        $this->transacoes = Arquivos::obterTransacoesDoArquivo($this->arquivo, $tipoBanco);

        $this->verificaLancamentosJaImportados()
            ->adicionarNovasMovimentacoesNoBanco();

        //todo:Criar conciliacao

    }

    private function verificaLancamentosJaImportados()
    {
        //....
        return $this;
    }

    private function adicionarNovasMovimentacoesNoBanco()
    {
        //...
        return $this;
    }

}
