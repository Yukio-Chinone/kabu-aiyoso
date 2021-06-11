<?php

use \Phalcon\Http\Request;

//ツイート取得に必要
require "../vendor/abraham/twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

class IndexController extends ControllerBase
{
    /**
     * 銘柄一覧ページの表示件数
     */
    const LIMIT = 50;

    /**
     * 銘柄詳細ページの表示件数
     */
    const STOCK_LIMIT = 30; //営業日数

    /**
     * 検索条件を保存するCookie名
     */
    const COOKIE_NAME = "search_condition";

    /**
     * 検索条件の保存日数
     */
    const COOKIE_DAY = 3650; //10年

    /**
     * ツイッターの認証情報
     */
    const API_KEY = "1PJZ4eFvWkrH2rcCzaUwUXwiq";
    const API_KEY_SECRET = "cGCiKiZTMzygs6RfNZw5uVObuySUhi7pZ2hZCeKhhwxNLTnsgS";
    const ACCESS_TOKEN = "1243880429456732160-0GNajWzPwDixN74Xs7X1VA1kEg296H";
    const ACCESS_TOKEN_SECRET = "ZkMw4yai9La8FrRjwfKw4w6i2Cw6EfOe37lxXEbmKvGyd";

    /**
     * 銘柄検索
     */
    public function indexAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        // 銘柄の検索可能件数
        $limit = self::LIMIT; // 有料会員

        // 銘柄情報の取得
        $listedCompanies = new ListedCompanies();
        $currentYmds = $listedCompanies->getCurrentYmds(self::STOCK_LIMIT);
        $latestYmd = $currentYmds[0];

        // 統計情報の取得 (学習数)
        $trainingInfos = $listedCompanies->getTrainingInfos();
        $trainingInfos['middle'] = $trainingInfos['training_middle'] - $trainingInfos['training_middle_error'];

        // 統計情報の取得 (予測数)
        $predictionInfos = $listedCompanies->getPredictionInfos();
        $predictionInfos['middle'] = $predictionInfos['prediction_middle'] - $predictionInfos['prediction_middle_error'];

        // 初期値セット
        $ymd = "";
        $stockCode = "";
        $marketNo = "";
        $cost = "";
        $searchResults = null;
        $searchResultCount = 0;
        $button = "";
        $topChart = false;

