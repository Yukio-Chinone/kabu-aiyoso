<?php


//ini_set('display_errors', "On");

require '../vendor/autoload.php';

use \Phalcon\Http\Response\Cookies;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;


class User extends \Phalcon\Mvc\Model
{
    /**
     * 自動ログイン (Cookieを利用)
     */
    const AUTO_LOGIN = true;

    /**
     * SNSの種類
     */
    const SNS_LOCAL = 0;
    const SNS_FACEBOOK = 1;

    /**
     * 状態
     */
    const STATIS_PROVISIONAL = 0; //仮登録
    const STATUS_ENABLE = 1; //有効
    const STATUS_DISABLE = 2; //無効

    /**
     * アクセスするセッション名
     */
    const SESSION_NAME = "finance_ai";

    /**
     * アクセスするクッキー名
     */
    const COOKIE_NAME = "finance_ai";

    /**
     * クッキーの保存期間(日)
     */
    const COOKIE_DAY = 30;

    /**
     * メッセージ保存用
     */
    const SESSION_MESSAGE = "finance_ai_message";

    /**
     * AWS SES にアクセスする為のキー
     */
    const AWS_ACCESS_KEY = "AKIAX6CK6FHNQE72ACNJ";
    const AWS_SECRET_KEY = "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";

    /**
     * 送信元Eメールアドレス
     */
    const FROM_EMAIL = "no-reply@kabu.aiyoso.com";

    /**
     * サイトドメイン
     */
    const SITE_DOMAIN = "https://kabu.aiyoso.com";
    //const SITE_DOMAIN = "http://localhost:8880";

    /**
     * 暗号化用のキー
     */
    const ENCRYPT_KEY = "kK3VZWLxkK3VZWLxkK3VZWLx";

    /**
     * SNSの名称とType
     * @var array
     */
    public static $snsTypes = [
        'local' => self::SNS_LOCAL,
        'facebook' => self::SNS_FACEBOOK
    ];

    /**
     * @var string
     * セッション保存
     */
    private $session;

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var integer
     */
    protected $sns_type;

    /**
     *
     * @var string
     */
    protected $sns_id;

    /**
     *
     * @var string
     */
    protected $access_token;

    /**
     *
     * @var integer
     */
    protected $payed;


    /**
     *
     * @var  string
     */
    protected $email;

