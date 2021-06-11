<?php

class Bookmark extends \Phalcon\Mvc\Model
{
    // Bookmarkの種類
    const ALL = 0;
    const BUY_HOLD = 1; // (買)保有中
    const BUY_FAVORITE = 2; // (買)検討中
    const SELL_HOLD = 3;// (売)保有中
    const SELL_FAVORITE = 4;// (売)検討中

    // Bookmarkの種類一覧
    public $statusList = [
        self::BUY_HOLD,
        self::BUY_FAVORITE,
        self::SELL_HOLD,
        self::SELL_FAVORITE,
    ];

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $user_id;

    /**
     *
     * @var integer
     */
    protected $stock_code;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var integer
     */
    protected $sort_no;

    /**
     *
     * @var string
     */
    protected $memo;

    /**
     *
     * @var string
     */
    protected $checked_at;

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
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Method to set the value of field stock_code
     *
     * @param integer $stock_code
     * @return $this
     */
    public function setStockCode($stock_code)
    {
        $this->stock_code = $stock_code;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Method to set the value of field sort_no
     *
     * @param integer $sort_no
     * @return $this
     */
    public function setSortNo($sort_no)
    {
        $this->sort_no = $sort_no;

        return $this;
    }

    /**
     * Method to set the value of field memo
     *
     * @param string $memo
     * @return $this
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;

        return $this;
    }

    /**
     * Method to set the value of field checked_at
     *
     * @param string $checked_at
     * @return $this
     */
    public function setCheckedAt($checked_at)
    {
        $this->checked_at = $checked_at;

        return $this;
    }

    /**
     * Method to set the value of field created_at
     *
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Returns the value of field stock_code
     *
     * @return integer
     */
    public function getStockCode()
    {
        return $this->stock_code;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the value of field sort_no
     *
     * @return integer
     */
    public function getSortNo()
    {
        return $this->sort_no;
    }

    /**
     * Returns the value of field memo
     *
     * @return string
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * Returns the value of field checked_at
     *
     * @return string
     */
    public function getCheckedAt()
    {
        return $this->checked_at;
    }

    /**
     * Returns the value of field created_at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("finance");
        $this->setSource("bookmark");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'bookmark';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Bookmark[]|Bookmark|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Bookmark|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * 新規レコードの追加
     *
     * int $userId,
     * int $stockCode,
     * int $status
     */
    public function insert(
        int $userId,
        int $stockCode,
        int $status
    )
    {
        $ret = $this->save(
            [
                'user_id' => $userId,
                'stock_code' => $stockCode,
                'status' => $status,
                'sort_no' => 1,
                'memo' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        return $ret;
    }

    /**
     * ステータスの更新
     *
     * int $userId,
     * int $stockCode,
     * int $status
     */
    public function updateStatus(
        int $userId,
        int $stockCode,
        int $status
    )
    {
        $ret = false;

        $results = $this->selectByUserId($userId, $stockCode);
        if (count($results) > 0) {
            if ($results[0] !== false) {
                $results[0]->setStatus($status);
                $results[0]->setUpdatedAt(date("Y-m-d H:i:s"));
                $ret = $results[0]->save();
            }
        }
        return $ret;
    }

    /**
     * チェック年月日の更新
     *
     * int $userId,
     * int $stockCode,
     * $ymd
     */
    public function updateCheckedAt(
        int $userId,
        int $stockCode,
        $ymd
    )
    {
        $ret = false;

        $results = $this->selectByUserId($userId, $stockCode);
        if (count($results) > 0) {
            if ($results[0] !== false) {
                $results[0]->setCheckedAt($ymd);
                $results[0]->setUpdatedAt(date("Y-m-d H:i:s"));
                $ret = $results[0]->save();
            }
        }
        return $ret;
    }

    /**
     * レコードの削除
     *
     * @param int $userId
     * @param int $stockCode
     */
    public function deleteRecord(
        int $userId,
        int $stockCode
    )
    {
        $ret = false;

        $results = $this->selectByUserId($userId, $stockCode);
        if (count($results) > 0) {
            if ($results[0] !== false) {
                $ret = $results[0]->delete();
            }
        }
        return $ret;
    }

    /**
     * レコードを検索
     *
     * @param int $userId
     * @param int $stockCode
     * @param int $status
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function selectByUserId(
        int $userId,
        int $stockCode = 0,
        int $status = 0,
        int $limit = 1,
        int $offset = 0
    )
    {
        $criteria = self::query();
        $models = $criteria->where("user_id = :user_id:", ["user_id" => $userId]);

        if (!empty($stockCode)) {
            $models->andWhere("stock_code = :stock_code:", ["stock_code" => $stockCode]);
        }
        if (!empty($status)) {
            $models->andWhere("status = :status:", ["status" => $status]);
        }
        if ($limit > 0) {
            $models->orderBy('stock_code ASC');
            $models->limit($limit, $offset);
        }
        $results = $models->execute();

        return $results;
    }

    /**
     * レコード数の検索
     *
     * @param int $userId
     * @param int $stockCode
     * @param int $status
     * @return int
     */
    public function selectCountByUserId(
        int $userId,
        int $stockCode = 0,
        int $status = 0
    )
    {
        $result = $this->selectByUserId($userId, $stockCode, $status, 999999);
        return count($result);
    }

    /**
     * お気に入りランキング
     *
     * @param bool $isBuy
     * @return mixed]
     */
    public function getRanking($isBuy = true)
    {
        $sql = "SELECT stock_code, count(stock_code) as counts, max(updated_at) as updated, max(created_at) as checked";
        $sql .= " FROM bookmark";
        if ($isBuy) {
            $sql .= " WHERE status=" . self::BUY_HOLD . " OR status=" . self::BUY_FAVORITE;
        } else {
            $sql .= " WHERE status=" . self::SELL_HOLD . " OR status=" . self::SELL_FAVORITE;
        }
        $sql .= " GROUP by stock_code";
        $sql .= " ORDER BY counts desc, checked desc, updated desc";

        $adapter = $this->di->get('db'); //生SQLを記述する為
        $results = $adapter->fetchAll($sql);
        return $results;
    }

}
