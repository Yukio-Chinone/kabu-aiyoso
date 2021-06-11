<?php

use \Phalcon\Http\Request;

class BookmarkController extends ControllerBase
{
    const LIMIT = 50; // お気に入り一覧ページの表示件数

    /**
     * 銘柄詳細ページの表示件数
     */
    const STOCK_LIMIT = 30; //営業日数

    // タイトルに追加する文字
    private $addTitle = [
        "",
        "(買) 保有中",
        "(買) 検討中",
        "(売) 保有中",
        "(売) 検討中"
    ];

    /**
     * 自分のお気に入り一覧
     *
     * @param $status 1:(買)保有中、2:(買)検討中、3:(売)保有中、4:(売)検討中
     */
    public function indexAction($status = 1)
    {
        // ログイン処理
        $this->loginProcess("/user/login");

        // お気に入り取得処理
        $this->favoriteProcess();

        // 入力パラメータ
        $offset = $this->request->getQuery("offset", null, 0);

        // ログインユーザーの「お気に入り登録」一覧を取得
        $checkedAts = [];
        $searchResults = [];
        $searchResultCount = 0;

        if ($this->logined) {
            $bookmark = new Bookmark();

            // お気に入り一覧
            $tmps = $bookmark->selectByUserId($this->userId, 0, $status, self::LIMIT, $offset);
            foreach ($tmps as $tmp) {

                $checkedAts[] = $tmp->getCheckedAt();
                $stockCode = $tmp->getStockCode();
                $companies = ListedCompanies::selectTargetStockList($stockCode, 1);
                $searchResults[] = $companies[0];
            }

            // お気に入り数
            $searchResultCount = $bookmark->selectCountByUserId($this->userId, 0, $status);
        }

        // 検索結果をVewに渡す
        $this->view->setVar("checkedAts", $checkedAts);
        $this->view->setVar("searchResults", $searchResults);
        $this->view->setVar("searchResultCount", $searchResultCount);

        // 銘柄ごとの予想日をViewに渡す
        $predictYmds = [];
        foreach ($searchResults as $searchResult) {
            $details = ListedCompanies::selectTargetStockList($searchResult->getStockCode(), self::STOCK_LIMIT);
            $predictYmds[$searchResult->getStockCode()] = [];
            foreach ($details as $detail) {
                $predictYmds[$searchResult->getStockCode()][] = $detail->getYmd();
            }
        }
        $this->view->setVar("predictYmds", $predictYmds);

        // お気に入り状態をViewに渡す
        $this->view->setVar("status", $status);

        // 現在のOffsetをViewに渡す
        if (count($searchResults) > 0) {
            $this->view->setVar("startOffset", $offset + 1);
            $this->view->setVar("endOffset", $offset + count($searchResults));
        } else {
            $this->view->setVar("startOffset", 0);
            $this->view->setVar("endOffset", 0);
        }

        // 次のOffsetをVewに渡す
        $nextOffset = (int)$offset + self::LIMIT;
        if ($nextOffset >= $searchResultCount) {
            $nextOffset = null;
        }
        $this->view->setVar("nextOffset", $nextOffset);

        // 前のOffsetをVewに渡す
        if ($offset > 0) {
            $prevOffset = (int)$offset - self::LIMIT;
            if ($prevOffset <= 0) {
                $prevOffset = 0;
            }
        } else {
            $prevOffset = null;
        }
        $this->view->setVar("prevOffset", $prevOffset);

        // クエリストリングのフォーマットを作成
        $queryFormat = "/bookmark/index/" . $status;
        $queryFormat .= "?offset={offset}";
        $queryFormat .= "#searchTop"; // 検索実行後の表示位置 (ID)

        //「現在」リンクのクエリストリングをSessionにセット
        $currentQueryString = str_replace("{offset}", $offset, $queryFormat);
        $this->session->set("currentQueryString", $currentQueryString);

        //「次へ」リンクのクエリストリングをVewに渡す
        $nextQueryString = "";
        if ($nextOffset !== null) {
            $nextQueryString = str_replace("{offset}", $nextOffset, $queryFormat);
        }
        $this->view->setVar("nextQueryString", $nextQueryString);

        //「前へ」リンクのクエリストリングをVewに渡す
        $prevQueryString = "";
        if ($prevOffset !== null) {
            $prevQueryString = str_replace("{offset}", $prevOffset, $queryFormat);
        }
        $this->view->setVar("prevQueryString", $prevQueryString);

        // titleタグをviewに渡す
        $this->view->setVar("title", $this->addTitle[$status] . " | AIの株価予想【人工知能x株式投資ソフト】");
        $this->view->setVar("subTitle", $this->addTitle[$status]);
    }

