<?php

class TrainingTime extends \Phalcon\Mvc\Model
{

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
    protected $started_time;

    /**
     *
     * @var integer
     */
    protected $ended_time;

    /**
     *
     * @var integer
     */
    protected $count;

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
     * Method to set the value of field count
     *
     * @param integer $count
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = $count;

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
     * Returns the value of field count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
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
        $this->setSource("training_time");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'training_time';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TrainingTime[]|TrainingTime|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TrainingTime|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
