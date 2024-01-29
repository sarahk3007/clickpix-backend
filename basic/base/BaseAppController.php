<?php

namespace app\base;

use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

use app\components\behaviors\CorsCustom;

class BaseAppController extends Controller
{

    public $userIdentity;
    public $user;
    public $requestData;
    public $client_version;

    /**
     * @inheritdoc
     */
    protected $authExceptActions = [];


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => CorsCustom::className()
        ];

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => $this->authExceptActions
        ];

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
                'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            ]
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        return $actions;
    }

    /**
     * Parse the JSON input and return as a StdClass or associative array
     *
     * @param bool $assoc
     * @return mixed
     */
    protected function getPostData($assoc = false)
    {
        return Yii::$app->request->bodyParams;
    }

    public function getRequestData()
    {
        return Yii::$app->request->bodyParams;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($event)
    {
        $this->enableCsrfValidation = false;

        $client_version = 1.0;
        $req_headers = \Yii::$app->request->headers;
        if (isset($req_headers['client-version'])) {
            $client_version = floatval($req_headers['client-version']);
        }
        $this->client_version = $client_version;

        $this->requestData = json_decode(file_get_contents('php://input'), true);

        return parent::beforeAction($event);
    }
}
