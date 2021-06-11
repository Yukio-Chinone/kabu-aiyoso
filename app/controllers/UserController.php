<?php

use \Phalcon\Http\Request;

class UserController extends ControllerBase
{

    /**
     * プレミアム検索有効化コード
     */
    const PREMIUM_CODE = "kmC7LzQwvsRe";

    /**
     * マイページ
     */
    public function indexAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("title", "マイページ | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * 新規登録（仮登録）
     */
    public function registerAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        if ($this->request->isPost()) {

            // CSRFトークンチェック
            if ($_SESSION["csrf_token"] != $this->request->getPost("csrf_token")) {
                echo "CSRFトークンが無効";
                exit();
            }

            // 入力値を取得
            $username = $this->request->getPost("username");
            $password = $this->request->getPost("password");

            // 2重登録チェック
            $user = new User();
            $result = $user->selectByUsername($username);
            if (count($result) <= 0) {

                // ユーザー新規登録（仮登録）
                $this->db->begin();
                $ret = $user->insert(0, null, null, User::STATIS_PROVISIONAL, $username, $password);
                if ($ret) {
                    $this->db->commit();
                    $userId = $user->getId();
                } else {

                    $this->db->rollback();
                    $this->logwrite("Error: can't register to the table.", "error");
                    $this->session->set(User::SESSION_MESSAGE, "ユーザー登録に失敗しました。");
                    header('Location: /user/message');
                    exit();
                }

                // 仮登録メールの送信
                $to = $username;
                $subject = "仮登録が完了しました - AIの株価予想";
                $body = "仮登録が完了しました\n";
                $body .= "以下URLをクリックして、本登録を完了してください。\n";
                $body .= User::SITE_DOMAIN . "/user/registerConfirm?hash=" . urlencode(User::encrypt($userId)) . "\n";
                $body .= "\n";
                $body .= "- AIの株価予想 -";
                User::sendMail($to, $subject, $body);

                //仮登録完了ページへリダイレクト
                $this->response->redirect('/user/registerProvisional');
                return;

            } else {
                $this->view->setVar("error_message", "メールアドレスは既に利用されています");
            }
        }
        else{

            // デフォルト値
            $username = "";
            $password = "";

            require_once BASE_PATH . '/vendor/autoload.php';
            $snsConfig = $this->di->getConfig()->sns;

            if (strstr($_SERVER['HTTP_HOST'], 'localhost')) {
                // ログインボタンのリンクをviewに渡す (デバッグ用のlocalhost)
                $this->view->setVar("fblink", "/user/callback/local");
            } else {
                // Facebook OAuth ログイン
                $fb = new Facebook\Facebook([
                    'app_id' => $snsConfig->facebook->app_id,
                    'app_secret' => $snsConfig->facebook->app_secret,
                    'default_graph_version' => $snsConfig->facebook->default_graph_version
                ]);
                $helper = $fb->getRedirectLoginHelper();

                $permissions = ['public_profile']; // アクセス権限: public_profile(必須)
                $fblink = $helper->getLoginUrl($snsConfig->facebook->callback_url, $permissions);

                // ログインボタンのリンクをviewに渡す
                $this->view->setVar("fblink", $fblink);
            }
        }

        // CSRFトークンをセット
        $_SESSION["csrf_token"] = rand(11111, 99999);
        $this->view->setVar("csrf_token", $_SESSION["csrf_token"]);

        $this->view->setVar("username", $username);
        $this->view->setVar("password", $password);

        $this->view->setVar("title", "新規登録 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * 仮登録完了
     */
    public function registerProvisionalAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("title", "会員仮登録 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * 本登録
     */
    public function registerConfirmAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        // ハッシュ値からユーザーIDを取得
        $hash = $this->request->getQuery("hash", null, "");
        $userId = User::decrypt($hash);
        if (empty($userId)) {
            echo "URLが無効です";
            exit();
        }

        // ユーザーの状態を判断
        $user = User::findFirst($userId);
        if ($user->getStatus() != User::STATIS_PROVISIONAL) {
            echo "既に本登録済です";
            exit();
        }

        // 本登録完了処理
        $user->setStatus(User::STATUS_ENABLE);
        $user->save();

        // 本登録完了メール
        $to = $user->getUsername();
        $subject = "本登録が完了しました - AIの株価予想";
        $body = "本登録が完了しました\n";
        $body .= "以下URLからログインしてください。\n";
        $body .= User::SITE_DOMAIN . "/user/login" . "\n";
        $body .= "\n";
        $body .= "- AIの株価予想 -";
        User::sendMail($to, $subject, $body);

        //本登録完了ページへリダイレクト
        $this->response->redirect('/user/registerComplete');
        return;
    }

    /**
     * 本登録完了
     */
    public function registerCompleteAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("title", "会員本登録 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * パスワード忘れ
     */
    public function resetAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $usernameError = "";
        $passwordError = "";

        if ($this->request->isPost()) {

            // CSRFトークンチェック
            if ($_SESSION["csrf_token"] != $this->request->getPost("csrf_token")) {
                echo "CSRFトークンが無効";
                exit();
            }

            // 入力値を取得
            $username = $this->request->getPost("username");

            $user = new User();
            $result = $user->selectByUsername($username);
            if (count($result) <= 0) {
                $usernameError = "メールアドレスが見つかりません";
            }
            else{

                // パスワードリセットメール
                $to = $username;
                $subject = "パスワード再設定のご案内 - AIの株価予想";
                $body = "以下のURLをクリックし、パスワード再設定手続きにお進みください。\n";
                $body .= User::SITE_DOMAIN . "/user/passwordReset?hash=" . urlencode(User::encrypt($result[0]->getId()))."\n";
                $body .= "\n";
                $body .= "- AIの株価予想 -";
                User::sendMail($to, $subject, $body);

                //本登録完了ページへリダイレクト
                $this->response->redirect('/user/resetDone');
                return;
            }
        }
        else{
            $username = "";
        }

        // エラーをセット
        $this->view->setVar("usernameError", $usernameError);

        // CSRFトークンをセット
        $_SESSION["csrf_token"] = rand(11111, 99999);
        $this->view->setVar("csrf_token", $_SESSION["csrf_token"]);

        $this->view->setVar("username", $username);
        $this->view->setVar("title", "パスワードをお忘れの方 | AIの株価予想【人工知能x株式投資ソフト】");
    }


    /**
     * パスワード忘れ実行
     *
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function resetDoneAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("title", "パスワードをお忘れの方 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * SNSを使ったログイン画面の表示
     *
     * [参考:facebook for developers]
     * https://developers.facebook.com/docs/php/howto/example_facebook_login
     * https://developers.facebook.com/docs/php/howto/example_retrieve_user_profile
     *
     * [facebook for developers での設定内容]
     * 1. プロダクト > Facebookログイン > 設定
     *   (1) クライアントOAuthログイン（はい）
     *   (2) ウェブOAuthログイン（はい）
     *   (3) ウェブOAuth再認証を強制（いいえ）
     *   (4) リダイレクトURIに制限モードを使用（はい）
     *   (5) 有効なOAuthリダイレクトURI（https://kabu.aiyoso.com/user/callback/facebook）　※コールバックURL
     * 2. 設定 > ベーシック
     *   (1) アプリID（自動）
     *   (2) app secret（自動）
     *   (3) 表示名（任意）
     *   (4) アプリドメイン（kabu.aiyoso.com）
     *   (5) 連絡先メールアドレス（任意）
     *   (6) カテゴリ（ビジネスページ）
     *   (7) ビジネス目的で使用（自分のビジネスをサポート）
     *
     * [facebook アプリ連携解除 (facebookの”一般ページ”ログイン後)]
     * 1. 画面右上の▼ > 設定
     *   (1) アプリとウェブサイト
     *   (2) アクティブなアプリとウェブサイト に にチェックつけて「削除」
     *
     * 【重要】
     * ホーム > 画面右下の「アプリを管理」だと、facebook for developers に行くので注意（ここだとアプリ自体を削除してしまう）
     */
    public function loginAction()
    {
        //ini_set('display_errors', "On");
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $usernameError = "";
        $passwordError = "";

        if ($this->request->isPost() == true) {

            // CSRFトークンチェック
            if ($_SESSION["csrf_token"] != $this->request->getPost("csrf_token")) {
                echo "CSRFトークンが無効";
                exit();
            }

            // 入力値を取得
            $username = $this->request->getPost("username");
            $password = $this->request->getPost("password");

            $user = new User();
            $result = $user->selectByUsername($username);
            if (count($result) <= 0) {
                $usernameError = "メールアドレスが見つかりません";
            }
            else{
                if(md5($password) != $result[0]->getPassword()){
                    $passwordError = "パスワードが違います";
                }
                else{
                    //メールアドレス＆パスワードが一致（ログイン処理）
                    // ユーザ情報をセッションに保存
                    $user->initialize();
                    $user->setUserSession($result[0]->getId(), 0, "", "", $username);

                    $userSession = $user->getUserSession();

                    // ユーザー情報をCookieに保存
                    if (!empty($userSession)) {
                        if (User::AUTO_LOGIN) {
                            $this->cookies->set(User::COOKIE_NAME, json_encode($userSession), time() + User::COOKIE_DAY * 86400);
                            $this->cookies->send();
                        }
                    } else {

                        $this->logwrite("Error: success to register to the table, but can't set the session.", "error");
                        $this->session->set(User::SESSION_MESSAGE, "ユーザーログインに成功しましたが、セッションの保存に失敗しました。");
                        header('Location: /user/message');
                        exit();
                    }

                    $this->view->disable();
                    header('Location: /user/complete');
                    exit();
                }

            }

        }else{

            // 初期値をセット
            $username = "";
            $password = "";

            require_once BASE_PATH . '/vendor/autoload.php';
            $snsConfig = $this->di->getConfig()->sns;

            if (strstr($_SERVER['HTTP_HOST'], 'localhost')) {
                // ログインボタンのリンクをviewに渡す (デバッグ用のlocalhost)
                $this->view->setVar("fblink", "/user/callback/local");
            } else {
                // Facebook OAuth ログイン
                $fb = new Facebook\Facebook([
                    'app_id' => $snsConfig->facebook->app_id,
                    'app_secret' => $snsConfig->facebook->app_secret,
                    'default_graph_version' => $snsConfig->facebook->default_graph_version
                ]);
                $helper = $fb->getRedirectLoginHelper();

                $permissions = ['public_profile']; // アクセス権限: public_profile(必須)
                $fblink = $helper->getLoginUrl($snsConfig->facebook->callback_url, $permissions);

                // ログインボタンのリンクをviewに渡す
                $this->view->setVar("fblink", $fblink);
            }
        }

        // メールログイン用の情報をセット
        $this->view->setVar("username", $username);
        $this->view->setVar("password", $password);

        // エラーをセット
        $this->view->setVar("usernameError", $usernameError);
        $this->view->setVar("passwordError", $passwordError);

        // CSRFトークンをセット
        $_SESSION["csrf_token"] = rand(11111, 99999);
        $this->view->setVar("csrf_token", $_SESSION["csrf_token"]);

        $this->view->setVar("title", "ログイン | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * パスワード再設定
     */
    public function passwordResetAction(){

        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        // ハッシュ値からユーザーIDを取得
        $hash = $this->request->getQuery("hash", null, "");
        $userId = User::decrypt($hash);
        if (empty($userId)) {
            echo "URLが無効です";
            exit();
        }

        // ユーザーの状態を判断
        $user = User::findFirst($userId);
        if(!$user){
            echo "ユーザーが見つかりません";
            exit();
        }

        $password1Error = "";
        $password2Error = "";

        if ($this->request->isPost() == true) {

            // CSRFトークンチェック
            if ($_SESSION["csrf_token"] != $this->request->getPost("csrf_token")) {
                echo "CSRFトークンが無効";
                exit();
            }

            // 入力値を取得
            $password1 = $this->request->getPost("password1");
            $password2 = $this->request->getPost("password2");

            if($password1 != $password2){
                $password2Error = "パスワードが一致しません。";
            }
            else{
                $user->setPassword(md5($password1))->save();

                //完了ページへリダイレクト
                $this->response->redirect('/user/passwordResetComplete');
                return;
            }
        }
        else{
            $password1 = "";
            $password2 = "";
        }

        // パスワード用の情報をセット
        $this->view->setVar("password1", $password1);
        $this->view->setVar("password2", $password2);

        // エラーをセット
        $this->view->setVar("password1Error", $password1Error);
        $this->view->setVar("password2Error", $password2Error);

        // CSRFトークンをセット
        $_SESSION["csrf_token"] = rand(11111, 99999);
        $this->view->setVar("csrf_token", $_SESSION["csrf_token"]);

        $this->view->setVar("title", "パスワード再設定 | AIの株価予想【人工知能x株式投資ソフト】");

    }

    /**
     * パスワード再設定（完了）
     */
    public function passwordResetCompleteAction(){

        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("title", "パスワード再設定 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * SNSからのCallback処理
     *
     * [参考:facebook for developers]
     * https://developers.facebook.com/docs/php/howto/example_facebook_login
     * https://developers.facebook.com/docs/php/howto/example_retrieve_user_profile
     */
    public function callbackAction($snsName)
    {
        if (!session_id()) { // CSRF対策の為に facebookログインではコールバックにセッションを利用する
            session_start();
        }

        require_once BASE_PATH . '/vendor/autoload.php';
        $snsConfig = $this->di->getConfig()->sns;

        if (!isset(User::$snsTypes[$snsName])) {

            $this->logwrite("Error: {$snsName} is not defined.", "error");
            $this->session->set(User::SESSION_MESSAGE, "SNS is not defined.");
            header('Location: /user/message');
            exit();
        }

        $snsType = User::$snsTypes[$snsName];
        $profile = [];

        // SNSの認可処理
        if ($snsType === User::SNS_LOCAL) {

            // Local Login
            $snsType = User::SNS_FACEBOOK; // Facebookログイン扱いにする
            $loginResult = $this->localLogin();

        } else if ($snsType === User::SNS_FACEBOOK) {

            // Facebook Login
            $loginResult = $this->facebookLogin();
        } else {

            // not applicable (N/A)
            $loginResult = [
                'error' => 'not found login target.'
            ];
        }

        if (!empty($loginResult['error'])) {

            $this->logwrite("Error: " . $loginResult['error'], "error");
            $this->session->set(User::SESSION_MESSAGE, "SNSログインエラーが発生しました。");
            header('Location: /user/message');
            exit();
        } else {
            $profile = $loginResult['profile'];
        }

        // ユーザーDB処理
        if (!empty($profile)) {

            $user = new User();
            $result = $user->selectBySnsId($snsType, $profile['id']);
            if (count($result) <= 0) {

                // ユーザー新規登録
                $this->db->begin();
                $ret = $user->insert($snsType, $profile['id'], $profile['access_token']);
                if ($ret) {
                    $this->db->commit();
                    $userId = $user->getId();
                } else {

                    $this->db->rollback();
                    $this->logwrite("Error: can't register to the table.", "error");
                    $this->session->set(User::SESSION_MESSAGE, "ユーザー登録に失敗しました。");
                    header('Location: /user/message');
                    exit();
                }
            } else {

                if ($result[0]->getStatus() == User::STATUS_ENABLE) {

                    // 登録済情報セット
                    $userId = $result[0]->getId();
                } else {

                    // ユーザー情報無効（念の為）
                    $user->clearUserSession();

                    // Cookieの削除（念の為）
                    if ($this->cookies->has(User::COOKIE_NAME)) {
                        $this->cookies->get(User::COOKIE_NAME)->delete();
                    }

                    $this->logwrite("Error: userId(" . $result[0]->getId() . ") is not enable.", "error");
                    $this->session->set(User::SESSION_MESSAGE, "ユーザー認証に失敗しました。");
                    header('Location: /user/message');
                    exit();
                }
            }

            // ユーザ情報をセッションに保存
            $user->setUserSession($userId, $snsType, $profile['id'], $profile['access_token'], $profile['name']);
            $userSession = $user->getUserSession();

            // ユーザー情報をCookieに保存
            if (!empty($userSession)) {
                if (User::AUTO_LOGIN) {
                    $this->cookies->set(User::COOKIE_NAME, json_encode($userSession), time() + User::COOKIE_DAY * 86400);
                    $this->cookies->send();
                }
            } else {

                $this->logwrite("Error: success to register to the table, but can't set the session.", "error");
                $this->session->set(User::SESSION_MESSAGE, "ユーザー登録に成功しましたが、セッションの保存に失敗しました。");
                header('Location: /user/message');
                exit();
            }
        } else {

            $this->logwrite("Error: not found sns user.", "error");
            $this->session->set(User::SESSION_MESSAGE, "該当SNSにユーザーがいません。");
            header('Location: /user/message');
            exit();
        }

        $this->view->disable();
        header('Location: /user/complete');
        exit();
    }

    /**
     * サイトのログアウト処理
     */
    public function logoutAction()
    {
        // お気に入り取得処理
        $this->favoriteProcess();

        // Sessionの削除
        $user = new User();
        $user->clearUserSession();

        // Cookieの削除
        if ($this->cookies->has(User::COOKIE_NAME)) {
            $this->cookies->get(User::COOKIE_NAME)->delete();
        }

        $this->view->setVar("title", "ログアウト | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * ログイン完了
     *
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function completeAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        $this->view->setVar("title", "ログイン完了 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * メッセージ表示
     *
     * @param string $action 処理内容
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function messageAction($action = "")
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        // メッセージ取得
        $message = $this->session->get(User::SESSION_MESSAGE);
        //$this->session->remove(User::SESSION_MESSAGE);

        $this->view->setVar("action", $action);
        $this->view->setVar("message", $message);
        $this->view->setVar("title", "メッセージ | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * プレミアム検索の登録
     *
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function premiumAction()
    {
        // ログイン処理
        $this->loginProcess();

        if ($this->logined) {

            if ($this->request->isPost() == true) {

                $email = $this->request->getPost("email");
                $code = $this->request->getPost("code");

                if ($code != self::PREMIUM_CODE) {

                    $this->session->set(User::SESSION_MESSAGE, "プレミアムコードが間違っています。");
                    header('Location: /user/message');
                    exit();
                }

                $user = User::findFirst($this->userId);
                if ($user) {

                    try {
                        $user->setPayed(1);
                        $user->setEmail($email);
                        $user->setPremiumCode($code);
                        $user->save();
                    } catch (Exception $e) {
                        $this->logwrite("Error: " . $e->getMessage(), "error");
                        $this->session->set(User::SESSION_MESSAGE, "エラーが発生しました。メールアドレスが既に利用されている可能性があります。");
                        header('Location: /user/message');
                        exit();
                    }
                }
            }
        }
        header('Location: /'); // トップページへ
        exit();
    }


}