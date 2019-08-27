<?php

class BillPayment extends \Commerce\Payments\Payment implements \Commerce\Interfaces\Payment
{
    public function __construct($modx, array $params = [])
    {
        parent::__construct($modx, $params);
    }

    public function init()
    {
        return [
            'code'  => 'bill',
            'title' => 'Bill',
        ];
    }

    public function getMarkup()
    {
        if (!class_exists('\Mpdf\Mpdf')) {
            return '<p style="color: red;">Mpdf не установлен! Выполните в консоли<br><code>composer require mpdf/mpdf</code></p>';
        }

        return '';
    }

    public function getPaymentMarkup()
    {
        $order = $this->modx->commerce->loadProcessor()->getOrder();

        $docid = $this->modx->commerce->getSetting('payment_success_page_id', $this->modx->getConfig('site_start'));
        $url   = $this->modx->makeUrl($docid);

        $out = ci()->tpl->parseChunk($this->getTemplate('control'), [
            'hash'         => $order['hash'],
            'success_page' => $url,
        ]);

        ci()->flash->set('last_order_id', $order['id']);

        return $out;
    }

    public function printBill($order_hash)
    {
        $path = $this->getSetting('path', 'assets/files/bills');
        $out   = '';
        $db    = ci()->db;
        $order = $db->getRow($db->select('*', $this->modx->getFullTableName('commerce_orders'), "`hash` = '" . $db->escape($order_hash) . "'"));

        if (!empty($order)) {
            ci()->flash->set('last_order_id', $order['id']);

            if (!file_exists(MODX_BASE_PATH . $path)) {
                $this->createPath($path);
            }

            $filename = $order['id'] . '.pdf';
            $filepath = MODX_BASE_PATH . $path . '/' . $filename;

            if (!file_exists($filepath)) {
                $processor = $this->modx->commerce->loadProcessor();
                $order     = $processor->loadOrder($order['id']);
                $cart      = $processor->getCart();
                $total     = $cart->getTotal();

                $out = ci()->tpl->parseChunk($this->getTemplate('bill'), [
                    'order'     => $order,
                    'settings'  => $this->settings,
                    'date'      => (new \DateTime($order['created_at']))->format('d.m.Y') . ' г.',
                    'total_fmt' => ci()->currency->format($total),
                    'total_ext' => $this->num2str($total),
                    'products'  => $this->modx->runSnippet('Cart', [
                        'instance'        => 'order',
                        'tpl'             => $this->getTemplate('bill_products_row'),
                        'subtotalsRowTpl' => $this->getTemplate('bill_subtotals_row'),
                        'ownerTPL'        => '@CODE:[+dl.wrap+][+subtotals+]',
                    ]),
                ]);

                $mpdf = new \Mpdf\Mpdf([
                    'tempDir'      => MODX_BASE_PATH . 'assets/cache',
                    'default_font' => 'FreeSans',
                ]);

                $mpdf->WriteHTML($out);
                $mpdf->Output($filepath, \Mpdf\Output\Destination::FILE);
            }

            header('Content-type: application/pdf');
            readfile($filepath);
        }

        return $out;
    }

    private function createPath($path)
    {
        $parts = explode('/', trim($path, '/'));
        $path = MODX_BASE_PATH;

        do {
            $path .= array_shift($parts) . '/';

            if (!file_exists($path)) {
                mkdir($path);
            }
        } while (!empty($parts));
    }

    private function getTemplate($name)
    {
        $chunk = $this->getSetting('tpl_' . $name);

        if (!empty($chunk)) {
            return $chunk;
        }

        $filename = realpath(MODX_BASE_PATH . 'assets/plugins/commercebillpayment/templates/' . $name . '.tpl');

        if ($filename && is_readable($filename)) {
            return '@CODE:' . file_get_contents($filename);
        }

        throw new \Exception('Template "' . print_r($name, true) . '" not found!');
    }

    private function num2str($num)
    {
        $nul = 'ноль';
        $ten = array(
            array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
            array('', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
        );
        $a20     = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать');
        $tens    = array(2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто');
        $hundred = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
        $unit    = array( // Units
            array('копейка', 'копейки', 'копеек', 1),
            array('рубль', 'рубля', 'рублей', 0),
            array('тысяча', 'тысячи', 'тысяч', 1),
            array('миллион', 'миллиона', 'миллионов', 0),
            array('миллиард', 'милиарда', 'миллиардов', 0),
        );
        //
        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
        $out             = array();
        if (intval($rub) > 0) {
            foreach (str_split($rub, 3) as $uk => $v) {
                // by 3 symbols
                if (!intval($v)) {
                    continue;
                }

                $uk                 = sizeof($unit) - $uk - 1; // unit key
                $gender             = $unit[$uk][3];
                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
                // mega-logic
                $out[] = $hundred[$i1]; # 1xx-9xx
                if ($i2 > 1) {
                    $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3];
                }
                # 20-99
                else {
                    $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3];
                }
                # 10-19 | 1-9
                // units without rub & kop
                if ($uk > 1) {
                    $out[] = $this->morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
                }

            } //foreach
        } else {
            $out[] = $nul;
        }

        $out[] = $this->morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
        $out[] = $kop . ' ' . $this->morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
        return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
    }

    /**
     * Склоняем словоформу
     * @ author runcore
     */
    private function morph($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;
        if ($n > 10 && $n < 20) {
            return $f5;
        }

        $n = $n % 10;
        if ($n > 1 && $n < 5) {
            return $f2;
        }

        if ($n == 1) {
            return $f1;
        }

        return $f5;
    }
}
