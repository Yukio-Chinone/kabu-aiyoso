<?php

use Phalcon\Mvc\Controller;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;

class ControllerBase extends Controller
{
    /**
     * @var array
     */
    protected $userSessions = [];

    /**
     * @var int
     */
    protected $userId = 0;

    /**
     * @var string
     */
    protected $userName = "";

    /**
     * @var bool
     */
    protected $logined = false;

    /**
     * @var bool
     */
    protected $payed = false;

    /**
     * @var string
     */
    protected $logger;

    /**
     * @var string
     */
    protected $logLevels;

    /**
     * CRSF対策用トークン
     */
    const CRSF_TOKEN = "csrf_token";

    /**
     * Controller共通の初期化
     */
    public function initialize()
    {
        $logfile = $this->di->getConfig()->app['log_file'];
        $this->logger = new FileAdapter($logfile);
        $this->logLevels = $this->di->getConfig()->app['log_levels'];

//        // CRSF対策 ....(curlやrss配信できなくなるのでコメント 2020/08/18)
//        if (empty($this->session->get(self::CRSF_TOKEN))) { //サーバ内（Session）にトークン無しか？
//
//            $crsfToken = $this->getRandStr(64);
//            $this->session->set(self::CRSF_TOKEN, $crsfToken); //サーバ内（Session）にトークンセット
//            $this->cookies->set(self::CRSF_TOKEN, $crsfToken); //ブラウザ内（Cookie）にトークンセット
//            $this->cookies->send();
//
//            $message = sprintf("[%s] トークンなし。トップページからやり直し。", date("Y-m-d H:i:s"));
//            $this->logwrite("Error: " . $message, "error");
//            header('Location: /'); //トップページからやり直し
//            exit();
//        } else { //サーバ内（Session）にトークン内にトークンあり
//            $accessible = false;
//
//            if ($this->cookies->has(self::CRSF_TOKEN)) { //ブラウザ内（Cookie）にトークンあり
//
//                $crsfTokenSession = trim($this->session->get(self::CRSF_TOKEN));
//                $crsfTokenCookie = trim($this->cookies->get(self::CRSF_TOKEN)->getValue());
//
//                if (!empty($crsfTokenSession) && $crsfTokenSession == $crsfTokenCookie) { //サーバとブラウザのトークンは一致か？
//                    $accessible = true; //アクセス可能
//                } else {
//                    $message = sprintf("[%s] サーバ(%s) != ブラウザ(%s) は不一致", date("Y-m-d H:i:s"), $crsfTokenSession, $crsfTokenCookie);
//                    $this->logwrite("Error: " . $message, "error");
//                }
//            } else {
//                $message = sprintf("[%s] ブラウザ（Cookie）にトークンなし", date("Y-m-d H:i:s"));
//                $this->logwrite("Error: " . $message, "error");
//            }
//
//            if (!$accessible) {
//                $this->session->remove(self::CRSF_TOKEN);
//                $this->cookies->get(self::CRSF_TOKEN)->delete();
//
//                $message = sprintf("[%s] アクセス無効", date("Y-m-d H:i:s"));
//                $this->logwrite("Error: " . $message, "error");
//                header('Location: /');
//                exit();
//            }
//        }
    }

    /**
     * ログ保存
     * (nginxの実行ユーザーで保存される)
     *
     * @param $msg
     * @param $logLevel ログレベル
     */
    public function logwrite($msg, $logLevel)
    {
        if (in_array($logLevel, (array)$this->logLevels)) {

            $file = debug_backtrace();
            $fileLine = $file[0]['file'] . "[" . $file[0]['line'] . "]";

            if (method_exists($this->logger, $logLevel)) {
                $this->logger->$logLevel($fileLine . ": " . $msg);
            } else {
                $this->logger->error($fileLine . ": " . $logLevel . " method is not defined.");
            }
        }
    }

