<?php

class ListedCompanies extends \Phalcon\Mvc\Model
{
    /**
     * 抵抗線/支持線付近の割合
     */
    public static $checkRange = 10;

    /**
     * 出来高が乱高下している時の標準偏差
     * @var float
     */
    public static $volumeStd = 0.3;

    /**
     * 訓練タイムアウト(秒)
     * @var int
     */
    public static $trainingTimeOut = 3600; #1.0h

    /**
     * 予想タイムアウト(秒)
     * @var int
     */
    public static $predictionTimeOut = 60;

    /**
     * AIのエラーメッセージ
     * @var array
     */
    public static $aiErrorMessages = [
        0 => "処理タイムアウト",
        1 => "銘柄データ無し",
        2 => "銘柄データDL失敗",
        3 => "データ不足で学習不可",
        4 => "訓練済AI無し",
        5 => "訓練済AIのDL失敗",
        8 => "データ不足で予測不可",
        9 => "低正解率の為AI更新なし",
        99 => "その他",
    ];

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $ymd;

    /**
     *
     * @var integer
     */
    protected $stock_code;

    /**
     *
     * @var integer
     */
    protected $number_of_days;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var integer
     */
    protected $open;

    /**
     *
     * @var integer
     */
    protected $high;

    /**
     *
     * @var integer
     */
    protected $low;

    /**
     *
     * @var integer
     */
    protected $close;

    /**
     *
     * @var integer
     */
    protected $sma75d;

    /**
     *
     * @var integer
     */
    protected $sma75d_up;

    /**
     *
     * @var integer
     */
    protected $volume;

    /**
     *
     * @var integer
     */
    protected $volume_up;

    /**
     *
     * @var double
     */
    protected $volume_std_devi;

    /**
     *
     * @var integer
     */
    protected $bollinger_p3;

    /**
     *
     * @var integer
     */
    protected $bollinger_p2;

    /**
     *
     * @var integer
     */
    protected $bollinger_p1;

    /**
     *
     * @var integer
     */
    protected $bollinger_m1;

    /**
     *
     * @var integer
     */
    protected $bollinger_m2;

    /**
     *
     * @var integer
     */
    protected $bollinger_m3;

    /**
     *
     * @var integer
     */
    protected $bollinger_p2_up;

    /**
     *
     * @var integer
     */
    protected $industry_id;

    /**
     *
     * @var integer
     */
    protected $market_no;

    /**
     *
     * @var integer
     */
    protected $cost;

    /**
     *
     * @var integer
     */
    protected $share_unit;

    /**
     *
     * @var integer
     */
    protected $started_time;

    /**
     *
     * @var integer
     */
    protected $ended_time;

    /**
     *
     * @var double
     */
    protected $correct_rate;

    /**
     *
     * @var double
     */
    protected $correct_rate_when_predicted;

    /**
     *
     * @var integer
     */
    protected $predicted_started_time;

    /**
     *
     * @var integer
     */
    protected $predicted_ended_time;

    /**
     *
     * @var double
     */
    protected $predicted_rate;

    /**
     *
     * @var double
     */
    protected $predicted_rate_max;

    /**
     *
     * @var double
     */
    protected $predicted_rate_min;

    /**
     *
     * @var integer
     */
    protected $predicted_close;

    /**
     *
     * @var double
     */
    protected $rate_of_increase;

    /**
     *
     * @var string
     */
    protected $current_trained_date;

    /**
     *
     * @var integer
     */
    protected $days_of_analysis;

    /**
     *
     * @var integer
     */
    protected $training_error_no;

    /**
     *
     * @var integer
     */
    protected $prodiction_error_no;

    /**
     *
     * @var integer
     */
    protected $p_sma5d;

    /**
     *
     * @var integer
     */
    protected $p_sma25d;

    /**
     *
     * @var integer
     */
    protected $p_sma75d;

    /**
     *
     * @var integer
     */
    protected $p_sma200d;

    /**
     *
     * @var integer
     */
    protected $p_sma13w;

    /**
     *
     * @var integer
     */
    protected $p_sma26w;

    /**
     *
     * @var integer
     */
    protected $p_sma52w;

    /**
     *
     * @var integer
     */
    protected $model_version;

    /**
     *
     * @var integer
     */
    protected $resistant_val;

