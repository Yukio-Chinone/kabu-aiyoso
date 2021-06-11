<?php

use \Phalcon\Config\Adapter\Yaml;

class Stock extends \Phalcon\Mvc\Model
{
    /**
     * チャート図のURLフォーマット
     */
    const S3_PATH_FORMAT = "https://s3-us-west-2.amazonaws.com/dlearn-ai/finance/%s/image/%s/%s_%s.%s";

    /**
     * Yahooファイナンスの該当銘柄ページ
     */
    //const YAHOO_FINANCE = "https://stocks.finance.yahoo.co.jp/stocks/detail/?code=%s.%s";
    //const YAHOO_FINANCE = "https://stocks.finance.yahoo.co.jp/stocks/chart/?code=%s.%s&ct=w";
    const YAHOO_FINANCE = "https://stocks.finance.yahoo.co.jp/stocks/chart/?code=%s.%s&ct=z&t=3m&q=c&l=off&z=m&p=m65,m130,s&a=v,ss,r";

    /**
     * 休日・祝日
     */
    private $holiday = [];

    /**
     * 日経平均の銘柄コード
     * @var int
     */
    public static $nikkei225 = 998407;

    /**
     * 業種カテゴリー
     */
    public static $industries = array(
        "",
        "水産・農林業", //01
        "鉱業",
        "建設業",
        "食料品",
        "繊維製品",
        "パルプ・紙",
        "化学",
        "医薬品",
        "石油・石炭製品",
        "ゴム製品", //10
        "ガラス・土石製品",
        "鉄鋼",
        "非鉄金属",
        "金属製品",
        "機械",
        "電気機器",
        "輸送用機器",
        "精密機器",
        "その他製品",
        "電気・ガス業", //20
        "陸運業",
        "海運業",
        "空運業",
        "倉庫・運輸関連業",
        "情報・通信",
        "卸売業",
        "小売業",
        "銀行業",
        "証券業",
        "保険業", //30
        "その他金融業",
        "不動産業",
        "サービス業"
    );

    /**
     * 市場種別
     */
    public static $marketType = array(
        //          市場名,   正規表現,    URL用のコード
        0 => array('日経平均', '日経平均', 'O'),
        3 => array('東証1部', '東証1部.*', 'T'),
        4 => array('東証2部', '東証2部.*', 'T'),
        5 => array('マザーズ', 'マザーズ', 'T'),
        //10 => array('JASDAQｽﾀﾝﾀﾞｰﾄﾞ', '東証JQS', 'T'),
        //11 => array('JASDAQｸﾞﾛｰｽ', '東証JQG', 'T'),
        //13 => array('名証1部', '名証1部', 'N'),
        //14 => array('名証2部', '名証2部', 'N'),
        //15 => array('名証ｾﾝﾄﾚｯｸｽ', '名古屋セ', 'N'),
        //16 => array('札証', '(札幌ア|札証)', 'S'),
        //17 => array('福証', '(福証|福岡Q)', 'F'),
    );

    /**
     * 市場名
     */
    public static $searchMarket = array(
        3 => '東証1部',
        4 => '東証2部',
        5 => '東証マザーズ',
        //10 => 'JASDAQスタンダード',
        //11 => 'JASDAQグロース',
        //13 => '名証1部',
        //14 => '名証2部',
        //15 => '名証セントレックス',
        //16 => '札証',
        //17 => '福証',
    );

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->holiday = (array)$this->di->getConfig()->get("holiday");
    }

    /**
     * 曜日を取得
     *
     * @param string $ymd (YYYY-MM-DD)
     * @return 結果（曜日）
     */
    public static function getYoubi($ymd)
    {
        $ymd = str_replace("/", "", $ymd);
        $ymd = str_replace("-", "", $ymd);
        $datetime = new DateTime($ymd);
        $week = array("日", "月", "火", "水", "木", "金", "土");
        $w = (int)$datetime->format('w');
        return $week[$w];
    }

    /**
     * 東証の祝日チェック処理
     *
     * @param string $ymd (YYYY-MM-DD)
     * @return 結果（true:祝日、false:祝日以外）
     */
    public static function isHoliday($ymd)
    {
        if (in_array($ymd, self::holiday)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 東証の公開日チェック処理
     *
     * @param string $ymd (YYYY-MM-DD)
     * @return 結果（true:公開日、false:休日）
     */
    public function isOpenStockMarket($ymd)
    {
        $result = true;
        $yobi = self::getYoubi($ymd);
        if ($yobi == "土" || $yobi == "日") { //土日か？
            $result = false;
        }
        if (self::isHoliday($ymd)) { //東証の祝日か？
            $result = false;
        }
        return $result;
    }

    /**
     * スマートフォン判定
     *
     * @return bool
     */
    static function isSmartPhone()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $uaList = array('iPhone', 'iPad', 'iPod', 'Android'); //スマホと判定する文字リスト
        foreach ($uaList as $uaSmt) {
            if (strpos($ua, $uaSmt) !== false) {
                return true; //スマホ
            }
        }
        return false; //スマホ以外
    }

    /**
     * S3の画像をサーバーURLを返す
     *
     * @param string $stockCode
     * @param string $ymd
     * @param string $extension
     * @param string $country
     */
    public static function getChartUrl($stockCode = "", $ymd = "", $extension = "png", $country = "japan")
    {
        if (empty($stockCode) || empty($ymd)) {
            return "not image";
        }

        $filePath = sprintf(self::S3_PATH_FORMAT, $country, $stockCode, $stockCode, $ymd, $extension);
        return $filePath;
    }
}