    /**
     *
     * @var  string
     */
    protected $premium_code;

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
     * Method to set the value of field payed
     *
     * @param integer $payed
     * @return $this
     */
    public function setPayed($payed)
    {
        $this->payed = $payed;

        return $this;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param $premiumCode
     * @return $this
     */
    public function setPremiumCode($premiumCode)
    {
        $this->premium_code = $premiumCode;

        return $this;
    }

    /**
     * @param $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

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
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the value of field payed
     *
     * @return integer
     */
    public function getPayed()
    {
        return $this->payed;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
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
        $this->setSource("user");
        $this->session = $this->getDI()->getSession();
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]|User|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * 新規ユーザー登録
     *
     * @param int $snsType
     * @param $snsId
     * @param $accessToken
     * @param int $status
     * @param null $username
     * @param null $password
     * @return mixed
     */
    public function insert(
        int $snsType,
        $snsId,
        $accessToken,
        $status = self::STATUS_ENABLE,
        $username = null,
        $password = null
    )
    {
        $ret = $this->save(
            [
                'status' => $status,
                'sns_type' => $snsType,
                'sns_id' => $snsId,
                'access_token' => $accessToken,
                'username' => $username,
                'password' => md5($password),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        return $ret;
    }

    /**
     * 指定カラムの更新
     *
     * @param int $userId アプリのユーザID
     * @param array $columns 変更する'カラム名'と'値'の連想配列
     */
    public function updateColumns(
        int $userId,
        array $columns
    )
    {
        $ret = faflse;

        $user = $this->findFirst($userId);
        if ($user) {
            foreach ($columns as $column => $value) {
                $method = "set" . ucfirst(strtolower($column));
                $user->$method();
            }
            $user->setUpdatedAt(date("Y-m-d H:i:s"));
            $ret = $user->save();
        }
    }

    /**
     * 登録済の「SNS ID」の検索
     *
     * @param int $sysType SNSタイプ
     * @param $snsId SNSのユーザーID
     * @return mixed
     */
    public function selectBySnsId(
        int $sysType,
        $snsId
    )
    {
        $criteria = self::query();
        $models = $criteria
            ->where("sns_type = :sns_type:", ["sns_type" => $sysType])
            ->andWhere("sns_id = :sns_id:", ["sns_id" => $snsId])
            ->limit(1, 0);

        $results = $models->execute();

        return $results;
    }

    /**
     * 登録済「Username」の検索
     *
     * @param $username
     * @return mixed
     */
    public function selectByUsername(
        $username
    )
    {
        $criteria = self::query();
        $models = $criteria
            ->where("username = :username:", ["username" => $username])
            ->limit(1, 0);

        $results = $models->execute();

        return $results;
    }

    /**
     * ユーザ情報をセッションに保存
     *
     * @param int $userId
     * @param int $snsType
     * @param $snsId
     * @param $accessToken
     * @param $snaName
     */
    public function setUserSession(
        int $userId,
        int $snsType,
        $snsId,
        $accessToken,
        $userName = ""
    )
    {
        $json = json_encode([
            "userId" => $userId,
            "snsType" => $snsType,
            "snsId" => $snsId,
            "accessToken" => $accessToken,
            "userName" => $userName
        ]);
        $this->session->set(self::SESSION_NAME, $json);
    }

    /**
     * ユーザ情報をセッションから取得
     *
     * @return array
     */
    public function getUserSession()
    {
        $results = [
            "userId" => 0,
            "snsType" => 0,
            "snsId" => "",
            "accessToken" => "",
            "userName" => "",
        ];
        $ret = $this->session->get(self::SESSION_NAME);
        if (!empty($ret)) {
            $results = json_decode($ret, true);
        }
        return $results;
    }

    /**
     * ログイン中か判定する
     *
     * @return bool
     */
    public function isLogined()
    {
        $result = false;
        $sessions = $this->getUserSession();
        if (!empty($sessions)) {
            if ($sessions['userId'] > 0) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * 指定ユーザーの支払い状態をチェック
     *
     * @param int $userId
     * @return bool true:支払い済、false:未払い
     */
    public static function isPayed(int $userId)
    {
        $result = false;
        if (!empty($userId)) {
            $user = parent::findFirst($userId);
            if ($user) {
                $ret = (int)$user->getPayed();
                if ($ret === 1) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    /**
     * ユーザー情報をセッションから削除
     */
    public function clearUserSession()
    {
        $this->session->remove(self::SESSION_NAME);
    }

    /**
     * メール送信処理
     *
     * @param $to
     * @param $subject
     * @param $body
     */
    public static function sendMail($to, $subject, $body)
    {
        $source = self::FROM_EMAIL;    //送信元アドレス
        $charset = 'ISO-2022-JP';   //変換先の文字コード

        try {
            //アクセスキー、シークレットキー、リージョンを指定しクライアントを生成する
            $client = SesClient::factory(
                array(
                    'key' => self::AWS_ACCESS_KEY,
                    'secret' => self::AWS_SECRET_KEY,
                    'region' => 'us-west-2',
                    'version' => 'latest'
                )
            );

            //添付ファイル無しのメールを送信
            $result = $client->sendEmail(array(
                    // Source（送信元）は必須
                    'Source' => $source,
                    // Destination（宛先）は必須
                    'Destination' => array(
                        'ToAddresses' => array($to),
                    ),
                    // Message（メッセージ部分）は必須
                    'Message' => array(
                        // Subject（件名）は必須
                        'Subject' => array(
                            // Data（件名部分データ）は必須
                            'Data' => $subject,
                            'Charset' => $charset,
                        ),
                        // Body（本文）は必須
                        'Body' => array(
                            'Text' => array(
                                // Data（本文データ）は必須
                                'Data' => $body,
                                'Charset' => $charset,
                            ),
                        ),
                    ),
                )
            );

        } catch (SesException $exc) {
            echo $exc->getMessage();
            exit();
        }
    }

    /**
     * 暗号化
     *
     * @param $text
     * @return string
     */
    public static function encrypt($text)
    {
        return openssl_encrypt($text, 'AES-128-ECB', self::ENCRYPT_KEY);
    }

    /**
     * 復号化
     *
     * @param $text
     * @return string
     */
    public static function decrypt($text)
    {
        return openssl_decrypt($text, 'AES-128-ECB', self::ENCRYPT_KEY);
    }
}
