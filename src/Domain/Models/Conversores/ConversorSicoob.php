<?php

class ConversorSicoob extends Conversor
{
    /** LAYOUT DE RETORNO - SICOB
     *'TRNTYPE' => Tipo da Transação (Débito ou Crédito)
     *'DTPOSTED' => Data da Transação
     *'TRNAMT' => Valores da Transação
     *'FITID' => Código único da transação
     *'CHECKNUM' => Protocolo da transação
     *'REFNUM' => Código de referência da transação
     *'MEMO' => Descrição da transação
     * @throws Exception
     */

    public function converteConjuntoDeDados()
    {
        $movimentacoes = (new OFX($this->arquivo))->getTransactions();
        $transacoes = new Transacoes($this->banco);

        foreach($movimentacoes as $movimentacao)
        {
            $dataExtraida = substr($movimentacao->DTPOSTED, 0, strpos($movimentacao->DTPOSTED, '120000[-3:BRT]'));
            $transacoes->adicionaMovimentacao(new Transacao($movimentacao->TRNTYPE, DateTime::createFromFormat('Ymd', $dataExtraida), $movimentacao->TRNAMT, $movimentacao->FITID, $movimentacao->CHECKNUM, $movimentacao->MEMO, $movimentacao->REFNUM));
        }

        return $transacoes;
    }}
