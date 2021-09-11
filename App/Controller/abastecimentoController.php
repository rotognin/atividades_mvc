<?php

namespace App\Controller;

use App\Model;

class abastecimentoController extends Controller
{
    public static function cadAbastecimentoAction(array $post, array $get)
    {
        $abaID = (isset($post['abaID'])) ? $post['abaID'] : 0;

        // A ser adicionado: verificar se o ID de abastecimento pertence ao
        // usuário logado, e ver uma forma também de saber se não foi alterado o ID
        // mexendo no HTML.
        if ($abaID > 0){
            if (!Model\Abastecimento::abastecimentoUsuario($abaID)){
                $_SESSION['mensagem'] = 'Abastecimento não realizado pelo usuário.';
                parent::viewAction('menu');
                return;
            }
        }

        $_SESSION['abaID'] = $abaID;
        parent::viewAction('cadAbastecimento');
    }

    private static function preencherArray(array $post)
    {
        $abastecimento = Model\Abastecimento::getArray();
        $abastecimento['abaID']          = $post['abaID'];
        $abastecimento['abaDataHora']    = $post['abaData'] . ' ' . $post['abaHora'] . ':00';
        $abastecimento['abaPlaca']       = $post['abaPlaca'];
        $abastecimento['abaCombustivel'] = $post['abaCombustivel'];
        $abastecimento['abaQuantidade']  = $post['abaQuantidade'];
        $abastecimento['abaValor']       = $post['abaValor'];
        $abastecimento['abaKm']          = $post['abaKm'];
        $abastecimento['abaPagamento']   = $post['abaPagamento'];
        $abastecimento['abaObservacao']  = $post['abaObservacao'];

        return $abastecimento;
    }

    public static function gravarAbastecimentoAction(array $post, array $get)
    {
        $abastecimento = self::preencherArray($post);

        if (Model\Abastecimento::gravar($abastecimento)) {
            parent::viewAction('menu');
        } else {
            $_SESSION['mensagem'] = 'Abastecimento não gravado.';
            parent::viewAction('cadAbastecimento');
        }
    }

    public static function atualizarAbastecimentoAction(array $post, array $get)
    {
        $abastecimento = self::preencherArray($post);

        if (Model\Abastecimento::atualizar($abastecimento)) {
            parent::viewAction('menu');
        } else {
            $_SESSION['mensagem'] = 'Abastecimento não atualizado.';
            parent::viewAction('cadAbastecimento');
        }
    }
}