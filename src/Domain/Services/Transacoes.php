<?php

class Transacoes
{
    private $banco;
    /**
     * @var DateTime
     */
    private $periodoInicial;
    /**
     * @var DateTime
     */
    private $periodoFinal;
    /**
     * @var Transacao[]
     */
    private $movimentacoes;

    /**
     * Transacoes constructor.
     * @param $banco
     * @throws Exception
     */
    public function __construct($banco)
    {
        $this->banco = $banco;
        $this->periodoFinal = (new DateTime());
        $this->movimentacoes = [];
        $this->periodoInicial = (new DateTime());
    }

    /**
     * @return mixed
     */
    public function banco()
    {
        return $this->banco;
    }

    /**
     * @return DateTime
     */
    public function periodoInicial()
    {
        array_map(function (Transacao $transacao) {

            if ($transacao->data()->menorQue($this->periodoInicial)) {
                $this->periodoInicial = $transacao->data();
            }
        }, $this->movimentacoes());

        return $this->periodoInicial;
    }

    /**
     * @return DateTime
     */
    public function periodoFinal()
    {
        $this->periodoFinal = $this->periodoInicial();
        array_map(function (Transacao $transacao) {
            if (!$this->dataMenorQue($transacao->data(), $this->periodoFinal)) {
                $this->periodoFinal = $transacao->data();
            }
        }, $this->movimentacoes());

        return $this->periodoFinal;
    }

    public function adicionaMovimentacao(Transacao $transacao)
    {
        $this->movimentacoes[] = $transacao;
        return $this;
    }

    /**
     * @return Transacao[]
     */
    public function movimentacoes()
    {
        return $this->movimentacoes;
    }

    public function criaChavesParaAComparacao()
    {
        $novoArray = [];
        foreach ($this->movimentacoes() as $movimentacao) {
            $key = trim($this->banco() . $movimentacao->tipo() . $movimentacao->data()->format('d/m/Y') . $movimentacao->valor()->toString() . $movimentacao->transacaoId() . $movimentacao->protocolo() . $movimentacao->descricao() . $movimentacao->referenciaId());
            $novoArray[$key] = $movimentacao;
        }

        return $novoArray;
    }

    public function retornaTransacoesQueNaoEstaoSalvasNoBanco(Transacoes $transacoesSalvasNoBanco)
    {
        $transacoesDoArquivo = $this->criaChavesParaAComparacao();
        $transacoesDoBanco = $transacoesSalvasNoBanco->criaChavesParaAComparacao();

        $transasoesQueNaoEstaoSalvas = array_filter($transacoesDoArquivo, function ($key) use ($transacoesDoBanco, $transacoesDoArquivo) {
            return !isset($transacoesDoBanco[$key]);

        }, ARRAY_FILTER_USE_KEY);

        return $transasoesQueNaoEstaoSalvas;
    }

    public function creditos()
    {
        $novoArray = [];

        $dados = array_filter($this->toArray(), function ($array) {
            return (in_array($array['tipo'], ['CREDIT']) && !$array['conciliacao']);
        });


        foreach ($dados as $movimentacao) {
            $key = trim($movimentacao['data'] . $movimentacao['valor']);
            $novoArray[$key] = $movimentacao;
        }

        return $novoArray;

    }

    public function debitos()
    {
        $novoArray = [];

        $dados = array_filter($this->toArray(), function ($array) {
            return (
                !$array['conciliacao'] &&
                in_array($array['tipo'], ['XFER', 'DEBIT']) &&
                !in_array($array['descricao'], ['DÉBITO SERVIÇO COBRANÇA', 'BB CP AUTOMATICO']));
        });


        foreach ($dados as $movimentacao) {
            $key = trim($movimentacao['data'] . $movimentacao['valor']);
            $novoArray[$key] = $movimentacao;
        }

        return $novoArray;
    }

    public function taxas()
    {
        return array_filter($this->toArray(), function ($array) {
            return (
                !$array['conciliacao'] &&
                in_array($array['descricao'], ['DÉBITO SERVIÇO COBRANÇA', 'BB CP AUTOMATICO']));
        });

    }

    private function toArray()
    {
        return array_map(function (Transacao $transacao) {
            return $transacao->toArray();
        }, $this->movimentacoes());
    }

    private function dataMenorQue(DateTime $dataAtual, DateTime $novaData)
    {
        return $dataAtual < $novaData;
    }
}
