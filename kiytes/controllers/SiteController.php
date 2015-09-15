<?php

namespace app\controllers;

use Yii;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\models\LoginForm;
use app\models\SignupForm;
use app\models\ProfileForm;

use app\models\User;

require_once Yii::getAlias('@app') . '/vendor/twilio/Services/Twilio.php';

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionHome()
    {
        if ( YII_DEBUG ) {error_log("[Site::Home]");}
        
        if ( YII_DEBUG ) {
            error_log('CLIENTS : ');
            $users = User::findAll(["user_type" => 0]);
            foreach($users as $user) {
                error_log("user : " . json_encode($user->getAttributes()));
            }

            error_log('DRIVERS : ');
            $users = User::findAll(["user_type" => 1]);
            foreach($users as $user) {
                error_log("user : " . json_encode($user->getAttributes()));
            }
        }
        
        return $this->render('index', ['content' => []]);
    }

    public function actionLogin()
    {
        if ( YII_DEBUG ) {error_log("[Site::Login]");}
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout = 'unsigned';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        if ( YII_DEBUG ) {error_log("[Site::Logout]");}
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionSignup()
    {
        if ( YII_DEBUG ) {error_log("[Site::Signup]");}
        
        $this->layout = 'unsigned';
        
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->goBack();
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    
    public function actionProfile()
    {
        if ( YII_DEBUG ) {error_log("[Site::Profile]");}
        if ( YII_DEBUG ) {error_log("[Site::Profile] POST vars : " . json_encode(Yii::$app->request->post()));}
        
        $this->layout = 'unsigned';
        
        if ( \Yii::$app->user->isGuest && !Yii::$app->request->get('id')) {
            return $this->goHome();
        }

        $model = new ProfileForm();
        if ( 
                Yii::$app->request->isPost 
                && Yii::$app->request->post('ProfileForm') ) {
            
            $model->saveProfile( );
        }
        
        if ( $model->isEditable ) {
            $this->layout = 'base';
        }
        
        return $this->render('profile', [
            'model' => $model,
        ]);
    }
    
    
    public function actionVerifyPhone()
    {
        if ( YII_DEBUG ) {error_log("[Site::VerifyPhone]");}
        if ( YII_DEBUG ) {error_log("[Site::VerifyPhone] request POST : " . json_encode(Yii::$app->request->post()));}
        
        $token = Yii::$app->request->get('token');
        $_currUser = (\Yii::$app->user->isGuest ? null : User::findOne(\Yii::$app->user->id));
        $content = [];
        
        if ( 
                (!$token && !$_currUser) 
                || ($_currUser && ('1' == $_currUser->phone_verified)) ) {
            
            return $this->goHome();
        }
        
        $this->layout = 'unsigned';
        
        if ( $_currUser && !$token ) {
            $verificationUrl = Yii::$app->homeUrl . 'verify-phone?token=' . $_currUser->phone_acttoken;
            
            if ( YII_DEBUG ) {error_log("[Site::VerifyPhone] phoneVerifyRequest");}
            if ( YII_DEBUG ) {error_log("[Site::VerifyPhone] twilioAccountSid : " . Yii::$app->params['twilioAccountSid']);}
            if ( YII_DEBUG ) {error_log("[Site::VerifyPhone] twilioAuthToken : "  . Yii::$app->params['twilioAuthToken']);}
            
	    /*
            $client = new \Services_Twilio(Yii::$app->params['twilioAccountSid'], Yii::$app->params['twilioAuthToken']);
            $smsBody = "Good day. Please use link {$verificationUrl} to verify your phone number on kytes";
            if ( YII_DEBUG ) {error_log("[Site::VerifyPhone] sms body : "  . $smsBody);}
            try {
                $sms = $client->account->messages->create(array(
                    "From" => Yii::$app->params['twilioNumber'],
                    "To" => "+79831284925", //"XXX-XXX-XXXX"
                    "Body" => $smsBody
                ));
                if ( YII_DEBUG ) {error_log("[Site::VerifyPhone] sms sid : "  . $sms->sid);}
            } catch(\Services_Twilio_RestException $ex) {
                if ( YII_DEBUG ) {error_log("[Site::VerifyPhone] twilio error : "  . $ex->getMessage());}
            }
	    */

            $content = [
                'actionType' => 'phoneVerifyRequest',
                
                'givenToken' => $token,
                
                'phoneVerified' => ($_currUser ? $_currUser->phone_verified : null),
                'phone' => ($_currUser ? substr_replace($_currUser->phone, '****', 4,3) : null),
                'phoneActToken' => ($_currUser ? $_currUser->phone_verified : null),
                'verificationUrl' => $verificationUrl,
            ];
        } elseif ( $token ) {
            $tarUser = User::findOne(['phone_acttoken' => $token, 'phone_verified' => '0']);
            if ( $tarUser && (0 === strcmp($token, $tarUser->phone_acttoken)) ) {
                $tarUser->phone_verified = 1;
                $tarUser->save();
            }
            
            $content = [
                'actionType' => 'phoneVerifyAction',
                
                'givenToken' => $token,
                
                'isValidToken' => ($tarUser ? 1 : 0),
                'phoneVerified' => ($tarUser ? $tarUser->phone_verified : null),
                'phone' => ($tarUser ? substr_replace($tarUser->phone, '****', 4,3) : null),
            ];
        }
        
        return $this->render('action', ['content' => $content]);
    }
}