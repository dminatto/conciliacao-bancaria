<?php

class OFX
{
    private $arquivoOFX;

    /**
     * OFX constructor.
     * @param $arquivoOFX
     */
    public function __construct($arquivoOFX)
    {
        $this->arquivoOFX = $arquivoOFX;
    }

    private function converteParaXML()
    {
        $content = $this->arquivoOFX;
        $line = strpos($content, "<OFX>");
        $ofx = substr($content, $line - 1);
        $buffer = $ofx;
        $count = 0;

        while ($pos = strpos($buffer, '<')) {
            $count++;
            $pos2 = strpos($buffer, '>');
            $element = substr($buffer, $pos + 1, $pos2 - $pos - 1);
            if (substr($element, 0, 1) == '/') $sla[] = substr($element, 1); else                $als[] = $element;
            $buffer = substr($buffer, $pos2 + 1);
        }

        $adif = array_diff($als, $sla);
        $adif = array_unique($adif);
        $ofxy = $ofx;

        foreach ($adif as $dif) {
            $dpos = 0;
            while ($dpos = strpos($ofxy, $dif, $dpos + 1)) {
                $npos = strpos($ofxy, '<', $dpos + 1);
                $ofxy = substr_replace($ofxy, "</$dif>\n<", $npos, 1);
                $dpos = $npos + strlen($element) + 3;
            }
        }
        $ofxy = str_replace('&', '&', $ofxy);
        return $ofxy;
    }

    public function getTransactions()
    {
        try {
            $content = $this->converteParaXML();
            $xml = new SimpleXMLElement(utf8_encode($content));
            $transactions = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->STMTTRN;
            return $transactions;

        } catch (\Exception $e) {
            die($e);
        }
    }
}
