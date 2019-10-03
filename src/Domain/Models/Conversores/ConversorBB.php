<?php


class ConversorBB extends Conversor
{
    /** LAYOUT DE RETORNO - BB
     *'TRNTYPE' => Tipo da Transação
     *'DTPOSTED' => Data da Transação
     *'TRNAMT' => Valor da Transação
     *'FITID' => Código único da transação
     *'CHECKNUM' => Protocolo da transação
     *'MEMO' => Descrição da transação
     * @throws Exception
     */
    public function converteConjuntoDeDados()
    {
        $documento = self::fechaTagsDoArquivoBB($this->arquivo);
        $movimentacoes = (new OFX($documento))->getTransactions();
        $transacoes = new Transacoes($this->banco);

        foreach($movimentacoes as $movimentacao)
        {
            $transacoes->adicionaMovimentacao(new Transacao($movimentacao->TRNTYPE, DateTime::createFromFormat('Ymd', $movimentacao->DTPOSTED), $movimentacao->TRNAMT, $movimentacao->FITID, $movimentacao->CHECKNUM, $movimentacao->MEMO));
        }

        return $transacoes;

    }

    private static function fechaTagsDoArquivoBB($arquivo)
    {
        $linhas = explode("\n", $arquivo);

        $novoArquivo = array_map(function ($linha) {
            $linha = trim($linha);

            $conteudoDepoisDaTag = strpos($linha, '>');

            if (is_int($conteudoDepoisDaTag)) {
                $conteudoDepoisDaTag = substr($linha, $conteudoDepoisDaTag + strlen('>'));
            }

            if (strlen(trim($conteudoDepoisDaTag)) > 0) {
                $openTag = substr($linha, 0, strpos($linha, '>') + 1);
                $endTag = str_replace('<', '</', $openTag);

                if (!strpos($linha, $endTag)) {
                    $linha .= $endTag;
                }
            }

            return $linha . "\n";

        }, $linhas);

        return implode("", $novoArquivo);
    }
}
