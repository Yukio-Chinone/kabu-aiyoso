<?php

use \Phalcon\Http\Request;

class ServiceController extends ControllerBase
{
    /**
     * 仕様
     */
    public function specAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("description", "");
        $this->view->setVar("title", "株価予想の仕様 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * 利用規約
     */
    public function termsAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("description", "");
        $this->view->setVar("title", "利用規約 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * 個人情報保護方針
     */
    public function privacyAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("description", "");
        $this->view->setVar("title", "個人情報保護方針 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * よくある質問
     */
    public function faqAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("description", "");
        $this->view->setVar("title", "よくある質問 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * 特定商取引法
     */
    public function legalAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("description", "");
        $this->view->setVar("title", "特定商取引法に基づく表記 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * 有料販売
     */
    public function saleAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("description", "");
        $this->view->setVar("title", "プレミアム検索機能 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * 投資実績
     */
    public function resultAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("description", "");
        $this->view->setVar("title", "投資実績：AI（人工知能）の指示通りに株を買った結果 | AIの株価予想【人工知能x株式投資ソフト】");
    }
}