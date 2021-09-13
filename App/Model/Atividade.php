<?php

namespace App\Model;

class Atividade
{
    /**
     * Retorna um array vazio com os campos da tabela de atividades
     */
    public static function getArray()
    {
        return array(
            'atvID'        => 0,
            'atvUsuID'     => $_SESSION['usuID'],
            'atvNome'      => '',
            'atvDescricao' => '',
            'atvInativo'   => 0,
            'atvStatus'    => 0
        );
    }

    /**
     * Realizar a validação dos dados
     */
    public static function validar(array $atividade)
    {
        if ($atividade['atvNome'] == ''){
            $_SESSION['mensagem'] = 'O nome da atividade deve ser preenchido.';
            return false;
        }

        if ($atividade['atvDescricao'] == ''){
            $_SESSION['mensagem'] = 'A Descrição da atividade deve ser informada.';
            return false;
        }

        return true;
    }

    /**
     * Carrega atividades de um usuário
     */
    public static function carregar(int $usuID, bool $apenasAtivas = false)
    {
        $sql = 'SELECT * FROM atividades_tb WHERE atvUsuID = :atvUsuID ';
        
        if ($apenasAtivas){
            $sql .= ' AND atvInativo = 0 ORDER BY atvStatus DESC';
        } else{
            $sql .= ' ORDER BY atvInativo ASC';
        }
        
        $conn = Conexao::getConexao()->prepare($sql);
        $conn->execute(array('atvUsuID' => $usuID));
        $result = $conn->fetchAll();

        return $result;
    }

    /**
     * Carrega uma atividade específica
     */
    public static function carregarAtividade(int $atvID)
    {
        $sql = 'SELECT * FROM atividades_tb WHERE atvID = :atvID';
        $conn = Conexao::getConexao()->prepare($sql);
        $conn->bindValue('atvID', $atvID, \PDO::PARAM_INT);
        $conn->execute();
        $result = $conn->fetchAll();

        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    /**
     * Verificar se a atividade informada pertence ao usuário logado.
     */
    public static function atividadeUsuario(int $atvID)
    {
        if ($atvID == 0){
            return false;
        }

        $result = self::carregarAtividade($atvID);

        if (empty($result)){
            return false;
        }

        return (int)$result['atvUsuID'] == (int)$_SESSION['usuID'];
    }

    public static function gravar(array $atividade)
    {
        $sql = 'INSERT INTO atividades_tb (' .
                'atvUsuID, atvNome, atvDescricao, atvInativo, atvStatus) ' .
                'VALUES (:atvUsuID, :atvNome, :atvDescricao, :atvInativo, :atvStatus)';

        $conn = Conexao::getConexao()->prepare($sql);
        return $conn->execute(array(
            'atvUsuID'     => $atividade['atvUsuID'],
            'atvNome'      => strtoupper($atividade['atvNome']),
            'atvDescricao' => strtoupper($atividade['atvDescricao']),
            'atvInativo'   => $atividade['atvInativo'],
            'atvStatus'    => $atividade['atvStatus']
        ));
    }

    public static function atualizar(array $atividade)
    {
        $sql = 'UPDATE atividades_tb SET atvNome = :atvNome, ' .
               'atvDescricao = :atvDescricao, atvInativo = :atvInativo, atvStatus = :atvStatus ' .
               'WHERE atvID = :atvID';

        $conn = Conexao::getConexao()->prepare($sql);
        return $conn->execute(array(
            'atvNome'      => strtoupper($atividade['atvNome']),
            'atvDescricao' => strtoupper($atividade['atvDescricao']),
            'atvInativo'   => $atividade['atvInativo'],
            'atvStatus'    => $atividade['atvStatus'],
            'atvID'        => $atividade['atvID']
        ));
    }

    public static function getSituacao(int $atvInativo)
    {
        $situacao = '';

        switch($atvInativo)
        {
            case 0:
                $situacao = 'Ativo';
                break;
            case 1:
                $situacao = 'Inativo';
                break;
        }

        return $situacao;
    }

}