    /**
     * Bookmarkのリクエスト処理
     */
    public function requestAction()
    {
        $request = new Request();
        $json = 0;

        if ($request->isPost() == true) {

            $stockCode = $request->getPost("stock_code", null, 0);
            $userId = $request->getPost("user_id", null, 0);
            $status = $request->getPost("status", null, 0);
            $ymd = $request->getPost("ymd", null, 0);

            // 入力パラメータチェック
            if (empty($stockCode)) {
                echo 0;
                exit();
            }
            if (empty($userId)) {
                echo 0;
                exit();
            }
            if (empty($status)) {
                echo 0;
                exit();
            }

            $bookmark = new Bookmark();

            // 現在のBookmarkを取得
            $bookmarks = $bookmark->selectByUserId($userId, $stockCode);

            $this->db->begin();

            if (count($bookmarks) <= 0) { //Bookmark未作成か？
                $ret = $bookmark->insert($userId, $stockCode, $status); // 新規レコードを作成
                if ($ret) {
                    if (!empty($ymd)) {
                        $ret = $bookmark->updateCheckedAt($userId, $stockCode, $ymd); // チェック日を登録
                    }
                }
            } else {
                if ($bookmarks[0]->getStatus() == $status) {
                    $ret = $bookmark->deleteRecord($userId, $stockCode); //既存レコードの削除
                } else {
                    $ret = $bookmark->updateStatus($userId, $stockCode, $status); //既存レコードの更新
                }
            }

            if ($ret) {
                $this->db->commit();
            } else {
                $this->db->rollback();
            }

            // 更新後のBookmarkを取得（最新のDBから取得）
            $bookmarks = $bookmark->selectByUserId($userId, $stockCode);
            if (count($bookmarks) > 0) { //Bookmark無しか？
                $result = [
                    "user_id" => $userId,
                    "stock_code" => $stockCode,
                    "status" => $bookmarks[0]->getStatus()
                ];
            } else {
                $result = [
                    "user_id" => $userId,
                    "stock_code" => $stockCode,
                    "status" => 0
                ];
            }
            $json = json_encode($result);
        }
        echo $json;
        $this->view->disable();
    }

    /**
     * Bookmarkのチェックリクエスト処理
     */
    public function checkAction()
    {
        $request = new Request();
        $json = 0;

        if ($request->isPost() == true) {

            $stockCode = $request->getPost("stock_code", null, 0);
            $userId = $request->getPost("user_id", null, 0);
            $ymd = $request->getPost("ymd", null, 0);

            // 入力パラメータチェック
            if (empty($stockCode)) {
                echo 0;
                exit();
            }
            if (empty($userId)) {
                echo 0;
                exit();
            }
            if (empty($ymd)) {
                echo 0;
                exit();
            }

            $bookmark = new Bookmark();

            // 現在のBookmarkを取得
            $bookmarks = $bookmark->selectByUserId($userId, $stockCode);

            if (count($bookmarks) > 0) { //Bookmark作成済か？

                $this->db->begin();

                $ret = $bookmark->updateCheckedAt($userId, $stockCode, $ymd); // チェック日の更新
                if ($ret) {
                    $this->db->commit();
                } else {
                    $this->db->rollback();
                }

                $bookmarks = $bookmark->selectByUserId($userId, $stockCode);
                $result = [
                    "user_id" => $userId,
                    "stock_code" => $stockCode,
                    "checked_at" => $bookmarks[0]->getCheckedAt()
                ];
            } else {
                $result = [
                    "user_id" => $userId,
                    "stock_code" => $stockCode,
                    "checked_at" => ""
                ];
            }
            $json = json_encode($result);
        }
        echo $json;
        $this->view->disable();
    }

}