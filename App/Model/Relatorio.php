<?php

namespace App\Model;

class Relatorio
{
    public static function padrao(array $parametros)
    {
        if (empty($parametros)){
            return false;
        }

        $sql = 'SELECT a.abaPlaca, v.veiModelo, a.abaDataHora, a.abaKm, a.abaCombustivel, ' .
               'a.abaQuantidade, a.abaValor, a.abaPagamento ' .
               'FROM abastecimentos_tb a ' .
               'LEFT JOIN veiculos_tb v ON a.abaPlaca = v.veiPlaca ' .
               'WHERE a.abaUsuID = :abaUsuID ';

        if ($parametros['relPlaca'] != 'todas'){
            $sql .= 'AND v.veiPlaca = :veiPlaca ';
        }

        if ($parametros['relCombustivel'] != 'todos'){
            $sql .= 'AND a.abaCombustivel = :abaCombustivel ';
        }

        if ($parametros['relPagamento'] != 'todas'){
            $sql .= 'AND a.abaPagamento = :abaPagamento ';
        }

        $sql .= 'ORDER BY ';

        switch ($parametros['relOrdem'])
        {
            case '1': // Data e Hora
                $sql .= 'a.abaDataHora ';
                break;
            case '2': // Placa
                $sql .= 'a.abaPlaca ';
                break;
            case '3': // Combustível
                $sql .= 'a.abaCombustivel ';
                break;
            case '4': // Forma de Pagamento
                $sql .= 'a.abaPagamento ';
                break;
        }

        if ($parametros['relOrdem'] == '1'){
            $sql .= 'ASC';
        } else {
            $sql .= 'DESC';
        }

        $conn = Conexao::getConexao()->prepare($sql);
        $conn->bindValue('abaUsuID', $parametros['abaUsuID'], \PDO::PARAM_INT);

        if ($parametros['relPlaca'] != 'todas'){
            $conn->bindValue('veiPlaca', $parametros['relPlaca']);
        }

        if ($parametros['relCombustivel'] != 'todos'){
            $conn->bindValue('abaCombustivel', (int)$parametros['relCombustivel'], \PDO::PARAM_INT);
        }

        if ($parametros['relPagamento'] != 'todas'){
            $conn->bindValue('abaPagamento', (int)$parametros['relPagamento'], \PDO::PARAM_INT);
        }

        $conn->execute();
        return $conn->fetchAll();
    }

}