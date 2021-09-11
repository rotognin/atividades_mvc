<?php

/**
 * Utilizando um tutorial em https://www.devmedia.com.br/teste-unitario-com-phpunit/41231
 */

namespace Tests;

use PHPUnit\Framework\TestCase;
use Validacoes\Calculos;

class CalculosTest extends TestCase
{
    public function testValorUnitario()
    {
        $this->assertIsString(Calculos::valorUnitario(2, 2,00));
    }

    /**
     * @dataProvider dpTestUltimoDia
     */
    public function testUltimoDia(int $mes, int $ano, int $dia)
    {
        $this->assertEquals($dia, Calculos::ultimoDia($mes, $ano));
    }

    public function dpTestUltimoDia()
    {
        return array(
            array(1, 2021, 31),
            array(2, 2020, 29),
            array(2, 2024, 29),
            array(10, 2021, 31),
            array(4, 2021, 30)
        );
    }

}
