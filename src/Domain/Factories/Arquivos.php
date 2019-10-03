<?php

class Arquivos
{
    const ARQUIVO_CAIXA = 1;
    const ARQUIVO_SICOOB = 2;
    const ARQUIVO_BB = 4;

    /**
     * @param $url
     * @param $banco
     * @return Transacoes
     * @throws \Exception
     */
    public static function obterTransacoesDoArquivo($url, $banco)
    {
        switch ($banco) {
            case self::ARQUIVO_CAIXA:
                $conjuntoDeDados = new ConversorCaixa($banco, $url);
                break;
            case self::ARQUIVO_SICOOB:
                $conjuntoDeDados = new ConversorSicoob($banco, $url);
                break;
            case self::ARQUIVO_BB:
                $conjuntoDeDados = new ConversorBB($banco, $url);
                break;
            default:
                throw new Exception('Banco não encontrado', 404);
                break;
        }
        return $conjuntoDeDados->converteConjuntoDeDados();
    }
}