        if ($this->request->isGet() == true) {

            $button = $this->request->getQuery("button", null, "");

            if ($button == "submit") {

                // 検索条件セット(null:入力値のサニタイズ無し)
                $ymd = $this->request->getQuery("ymd", null, "");
                $stockCode = $this->request->getQuery("stock_code", null, "");
                $stockCode = mb_convert_kana($stockCode, "aK");
                $beforeYmd = $listedCompanies->getBeforeYmd($ymd, 1);

                // 空白の削除
                $stockCode = str_replace(" ", "", $stockCode);
                $stockCode = str_replace("　", "", $stockCode);

                if (empty($stockCode)) {

                    // 銘 柄コード or 企業名称が無しの場合は、条件を指定
                    $marketNo = $this->request->getQuery("market_no", null, "");
                    $cost = $this->request->getQuery("cost", null, "");
                    $predictedRate = $this->request->getQuery("predicted_rate", null, "");
                    $correctRate = $this->request->getQuery("correct_rate", null, "");
                    $rateOfIncrease = $this->request->getQuery("rate_of_increase", null, "");
                    $updown = $this->request->getQuery("updown", null, "");
                    $sma75dClose = $this->request->getQuery("sma75d_close", null, "");
                    $sma75dUp = $this->request->getQuery("sma75d_up", null, "");
                    $sma25dUp = $this->request->getQuery("sma25d_up", null, "");
                    $bollingerP2Up = $this->request->getQuery("bollinger_p2_up", null, "");
                    $bandwalk = $this->request->getQuery("bandwalk", null, "");
                    $upBeard = $this->request->getQuery("up_beard", null, "");
                    $downBeard = $this->request->getQuery("down_beard", null, "");
                    $crossfoot = $this->request->getQuery("crossfoot", null, "");
                    $straddleLine = $this->request->getQuery("straddle_line", null, "");
                    $resistantLine = $this->request->getQuery("resistant_line", null, "");
                    $aroundLine = $this->request->getQuery("around_line", null, "");
                    $bbPosition = $this->request->getQuery("bb_position", null, "");
                    $profitValue = $this->request->getQuery("profit_value", null, "");
                    $rsi = $this->request->getQuery("rsi", null, "");
                    $orderCorrectRate = $this->request->getQuery("order_correct_rate", null, 0);
                    $orderPredictedRate = $this->request->getQuery("order_predicted_rate", null, 0);
                    $rateOfIncreaseUp = $this->request->getQuery("rate_of_increase_up", null, 0);
                    $rateOfIncreaseDn = $this->request->getQuery("rate_of_increase_dn", null, 0);
                    $offset = $this->request->getQuery("offset", null, 0);
                } else {
                    // 銘柄コード or 企業名称を指定した場合は、その他の検索条件を削除
                    $marketNo = "";
                    $cost = 9999999999;
                    $predictedRate = "";
                    $correctRate = "";
                    $rateOfIncrease = "";
                    $updown = "";
                    $sma75dClose = "";
                    $sma75dUp = "";
                    $sma25dUp = "";
                    $bollingerP2Up = "";
                    $bandwalk = "";
                    $upBeard = "";
                    $downBeard = "";
                    $crossfoot = "";
                    $straddleLine = "";
                    $resistantLine = "";
                    $aroundLine = "";
                    $bbPosition = "";
                    $profitValue = "";
                    $rsi = "";
                    $orderCorrectRate = "";
                    $orderPredictedRate = "";
                    $rateOfIncreaseUp = "";
                    $rateOfIncreaseDn = "";
                    $offset = 0;
                }
                $optionArea = $this->request->getQuery("option_area", null, "");

                // 銘柄の検索処理 (一覧取得)
                $searchResults = ListedCompanies::selectSuggestionList(
                    $latestYmd,
                    $ymd,
                    $beforeYmd,
                    $stockCode,
                    $marketNo,
                    $cost,
                    (float)$predictedRate,
                    (float)$correctRate,
                    (float)$rateOfIncrease,
                    $updown,
                    $sma75dClose,
                    $sma75dUp,
                    $sma25dUp,
                    $bollingerP2Up,
                    $bandwalk,
                    $upBeard,
                    $downBeard,
                    $crossfoot,
                    $straddleLine,
                    $resistantLine,
                    $aroundLine,
                    $bbPosition,
                    $profitValue,
                    $rsi,
                    $orderCorrectRate,
                    $orderPredictedRate,
                    $rateOfIncreaseUp,
                    $rateOfIncreaseDn,
                    "",
                    (int)$limit,
                    (int)$offset
                );

                // 銘柄の検索処理 (件数取得)
                $searchResultCount = ListedCompanies::selectSuggestionCount(
                    $latestYmd,
                    $ymd,
                    $beforeYmd,
                    $stockCode,
                    $marketNo,
                    $cost,
                    (float)$predictedRate,
                    (float)$correctRate,
                    (float)$rateOfIncrease,
                    $updown,
                    $sma75dClose,
                    $sma75dUp,
                    $sma25dUp,
                    $bollingerP2Up,
                    $bandwalk,
                    $upBeard,
                    $downBeard,
                    $crossfoot,
                    $straddleLine,
                    $resistantLine,
                    $aroundLine,
                    $bbPosition,
                    $profitValue,
                    $rsi,
                    $orderCorrectRate,
                    $orderPredictedRate,
                    $rateOfIncreaseUp,
                    $rateOfIncreaseDn,
                    ""
                );

                // 検索ログに追加（直接指定or１件ヒットの場合のみ追加する）
                if ($searchResultCount == 1) {
                    foreach ($searchResults as $searchResult) { //銘柄分ループ
                        $searchLog = new SearchLog();
                        $searchLog->addAccessCount($searchResult->getStockCode());
                        break;
                    }
                }

                // 銘柄指定の検索エラー
                $stockError = "";
                if (!empty($stockCode) && count($searchResults) === 1) { // 銘柄コード指定で検索ヒットか？
                    $stockError = ListedCompanies::selectTargetStockErrorMessages($stockCode, $ymd);
                }
                $this->view->setVar("stockError", $stockError);

                // Cookieセット(銘柄コード or 企業名称を指定した場合は、その他の検索条件は削除されている)
                $searchCondition = [
                    "ymd" => $ymd,
                    "stockCode" => $stockCode,
                    "marketNo" => $marketNo,
                    "cost" => $cost,
                    "predictedRate" => (float)$predictedRate,
                    "correctRate" => (float)$correctRate,
                    "rateOfIncrease" => (float)$rateOfIncrease,
                    "updown" => $updown,
                    "sma75dClose" => $sma75dClose,
                    "sma75dUp" => $sma75dUp,
                    "sma25dUp" => $sma25dUp,
                    "bollingerP2Up" => $bollingerP2Up,
                    "bandwalk" => $bandwalk,
                    "upBeard" => $upBeard,
                    "downBeard" => $downBeard,
                    "crossfoot" => $crossfoot,
                    "straddleLine" => $straddleLine,
                    "resistantLine" => $resistantLine,
                    "aroundLine" => $aroundLine,
                    "bbPosition" => $bbPosition,
                    "profitValue" => $profitValue,
                    "rsi" => $rsi,
                    "orderCorrectRate" => $orderCorrectRate,
                    "orderPredictedRate" => $orderPredictedRate,
                    "rateOfIncreaseUp" => $rateOfIncreaseUp,
                    "rateOfIncreaseDn" => $rateOfIncreaseDn,
                    "volumeUp" => "",
                    "optionArea" => $optionArea
                ];
                $this->cookies->set(self::COOKIE_NAME, json_encode($searchCondition), time() + self::COOKIE_DAY * 86400);
                $this->cookies->send();

            } else if ($button == "reset") {
                // Cookieクリア
                $this->cookies->get(self::COOKIE_NAME)->delete();

                header('Location: /');
                exit();

            } else {
                if ($this->cookies->has(self::COOKIE_NAME)) {
                    /**
                     * Cookieに保存してある検索条件をセット
                     */
                    $condition = json_decode($this->cookies->get(self::COOKIE_NAME)->getValue(), true);
                    //print_r($condition);exit();
                    //$ymd = $condition["ymd"];
                    $stockCode = $condition["stockCode"];
                    $marketNo = $condition["marketNo"];
                    $cost = $condition["cost"];
                    $predictedRate = $condition["predictedRate"];
                    $correctRate = $condition["correctRate"];
                    $rateOfIncrease = $condition["rateOfIncrease"];
                    $updown = $condition["updown"];
                    $sma75dClose = $condition["sma75dClose"];
                    $sma75dUp = $condition["sma75dUp"];
                    $sma25dUp = $condition["sma25dUp"];
                    $bollingerP2Up = $condition["bollingerP2Up"];
                    $bandwalk = $condition["bandwalk"];
                    $upBeard = $condition["upBeard"];
                    $downBeard = $condition["downBeard"];
                    $crossfoot = $condition["crossfoot"];
                    $straddleLine = $condition["straddleLine"];
                    $resistantLine = $condition["resistantLine"];
                    $aroundLine = $condition["aroundLine"];
                    $bbPosition = $condition["bbPosition"];
                    $profitValue = $condition["profitValue"];
                    $rsi = $condition["rsi"];
                    $orderCorrectRate = $condition["orderCorrectRate"];
                    $orderPredictedRate = $condition["orderPredictedRate"];
                    $rateOfIncreaseUp = $condition["rateOfIncreaseUp"];
                    $rateOfIncreaseDn = $condition["rateOfIncreaseDn"];
                    $offset = 0;
                    $optionArea = $condition["optionArea"];
                } else {
                    /**
                     * デフォルトの検索条件をセット
                     */
                    $marketNo = "";
                    $cost = 9999999999;
                    $predictedRate = "5"; //過去10日の予想ズレ (デフォルト)
                    $correctRate = "99"; //AIの正解率 (デフォルト)
                    $rateOfIncrease = 1;
                    $updown = "";
                    $sma75dClose = "";
                    $sma75dUp = "";
                    $sma25dUp = "";
                    $bollingerP2Up = "";
                    $bandwalk = "";
                    $upBeard = "";
                    $downBeard = "";
                    $crossfoot = "";
                    $straddleLine = "";
                    $resistantLine = "";
                    $aroundLine = "";
                    $bbPosition = "";
                    $profitValue = "";
                    $rsi = "";
                    $orderCorrectRate = "1";
                    $orderPredictedRate = "1";
                    $rateOfIncreaseUp = "";
                    $rateOfIncreaseDn = "";
                    $offset = 0;
                    $optionArea = "";
                }

                //今日の上昇予想
                $topChart = true;
                $ymd = $latestYmd;
                $beforeYmd = $listedCompanies->getBeforeYmd($ymd, 1);
                $searchResults = ListedCompanies::selectSuggestionList(
                    $latestYmd,
                    $ymd,
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
        }

        // 検索条件をVewに渡す
        $this->view->setVar("currentYmds", $currentYmds);
        $this->view->setVar("latestYmd", $latestYmd);
        $this->view->setVar("stockCode", $stockCode);
        $this->view->setVar("marketNo", $marketNo);
        $this->view->setVar("cost", $cost);
        $this->view->setVar("ymd", $ymd);
        $this->view->setVar("predictedRate", $predictedRate);
        $this->view->setVar("correctRate", $correctRate);
        $this->view->setVar("rateOfIncrease", $rateOfIncrease);
        $this->view->setVar("updown", $updown);
        $this->view->setVar("sma75dClose", $sma75dClose);
        $this->view->setVar("sma75dUp", $sma75dUp);
        $this->view->setVar("sma25dUp", $sma25dUp);
        $this->view->setVar("bollingerP2Up", $bollingerP2Up);
        $this->view->setVar("bandwalk", $bandwalk);
        $this->view->setVar("upBeard", $upBeard);
        $this->view->setVar("downBeard", $downBeard);
        $this->view->setVar("crossfoot", $crossfoot);
        $this->view->setVar("straddleLine", $straddleLine);
        $this->view->setVar("resistantLine", $resistantLine);
        $this->view->setVar("aroundLine", $aroundLine);
        $this->view->setVar("bbPosition", $bbPosition);
        $this->view->setVar("profitValue", $profitValue);
        $this->view->setVar("rsi", $rsi);
        $this->view->setVar("orderCorrectRate", $orderCorrectRate);
        $this->view->setVar("orderPredictedRate", $orderPredictedRate);
        $this->view->setVar("rateOfIncreaseUp", $rateOfIncreaseUp);
        $this->view->setVar("rateOfIncreaseDn", $rateOfIncreaseDn);
        $this->view->setVar("offset", $offset);
        $this->view->setVar("optionArea", $optionArea);

        // 検索結果をVewに渡す
        $this->view->setVar("topChart", $topChart);
        $this->view->setVar("searchResults", $searchResults);
        $this->view->setVar("searchResultCount", $searchResultCount);
        $this->view->setVar("button", $button);

        // 銘柄ごとの予想日をViewに渡す
        $predictYmds = [];

        foreach ($searchResults as $searchResult) { //銘柄分ループ

            $predictSkip = 0;
            $cnt = 0;

            $details = ListedCompanies::selectTargetStockList($searchResult->getStockCode(), self::STOCK_LIMIT); //直近日付からn日分取得
            $predictYmds[$searchResult->getStockCode()] = [];
            foreach ($details as $detail) {
                if ($ymd == $detail->getYmd()) {
                    $predictSkip = $cnt;
                }
                $predictYmds[$searchResult->getStockCode()][] = $detail->getYmd();
                $cnt++;
            }
        }
        $this->view->setVar("predictYmds", $predictYmds);
        $this->view->setVar("predictSkip", $predictSkip);

        // 現在のOffsetをViewに渡す
        if (count($searchResults) > 0) {
            $this->view->setVar("startOffset", $offset + 1);
            $this->view->setVar("endOffset", $offset + count($searchResults));
        } else {
            $this->view->setVar("startOffset", 0);
            $this->view->setVar("endOffset", 0);
        }

        // 次のOffsetをVewに渡す
        $nextOffset = (int)$offset + $limit;
        if ($nextOffset >= $searchResultCount) {
            $nextOffset = null;
        }
        $this->view->setVar("nextOffset", $nextOffset);

        // 前のOffsetをVewに渡す
        if ($offset > 0) {
            $prevOffset = (int)$offset - $limit;
            if ($prevOffset <= 0) {
                $prevOffset = 0;
            }
        } else {
            $prevOffset = null;
        }
        $this->view->setVar("prevOffset", $prevOffset);

        // クエリストリングのフォーマットを作成
        $queryFormat = "/?ymd=" . urlencode($ymd);
        $queryFormat .= "&stock_code=" . urlencode($stockCode);
        $queryFormat .= "&market_no=" . $marketNo;
        $queryFormat .= "&cost=" . $cost;
        $queryFormat .= "&predicted_rate=" . $predictedRate;
        $queryFormat .= "&correct_rate=" . $correctRate;
        $queryFormat .= "&rate_of_increase=" . $rateOfIncrease;
        $queryFormat .= "&updown=" . urlencode($updown);
        $queryFormat .= "&sma75d_close=" . $sma75dClose;
        $queryFormat .= "&sma75d_up=" . $sma75dUp;
        $queryFormat .= "&sma25d_up=" . $sma25dUp;
        $queryFormat .= "&bollinger_p2_up=" . $bollingerP2Up;
        $queryFormat .= "&bandwalk=" . $bandwalk;
        $queryFormat .= "&up_beard=" . $upBeard;
        $queryFormat .= "&down_beard=" . $downBeard;
        $queryFormat .= "&crossfoot=" . $crossfoot;
        $queryFormat .= "&straddle_line=" . $straddleLine;
        $queryFormat .= "&resistant_line=" . $resistantLine;
        $queryFormat .= "&around_line=" . $aroundLine;
        $queryFormat .= "&bb_position=" . $bbPosition;
        $queryFormat .= "&profit_value=" . $profitValue;
        $queryFormat .= "&rsi=" . $rsi;
        $queryFormat .= "&order_correct_rate=" . $orderCorrectRate;
        $queryFormat .= "&order_predicted_rate=" . $orderPredictedRate;
        $queryFormat .= "&rate_of_increase_up=" . $rateOfIncreaseUp;
        $queryFormat .= "&rate_of_increase_dn=" . $rateOfIncreaseDn;
        $queryFormat .= "&option_area=" . $optionArea;
        $queryFormat .= "&button=submit"; // 検索実行
        $queryFormat .= "&offset={offset}";
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

        // 統計情報をviewに渡す
        $this->view->setVar("trainingInfos", $trainingInfos);
        $this->view->setVar("predictionInfos", $predictionInfos);

        // titleタグをviewに渡す
        $title = "";
        if (!empty($stockCode)) {
            if (is_numeric($stockCode)) { //数値か？
                foreach ($searchResults as $searchResult) { //ヒットした場合には、1回のみループ
                    $title = $stockCode . ": " . $searchResult->getName() . " | ";
                    break;
                }
            } else { //数値以外か？
                $title = $stockCode . " | ";
            }
        }
        $this->view->setVar("title", $title . "AIの株価予想【人工知能x株式投資ソフト】");
        $this->view->setVar("subTitle", "検索結果");

        //銘柄検索ランキングを取得
        $searchLog = new SearchLog();
        $oneWeekAgo = date("Y-m-d", strtotime("-1 week"));

        $rankingLimit = 50;
        $searchRanking = $searchLog->getRanking($oneWeekAgo, $rankingLimit);
        $this->view->setVar("rankingLimit", $rankingLimit);
        $this->view->setVar("searchRanking", $searchRanking);
    }

    /**
     * 検索分析のリクエスト処理
     */
    public function requestAction()
    {
        // ログイン処理
        $this->loginProcess();

        $request = new Request();
        $results = [];

        $this->view->disable();

        if ($request->isPost() == true) {


            // 検索条件の取得
            $listedCompanies = new ListedCompanies();
            $currentYmds = $listedCompanies->getCurrentYmds(self::STOCK_LIMIT);

            $latestYmd = $currentYmds[0];
            $ymd = $request->getPost("ymd", null, 0);
            $beforeYmd = $listedCompanies->getBeforeYmd($ymd, 1);
            $stockCode = $request->getPost("stock_code", null, 0);

            $results["updown"] = [];
            $results["updown"][">="] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                ">=", //上昇/下降予想 ..........●上昇予想:>=、下降予想:<=
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
                "", "", "", "", "", "", "", 1, 0
            );

            $results["updown"]["<="] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "<=", //上昇/下降予想 ..........上昇予想:>=、●下降予想:<=
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
                "", "", "", "", "", "", "", 1, 0
            );
            //$this->logwrite(json_encode($results["updown"]), "debug");

            $results["sma75dUp"] = [];
            $results["sma75dUp"]["1"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "1", //75日移動平均の向き ....... ●上向き↗(上昇)(1)、下向き↘(下降)(0)
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
                "", "", "", "", "", "", "", 1, 0
            );

            $results["sma75dUp"]["0"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "0", //75日移動平均の向き ....... 上向き↗(上昇)(1)、●下向き↘(下降)(0)
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
                "", "", "", "", "", "", "", 1, 0
            );
            //$this->logwrite(json_encode($results["sma75dUp"]), "debug");

            $results["sma25dUp"] = [];
            $results["sma25dUp"]["1"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "",
                "1", //25日移動平均の向き ....... ●上向き↗(上昇)(1)、下向き↘(下降)(0)
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["sma25dUp"]["0"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "",
                "0", //25日移動平均の向き ....... 上向き↗(上昇)(1)、●下向き↘(下降)(0)
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "", "", "", "", "", "", "", 1, 0
            );
            //$this->logwrite(json_encode($results["sma25dUp"]), "debug");

            $results["bollingerP2Up"] = [];
            $results["bollingerP2Up"]["1"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "",
                "",
                "1", //BollingerBand の向き .... ●上向き↗(1)、下向き↘(0)
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["bollingerP2Up"]["0"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "",
                "",
                "0", //BollingerBand の向き .... 上向き↗(1)、●下向き↘(0)
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "", "", "", "", "", "", "", 1, 0
            );
            //$this->logwrite(json_encode($results["bollingerP2Up"]), "debug");

            /*
            $results["upBeard"] = [];
            $results["upBeard"]["1"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "",
                "",
                "",
                "",
                "1", //上髭のチェック ..... ●上髭が足より短い(1)、上髭が足より長い(2)
                "",
                "",
                "",
                "",
                "",
                "",
                "", "", "", "", "", 1, 0
            );

            $results["upBeard"]["2"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "",
                "",
                "",
                "",
                "2", //上髭のチェック ..... 上髭が足より短い(1)、●上髭が足より長い(2)
                "",
                "",
                "",
                "",
                "",
                "",
                "", "", "", "", "", 1, 0
            );
            //$this->logwrite(json_encode($results["upBeard"]), "debug");

            $results["downBeard"] = [];
            $results["downBeard"]["1"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "1", //下髭のチェック ..... ●下髭が足より短い(1)、下髭が足より長い(2)
                "",
                "",
                "",
                "",
                "",
                "", "", "", "", "", 1, 0
            );

            $results["downBeard"]["2"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "2", //下髭のチェック ..... 下髭が足より短い(1)、●下髭が足より長い(2)
                "",
                "",
                "",
                "",
                "",
                "", "", "", "", "", 1, 0
            );
            //$this->logwrite(json_encode($results["downBeard"]), "debug");

            $results["crossfoot"] = [];
            $results["crossfoot"]["1"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "1", //●十字足[気迷い] を含めない(1)
                "",
                "",
                "",
                "",
                "", "", "", "", "", 1, 0
            );
            //$this->logwrite(json_encode($results["crossfoot"]), "debug");
            */

            $results["straddleLine"] = [];
            $results["straddleLine"]["moveUp"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "moveUp", //移動平均線の跨ぎ ..... ●移動平均を下↗上へ跨ぐ(moveUp)、移動平均を上↘下へ跨ぐ(moveDn)
                "",
                "",
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["straddleLine"]["moveDn"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "moveDn", //移動平均線の跨ぎ ..... 移動平均を下↗上へ跨ぐ(moveUp)、●移動平均を上↘下へ跨ぐ(moveDn)
                "",
                "",
                "",
                "", "", "", "", "", "", "", 1, 0
            );
            //$this->logwrite(json_encode($results["straddleLine"]), "debug");

            $results["resistantLine"] = [];
            $results["resistantLine"]["1"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "1", //抵抗線/支持線の跨ぎ ....●抵抗線を下↗上へ跨ぐ(1)、抵抗線を上↘下へ跨ぐ(3)
                //                                       支持線を上↘下へ跨ぐ(2)、支持線を下↗上へ跨ぐ(4)
                "",
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["resistantLine"]["3"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "3", //抵抗線/支持線の跨ぎ ....抵抗線を下↗上へ跨ぐ(1)、●抵抗線を上↘下へ跨ぐ(3)
                //                                      支持線を上↘下へ跨ぐ(2)、支持線を下↗上へ跨ぐ(4)
                "",
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["resistantLine"]["2"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "2", //抵抗線/支持線の跨ぎ ....抵抗線を下↗上へ跨ぐ(1)、抵抗線を上↘下へ跨ぐ(3)
                //                                      ●支持線を上↘下へ跨ぐ(2)、支持線を下↗上へ跨ぐ(4)
                "",
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["resistantLine"]["4"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "4", //抵抗線/支持線の跨ぎ ....抵抗線を下↗上へ跨ぐ(1)、抵抗線を上↘下へ跨ぐ(3)
                //                                      支持線を上↘下へ跨ぐ(2)、●支持線を下↗上へ跨ぐ(4)
                "",
                "",
                "", "", "", "", "", "", "", 1, 0
            );
            //$this->logwrite(json_encode($results["resistantLine"]), "debug");

            $results["aroundLine"] = [];
            $results["aroundLine"]["5"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "5", //株価との位置 .....●抵抗上ﾚﾝｼﾞ外(5)、抵抗付近ﾚﾝｼﾞ外(3)、抵抗付近ﾚﾝｼﾞ内(1)
                //                支持付近ﾚﾝｼﾞ内(2)、支持付近ﾚﾝｼﾞ外(4)、支持線下ﾚﾝｼﾞ外(6)
                //                移動平均付近上(moveAroundUp)、移動平均付近下(moveAroundDn)
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["aroundLine"]["3"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "3", //株価との位置 .....抵抗上ﾚﾝｼﾞ外(5)、●抵抗付近ﾚﾝｼﾞ外(3)、抵抗付近ﾚﾝｼﾞ内(1)、
                //                支持付近ﾚﾝｼﾞ内(2)、支持付近ﾚﾝｼﾞ外(4)、支持線下ﾚﾝｼﾞ外(6)
                //                移動平均付近上(moveAroundUp)、移動平均付近下(moveAroundDn)
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["aroundLine"]["1"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "1", //株価との位置 .....抵抗上ﾚﾝｼﾞ外(5)、抵抗付近ﾚﾝｼﾞ外(3)、●抵抗付近ﾚﾝｼﾞ内(1)、
                //                支持付近ﾚﾝｼﾞ内(2)、支持付近ﾚﾝｼﾞ外(4)、支持線下ﾚﾝｼﾞ外(6)
                //                移動平均付近上(moveAroundUp)、移動平均付近下(moveAroundDn)
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["aroundLine"]["2"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "2", //株価との位置 .....抵抗上ﾚﾝｼﾞ外(5)、抵抗付近ﾚﾝｼﾞ外(3)、抵抗付近ﾚﾝｼﾞ内(1)、
                //                ●支持付近ﾚﾝｼﾞ内(2)、支持付近ﾚﾝｼﾞ外(4)、支持線下ﾚﾝｼﾞ外(6)
                //                移動平均付近上(moveAroundUp)、移動平均付近下(moveAroundDn)
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["aroundLine"]["4"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "4", //株価との位置 .....抵抗上ﾚﾝｼﾞ外(5)、抵抗付近ﾚﾝｼﾞ外(3)、抵抗付近ﾚﾝｼﾞ内(1)、
                //                支持付近ﾚﾝｼﾞ内(2)、●支持付近ﾚﾝｼﾞ外(4)、支持線下ﾚﾝｼﾞ外(6)
                //                移動平均付近上(moveAroundUp)、移動平均付近下(moveAroundDn)
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["aroundLine"]["6"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "6", //株価との位置 .....抵抗上ﾚﾝｼﾞ外(5)、抵抗付近ﾚﾝｼﾞ外(3)、抵抗付近ﾚﾝｼﾞ内(1)、
                //                支持付近ﾚﾝｼﾞ内(2)、支持付近ﾚﾝｼﾞ外(4)、●支持線下ﾚﾝｼﾞ外(6)
                //                移動平均付近上(moveAroundUp)、移動平均付近下(moveAroundDn)
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["aroundLine"]["moveAroundUp"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "moveAroundUp", //株価との位置 .....抵抗上ﾚﾝｼﾞ外(5)、抵抗付近ﾚﾝｼﾞ外(3)、抵抗付近ﾚﾝｼﾞ内(1)、
                //                支持付近ﾚﾝｼﾞ内(2)、支持付近ﾚﾝｼﾞ外(4)、支持線下ﾚﾝｼﾞ外(6)
                //                ●移動平均付近上(moveAroundUp)、移動平均付近下(moveAroundDn)
                "",
                "", "", "", "", "", "", "", 1, 0
            );

            $results["aroundLine"]["moveAroundDn"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "moveAroundDn", //株価との位置 .....抵抗上ﾚﾝｼﾞ外(5)、抵抗付近ﾚﾝｼﾞ外(3)、抵抗付近ﾚﾝｼﾞ内(1)、
                //                支持付近ﾚﾝｼﾞ内(2)、支持付近ﾚﾝｼﾞ外(4)、支持線下ﾚﾝｼﾞ外(6)
                //                移動平均付近上(moveAroundUp)、●移動平均付近下(moveAroundDn)
                "",
                "", "", "", "", "", "", "", 1, 0
            );
            //$this->logwrite(json_encode($results["aroundLine"]), "debug");

            $results["bbPosition"] = [];
            $results["bbPosition"]["+3"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "+3", //BollingerBand と 株価 .... ●+3σ以上(+3)、+2σ以上(+2)、+2σを上↘下に(+2Dn)
                //                          -2σを下↗上に(-2Up)、-2σ以下(-2)、-3σ以下(-3)
                "", "", "", "", "", "", "", 1, 0
            );

            $results["bbPosition"]["+2"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "+2", //BollingerBand と 株価 .... +3σ以上(+3)、●+2σ以上(+2)、+2σを上↘下に(+2Dn)
                //                          -2σを下↗上に(-2Up)、-2σ以下(-2)、-3σ以下(-3)

                "", "", "", "", "", "", "", 1, 0
            );

            $results["bbPosition"]["+2Dn"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "+2Dn", //BollingerBand と 株価 .... +3σ以上(+3)、+2σ以上(+2)、●+2σを上↘下に(+2Dn)
                //                          -2σを下↗上に(-2Up)、-2σ以下(-2)、-3σ以下(-3)
                "", "", "", "", "", "", "", 1, 0
            );

            $results["bbPosition"]["-2Up"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "-2Up", //BollingerBand と 株価 .... +3σ以上(+3)、+2σ以上(+2)、+2σを上↘下に(+2Dn)
                //                          ●-2σを下↗上に(-2Up)、-2σ以下(-2)、-3σ以下(-3)
                "", "", "", "", "", "", "", 1, 0
            );

            $results["bbPosition"]["-2"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "-2", //BollingerBand と 株価 .... +3σ以上(+3)、+2σ以上(+2)、+2σを上↘下に(+2Dn)
                //                          -2σを下↗上に(-2Up)、●-2σ以下(-2)、-3σ以下(-3)
                "", "", "", "", "", "", "", 1, 0
            );

            $results["bbPosition"]["-3"] = ListedCompanies::selectSuggestionCount(
                $latestYmd, $ymd, $beforeYmd, $stockCode, "", 9999999999, 0, 0, 1,
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
                "-3", //BollingerBand と 株価 .... +3σ以上(+3)、+2σ以上(+2)、+2σを上↘下に(+2Dn)
                //                          -2σを下↗上に(-2Up)、-2σ以下(-2)、●-3σ以下(-3)
                "", "", "", "", "", "", "", 1, 0
            );
            //$this->logwrite(json_encode($results["bbPosition"]), "debug");

        }

        $json = json_encode($results);
        //$this->logwrite($json, "debug");
        echo $json;
    }

    /**
     * 銘柄詳細
     */
    public function detailAction($stockCode = "")
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        // 銘柄情報の取得
        $listedCompanies = new ListedCompanies();

        if (!empty($stockCode)) {
            $searchResults = ListedCompanies::selectTargetStockList($stockCode, self::STOCK_LIMIT);
            if (count($searchResults) > 0) {
                foreach ($searchResults as $searchResult) {
                    $title = $stockCode . ": " . $searchResult->getName();
                    break;
                }
            } else {
                $title = $stockCode . ": ----";
            }

            // 銘柄指定の検索エラー
            $stockError = ListedCompanies::selectTargetStockErrorMessages($stockCode);
            $this->view->setVar("stockError", $stockError);

        } else {
            $searchResults = null;
            $title = "----: ----";
        }

        // 銘柄コードをviewに渡す
        $this->view->setVar("stockCode", $stockCode);

        // 統計情報の取得 (学習数)
        $trainingInfos = $listedCompanies->getTrainingInfos();
        $trainingInfos['middle'] = $trainingInfos['training_middle'] - $trainingInfos['training_middle_error'];

        // 統計情報の取得 (予測数)
        $predictionInfos = $listedCompanies->getPredictionInfos();
        $predictionInfos['middle'] = $predictionInfos['prediction_middle'] - $predictionInfos['prediction_middle_error'];

        // 検索結果をVewに渡す
        $this->view->setVar("searchResults", $searchResults);

        // 統計情報をviewに渡す
        $this->view->setVar("trainingInfos", $trainingInfos);
        $this->view->setVar("predictionInfos", $predictionInfos);

        // titleタグをviewに渡す
        $this->view->setVar("title", $title . " | AIの株価予想【人工知能x株式投資ソフト】");
        $this->view->setVar("subTitle", "銘柄詳細");

        // 戻るリンクをviewに渡す
        $prevLink = $this->session->get("currentQueryString");
        if (!empty($prevLink)) {
            $prevLink = str_replace("#searchTop", "#stock" . $stockCode, $prevLink);
        }
        $this->view->setVar("description", "");
        $this->view->setVar("prevLink", $prevLink);
    }

    /**
     * 銘柄のツイート
     */
    public function tweetAction($stockCode = "")
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        // 銘柄情報の取得
        $listedCompanies = new ListedCompanies();

        $quary = "";

        if (!empty($stockCode)) {
            $searchResults = ListedCompanies::selectTargetStockList($stockCode, self::STOCK_LIMIT);
            if (count($searchResults) > 0) {
                foreach ($searchResults as $searchResult) {
                    $title = $stockCode . ": " . $searchResult->getName();
                    $quary = $searchResult->getName();
                    break;
                }
            } else {
                $title = $stockCode . ": ----";
            }

            // 銘柄指定の検索エラー
            $stockError = ListedCompanies::selectTargetStockErrorMessages($stockCode);
            $this->view->setVar("stockError", $stockError);

        } else {
            $searchResults = null;
            $title = "----: ----";
        }

        // 銘柄コードをviewに渡す
        $this->view->setVar("stockCode", $stockCode);

        // 統計情報の取得 (学習数)
        $trainingInfos = $listedCompanies->getTrainingInfos();
        $trainingInfos['middle'] = $trainingInfos['training_middle'] - $trainingInfos['training_middle_error'];

        // 統計情報の取得 (予測数)
        $predictionInfos = $listedCompanies->getPredictionInfos();
        $predictionInfos['middle'] = $predictionInfos['prediction_middle'] - $predictionInfos['prediction_middle_error'];

        // 検索結果をVewに渡す
        $this->view->setVar("searchResults", $searchResults);

        // 統計情報をviewに渡す
        $this->view->setVar("trainingInfos", $trainingInfos);
        $this->view->setVar("predictionInfos", $predictionInfos);

        // titleタグをviewに渡す
        $this->view->setVar("title", $title . " | AIの株価予想【人工知能x株式投資ソフト】");
        $this->view->setVar("subTitle", "銘柄詳細");

        // 戻るリンクをviewに渡す
        $prevLink = $this->session->get("currentQueryString");
        if (!empty($prevLink)) {
            $prevLink = str_replace("#searchTop", "#stock" . $stockCode, $prevLink);
        }
        $this->view->setVar("prevLink", $prevLink);

        // 銘柄のツイート情報を取得
        $consumerKey = self::API_KEY;
        $consumerSecret = self::API_KEY_SECRET;
        $accessToken = self::ACCESS_TOKEN;
        $accessTokenSecret = self::ACCESS_TOKEN_SECRET;

        $connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
        //$quaryの条件でツイートを検索
        $statuses = $connection->get('search/tweets', ['q' => $quary, 'count' => 500, 'tweet_mode' => 'extended']);

        $tweets = [];

        if (isset($statuses->errors)) {
            //取得失敗
            $item = 'エラー: ' . $statuses->errors[0]->message;
            $tweets[] = $item;
        } else {
            //検索結果がない場合はメッセージを表示
            if (count($statuses->statuses) == 0) {
                $item = "該当するツイートはありませんでした";
                $tweets[] = $item;
            } else {
                //取得成功
                foreach ($statuses->statuses as $tweet) {
                    $item = '名前: ' . $tweet->user->name . '<br>';
                    $item .= $tweet->full_text . '<br>';
                    $item .= '作成日: ' . date('Y-m-d H:i:s', strtotime($tweet->created_at));
                    $tweets[] = $item;
                }
            }
        }
        $this->view->setVar("description", "");
        $this->view->setVar("tweets", $tweets);
    }

    /**
     * 銘柄詳細
     */
    public function errorAction($stockCode = "")
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        // 訓練と予測のエラー情報を取得
        $listedCompanies = new ListedCompanies();
        $errors = $listedCompanies->getErrors();
        $this->view->setVar("errors", $errors);

        // titleタグをviewに渡す
        $this->view->setVar("title", "AIエラーの内訳 | AIの株価予想【人工知能x株式投資ソフト】");
    }

    /**
     * 取り扱い銘柄一覧
     */
    public function stocklistAction()
    {
        // ログイン処理
        $this->loginProcess();

        // お気に入り取得処理
        $this->favoriteProcess();

        // 銘柄情報の取得
        $listedCompanies = new ListedCompanies();
        $currentYmds = $listedCompanies->getCurrentYmds(self::STOCK_LIMIT);
        $latestYmd = $currentYmds[0];
        $beforeYmd = $listedCompanies->getBeforeYmd($latestYmd, 1);

        // 銘柄の検索処理 (一覧取得)
        $searchResults = ListedCompanies::selectSuggestionList(
            $latestYmd,
            $latestYmd,
            $beforeYmd,
            "",
            "",
            9999999999,
            100,
            0,
            0,
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
            "",
            "",
            "",
            "",
            "",
            "",
            10000,
            0
        );

        // 検索結果をVewに渡す
        $this->view->setVar("searchResults", $searchResults);

        // titleタグをviewに渡す
        $this->view->setVar("title", "取り扱い銘柄一覧 | AIの株価予想【人工知能x株式投資ソフト】");
    }
}

