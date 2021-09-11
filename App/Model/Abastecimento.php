<?php

namespace App\Model;

class Abastecimento
{
    /**
     * Retorna um array com os campos do cadastro de Abastecimentos
     */
    public static function getArray()
    {
        return array(
            'abaID'          => 0,
            'abaUsuID'       => $_SESSION['usuID'], 
            'abaPlaca'       => '',
            'abaDataHora'    => date('Y/m/d H:i:s'),
            'abaCombustivel' => 0,
            'abaQuantidade'  => 0.00,
            'abaValor'       => 0.00,
            'abaKm'          => 0,
            'abaPagamento'   => 0,
            'abaObservacao'  => ''
        );
    }

    public static function gravar(array $abastecimento)
    {
        $sql = 'INSERT INTO abastecimentos_tb (' .
                'abaUsuID, abaPlaca, abaDataHora, abaCombustivel, abaQuantidade, ' . 
                'abaValor, abaKm, abaPagamento, abaObservacao) ' .
                'VALUES (:abaUsuID, :abaPlaca, :abaDataHora, :abaCombustivel, ' . 
                ':abaQuantidade, :abaValor, :abaKm, :abaPagamento, :abaObservacao)';
        $conn = Conexao::getConexao()->prepare($sql);
        return $conn->execute(array(
            'abaUsuID'       => $abastecimento['abaUsuID'],
            'abaPlaca'       => strtoupper($abastecimento['abaPlaca']),
            'abaDataHora'    => $abastecimento['abaDataHora'],
            'abaCombustivel' => $abastecimento['abaCombustivel'],
            'abaQuantidade'  => $abastecimento['abaQuantidade'],
            'abaValor'       => $abastecimento['abaValor'],
            'abaKm'          => $abastecimento['abaKm'],
            'abaPagamento'   => $abastecimento['abaPagamento'],
            'abaObservacao'  => strtoupper($abastecimento['abaObservacao'])
        ));
    }

    public static function atualizar(array $abastecimento)
    {
        $sql = 'UPDATE abastecimentos_tb SET ' .
                'abaUsuID = :abaUsuID, abaPlaca = :abaPlaca, ' . 
                'abaDataHora = :abaDataHora, abaCombustivel = :abaCombustivel, ' .
                'abaQuantidade = :abaQuantidade, abaValor = :abaValor, ' . 
                'abaKm = :abaKm, abaPagamento = :abaPagamento, ' . 
                'abaObservacao = :abaObservacao ' .
                'WHERE abaID = :abaID';
        $conn = Conexao::getConexao()->prepare($sql);
        return $conn->execute(array(
            'abaUsuID'       => $abastecimento['abaUsuID'],
            'abaPlaca'       => strtoupper($abastecimento['abaPlaca']),
            'abaDataHora'    => $abastecimento['abaDataHora'],
            'abaCombustivel' => $abastecimento['abaCombustivel'],
            'abaQuantidade'  => $abastecimento['abaQuantidade'],
            'abaValor'       => $abastecimento['abaValor'],
            'abaKm'          => $abastecimento['abaKm'],
            'abaPagamento'   => $abastecimento['abaPagamento'],
            'abaObservacao'  => strtoupper($abastecimento['abaObservacao']),
            'abaID'          => $abastecimento['abaID']
        ));
    }

    public static function carregarAbastecimentoUnico(int $abaID)
    {
        $sql = 'SELECT * FROM abastecimentos_tb WHERE abaID = :abaID';
        $conn = Conexao::getConexao()->prepare($sql);
        $conn->bindValue('abaID', $abaID, \PDO::PARAM_INT);
        $conn->execute();
        $result = $conn->fetchAll();

        return $result[0];
    }

    public static function abastecimentoUsuario(int $abaID)
    {
        if ($abaID == 0){
            return false;
        }

        $result = self::carregarAbastecimentoUnico($abaID);

        if (empty($result)){
            return false;
        }

        return (int)$result['abaUsuID'] == (int)$_SESSION['usuID'];
    }

    public static function ultimos(int $quantidade = 0)
    {
        $sql = 'SELECT a.abaID, a.abaPlaca, v.veiMarca, v.veiModelo, a.abaDataHora, ' . 
               'a.abaKm, a.abaCombustivel, a.abaValor ' . 
               'FROM abastecimentos_tb a ' . 
               'LEFT JOIN veiculos_tb v ON a.abaPlaca = v.veiPlaca ' .
               'WHERE a.abaUsuID = :abaUsuID ' .
               'ORDER BY abaDataHora DESC ';

        if ($quantidade > 0){
            $sql .= 'LIMIT ' . $quantidade;
        }

        $conn = Conexao::getConexao()->prepare($sql);
        $conn->execute(array('abaUsuID' => $_SESSION['usuID']));
        return $conn->fetchAll();
    }
}