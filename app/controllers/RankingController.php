<?php

use \Phalcon\Http\Request;

class RankingController extends ControllerBase
{
    const BUY = 1;
    const SEL = 2;


    /**
     * お気に入りランキング一覧
     *
     * @param $status 1:買い、2:売り
     */
    public function indexAction($status = 0)
    {
        // ログイン処理
        $this->loginProcess("/user/login");

        // お気に入り取得処理
        $this->favoriteProcess();

        // 入力パラメータ
        $isBuy = ($status == self::BUY) ? true : false;

        $bookmark = new Bookmark();
        $resultOfranking = $bookmark->getRanking($isBuy);
        $this->view->setVar("resultOfranking", $resultOfranking);
        $this->view->setVar("status", $status);
        $this->view->setVar("title", "お気に入りランキング | AIの株価予想【人工知能x株式投資ソフト】");
    }
}