<?php

namespace App\Model;

class Horario
{
    /**
     * Retorna um array com os campos do cadastro de Horários
     */
    public static function getArray()
    {
        return array(
            'horID'          => 0,
            'horAtvID'       => 0,
            'horDataIni'     => '',
            'horHoraIni'     => '',
            'horDataFim'     => '',
            'horHoraFim'     => ''
        );
    }

    /**
     * Buscar último registro da atividade
     */
    public static function buscarUltimo(int $atvID)
    {
        $sql = 'SELECT * FROM horarios_tb WHERE horAtvID = :horAtvID ' .
               'ORDER BY horID DESC LIMIT 1';
        $conn = Conexao::getConexao()->prepare($sql);
        $conn->bindValue('horAtvID', $atvID, \PDO::PARAM_INT);
        $conn->execute();
        $result = $conn->fetchAll();

        return (empty($result)) ? false : $result[0];
    }

    /**
     * Gravação do início de uma atividade
     */
    public static function gravar(array $horario)
    {

        // Ao mesmo tempo que uma atividade é iniciada, deverá alterar
        // o status dela para aberta
        Atividade::alterarStatus($horario['horAtvID'], 1);

        $sql = 'INSERT INTO horarios_tb (' .
                'horAtvID, horDataIni, horHoraIni) ' .
                'VALUES (:horAtvID, :horDataIni, :horHoraIni)';
        $conn = Conexao::getConexao()->prepare($sql);
        return $conn->execute(array(
            'horAtvID'   => $horario['horAtvID'],
            'horDataIni' => $horario['horDataIni'],
            'horHoraIni' => $horario['horHoraIni'])
        );
    }

    public static function atualizar(array $horario)
    {
        // Quando uma atividade for finalizada, deverá alterar
        // o status dela para fechada
        Atividade::alterarStatus($horario['horAtvID'], 0);

        $sql = 'UPDATE horarios_tb SET ' .
                'horDataFim = :horDataFim, horHoraFim = :horHoraFim ' . 
                'WHERE horID = :horID';
        $conn = Conexao::getConexao()->prepare($sql);
        return $conn->execute(array(
            'horDataFim' => $horario['horDataFim'],
            'horHoraFim' => $horario['horHoraFim'],
            'horID'      => $horario['horID']
        ));
    }

    public static function carregarHorario(int $horID)
    {
        $sql = 'SELECT * FROM horarios_tb WHERE horID = :horID';
        $conn = Conexao::getConexao()->prepare($sql);
        $conn->bindValue('horID', $horID, \PDO::PARAM_INT);
        $conn->execute();
        $result = $conn->fetchAll();

        return $result[0];
    }
}