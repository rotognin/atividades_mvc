<?php

namespace App\Controller;

class relatorioController extends Controller
{
    public static function relPadraoAction()
    {
        parent::viewAction('relPadrao');
    }

    public static function relPadraoListarAction($post, $get)
    {   
        $addGet = serialize($post);
        parent::viewAction('relPadraoListar', $addGet);
    }
}