    /**
     * Cookieを利用した自動ログイン
     *
     * @param string $urlBeforeLogin 未ログイン時のリダイレクトURL (省略時はリダイレクトなし)
     * @param string $urlBeforePay 未支払い時のリダイレクトURL (省略時はリダイレクトなし)
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    protected function loginProcess(
        $urlBeforeLogin = "",
        $urlBeforePay = ""
    )
    {
        $user = new User();

        if ($user->isLogined()) { // ログイン済 (ログインセッション有り)

            $this->userSessions = $user->getUserSession();
            $this->userId = $this->userSessions['userId'];
            $this->userName = $this->userSessions['userName'];
            $this->logined = true;
            $this->payed = User::isPayed($this->userId);

        } else { // 未ログイン (ログインセッション無し)

            $this->userSessions = [];
            $this->userId = 0;
            $this->userName = "";
            $this->logined = false;
            $this->payed = false;

            if (User::AUTO_LOGIN) { // Cookieを利用した自動ログイン有効か？
                if ($this->cookies->has(User::COOKIE_NAME)) { // Cookieにログイン情報ありか？

                    // Cookieからログイン情報を取得
                    $userCookie = json_decode($this->cookies->get(User::COOKIE_NAME)->getValue(), true);

                    if ($userCookie["snsType"] === User::SNS_FACEBOOK) { //Facebookログイン

                        // Facebook Auto Login
                        if (strstr($_SERVER['HTTP_HOST'], 'localhost')) {
                            $loginResult = $this->localLogin($userCookie['accessToken']);
                        } else {
                            $loginResult = $this->facebookLogin($userCookie['accessToken']);
                        }
                    }
                    else if($userCookie["snsType"] === 0 && !empty($userCookie["userName"])){ //メールアドレスログイン
                        $loginResult = [
                            "profile" => [
                                "name" => $userCookie["userName"],
                            ],
                            "error" => ""
                            ];
                    }
                    else {

                        // not applicable (N/A)
                        $loginResult = [
                            'error' => 'not found login target.'
                        ];
                    }

                    if (empty($loginResult['error'])) { // 認証・認可OK

                        $profile = $loginResult['profile'];
                        $userId = $userCookie['userId'];
                        $userName = $userCookie['userName'];
                        $snsType = $userCookie['snsType'];

                        if ($userCookie["snsType"] === User::SNS_FACEBOOK) { //Facebookログイン
                            $result = $user->selectBySnsId($snsType, $profile['id']);
                        }
                        else{ //メールアドレスログイン
                            $result = $user->selectByUsername($userName);
                        }
                        if (count($result) <= 0) {

                            // DBユーザー存在しない (Cookie削除)
                            $this->cookies->get(User::COOKIE_NAME)->delete();
                        } else {
                            if ($result[0]->getStatus() == User::STATUS_ENABLE) {

                                // DBユーザー有効 (Sessionに登録)
                                $user->setUserSession($userId, $snsType, $profile['id'], $profile['access_token'], $profile['name']);
                                header('Location: /');
                                exit;

                            } else {

                                // DBユーザー無効 (Cookie削除)
                                $this->logwrite("Error: userId(" . $userCookie['userId'] . ") is not enable(can't autologin).", "error");
                                $this->cookies->get(User::COOKIE_NAME)->delete();
                            }
                        }
                    } else { // 認証・認可NG

                        // Cookie削除
                        $this->logwrite("Error: userId(" . $userCookie['userId'] . ") is not sns auth(can't autologin): " . $loginResult['error'], "error");
                        $this->cookies->get(User::COOKIE_NAME)->delete();
                    }
                }
            }

            if (!empty($urlBeforeLogin) && $this->logined === false) {
                header('Location: ' . $urlBeforeLogin);
                exit;
            }
            if (!empty($urlBeforePay) && $this->payed === false) {
                header('Location: ' . $urlBeforePay);
                exit;
            }
        }

        // ログイン状態・支払い状態をviewに渡す
        $this->view->setVar("logined", $this->logined);
        $this->view->setVar("payed", $this->payed);
        $this->view->setVar("userId", $this->userId);
        $this->view->setVar("userName", $this->userName);
    }

    /**
     * お気に入り処理
     */
    protected function favoriteProcess()
    {
        // ログインユーザーの「お気に入り登録」一覧を取得
        $userBookmarks = [];
        $userBookmarksCount = [
            Bookmark::ALL => 0,
            Bookmark::BUY_HOLD => 0,
            Bookmark::BUY_FAVORITE => 0,
            Bookmark::SELL_HOLD => 0,
            Bookmark::SELL_FAVORITE => 0,
        ];

        if ($this->logined) {
            $bookmark = new Bookmark();
            $tmps = $bookmark->selectByUserId($this->userId, 0, 0, 999999);
            foreach ($tmps as $tmp) {
                $sc = $tmp->getStockCode();
                $userBookmarks[$sc]['status'] = $tmp->getStatus();
                $userBookmarks[$sc]['checked_at'] = $tmp->getCheckedAt();
                $userBookmarksCount[Bookmark::ALL]++;
                $userBookmarksCount[$tmp->getStatus()]++;
            }
        }

        // ログインユーザーの「お気に入り登録」情報を取得をviewに渡す
        $this->view->setVar("userBookmarks", $userBookmarks);
        $this->view->setVar("userBookmarksCount", $userBookmarksCount);
    }

