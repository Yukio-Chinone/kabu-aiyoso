<?php

use \Phalcon\Http\Request;

class InquiryController extends ControllerBase
{
    /**
     * お問い合わせ (入力)
     */
    public function indexAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("title", "お問い合わせ | AIの株価予想【人工知能x株式投資ソフト】");
    }
}