    /**
     *
     * @var integer
     */
    protected $support_val;

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
     * Method to set the value of field ymd
     *
     * @param string $ymd
     * @return $this
     */
    public function setYmd($ymd)
    {
        $this->ymd = $ymd;

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
     * Method to set the value of field number_of_days
     *
     * @param integer $number_of_days
     * @return $this
     */
    public function setNumberOfDays($number_of_days)
    {
        $this->number_of_days = $number_of_days;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field open
     *
     * @param integer $open
     * @return $this
     */
    public function setOpen($open)
    {
        $this->open = $open;

        return $this;
    }

    /**
     * Method to set the value of field high
     *
     * @param integer $high
     * @return $this
     */
    public function setHigh($high)
    {
        $this->high = $high;

        return $this;
    }

    /**
     * Method to set the value of field low
     *
     * @param integer $low
     * @return $this
     */
    public function setLow($low)
    {
        $this->low = $low;

        return $this;
    }

    /**
     * Method to set the value of field close
     *
     * @param integer $close
     * @return $this
     */
    public function setClose($close)
    {
        $this->close = $close;

        return $this;
    }

    /**
     * Method to set the value of field sma75d
     *
     * @param integer $sma75d
     * @return $this
     */
    public function setSma75d($sma75d)
    {
        $this->sma75d = $sma75d;

        return $this;
    }

    /**
     * Method to set the value of field sma75d_up
     *
     * @param integer $sma75d_up
     * @return $this
     */
    public function setSma75dUp($sma75d_up)
    {
        $this->sma75d_up = $sma75d_up;

        return $this;
    }

    /**
     * Method to set the value of field volume
     *
     * @param integer $volume
     * @return $this
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Method to set the value of field volume_up
     *
     * @param integer $volume_up
     * @return $this
     */
    public function setVolumeUp($volume_up)
    {
        $this->volume_up = $volume_up;

        return $this;
    }

    /**
     * Method to set the value of field volume_std_devi
     *
     * @param double $volume_std_devi
     * @return $this
     */
    public function setVolumeStdDevi($volume_std_devi)
    {
        $this->volume_std_devi = $volume_std_devi;

        return $this;
    }

    /**
     * Method to set the value of field bollinger_p3
     *
     * @param integer $bollinger_p3
     * @return $this
     */
    public function setBollingerP3($bollinger_p3)
    {
        $this->bollinger_p3 = $bollinger_p3;

        return $this;
    }

    /**
     * Method to set the value of field bollinger_p2
     *
     * @param integer $bollinger_p2
     * @return $this
     */
    public function setBollingerP2($bollinger_p2)
    {
        $this->bollinger_p2 = $bollinger_p2;

        return $this;
    }

    /**
     * Method to set the value of field bollinger_p1
     *
     * @param integer $bollinger_p1
     * @return $this
     */
    public function setBollingerP1($bollinger_p1)
    {
        $this->bollinger_p1 = $bollinger_p1;

        return $this;
    }

    /**
     * Method to set the value of field bollinger_m1
     *
     * @param integer $bollinger_m1
     * @return $this
     */
    public function setBollingerM1($bollinger_m1)
    {
        $this->bollinger_m1 = $bollinger_m1;

        return $this;
    }

    /**
     * Method to set the value of field bollinger_m2
     *
     * @param integer $bollinger_m2
     * @return $this
     */
    public function setBollingerM2($bollinger_m2)
    {
        $this->bollinger_m2 = $bollinger_m2;

        return $this;
    }

    /**
     * Method to set the value of field bollinger_m3
     *
     * @param integer $bollinger_m3
     * @return $this
     */
    public function setBollingerM3($bollinger_m3)
    {
        $this->bollinger_m3 = $bollinger_m3;

        return $this;
    }

    /**
     * Method to set the value of field bollinger_p2_up
     *
     * @param integer $bollinger_p2_up
     * @return $this
     */
    public function setBollingerP2Up($bollinger_p2_up)
    {
        $this->bollinger_p2_up = $bollinger_p2_up;

        return $this;
    }

    /**
     * Method to set the value of field industry_id
     *
     * @param integer $industry_id
     * @return $this
     */
    public function setIndustryId($industry_id)
    {
        $this->industry_id = $industry_id;

        return $this;
    }

    /**
     * Method to set the value of field market_no
     *
     * @param integer $market_no
     * @return $this
     */
    public function setMarketNo($market_no)
    {
        $this->market_no = $market_no;

        return $this;
    }

    /**
     * Method to set the value of field cost
     *
     * @param integer $cost
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Method to set the value of field share_unit
     *
     * @param integer $share_unit
     * @return $this
     */
    public function setShareUnit($share_unit)
    {
        $this->share_unit = $share_unit;

        return $this;
    }

    /**
     * Method to set the value of field started_time
     *
     * @param integer $started_time
     * @return $this
     */
    public function setStartedTime($started_time)
    {
        $this->started_time = $started_time;

        return $this;
    }

    /**
     * Method to set the value of field ended_time
     *
     * @param integer $ended_time
     * @return $this
     */
    public function setEndedTime($ended_time)
    {
        $this->ended_time = $ended_time;

        return $this;
    }

    /**
     * Method to set the value of field correct_rate
     *
     * @param double $correct_rate
     * @return $this
     */
    public function setCorrectRate($correct_rate)
    {
        $this->correct_rate = $correct_rate;

        return $this;
    }

    /**
     * Method to set the value of field correct_rate_when_predicted
     *
     * @param double $correct_rate_when_predicted
     * @return $this
     */
    public function setCorrectRateWhenPredicted($correct_rate_when_predicted)
    {
        $this->correct_rate_when_predicted = $correct_rate_when_predicted;

        return $this;
    }

    /**
     * Method to set the value of field predicted_started_time
     *
     * @param integer $predicted_started_time
     * @return $this
     */
    public function setPredictedStartedTime($predicted_started_time)
    {
        $this->predicted_started_time = $predicted_started_time;

        return $this;
    }

    /**
     * Method to set the value of field predicted_ended_time
     *
     * @param integer $predicted_ended_time
     * @return $this
     */
    public function setPredictedEndedTime($predicted_ended_time)
    {
        $this->predicted_ended_time = $predicted_ended_time;

        return $this;
    }

    /**
     * Method to set the value of field predicted_rate
     *
     * @param double $predicted_rate
     * @return $this
     */
    public function setPredictedRate($predicted_rate)
    {
        $this->predicted_rate = $predicted_rate;

        return $this;
    }

    /**
     * Method to set the value of field predicted_rate_max
     *
     * @param double $predicted_rate_max
     * @return $this
     */
    public function setPredictedRateMax($predicted_rate_max)
    {
        $this->predicted_rate_max = $predicted_rate_max;

        return $this;
    }

    /**
     * Method to set the value of field predicted_rate_min
     *
     * @param double $predicted_rate_min
     * @return $this
     */
    public function setPredictedRateMin($predicted_rate_min)
    {
        $this->predicted_rate_min = $predicted_rate_min;

        return $this;
    }

    /**
     * Method to set the value of field predicted_close
     *
     * @param integer $predicted_close
     * @return $this
     */
    public function setPredictedClose($predicted_close)
    {
        $this->predicted_close = $predicted_close;

        return $this;
    }

    /**
     * Method to set the value of field rate_of_increase
     *
     * @param double $rate_of_increase
     * @return $this
     */
    public function setRateOfIncrease($rate_of_increase)
    {
        $this->rate_of_increase = $rate_of_increase;

        return $this;
    }

    /**
     * Method to set the value of field current_trained_date
     *
     * @param string $current_trained_date
     * @return $this
     */
    public function setCurrentTrainedDate($current_trained_date)
    {
        $this->current_trained_date = $current_trained_date;

        return $this;
    }

    /**
     * Method to set the value of field days_of_analysis
     *
     * @param integer $days_of_analysis
     * @return $this
     */
    public function setDaysOfAnalysis($days_of_analysis)
    {
        $this->days_of_analysis = $days_of_analysis;

        return $this;
    }

    /**
     * Method to set the value of field training_error_no
     *
     * @param integer $training_error_no
     * @return $this
     */
    public function setTrainingErrorNo($training_error_no)
    {
        $this->training_error_no = $training_error_no;

        return $this;
    }

    /**
     * Method to set the value of field prodiction_error_no
     *
     * @param integer $prodiction_error_no
     * @return $this
     */
    public function setProdictionErrorNo($prodiction_error_no)
    {
        $this->prodiction_error_no = $prodiction_error_no;

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
     * Returns the value of field ymd
     *
     * @return string
     */
    public function getYmd()
    {
        return $this->ymd;
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
     * Returns the value of field number_of_days
     *
     * @return integer
     */
    public function getNumberOfDays()
    {
        return $this->number_of_days;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field open
     *
     * @return integer
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * Returns the value of field high
     *
     * @return integer
     */
    public function getHigh()
    {
        return $this->high;
    }

    /**
     * Returns the value of field low
     *
     * @return integer
     */
    public function getLow()
    {
        return $this->low;
    }

    /**
     * Returns the value of field close
     *
     * @return integer
     */
    public function getClose()
    {
        return $this->close;
    }

    /**
     * Returns the value of field sma75d
     *
     * @return integer
     */
    public function getSma75d()
    {
        return $this->sma75d;
    }

    /**
     * Returns the value of field sma75d_up
     *
     * @return integer
     */
    public function getSma75dUp()
    {
        return $this->sma75d_up;
    }

    /**
     * Returns the value of field volume
     *
     * @return integer
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Returns the value of field volume_up
     *
     * @return integer
     */
    public function getVolumeUp()
    {
        return $this->volume_up;
    }

    /**
     * Returns the value of field volume_std_devi
     *
     * @return double
     */
    public function getVolumeStdDevi()
    {
        return $this->volume_std_devi;
    }

    /**
     * Returns the value of field bollinger_p3
     *
     * @return integer
     */
    public function getBollingerP3()
    {
        return $this->bollinger_p3;
    }

    /**
     * Returns the value of field bollinger_p2
     *
     * @return integer
     */
    public function getBollingerP2()
    {
        return $this->bollinger_p2;
    }

    /**
     * Returns the value of field bollinger_p1
     *
     * @return integer
     */
    public function getBollingerP1()
    {
        return $this->bollinger_p1;
    }

    /**
     * Returns the value of field bollinger_m1
     *
     * @return integer
     */
    public function getBollingerM1()
    {
        return $this->bollinger_m1;
    }

    /**
     * Returns the value of field bollinger_m2
     *
     * @return integer
     */
    public function getBollingerM2()
    {
        return $this->bollinger_m2;
    }

    /**
     * Returns the value of field bollinger_m3
     *
     * @return integer
     */
    public function getBollingerM3()
    {
        return $this->bollinger_m3;
    }

    /**
     * Returns the value of field bollinger_p2_up
     *
     * @return integer
     */
    public function getBollingerP2Up()
    {
        return $this->bollinger_p2_up;
    }

    /**
     * Returns the value of field industry_id
     *
     * @return integer
     */
    public function getIndustryId()
    {
        return $this->industry_id;
    }

    /**
     * Returns the value of field market_no
     *
     * @return integer
     */
    public function getMarketNo()
    {
        return $this->market_no;
    }

    /**
     * Returns the value of field cost
     *
     * @return integer
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Returns the value of field share_unit
     *
     * @return integer
     */
    public function getShareUnit()
    {
        return $this->share_unit;
    }

    /**
     * Returns the value of field started_time
     *
     * @return integer
     */
    public function getStartedTime()
    {
        return $this->started_time;
    }

    /**
     * Returns the value of field ended_time
     *
     * @return integer
     */
    public function getEndedTime()
    {
        return $this->ended_time;
    }

    /**
     * Returns the value of field correct_rate
     *
     * @return double
     */
    public function getCorrectRate()
    {
        return $this->correct_rate;
    }

    /**
     * Returns the value of field correct_rate_when_predicted
     *
     * @return double
     */
    public function getCorrectRateWhenPredicted()
    {
        return $this->correct_rate_when_predicted;
    }

    /**
     * Returns the value of field predicted_started_time
     *
     * @return integer
     */
    public function getPredictedStartedTime()
    {
        return $this->predicted_started_time;
    }

    /**
     * Returns the value of field predicted_ended_time
     *
     * @return integer
     */
    public function getPredictedEndedTime()
    {
        return $this->predicted_ended_time;
    }

    /**
     * Returns the value of field predicted_rate
     *
     * @return double
     */
    public function getPredictedRate()
    {
        return $this->predicted_rate;
    }

    public static function getPredictedRates($stockCode, $limit = 10)
    {
        $criteria = self::query();
        $models = $criteria
            ->columns('predicted_rate')
            ->where('stock_code = :stock_code:', ['stock_code' => $stockCode])
            ->orderBy('ymd DESC')
            ->limit($limit, 0)
            ->execute();

        $result = [];
        if (sizeof($models) > 0) {
            foreach ($models as $model) {
                $shearRrate = round(100 - $model['predicted_rate'], 2);
                $result[] = $shearRrate;
            }
        }
        return $result;
    }

    /**
     * Returns the value of field predicted_rate_max
     *
     * @return double
     */
    public function getPredictedRateMax()
    {
        return $this->predicted_rate_max;
    }

    /**
     * Returns the value of field predicted_rate_min
     *
     * @return double
     */
    public function getPredictedRateMin()
    {
        return $this->predicted_rate_min;
    }

    /**
     * Returns the value of field predicted_close
     *
     * @return integer
     */
    public function getPredictedClose()
    {
        return $this->predicted_close;
    }

    /**
     * Returns the value of field rate_of_increase
     *
     * @return double
     */
    public function getRateOfIncrease()
    {
        return $this->rate_of_increase;
    }

    /**
     * Returns the value of field current_trained_date
     *
     * @return string
     */
    public function getCurrentTrainedDate()
    {
        return $this->current_trained_date;
    }

    /**
     * @return mixed
     */
    public function getTargetTrainedYmd()
    {
        return $this->target_trained_ymd;
    }

    /**
     * Returns the value of field days_of_analysis
     *
     * @return integer
     */
    public function getDaysOfAnalysis()
    {
        return $this->days_of_analysis;
    }

    /**
     * Returns the value of field training_error_no
     *
     * @return integer
     */
    public function getTrainingErrorNo()
    {
        return $this->training_error_no;
    }

    /**
     * Returns the value of field prodiction_error_no
     *
     * @return integer
     */
    public function getProdictionErrorNo()
    {
        return $this->prodiction_error_no;
    }

    /**
     * @return int
     */
    public function getPsma5d()
    {
        return $this->p_sma5d;
    }

    /**
     * @return int
     */
    public function getPsma25d()
    {
        return $this->p_sma25d;
    }

    /**
     * @return int
     */
    public function getPsma75d()
    {
        return $this->p_sma75d;
    }

    /**
     * @return int
     */
    public function getPsma200d()
    {
        return $this->p_sma200d;
    }

    /**
     * @return int
     */
    public function getPsma13w()
    {
        return $this->p_sma13w;
    }

    /**
     * @return int
     */
    public function getPsma26w()
    {
        return $this->p_sma26w;
    }

    /**
     * @return int
     */
    public function getPsma52w()
    {
        return $this->p_sma52w;
    }

    public function getRsi()
    {
        return $this->rsi;
    }

    /**
     * @return int
     */
    public function getModelVersion()
    {
        return $this->model_version;
    }

    /**
     * @return mixed
     */
    public function getModelVersionPredict()
    {
        return $this->model_version_predict;
    }

    /**
     * Returns the value of field resistant_val
     *
     * @return string
     */
    public function getResistantVal()
    {
        return $this->resistant_val;
    }

    /**
     * Returns the value of field support_val
     *
     * @return string
     */
    public function getSupportVal()
    {
        return $this->support_val;
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
        $this->setSource("listed_companies");
        $this->adapter = $this->di->get('db'); //生SQLを記述する為
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'listed_companies';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ListedCompanies[]|ListedCompanies|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ListedCompanies|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }


    /**
     * 最新の銘柄情報を取得
     *
     * @param int $stockCode
     * @return |null
     */
    public static function findByStockCode(int $stockCode)
    {
        $criteria = self::query();
        $models = $criteria
            ->where('stock_code = :stock_code:', ['stock_code' => $stockCode])
            ->orderBy('ymd DESC')
            ->limit(1, 0)
            ->execute();

        if (sizeof($models) > 0) {
            $result = $models[0];
        } else {
            $result = null;
        }
        return $result;
    }

    /**
     * 最新の訓練日時
     *
     * @param $stockCode
     * @param $ymd
     * @return mixed
     */
    public static function getLatestTrainedDate($stockCode, $ymd = null)
    {

        $criteria = self::query();
        $models = $criteria
            ->where('stock_code = :stock_code:', ['stock_code' => $stockCode]);

        if (!empty($ymd)) {
            $models->andWhere("ymd <= :ymd:", ["ymd" => $ymd]);
        }

        $models
            ->andWhere("model_version > 0")
            ->orderBy('ymd DESC')
            ->limit(1, 0)
            ->execute();

        $obj = $models->execute();
        if (!empty($obj)) {
            if(isset($obj[0])) {
                $result = $obj[0]->getYmd();
            }
            else{
                $result = "....";
            }
        } else {
            $result = "-";
        }
        return $result;
    }

    /**
     * お薦め銘柄一覧の取得
     *
     * @param $latestYmd 最新の年月日
     * @param $ymd 対象年月日
     * @param $stockCode 銘柄コード
     * @param string $marketNo 市場コード
     * @param string $cost 購入価格
     * @param float $predictedRate 予想の正解率
     * @param float $correctRate 訓練後の正解率
     * @param float $rateOfIncrease 上昇率
     * @param string $updown ">=" または "<="
     * @param string $sma75dClose
     * @param string $sma75dUp
     * @param string $sma25dUp
     * @param string $bollingerP2Up
     * @param string $bandwalk
     * @param string $upBeard
     * @param string $downBeard
     * @param string $crossfoot
     * @param string $straddleLine
     * @param string $resistantLine
     * @param string $aroundLine
     * @param string $bbPosition
     * @param string $profitValue
     * @param string $rsi
     * @param string $orderCorrectRate
     * @param string $orderPredictedRate
     * @param string $rateOfIncreaseUp
     * @param string $rateOfIncreaseDn
     * @param string $volumeUp
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public static function selectSuggestionList(
        $latestYmd,
        $ymd,
        $beforeYmd,
        $stockCode = "",
        $marketNo = "",
        $cost = "",
        float $predictedRate = 0,
        float $correctRate = 0,
        float $rateOfIncrease = 0,
        $updown = "",
        $sma75dClose = "",
        $sma75dUp = "",
        $sma25dUp = "",
        $bollingerP2Up = "",
        $bandwalk = "",
        $upBeard = "",
        $downBeard = "",
        $crossfoot = "",
        $straddleLine = "",
        $resistantLine = "",
        $aroundLine = "",
        $bbPosition = "",
        $profitValue = "",
        $rsi = "",
        $orderCorrectRate = "",
        $orderPredictedRate = "",
        $rateOfIncreaseUp = "",
        $rateOfIncreaseDn = "",
        $volumeUp = "",
        int $limit = 0,
        int $offset = 0
    )
    {
        $criteria = self::query();
        $models = $criteria
            ->where("ymd = :ymd:", ["ymd" => $ymd])
            ->andWhere("cost <= :cost:", ["cost" => $cost]);

        if ($predictedRate > 0) {
            $models->andWhere("predicted_rate >= :predicted_rate:", ["predicted_rate" => floatval(100 - $predictedRate)]);
        }
        if ($correctRate > 0) {
            $models->andWhere("correct_rate_when_predicted >= :correct_rate_when_predicted:", ["correct_rate_when_predicted" => floatval($correctRate)]);
        }
        if ($updown !== "") {
            if ($updown === ">=") {
                $models->andWhere("rate_of_increase >= :rate_of_increase:", ['rate_of_increase' => floatval($rateOfIncrease)]);
            } else if ($updown === "<=") {
                $models->andWhere("rate_of_increase <= :rate_of_increase:", ['rate_of_increase' => floatval(-1 * $rateOfIncrease)]);
            }
        }
        if ($stockCode !== "") {
            $models->andWhere("stock_code = :stock_code: OR name LIKE :name:",
                [
                    "stock_code" => $stockCode,
                    "name" => "%" . $stockCode . "%"
                ]);
        }
        if ($marketNo !== "") {
            $models->andWhere("market_no = :market_no:", ["market_no" => $marketNo]);
        }
        if ($rsi !== "") {
            if ($rsi == 70) {
                $models->andWhere("rsi >= 70");
            } else if ($rsi == 30) {
                $models->andWhere("rsi <= 30");
            } else {
                $models->andWhere("rsi > 30 ");
                $models->andWhere("rsi < 70 ");
            }
        }
        if ($sma75dClose !== "") {
            if ($sma75dClose == 1) { //75日移動平均の上にロウソク足 (上昇傾向)
                $models->andWhere("close > sma75d");

            } else { //75日移動平均の下にロウソク足 (下降傾向)
                $models->andWhere("close < sma75d");
            }
        }
        if ($sma75dUp !== "") {
            if ($sma75dUp == 1) { //75日移動平均が上向き (上昇)
                $models->andWhere("sma75d_up = 1");
            } else { //75日移動平均が下向き (下降)
                $models->andWhere("sma75d_up = 0");
            }
        }
        if ($sma25dUp !== "") {
            if ($sma25dUp == 1) { //25日移動平均が上向き (上昇)
                $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND L2.p_sma25d < ListedCompanies.p_sma25d
                AND L2.ymd = '{$beforeYmd}')";
                $models->andWhere($subQuery);
            } else { //75日移動平均が下向き (下降)
                $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND L2.p_sma25d > ListedCompanies.p_sma25d
                AND L2.ymd = '{$beforeYmd}')";
                $models->andWhere($subQuery);
            }
        }
        if ($volumeUp !== "") {
            if ($volumeUp == 1) {//出来高が徐々に上昇 (安定)
                $models
                    ->andWhere("volume_up = 1")
                    ->andWhere("volume_std_devi < :volume_std_devi:", ["volume_std_devi" => self::$volumeStd]);
            } else { //出来高が徐々に下降 (安定)
                $models
                    ->andWhere("volume_up = 0")
                    ->andWhere("volume_std_devi < :volume_std_devi:", ["volume_std_devi" => self::$volumeStd]);
            }
        }
        if ($bollingerP2Up !== "") {
            if ($bollingerP2Up == 1) { //ボリンジャーバンドが上昇傾向
                $models->andWhere("bollinger_p2_up = 1");
            } else { //ボリンジャーバンドが下降傾向
                $models->andWhere("bollinger_p2_up = 0");
            }
        }
        if ($bandwalk !== "") { //バンドウォーク (±2σラインに沿う)
            $models->andWhere("(close BETWEEN bollinger_p3 AND bollinger_p2) OR (close BETWEEN bollinger_m1 AND bollinger_m3)");
        }
        if ($crossfoot !== "") { //十字足(気迷い)を含めない
            $models->andWhere("open != close");
        }

        if ($straddleLine !== "") {

            //上昇
            $subQuery25dUp = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close >  ListedCompanies.p_sma25d 
                AND L2.close < ListedCompanies.p_sma25d 
                AND L2.ymd = '{$beforeYmd}')";
            $subQuery25dUp .= " AND close >= p_sma25d AND p_sma25d >= open"; //当日の始値と終値で挟む（歯抜け以外）

            $subQuery75dUp = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close >  ListedCompanies.p_sma75d 
                AND L2.close < ListedCompanies.p_sma75d 
                AND L2.ymd = '{$beforeYmd}')";
            $subQuery75dUp .= " AND close >= p_sma75d AND p_sma75d >= open"; //当日の始値と終値で挟む（歯抜け以外）

            $subQuery13wUp = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close >  ListedCompanies.p_sma13w
                AND L2.close < ListedCompanies.p_sma13w
                AND L2.ymd = '{$beforeYmd}')";
            $subQuery13wUp .= " AND close >= p_sma13w AND p_sma13w >= open"; //当日の始値と終値で挟む（歯抜け以外）

            $subQuery26wUp = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close >  ListedCompanies.p_sma26w
                AND L2.close < ListedCompanies.p_sma26w
                AND L2.ymd = '{$beforeYmd}')";
            $subQuery26wUp .= " AND close >= p_sma26w AND p_sma26w >= open"; //当日の始値と終値で挟む（歯抜け以外）

            $subQuery52wUp = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close >  ListedCompanies.p_sma52w
                AND L2.close < ListedCompanies.p_sma52w
                AND L2.ymd = '{$beforeYmd}')";
            $subQuery52wUp .= " AND close >= p_sma52w AND p_sma52w >= open"; //当日の始値と終値で挟む（歯抜け以外）

            //下降
            $subQuery25dDn = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close <  ListedCompanies.p_sma25d 
                AND L2.close > ListedCompanies.p_sma25d 
                AND L2.ymd = '{$beforeYmd}')";
            $subQuery25dDn .= " AND open >= p_sma25d AND p_sma25d >= close"; //当日の始値と終値で挟む（歯抜け以外）

            $subQuery75dDn = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close <  ListedCompanies.p_sma75d 
                AND L2.close > ListedCompanies.p_sma75d 
                AND L2.ymd = '{$beforeYmd}')";
            $subQuery75dDn .= " AND open >= p_sma75d AND p_sma75d >= close"; //当日の始値と終値で挟む（歯抜け以外）

            $subQuery13wDn = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close <  ListedCompanies.p_sma13w
                AND L2.close > ListedCompanies.p_sma13w
                AND L2.ymd = '{$beforeYmd}')";
            $subQuery13wDn .= " AND open >= p_sma13w AND p_sma13w >= close"; //当日の始値と終値で挟む（歯抜け以外）

            $subQuery26wDn = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close <  ListedCompanies.p_sma26w
                AND L2.close > ListedCompanies.p_sma26w
                AND L2.ymd = '{$beforeYmd}')";
            $subQuery26wDn .= " AND open >= p_sma26w AND p_sma26w >= close"; //当日の始値と終値で挟む（歯抜け以外）

            $subQuery52wDn = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close <  ListedCompanies.p_sma52w
                AND L2.close > ListedCompanies.p_sma52w
                AND L2.ymd = '{$beforeYmd}')";
            $subQuery52wDn .= " AND open >= p_sma52w AND p_sma52w >= close"; //当日の始値と終値で挟む（歯抜け以外）

            if ($straddleLine === "moveUp") {
                // 移動平均線を下から上に跨いだ (前日から当日にかけて)
                $models->andWhere(
                    "(($subQuery25dUp) OR ($subQuery75dUp) OR ($subQuery13wUp) OR ($subQuery26wUp) OR ($subQuery52wUp))"
                );
            }
            if ($straddleLine === "moveDn") {
                // 移動平均線を上から下に跨いだ (前日から当日にかけて)
                $models->andWhere(
                    "(($subQuery25dDn) OR ($subQuery75dDn) OR ($subQuery13wDn) OR ($subQuery26wDn) OR ($subQuery52wDn))"
                );
            }

            if ($straddleLine === "25dUp") {
                // 25日移動平均線を下から上に跨いだ (前日から当日にかけて)
                $models->andWhere($subQuery25dUp);
            }
            if ($straddleLine === "75dUp") {
                // 75日移動平均線を下から上に跨いだ (前日から当日にかけて)
                $models->andWhere($subQuery75dUp);
            }
            if ($straddleLine === "13wUp") {
                // 13週移動平均線を下から上に跨いだ (前日から当日にかけて)
                $models->andWhere($subQuery13wUp);
            }
            if ($straddleLine === "26wUp") {
                // 26週移動平均線を下から上に跨いだ (前日から当日にかけて)
                $models->andWhere($subQuery26wUp);
            }
            if ($straddleLine === "52wUp") {
                // 52週移動平均線を下から上に跨いだ (前日から当日にかけて)
                $models->andWhere($subQuery52wUp);
            }
            if ($straddleLine === "25dDn") {
                // 25日移動平均線を上から下に跨いだ (前日から当日にかけて)
                $models->andWhere($subQuery25dDn);
            }
            if ($straddleLine === "75dDn") {
                // 75日移動平均線を上から下に跨いだ (前日から当日にかけて)
                $models->andWhere($subQuery75dDn);
            }
            if ($straddleLine === "13wDn") {
                // 13週移動平均線を上から下に跨いだ (前日から当日にかけて)
                $models->andWhere($subQuery13wDn);
            }
            if ($straddleLine === "26wDn") {
                // 26週移動平均線を上から下に跨いだ (前日から当日にかけて)
                $models->andWhere($subQuery26wDn);
            }
            if ($straddleLine === "52wDn") {
                // 52週移動平均線を上から下に跨いだ (前日から当日にかけて)
                $models->andWhere($subQuery52wDn);
            }
        }

        if ($resistantLine !== "") { //株価が抵抗線や支持線を跨いだ
            if ($resistantLine === "1") {
                // 抵抗線を下から上に跨いだ (前日から当日にかけて)
                $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close >  ListedCompanies.resistant_val
                AND L2.close < ListedCompanies.resistant_val
                AND L2.ymd = '{$beforeYmd}')";
                $models->andWhere($subQuery);
            }
            if ($resistantLine === "2") {
                // 支持線を上から下に跨いだ (前日から当日にかけて)
                $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close <  ListedCompanies.support_val
                AND L2.close > ListedCompanies.support_val
                AND L2.ymd = '{$beforeYmd}')";
                $models->andWhere($subQuery);
            }
            if ($resistantLine === "3") {
                // 抵抗線を上から下に跨いだ (前日から当日にかけて)
                $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close <  ListedCompanies.resistant_val
                AND L2.close > ListedCompanies.resistant_val
                AND L2.ymd = '{$beforeYmd}')";
                $models->andWhere($subQuery);
            }
            if ($resistantLine === "4") {
                // 支持線を下から上に跨いだ (前日から当日にかけて)
                $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close >  ListedCompanies.support_val
                AND L2.close < ListedCompanies.support_val
                AND L2.ymd = '{$beforeYmd}')";
                $models->andWhere($subQuery);
            }

        }

        if ($aroundLine !== "") {
            if ($aroundLine === "1") {
                // 抵抗線付近 (レンジ内)
                $models->andWhere("resistant_val > 0 ");
                $models->andWhere("support_val > 0");
                $models->andWhere("resistant_val > support_val ");
                $models->andWhere("resistant_val >= close");
                $models->andWhere("close >= (resistant_val - ((resistant_val - support_val) / " . self::$checkRange . "))"); # レンジをn分の1に区分する
            }
            if ($aroundLine === "2") {
                // 支持線付近 (レンジ内)
                $models->andWhere("resistant_val > 0 ");
                $models->andWhere("support_val > 0");
                $models->andWhere("resistant_val > support_val ");
                $models->andWhere("support_val <= close");
                $models->andWhere("close <= (support_val + ((resistant_val - support_val) / " . self::$checkRange . "))"); # レンジをn分の1に区分する
            }
            if ($aroundLine === "3") {
                // 抵抗線付近 (レンジ外)
                $models->andWhere("resistant_val > 0 ");
                $models->andWhere("support_val > 0");
                $models->andWhere("resistant_val > support_val ");
                $models->andWhere("resistant_val < close");
                $models->andWhere("close <= (resistant_val + ((resistant_val - support_val) / " . self::$checkRange . "))"); # レンジをn分の1に区分する
            }
            if ($aroundLine === "4") {
                // 支持線付近 (レンジ外)
                $models->andWhere("resistant_val > 0 ");
                $models->andWhere("support_val > 0");
                $models->andWhere("resistant_val > support_val ");
                $models->andWhere("support_val > close");
                $models->andWhere("close >= (support_val - ((resistant_val - support_val) / " . self::$checkRange . "))"); # レンジをn分の1に区分する
            }
            if ($aroundLine === "5") {
                // 抵抗線以上 (レンジ外)
                $models->andWhere("resistant_val > 0 ");
                $models->andWhere("resistant_val < close");
            }
            if ($aroundLine === "6") {
                // 支持線以下 (レンジ外)
                $models->andWhere("support_val > 0");
                $models->andWhere("support_val > close");
            }

            if ($aroundLine === "moveAroundUp") {
                // 移動平均の上付近
                $sqlAdd1 = "(close >= p_sma25d) AND ((p_sma25d + ABS(open - close)) >= close)";
                $sqlAdd2 = "(close >= p_sma75d) AND ((p_sma75d + ABS(open - close)) >= close)";
                $sqlAdd3 = "(close >= p_sma13w) AND ((p_sma13w + ABS(open - close)) >= close)";
                $sqlAdd4 = "(close >= p_sma26w) AND ((p_sma26w + ABS(open - close)) >= close)";
                $sqlAdd5 = "(close >= p_sma52w) AND ((p_sma52w + ABS(open - close)) >= close)";
                $sqlAddAll = "($sqlAdd1 OR $sqlAdd2 OR $sqlAdd3 OR $sqlAdd4 OR $sqlAdd5)";
                $models->andWhere($sqlAddAll);
            }
            if ($aroundLine === "moveAroundDn") {
                // 移動平均の下付近
                $sqlAdd1 = "(close <= p_sma25d) AND ((p_sma25d - ABS(open - close)) <= close)";
                $sqlAdd2 = "(close <= p_sma75d) AND ((p_sma75d - ABS(open - close)) <= close)";
                $sqlAdd3 = "(close <= p_sma13w) AND ((p_sma13w - ABS(open - close)) <= close)";
                $sqlAdd4 = "(close <= p_sma26w) AND ((p_sma26w - ABS(open - close)) <= close)";
                $sqlAdd5 = "(close <= p_sma52w) AND ((p_sma52w - ABS(open - close)) <= close)";
                $sqlAddAll = "($sqlAdd1 OR $sqlAdd2 OR $sqlAdd3 OR $sqlAdd4 OR $sqlAdd5)";
                $models->andWhere($sqlAddAll);
            }

        }

        if ($bbPosition !== "") {
            if ($bbPosition === "+3") {
                // ボリンジャーバンド+3σ以上
                $models->andWhere("close >= bollinger_p3");
            }
            if ($bbPosition === "+2") {
                // ボリンジャーバンド+2σ以上
                $models->andWhere("close >= bollinger_p2");
            }
            if ($bbPosition === "-2") {
                // ボリンジャーバンド-2σ以下
                $models->andWhere("close <= bollinger_m2");
            }

            if ($bbPosition === "-3") {
                // ボリンジャーバンド-3σ以下
                $models->andWhere("close <= bollinger_m3");
            }
            if ($bbPosition === "+2Dn") {
                // 株価がボリンジャーバンド+2σを上↘下に跨いだ
                $subQueryP2Dn = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close <  ListedCompanies.bollinger_p2 
                AND L2.close > ListedCompanies.bollinger_p2 
                AND L2.ymd = '{$beforeYmd}')";
                $models->andWhere($subQueryP2Dn);
            }
            if ($bbPosition === "-2Up") {
                // 株価がボリンジャーバンド-2σを下↗上に跨いだ
                $subQueryM2Up = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code 
                AND ListedCompanies.close >  ListedCompanies.bollinger_m2 
                AND L2.close < ListedCompanies.bollinger_m2 
                AND L2.ymd = '{$beforeYmd}')";
                $models->andWhere($subQueryM2Up);
            }
        }

        if ($upBeard !== "") {
            if ($upBeard == 1) {//上髭が足より短い (売り圧力が弱い)
                $models->andWhere("(close > open and ((high - close) < (close - open))) OR (open > close and ((high - open) < (open - close)))");
            } else if ($upBeard == 2) {//上髭が足より長い (売り圧力が強い)
                $models->andWhere("(close >= open and ((high - close) > (close - open))) OR (open > close and ((high - open) > (open - close)))");
            }
        }
        if ($downBeard !== "") {
            if ($downBeard == 1) {//下髭が足より短い (買い圧力が弱い)
                $models->andWhere("(close > open and ((close - open) > (open - low))) OR (open > close and ((open - close) > (close - low)))");
            } else if ($downBeard == 2) {//下髭が足より長い (買い圧力が強い)
                $models->andWhere("(close > open and ((close - open) < (open - low))) OR (open > close and ((open - close) < (close - low)))");
            }
        }

        //分析用の検索（利益実績）
        if ($profitValue != "") {

            if ($profitValue > 0) { // プラス値か？
                $sub = "SELECT close as max_close FROM ListedCompanies L3 WHERE ListedCompanies.stock_code = L3.stock_code AND ymd between '{$ymd}' and '{$latestYmd}' ORDER BY max_close DESC LIMIT 1";
                $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code
                AND L2.ymd = '{$ymd}'
                AND ( ({$sub}) - L2.close ) > {$profitValue})";
            } else if ($profitValue < 0) { //マイナス値か？
                $profitValue = abs($profitValue);
                $sub = "SELECT close as min_close FROM ListedCompanies L3 WHERE ListedCompanies.stock_code = L3.stock_code AND ymd between '{$ymd}' and '{$latestYmd}' ORDER BY min_close ASC LIMIT 1";
                $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code
                AND L2.ymd = '{$ymd}'
                AND ( L2.close - ({$sub}) ) > {$profitValue})";
            }

            $models->andWhere($subQuery);
        }
        //if(true) {
        if (false) {
            // 実績 △ / 予想 △  (353)
            //
            // ① 抵抗線まで伸びる
            // ② 抵抗線との間に移動平均が無い (あった場合には抜けたら伸びる) >> 伸びても支持線まで戻る場合あり
            // ③ 抵抗線より上の場合、移動平均を抜けたらあがる
            // ④ 抵抗線より上の場合、移動平均が無ければバンドウォーク
            // ⑤ 抵抗線を跨いだ翌日に下がったら下降になる場合あり >>> 跨いだら1日まつ
            // -- 移動平均はまとまっているほうが良い --
            $testYmd = "2019-06-28";
            $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code
                AND ListedCompanies.close > (L2.close / 100 * 103)
                AND L2.rate_of_increase > 1
                AND L2.ymd = '{$testYmd}')";
            $models->andWhere($subQuery);
        }
        //if(true) {
        if (false) {
            // 実績 ▼ / 予想 △  (47)
            //
            // ① +3αを超えた
            // ② 抵抗線を跨いだ翌日に下がった
            // ③ 抵抗線まで1/5以上
            // ④ 上に移動平均線が３本(25/75/13w)が下向き
            // ⑤ 支持線を下から抜けたが、すぐ移動平均、かつ全ての移動平均が下向き
            // ⑥ 支持線に跳ね返り陰線
            // ⑦ 既に+3αから陰線
            $testYmd = "2019-06-28";
            $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code
                AND ListedCompanies.close <= (L2.close / 100 * 97)
                AND L2.rate_of_increase > 1
                AND L2.ymd = '{$testYmd}')";
            $models->andWhere($subQuery);
        }
        //if(true) {
        if (false) {
            // 予想 △  (832)
            $testYmd = "2019-06-28";
            $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code
                AND L2.rate_of_increase > 1
                AND L2.ymd = '{$testYmd}')";
            $models->andWhere($subQuery);
        }

        //if(true) {
        if (false) {
            // 実績 ▼ / 予想 ▼  (175)
            $testYmd = "2019-06-28";
            $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code
                AND ListedCompanies.close <= (L2.close / 100 * 97)
                AND L2.rate_of_increase <= 0
                AND L2.ymd = '{$testYmd}')";
            $models->andWhere($subQuery);
        }
        //if(true) {
        if (false) {
            // 実績 △ / 予想 ▼  (804)
            $testYmd = "2019-06-28";
            $subQuery = "stock_code = (SELECT stock_code FROM ListedCompanies L2 
                WHERE ListedCompanies.stock_code = L2.stock_code
                AND ListedCompanies.close > (L2.close / 100 * 103)
                AND L2.rate_of_increase <= 0        
                AND L2.ymd = '{$testYmd}')";
            $models->andWhere($subQuery);
        }

        if ($limit > 0) {

            $profitOrder = "";
            if (!empty($rateOfIncreaseUp)) { //利益率の大きい順
                $profitOrder = " rate_of_increase DESC, ";
            } else if (!empty($rateOfIncreaseDn)) { //利益率の小さい順
                $profitOrder = " rate_of_increase ASC, ";
            }

            if ($orderCorrectRate > 0 && $orderPredictedRate > 0) {
                $models->andWhere("predicted_rate <= 100 "); //100％を超えるバグ対応
                $models->orderBy("{$profitOrder}predicted_rate DESC, correct_rate DESC, stock_code ASC");
            } else if ($orderCorrectRate > 0) {
                $models->orderBy("{$profitOrder}correct_rate DESC, stock_code ASC");
            } else if ($orderPredictedRate > 0) {
                $models->andWhere("predicted_rate <= 100 "); //100％を超えるバグ対応
                $models->orderBy("{$profitOrder}predicted_rate DESC, stock_code ASC");
            } else {
                $models->orderBy("{$profitOrder}stock_code ASC");
            }

            $models->limit($limit, $offset);
        }
        $results = $models->execute();

        return $results;
    }

    /**
     * お薦め銘柄件数の取得
     *
     * @param $latestYmd 最新の年月日
     * @param $ymd 対象年月日
     * @param $stockCode 銘柄コード
     * @param string $marketNo 市場コード
     * @param string $cost 購入価格
     * @param float $predictedRate 予想の正解率
     * @param float $correctRate 訓練後の正解率
     * @param float $rateOfIncrease 上昇率
     * @param string $updown ">=" または "<="
     * @param string $sma75dClose
     * @param string $sma75dUp
     * @param string $bollingerP2Up
     * @param string $bandwalk
     * @param string $upBeard
     * @param string $downBeard
     * @param string $crossfoot
     * @param string $straddleLine
     * @param string $resistantLine
     * @param string $aroundLine
     * @param string $bbPosition
     * @param string $profitValue
     * @param string $rsi
     * @param string $orderCorrectRate
     * @param string $orderPredictedRate
     * @param string $rateOfIncreaseUp
     * @param string $rateOfIncreaseDn
     * @param string $volumeUp
     * @return int
     */
    public static function selectSuggestionCount(
        $latestYmd,
        $ymd,
        $beforeYmd,
        $stockCode = "",
        $marketNo = "",
        $cost = "",
        float $predictedRate = 0,
        float $correctRate = 0,
        float $rateOfIncrease = 0,
        $updown = ">=",
        $sma75dClose = "",
        $sma75dUp = "",
        $sma25dUp = "",
        $bollingerP2Up = "",
        $bandwalk = "",
        $upBeard = "",
        $downBeard = "",
        $crossfoot = "",
        $straddleLine = "",
        $resistantLine = "",
        $aroundLine = "",
        $bbPosition = "",
        $profitValue = "",
        $rsi = "",
        $orderCorrectRate = "",
        $orderPredictedRate = "",
        $rateOfIncreaseUp = "",
        $rateOfIncreaseDn = "",
        $volumeUp = ""
    )
    {
        $result = self::selectSuggestionList(
            $latestYmd,
            $ymd,
            $beforeYmd,
            $stockCode,
            $marketNo,
            $cost,
            $predictedRate,
            $correctRate,
            $rateOfIncrease,
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
            $volumeUp);

        return count($result);
    }

    /**
     * 該当銘柄のデータリストを取得する
     *
     * @param int $stock_code
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function selectTargetStockList(
        int $stockCode = 0,
        int $limit = 20,
        int $offset = 0
    )
    {
        $criteria = self::query();
        $models = $criteria
            ->where("stock_code = :stock_code:", ["stock_code" => $stockCode])
            ->andWhere("predicted_rate > 0")//未来予測正解率あり(チャートあり)
            ->orderBy('ymd DESC')
            ->limit($limit, $offset);
        $results = $models->execute();

        return $results;
    }

    /**
     * 指定銘柄の最新の終値を取得
     *
     * @param int $stockCode
     * @return int
     */
    public static function selectRecentClose(int $stockCode, $ymd = "")
    {
        $criteria = self::query();
        $models = $criteria->where("stock_code = :stock_code:", ["stock_code" => $stockCode]);

        if (!empty($ymd)) {
            $models->andWhere("ymd = :ymd:", ["ymd" => $ymd]);
        }
        $models->orderBy('ymd DESC');
        $models->limit(1, 0);

        $obj = $models->execute();
        if (!empty($obj)) {
            $result = $obj[0]->getClose();
        } else {
            $result = 0;
        }
        return $result;
    }

    /**
     * 指定年月日以降の「最大終値」と「最小終値」を取得
     *
     * @param int $stockCode
     * @param string $latestYmd
     * @param string $ymd
     * @return array
     */
    public static function selectMaxMinClose(int $stockCode, $latestYmd, $ymd = "", $mode = "max")
    {
        $result = 0;
        $order = [
            "max" => "DESC",
            "min" => "ASC"
        ];

        if (!empty($ymd)) {
            $criteria = self::query();
            $models = $criteria
                ->where("stock_code = :stock_code:", ["stock_code" => $stockCode])
                ->betweenWhere('ymd', $ymd, $latestYmd)
                //->andWhere("ymd < :ymd:", ["ymd" => $ymd]) # 遅い
                ->orderBy('close ' . $order[$mode])
                ->limit(1, 0);

            $obj = $models->execute();
            if (!empty($obj)) {
                $result = $obj[0]->getClose();
            }
        }
        return $result;
    }

    /**
     * 該当銘柄のエラーを取得する
     *
     * @param $stockCode
     * @param $ymd 対象年月日
     * @return string
     */
    public static function selectTargetStockErrorMessages(
        $stockCode,
        $ymd = ""
    )
    {
        $message = "";

        $criteria = self::query();
        $models = $criteria->where("stock_code = :stock_code: OR name LIKE :name:",
            [
                "stock_code" => $stockCode,
                "name" => "%" . $stockCode . "%"
            ]);
        //$models = $criteria->where("stock_code = :stock_code:", ["stock_code" => $stockCode]);

        if (!empty($ymd)) {
            $models->andWhere("ymd = :ymd:", ["ymd" => $ymd]);
        }
        $models
            ->orderBy('ymd DESC')
            ->limit(1, 0);
        $results = $models->execute();

        if (count($results) > 0) {

            $errors = [
                "training" => "",
                "prediction" => "",
            ];

            // 学習結果を取得
            $statedTime = (int)$results[0]->getStartedTime();
            $endedTime = (int)$results[0]->getEndedTime();
            $errorNo = (int)$results[0]->getTrainingErrorNo();

            // 学習エラーメッセージを取得
            if ($results[0]->getProdictionErrorNo() > 0) { //学習エラーか？
                $errors["training"] = self::$aiErrorMessages[$errorNo]; //OK
            } else if ($statedTime > 0 && $endedTime === 0) {
                if (time() - $statedTime > self::$trainingTimeOut) { //学習
                    $errors["training"] = self::$aiErrorMessages[$errorNo]; //OK
                }
            }

            // 予想結果を取得
            $statedTime = (int)$results[0]->getPredictedStartedTime();
            $endedTime = (int)$results[0]->getPredictedEndedTime();
            $errorNo = (int)$results[0]->getProdictionErrorNo();

            // 予測エラーメッセージを取得
            if ($results[0]->getProdictionErrorNo() > 0) { //予測エラーか？
                $errors["prediction"] = self::$aiErrorMessages[$errorNo]; //OK
            } else if ($statedTime > 0 && $endedTime === 0) {
                if (time() - $statedTime > self::$predictionTimeOut) { //予測
                    $errors["prediction"] = self::$aiErrorMessages[$errorNo];
                }
            }

            // エラーメッセージの整理
            if (!empty($errors["training"])) {
                $message = "AI学習エラー: " . $errors["training"];
            } else if (!empty($errors["prediction"])) {
                $message = "AI予想エラー: " . $errors["prediction"];
            }
        } else {
            $message = "該当データが見つかりません";
        }

        return $message;
    }

    /**
     * 最新の年月日を取得
     *
     * @param int $limit
     * @return array
     */
    public function getCurrentYmds(int $limit = 20)
    {
        $list = [];
        $sql = "SELECT DISTINCT ymd FROM listed_companies ORDER BY ymd DESC LIMIT {$limit}";
        $results = $this->adapter->fetchAll($sql);
        foreach ($results as $result) {
            $list[] = $result['ymd'];
        }
        return $list;
    }

    /**
     * 指定日からの経過日を取得する
     *
     * @param string $ymd 指定日
     * @param int $day 経過日数
     * @return string
     */
    public function getBeforeYmd($ymd, int $day = 0)
    {
        $result = "";
        $sql = "SELECT DISTINCT ymd FROM listed_companies WHERE ymd <= '{$ymd}' ORDER BY ymd DESC LIMIT $day,1";
        $results = $this->adapter->fetchAll($sql);
        foreach ($results as $result) {
            $result = $result['ymd'];
        }
        return $result;
    }

    /**
     * 訓練した年月日を取得
     *
     * @param int $limit
     * @return array
     */
    public function getTrainingYmds(int $limit = 1)
    {
        $list = [];
        $sql = "SELECT ymd FROM training_time ORDER BY ymd DESC LIMIT {$limit}";
        $results = $this->adapter->fetchAll($sql);
        foreach ($results as $result) {
            $list[] = $result['ymd'];
        }
        return $list;
    }

    /**
     * 訓練の統計情報を取得
     *
     * @return array
     */
    public function getTrainingInfos()
    {
        $sqls = [];
        $list = [];

        $ymds = $this->getTrainingYmds(1);
        $ymd = isset($ymds[0]) ? $ymds[0] : "";

        //訓練の実施日
        $sqls['training_ymd'] = $ymd;

        //訓練「対象」数
        $sql = "SELECT count(id) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sqls['training_target'] = $sql;

        //訓練「待ち」の件数
        $sql = "SELECT count(id) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sql .= " AND started_time = 0";
        $sql .= " AND ended_time = 0";
        $sqls['training_waiting'] = $sql;

        //訓練「途中」の件数
        $sql = "SELECT count(id) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sql .= " AND started_time > 0";
        $sql .= " AND ended_time = 0";
        $sqls['training_middle'] = $sql;

        //訓練「途中」の中の「失敗」件数
        $sql = "SELECT count(id) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sql .= " AND started_time > 0";
        $sql .= " AND ended_time = 0";
        $sql .= " AND UNIX_TIMESTAMP() - started_time > " . self::$trainingTimeOut;
        $sqls['training_middle_error'] = $sql;

        //訓練「完了（Model更新あり）」の件数
        $sql = "SELECT count(id) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sql .= " AND started_time > 0";
        $sql .= " AND ended_time > 0";
        $sql .= " AND training_error_no = 0";
        $sqls['training_complete'] = $sql;

        //訓練「完了（Model更新なし）」の件数
        $sql = "SELECT count(id) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sql .= " AND started_time > 0";
        $sql .= " AND ended_time > 0";
        $sql .= " AND training_error_no > 0";
        $sqls['training_complete_noupdate'] = $sql;

        //訓練「エラー」の件数
//        $sql = "SELECT count(id) as count";
//        $sql .= " FROM listed_companies";
//        $sql .= " WHERE ymd ='" . $ymd . "'";
//        $sql .= " AND training_error_no > 0";
//        $sql .= " AND training_error_no != 9";
//        $sqls['training_error'] = $sql;

        foreach ($sqls as $name => $sql) {
            if ($name == "training_ymd") {
                $list[$name] = $sqls[$name];
            } else if (strstr($sql, 'GROUP BY') !== false) {
                $results = $this->adapter->fetchAll($sql);
                $list[$name] = count($results);
            } else {
                $results = $this->adapter->fetchAll($sql);
                $list[$name] = $results[0]['count'];
            }
        }
        return $list;
    }

    /**
     * 予測の統計情報を取得
     *
     * @return array
     */
    public function getPredictionInfos()
    {
        $sqls = [];
        $list = [];

        $ymds = $this->getCurrentYmds(1);
        $ymd = isset($ymds[0]) ? $ymds[0] : "";

        //予想の実施日
        $sqls['prediction_ymd'] = $ymd;

        //予想「対象」数
        $sql = "SELECT count(id) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sqls['prediction_target'] = $sql;

        //予想「待ち」の件数
        $sql = "SELECT count(id) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sql .= " AND predicted_started_time = 0";
        $sql .= " AND predicted_ended_time = 0";
        $sqls['prediction_waiting'] = $sql;

        //予測「途中」の件数
        $sql = "SELECT count(id) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sql .= " AND predicted_started_time > 0";
        $sql .= " AND predicted_ended_time = 0";
        $sqls['prediction_middle'] = $sql;

        //予測「途中」の中の「失敗」件数
        $sql = "SELECT count(id) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sql .= " AND predicted_started_time > 0";
        $sql .= " AND predicted_ended_time = 0";
        $sql .= " AND UNIX_TIMESTAMP() - started_time > " . self::$predictionTimeOut;
        $sqls['prediction_middle_error'] = $sql;

        //予測「完了」の件数
        $sql = "SELECT count(id) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sql .= " AND predicted_started_time > 0";
        $sql .= " AND predicted_ended_time > 0";
        $sqls['prediction_complete'] = $sql;

        //予測「エラー」の件数
        $sql = "SELECT count(id) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sql .= " AND prodiction_error_no > 0";
        $sqls['prediction_error'] = $sql;

        foreach ($sqls as $name => $sql) {
            if ($name == "prediction_ymd") {
                $list[$name] = $sqls[$name];
            } else {
                $results = $this->adapter->fetchAll($sql);
                $list[$name] = $results[0]['count'];
            }
        }

        return $list;
    }

    /**
     * 訓練/予測のエラー情報を取得
     *
     * @return array
     */
    public function getErrors()
    {
        $sqls = [];
        $list = [];

        $ymds = $this->getTrainingYmds(1);
        $ymd = isset($ymds[0]) ? $ymds[0] : "";

        //訓練の実施日
        $sqls['training_ymd'] = $ymd;

        //訓練の「エラー」種類毎の数
        $sql = "SELECT training_error_no, count(training_error_no) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sql .= " AND started_time > 0";
        $sql .= " AND ended_time = 0";
        $sql .= " AND UNIX_TIMESTAMP() - started_time > " . self::$trainingTimeOut;
        $sql .= " GROUP BY training_error_no";
        $sqls['training_error'] = $sql;

        $ymds = $this->getCurrentYmds(1);
        $ymd = isset($ymds[0]) ? $ymds[0] : "";

        //予想の実施日
        $sqls['prediction_ymd'] = $ymd;

        //予測の「エラー」種類毎の数
        $sql = "SELECT prodiction_error_no, count(prodiction_error_no) as count";
        $sql .= " FROM listed_companies";
        $sql .= " WHERE ymd ='" . $ymd . "'";
        $sql .= " AND predicted_started_time > 0";
        $sql .= " AND predicted_ended_time = 0";
        $sql .= " AND UNIX_TIMESTAMP() - started_time > " . self::$predictionTimeOut;
        $sql .= " GROUP BY prodiction_error_no";
        $sqls['prediction_error'] = $sql;

        $trainingErrorCount = 0;
        $predictionErrorCount = 0;

        foreach ($sqls as $name => $sql) {
            if ($name == "training_ymd" || $name == "prediction_ymd") {
                $list[$name] = $sqls[$name];
            } else {
                $results = $this->adapter->fetchAll($sql);
                foreach ($results as $result) {
                    if (isset($result['training_error_no'])) {
                        $errorNo = $result['training_error_no'];
                        $list[$name][$errorNo] = $result['count'];
                        $trainingErrorCount += $result['count'];
                    }
                    if (isset($result['prodiction_error_no'])) {
                        $errorNo = $result['prodiction_error_no'];
                        $list[$name][$errorNo] = $result['count'];
                        $predictionErrorCount += $result['count'];
                    }
                }
            }
        }

        $list['training_error_count'] = $trainingErrorCount;
        $list['prediction_error_count'] = $predictionErrorCount;

        return $list;
    }
}
