<?php

class SearchLog extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $stock_code;

    /**
     *
     * @var string
     */
    protected $access_ymd;

    /**
     *
     * @var integer
     */
    protected $access_count;

    /**
     *
     * @var string
     */
    protected $created_at;

    /**
     *
     * @var string
     */
    protected $updated_at;


    /**
     * Returns the value of field stock_code
     *
     * @return integer
     */
    public function getAccessCount()
    {
        return $this->access_count;
    }


    /**
     * Returns the value of field updated_at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }


    /**
     * Method to set the value of field created_at
     *
     * @param int $accessCount
     * @return $this
     */
    public function setAccessCount($accessCount)
    {
        $this->access_count = $accessCount;
        return $this;
    }


    /**
     * Method to set the value of field updated_at
     *
     * @param string $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }


    /**
     * 銘柄コードの検索
     *
     * @param int $stockCode
     * @return array
     */
    public function selectByStockCode(
        int $stockCode = 0
    )
    {
        $criteria = self::query();
        $models = $criteria->where("stock_code = :stock_code:", ["stock_code" => $stockCode]);
        $models->andWhere("access_ymd = :access_ymd:", ["access_ymd" => date("Y-m-d")]);
        $models->orderBy('stock_code ASC');
        $models->limit(1, 0);
        $results = $models->execute();

        return $results;
    }

    /**
     * 銘柄検索ランキング
     *
     * @param bool $isBuy
     * @return mixed
     */
    public function getRanking($ymd, $limit = "")
    {
        $sql = "SELECT stock_code, sum(access_count) as count";
        $sql .= " FROM search_log";
        $sql .= " WHERE access_ymd >= '" . $ymd . "'";
        $sql .= " GROUP by stock_code";
        $sql .= " ORDER BY count desc, stock_code asc";
        if (!empty($limit)) {
            $sql .= " LIMIT " . $limit;
        }

        $adapter = $this->di->get('db'); //生SQLを記述する為
        $results = $adapter->fetchAll($sql);
        return $results;
    }

    /**
     * 銘柄アクセスカウントの増加
     *
     * @param int $stockCode
     * @return bool
     */
    public function addAccessCount(
        int $stockCode
    )
    {
        $ret = false;

        $results = $this->selectByStockCode($stockCode);
        if (count($results) > 0) {

            if ($results[0] !== false) {

                //レコードの更新（カウンタを+1する）
                $accessCount = $results[0]->getAccessCount() + 1;
                $results[0]->setAccessCount($accessCount);
                $results[0]->setUpdatedAt(date("Y-m-d"));
                $ret = $results[0]->save();
            }
        } else {
            //レコードの新規作成（カウンタを1にする）
            $ret = $this->save(
                [
                    'stock_code' => $stockCode,
                    'access_ymd' => date("Y-m-d"),
                    'access_count' => 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
        }
        return $ret;
    }

}