    /**
     * Localログイン
     *
     * @param string $accessToken
     * @return mixed
     */
    protected function localLogin($accessToken = "")
    {
        if (empty($accessToken)) {
            $accessToken = 'token1234567890'; // アクセストークンを新規取得
        }

        $profile = [ // プロフィール・アクセストークン情報取得
            'name' => '開発太郎<5>',
            'id' => 'userid123456789d'
        ];

        $result['profile'] = $profile; // プロフィール情報取得
        $result['profile']['access_token'] = $accessToken;
        return $result;
    }

    /**
     * Facebookログイン
     *
     * <参考: facebook for developers>
     * https://developers.facebook.com/docs/php/howto/example_facebook_login
     * https://developers.facebook.com/docs/php/howto/example_retrieve_user_profile
     *
     * @param string $accessToken 取得済の access token
     * @return array
     */
    protected function facebookLogin($accessToken = "")
    {
        require_once BASE_PATH . '/vendor/autoload.php';
        $snsConfig = $this->di->getConfig()->sns;

        $result = [
            'error' => null,
            'profile' => ''
        ];

        $fb = new Facebook\Facebook([
            'app_id' => $snsConfig->facebook->app_id,
            'app_secret' => $snsConfig->facebook->app_secret,
            'default_graph_version' => $snsConfig->facebook->default_graph_version,
        ]);
        $helper = $fb->getRedirectLoginHelper();

        /**
         * access token 新規取得処理
         */
        if (empty($accessToken)) {

            // ① access token Object 取得
            try {
                $accessTokenObj = $helper->getAccessToken();
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                $result['error'] = "Error: " . $e->getMessage();
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                $result['error'] = "Error: " . $e->getMessage();
            }
            if (!empty($result['error'])) {
                return $result;
            }

            if (!isset($accessTokenObj)) {
                if ($helper->getError()) {

                    $result['error'] = "Error: 401 Unauthorized. ";
                    $result['error'] .= $helper->getError() . ". ";
                    $result['error'] .= $helper->getErrorCode() . ". ";
                    $result['error'] .= $helper->getErrorReason() . ". ";
                    $result['error'] .= $helper->getErrorDescription();
                } else {
                    $result['error'] = "Error: 400 Bad Request";
                }
                return $result;
            }
            $accessToken = $accessTokenObj->getValue();

            // ② OAuth 2.0
            $oAuth2Client = $fb->getOAuth2Client();

            // ③ access token の不正チェック (トークンハイジャック防止)
            try {
                $tokenMetadata = $oAuth2Client->debugToken($accessTokenObj);
                $tokenMetadata->validateAppId($snsConfig->facebook->app_id);
                $tokenMetadata->validateExpiration();
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                $result['error'] = "Error: validation access token: " . $e->getMessage();
                return $result;
            }

            // ④ 生存期間が長い access token に変更
            if (!$accessTokenObj->isLongLived()) {
                // Exchanges a short-lived access token for a long-lived one
                try {
                    $accessTokenObj = $oAuth2Client->getLongLivedAccessToken($accessTokenObj);
                } catch (Facebook\Exceptions\FacebookSDKException $e) {
                    $result['error'] = "Error: getting long-lived access token: " . $e->getMessage();
                    return $result;
                }
                $accessToken = $accessTokenObj->getValue();
            }
        }

        /**
         * プロフィール情報取得処理
         */
        try {
            $response = $fb->get('/me?fields=id,name&locale=ja_JP', $accessToken);
            $user = $response->getGraphUser();
            $result['profile'] = [
                "id" => $user->getId(),
                "name" => $user->getName(),
                "access_token" => $accessToken,
            ];
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            $result['error'] = "Error: " . $e->getMessage();
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            $result['error'] = "Error: " . $e->getMessage();
        }
        return $result;
    }

    /**
     * キャメルケース変換
     *
     * @param $str
     * @return mixed
     */
    public function camelize($str)
    {
        $str = ucwords($str, '_');
        return str_replace('_', '', $str);
    }

    /**
     * スネークケース変換
     *
     * @param $str
     * @return string
     */
    public function snakize($str)
    {
        $str = preg_replace('/[a-z]+(?=[A-Z])|[A-Z]+(?=[A-Z][a-z])/', '\0_', $str);
        return strtolower($str);
    }

    /**
     * ランダム文字列の生成
     *
     * @param $length
     * @return string|null
     */
    public function getRandStr($length = 16)
    {
        $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        $result = "";
        for ($i = 0; $i < $length; $i++) {
            $result .= $str[rand(0, count($str) - 1)];
        }
        return $result;
    }

    /**
     * 今日の上昇予想チャートを取得
     *
     * @return array
     */
    public function getTodaysChart()
    {
        $cnt = 0;
        $result = [];

        // 銘柄情報の取得
        $listedCompanies = new ListedCompanies();
        $currentYmds = $listedCompanies->getCurrentYmds(30);
        $latestYmd = $currentYmds[0];

        if ($this->request->isGet() == true) {

            //今日の上昇予想
            $beforeYmd = $listedCompanies->getBeforeYmd($latestYmd, 1);
            $searchResults = ListedCompanies::selectSuggestionList(
                $latestYmd,
                $latestYmd,
                $beforeYmd,
                "",
                "",
                9999999999,
                (float)0,
                (float)99,
                (float)1,
                ">=",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                1,
                1,
                "",
                "",
                "",
                (int)1,
                (int)0
            );

        }

        foreach ($searchResults as $searchResult) { //銘柄分ループ

            $stockCode = $searchResult->getStockCode();
            $result[$cnt]["title"] = "AIが選んだ今日の上昇予想チャート";
            $result[$cnt]["ymd"] = $latestYmd;
            $result[$cnt]["code"] = $stockCode;
            $result[$cnt]["name"] = $searchResult->getName();
            $result[$cnt]["url"] = "https://kabu.aiyoso.com/index/detail/" . $stockCode;
            $result[$cnt]["img_url"] = "https://s3-us-west-2.amazonaws.com/dlearn-ai/finance/japan/image/{$stockCode}/{$stockCode}_{$latestYmd}.png";
            $cnt++;
        }
        
        return $result;
    }
}
