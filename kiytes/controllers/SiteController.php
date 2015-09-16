<?php

namespace app\controllers;

use Yii;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\db\Query;

use app\models\LoginForm;
use app\models\SignupForm;
use app\models\ProfileForm;

use app\models\User;
use app\models\Address;
use app\models\RelUserAddress;
use app\models\Ride;

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
        
        if ( \Yii::$app->user->isGuest && !Yii::$app->request->get('id')) {
            return $this->goHome();
        }

        $model = new ProfileForm();
        if ( 
                Yii::$app->request->isPost 
                && Yii::$app->request->post('ProfileForm') ) {
            
            $model->saveProfile( );
        }
        
        if ( !$model->isEditable ) {
            $this->layout = 'unsigned';
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
            
            $client = new \Services_Twilio(Yii::$app->params['twilioAccountSid'], Yii::$app->params['twilioAuthToken']);
            $smsBody = "Good day. Please use link {$verificationUrl} to verify your phone number on kytes";
            if ( YII_DEBUG ) {error_log("[Site::VerifyPhone] sms body : "  . $smsBody);}
            $twilioEx = null;
            try {
                $sms = $client->account->messages->create(array(
                    "From" => Yii::$app->params['twilioNumber'],
                    "To" => "+79831284925", //"XXX-XXX-XXXX"
                    "Body" => $smsBody
                ));
                if ( YII_DEBUG ) {error_log("[Site::VerifyPhone] sms sid : "  . $sms->sid);}
            } catch(\Services_Twilio_RestException $twilioEx) {
                if ( YII_DEBUG ) {error_log("[Site::VerifyPhone] twilio error [{$twilioEx->getCode()}] : "  . $twilioEx->getMessage());}
            }

            $content = [
                'actionType' => 'phoneVerifyRequest',
                
                'givenToken' => $token,
                
                'phoneVerified' => ($_currUser ? $_currUser->phone_verified : null),
                'phone' => ($_currUser ? substr_replace($_currUser->phone, '****', 4,3) : null),
                'phoneActToken' => ($_currUser ? $_currUser->phone_verified : null),
                'verificationUrl' => $verificationUrl,
                'twilioException' => $twilioEx,
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
    
    public function actionDrivers() {
        $_currUser = ( Yii::$app->user->isGuest ? null : User::findOne(['id' => Yii::$app->user->id]) );
        if ( !\Yii::$app->user->isGuest && (User::$_TYPE_DRIVER === $_currUser->user_type) ) {
            return $this->goHome();
        }
        
        if ( $_currUser && $_currUser->is_admin && Yii::$app->request->get('user_id') && ('markAdmin' === Yii::$app->request->get('action')) ) {
            $tarUser = User::findOne(['id' => intval(Yii::$app->request->get('user_id'))]);
            if ( $tarUser ) {
                $tarUser->is_admin = 1;
                $tarUser->save();
            }
        }
        
        $drivers = [];
        
        $currPage = Yii::$app->request->get('page', 0);
        $filteredAddress = Yii::$app->request->get('address', null);
        
        $queryUserCount = new Query;
        $queryUserCount->select('*')
            ->from('users u')
            ->leftJoin('cars c', 'c.driver_id = u.id');
        
        $queryUser = new Query;
        $queryUser->select(
                'u.id as id, u.first_name as fname, u.last_name as lname, u.is_admin as is_admin, '
                . 'u.photo as photo, u.is_complete as is_complete, c.price_mile as price_mile, '
                . 'c.id as car_id, c.car_photo as car_photo, c.make as make, c.model as model, '
                . 'c.year as year, c.license_plate as license_plate, avg(r.rate) as rate')
            ->from('users u')
            ->leftJoin('cars c', 'c.driver_id = u.id')
            ->leftJoin('rates r', 'r.rated_id = u.id')
            ->groupBy(
                'id, fname, lname, is_admin, photo, is_complete, price_mile, car_id, '
                . 'car_photo, make, model, year, license_plate')
            
            ->orderBy('rate desc')
            ->offset( intval(Yii::$app->params['driversPerPageLimit'] * $currPage) ) 
            ->limit( Yii::$app->params['driversPerPageLimit'] );
        
        if ($filteredAddress) {
            $queryUser->leftJoin('rel_user_address rua', 'rua.user_id = u.id');
            $queryUser->leftJoin('addresses adr', 'adr.id = rua.address_id');
            $queryUser->where('(u.user_type = :utype) AND (adr.address LIKE :address)', [
                ':utype'=>User::$_TYPE_DRIVER,
                ':address' => "%{$filteredAddress}%"]);
            
            $queryUserCount->leftJoin('rel_user_address rua', 'rua.user_id = u.id');
            $queryUserCount->leftJoin('addresses adr', 'adr.id = rua.address_id');
            $queryUserCount->where('(u.user_type = :utype) AND (adr.address LIKE :address)', [
                ':utype'=>User::$_TYPE_DRIVER,
                ':address' => "%{$filteredAddress}%"]);
        } else {
            $queryUser->where('u.user_type = :utype', [':utype'=>User::$_TYPE_DRIVER]);
            $queryUserCount->where('u.user_type = :utype', [':utype'=>User::$_TYPE_DRIVER]);
        }
        
        $totalCount = $queryUserCount->count();
                
        $context = [
            'is_guest' => Yii::$app->user->isGuest,
            'curr_user_id' => (Yii::$app->user->isGuest ? null : Yii::$app->user->id),
            'is_admin' => ($_currUser ? 1 === $_currUser->is_admin : false),
            'is_drivers' => 1,
            
            'total_pages' => ceil($totalCount / Yii::$app->params['driversPerPageLimit']),
            'total_count' => $totalCount,
            'current_page' => $currPage,
            
            'filtered_address' => $filteredAddress,
            
            'address_start' => Yii::$app->request->get('address_start', null),
            'address_dest' => Yii::$app->request->get('address_dest', null),
            'time_start' => Yii::$app->request->get('time_start', null),
            'message' => Yii::$app->request->get('message', null),        
        ];
        
        $rows = $queryUser->all();
        
        foreach($rows as $row) {
            $drivers[] = [
                'id' => $row['id'],

                'first_name' => $row['fname'],
                'last_name' => $row['lname'],
                'is_admin' => $row['is_admin'],
                
                'rating' => sprintf("%.2f", $row['rate']),
                'price_mile' => $row['price_mile'],
                'photo' => $row['photo'],
                'is_complete' => $row['is_complete'],

                'car_id' => $row['car_id'],
                'make' => $row['make'],
                'model' => $row['model'],
                'year' => $row['year'],
                'license_plate' => $row['license_plate'],
                'car_photo' => $row['car_photo'],
            ];
        }
        
        return $this->render('drivers', [
            'drivers' => $drivers,
            'context' => $context,
        ]);
    }
    
    public function actionUsers() {
        $_currUser = ( Yii::$app->user->isGuest ? null : User::findOne(['id' => Yii::$app->user->id]) );
        if ( !$_currUser || (0===$_currUser->is_admin) ) {
            return $this->goHome();
        }
        
        if ( $_currUser && $_currUser->is_admin && Yii::$app->request->get('user_id') && ('markAdmin' === Yii::$app->request->get('action')) ) {
            $tarUser = User::findOne(['id' => intval(Yii::$app->request->get('user_id'))]);
            if ( $tarUser ) {
                $tarUser->is_admin = 1;
                $tarUser->save();
            }
        }
        
        $users = [];
        
        $currPage = Yii::$app->request->get('page', 0);
        $filteredAddress = Yii::$app->request->get('address', null);
        
        $queryUserCount = new Query;
        $queryUserCount->select('*')
            ->from('users u');
        
        $queryUser = new Query;
        $queryUser->select(
                'u.id as id, u.first_name as fname, u.last_name as lname, u.is_admin as is_admin, '
                . 'u.photo as photo, u.is_complete as is_complete, avg(r.rate) as rate')
            ->from('users u')
            ->leftJoin('rates r', 'r.rated_id = u.id')
            ->groupBy(
                'id, fname, lname, is_admin, photo, is_complete')
            
            ->orderBy('rate desc')
            ->offset( intval(Yii::$app->params['driversPerPageLimit'] * $currPage) ) 
            ->limit( Yii::$app->params['driversPerPageLimit'] );
        
        if ($filteredAddress) {
            $queryUser->leftJoin('rel_user_address rua', 'rua.user_id = u.id');
            $queryUser->leftJoin('addresses adr', 'adr.id = rua.address_id');
            $queryUser->where('(u.user_type = :utype) AND (adr.address LIKE :address)', [
                ':utype'=>User::$_TYPE_CUSTOMER,
                ':address' => "%{$filteredAddress}%"]);
            
            $queryUserCount->leftJoin('rel_user_address rua', 'rua.user_id = u.id');
            $queryUserCount->leftJoin('addresses adr', 'adr.id = rua.address_id');
            $queryUserCount->where('(u.user_type = :utype) AND (adr.address LIKE :address)', [
                ':utype'=>User::$_TYPE_CUSTOMER,
                ':address' => "%{$filteredAddress}%"]);
        } else {
            $queryUser->where('u.user_type = :utype', [':utype'=>User::$_TYPE_CUSTOMER]);
            $queryUserCount->where('u.user_type = :utype', [':utype'=>User::$_TYPE_CUSTOMER]);
        }
        
        $totalCount = $queryUserCount->count();
                
        $context = [
            'is_guest' => Yii::$app->user->isGuest,
            'curr_user_id' => (Yii::$app->user->isGuest ? null : Yii::$app->user->id),
            'is_admin' => ($_currUser ? 1 === $_currUser->is_admin : false),
            'is_drivers' => 0,
            
            'total_pages' => ceil($totalCount / Yii::$app->params['driversPerPageLimit']),
            'total_count' => $totalCount,
            'current_page' => $currPage,
            
            'filtered_address' => $filteredAddress,
            
            'address_start' => Yii::$app->request->get('address_start', null),
            'address_dest' => Yii::$app->request->get('address_dest', null),
            'time_start' => Yii::$app->request->get('time_start', null),
            'message' => Yii::$app->request->get('message', null),        
        ];
        
        $rows = $queryUser->all();
        
        foreach($rows as $row) {
            $users[] = [
                'id' => $row['id'],

                'first_name' => $row['fname'],
                'last_name' => $row['lname'],
                
                'is_admin' => $row['is_admin'],
                
                'rating' => sprintf("%.2f", $row['rate']),
                'photo' => $row['photo'],
                'is_complete' => $row['is_complete'],
            ];
        }
        
        return $this->render('drivers', [
            'drivers' => $users,
            'context' => $context,
        ]);
    }
    
    public function actionInvitation() {
        $_currUser = ( Yii::$app->user->isGuest ? null : User::findOne(['id' => Yii::$app->user->id]) );
        $_request = Yii::$app->request;
        if ( !$_currUser || (User::$_TYPE_DRIVER === $_currUser->user_type) ) {
            return $this->goHome();
        }
        
        $_driver = ($_request->get('driver') ? User::findOne(['id' => $_request->get('driver')]) : null);
        
        if ( '1' === $_request->get('send_invite') ) {
            $errorMsg = null;
            if ( !$_currUser ) {
                $errorMsg = "Please log in";
            } elseif( !$_request->get('address_start') ) {
                $errorMsg = "Start Address Not Specified";
            } elseif( !$_request->get('address_dest') ) {
                $errorMsg = "Destination Address Not Specified";
            } elseif( !$_request->get('time_start') ) {
                $errorMsg = "Ride Date And Time Not Specified";
            } elseif( !$_driver ) {
                $errorMsg = "Driver Not Choosen";
            } else {
                $addressStart = Address::findOne(['id' => $_request->get('address_start')]);
                $addressDest = Address::findOne(['id' => $_request->get('address_dest')]);

                if ( !$addressStart ) {
                    $errorMsg = "Start Address Not Found";
                } elseif( !$addressDest ) {
                    $errorMsg = "Destination Address Not Found";
                } else {
                    $ruaStart = RelUserAddress::findOne(['user_id' => $_currUser->id, 'address_id' => $addressStart->id]);
                    $ruaDest = RelUserAddress::findOne(['user_id' => $_currUser->id, 'address_id' => $addressDest->id]);

                    if ( !$ruaStart ) {
                        $errorMsg = "Start Address Is Not In Your Addresses List";
                    } elseif( !$ruaDest ) {
                        $errorMsg = "Destination Address Is Not In Your Addresses List";
                    } else {
                        $timeStart = \DateTime::createFromFormat("d/m/Y H:i", $_request->get('time_start'));
                        $dtNowPlsHour = new \DateTime();
                        $dtNowPlsHour->add(new \DateInterval("PT1H"));

                        if ( YII_DEBUG ) {
                            error_log("datetime now + 1 hour : {$dtNowPlsHour->format("d/m/Y H:i")}");
                            error_log("datetime start : {$timeStart->format("d/m/Y H:i")}");
                        }
                        
                        if ( $dtNowPlsHour > $timeStart ) {
                            $errorMsg = "Ride Start Time Should Be At Least In Next Hour";
                        } else {
                            if ( YII_DEBUG ) {
                                error_log("ready to create ride invite : ");
                                error_log("*** address start : {$addressStart->address} [{$addressStart->id}]");
                                error_log("*** address dest : {$addressDest->address} [{$addressDest->id}]");
                                error_log("*** time start : {$timeStart->format('d/m/Y H:i')}");
                                error_log("*** driver : {$_driver->email} [{$_driver->id}]");
                                error_log("*** current user : {$_currUser->email} [{$_currUser->id}]");
                                
                                $ride = new Ride;
                                
                                $ride->client_id = $_currUser->id;
                                $ride->driver_id = $_driver->id;
                                $ride->status = Ride::$_STATUS_INVITATION;
                                $ride->message = $_request->get('message', null);
                                $ride->time_start = $timeStart->getTimestamp();
                                $ride->address_start = $addressStart->id;
                                $ride->address_end = $addressDest->id;
                                
                                $ride->generateRideToken();
                                
                                try {
                                    $ride->save();
                                    Yii::$app->session->addFlash('home_notification', 'Invitation has been sent successfully');
                                    return $this->goHome();
                                } catch (\Exception $ex) {
                                    $errorMsg = "Failed to create new invitation, try again later";
                                }
                            }
                        }
                    }
                }
            }
            
            if ( $errorMsg ) {
                Yii::$app->session->addFlash('invite_error', $errorMsg, false);
            }
        }
        
        $_addresses = [];
        $addresses = new Query();
        //$addresses = $addresses->select('adr.id as id, adr.address as address')->from('addresses adr')->all();
        $addresses = $addresses->select('adr.id as id, adr.address as address')
            ->from('addresses adr')
            ->leftJoin('rel_user_address rua', 'rua.address_id = adr.id')
            ->leftJoin('users u', 'u.id = rua.user_id')
            ->where('u.id = :uid', [':uid' => $_currUser->id])
            ->all();
        
        foreach($addresses as $address) {
            $_addresses[$address['id']] = $address['address'];
        }
        
        $context = [
                'hire' => $_request->get('hire', 0),
                'customer' => $_currUser,
                'driver' => $_driver,
                'address_start' => $_request->get('address_start', null),
                'address_dest' => $_request->get('address_dest', null),
                'addresses' => json_encode($_addresses),
                'time_start' => $_request->get('time_start', null),
                'message' => $_request->get('message', null),
            ];
        
        return $this->render('invitation', [
            'context' => $context,
            'history' => [],
        ]);
    }
    
    public function actionRides() {
        $_currUser = ( Yii::$app->user->isGuest ? null : User::findOne(['id' => Yii::$app->user->id]) );
        $_request = Yii::$app->request;
        
        $isAdminMode = false;
        if ( $_currUser && $_currUser->is_admin && $_request->get('id') ) {
            $isAdminMode = true;
            $_currUser = User::findOne(['id' => $_request->get('id')]);
        }
        
        if ( !$_currUser ) {
            return $this->goHome();
        }
        
        $context = [
            'user_id' => $_currUser->id,
            'user_email' => $_currUser->email,
            'user_type' => $_currUser->user_type,
            'is_admin' => $isAdminMode,
        ];
        
        $ridesPending = null;
        $ridesActive = null;
        $ridesHistory = null;

        if ( User::$_TYPE_CUSTOMER === $_currUser->user_type ) {
            if ( $_request->get('ride_id') && $_request->get('action') ) {
                $ride = Ride::find()
                    ->where('(id=:ride_id) AND (client_id = :client_id)', [
                            'ride_id' => intval($_request->get('ride_id')),
                            'client_id' => $_currUser->id,
                        ])
                    ->one();
                
                if ( $ride && in_array($_request->get('action'), ['markComplete', 'rateDriver']) ) {
                    if ( ('markComplete' === $_request->get('action')) && in_array($ride->status, [Ride::$_STATUS_ACCEPTED]) ) {
                        $ride->status = Ride::$_STATUS_COMPLETE;
                        $ride->save();
                    } elseif ( ('rateDriver' === $_request->get('action')) && in_array($ride->status, [Ride::$_STATUS_COMPLETE]) ) {
                        $rate_score = ($_request->get('rate') ? floatval($_request->get('rate')) : null);
                        if ( $rate_score ) {
                            $_driver = User::findOne([
                                    'id' => $ride->driver_id,
                                    'user_type' => User::$_TYPE_DRIVER,
                                ]);
                            
                            if ( $_driver && !$_driver->isRatedBy($_currUser->id) ) {
                                $_driver->rateByUser($_currUser->id, $rate_score);
                            }
                        }
                    }
                }
            }
            
            $ridesPending = Ride::find()
                ->where('(client_id=:client_id) AND (status IN (:stat_invitation, :stat_pending))', [
                        'client_id' => $_currUser->id,
                        'stat_invitation' => Ride::$_STATUS_INVITATION,
                        'stat_pending' => Ride::$_STATUS_PENDING,
                    ])
                ->orderBy('created_at ASC')
                ->all();
                    
            $ridesActive = Ride::find()
                ->where('(client_id=:client_id) AND (status=:stat_accepted)', [
                        'client_id' => $_currUser->id,
                        'stat_accepted' => Ride::$_STATUS_ACCEPTED,
                    ])
                ->orderBy('created_at ASC')
                ->all();
            
            $ridesHistory = Ride::find()
                ->where('(client_id=:client_id) AND (status IN (:stat_declined, :stat_completed))', [
                        'client_id' => $_currUser->id,
                        'stat_declined' => Ride::$_STATUS_DECLINED,
                        'stat_completed' => Ride::$_STATUS_COMPLETE,
                    ])
                ->limit(10)
                ->orderBy('created_at ASC')
                ->all();
        } else {
            if ( $_request->get('ride_id') && $_request->get('action') ) {
                $ride = Ride::find()
                    ->where('(id=:ride_id) AND (driver_id = :driver_id)', [
                            'ride_id' => intval($_request->get('ride_id')),
                            'driver_id' => $_currUser->id,
                        ])
                    ->one();
                
                if ( $ride && in_array($_request->get('action'), ['accept', 'decline', 'rateClient']) ) {
                    if ( ('accept' === $_request->get('action')) && in_array($ride->status, [Ride::$_STATUS_INVITATION, Ride::$_STATUS_PENDING]) ) {
                        $ride->status = Ride::$_STATUS_ACCEPTED;
                        $ride->save();
                    } elseif( ('decline' === $_request->get('action')) && in_array($ride->status, [Ride::$_STATUS_INVITATION, Ride::$_STATUS_PENDING]) ) {
                        $ride->status = Ride::$_STATUS_DECLINED;
                        $ride->save();
                    } elseif( ('rateClient' === $_request->get('action')) && in_array($ride->status, [Ride::$_STATUS_COMPLETE]) ) {
                        $rate_score = ($_request->get('rate') ? floatval($_request->get('rate')) : null);
                        if ( $rate_score ) {
                            $_client = User::findOne([
                                    'id' => $ride->client_id,
                                    'user_type' => User::$_TYPE_CUSTOMER,
                                ]);
                            
                            if ( $_client && !$_client->isRatedBy($_currUser->id) ) {
                                $_client->rateByUser($_currUser->id, $rate_score);
                            }
                        }
                    }
                }
            }
            
            $ridesPending = Ride::find()
                ->where('(driver_id=:driver_id) AND (status IN (:stat_invitation, :stat_pending))', [
                        'driver_id' => $_currUser->id,
                        'stat_invitation' => Ride::$_STATUS_INVITATION,
                        'stat_pending' => Ride::$_STATUS_PENDING,
                    ])
                ->orderBy('created_at ASC')
                ->all();
                    
            $ridesActive = Ride::find()
                ->where('(driver_id=:driver_id) AND (status=:stat_accepted)', [
                        'driver_id' => $_currUser->id,
                        'stat_accepted' => Ride::$_STATUS_ACCEPTED,
                    ])
                ->orderBy('created_at ASC')
                ->all();
            
            $ridesHistory = Ride::find()
                ->where('(driver_id=:driver_id) AND (status IN (:stat_declined, :stat_completed))', [
                        'driver_id' => $_currUser->id,
                        'stat_declined' => Ride::$_STATUS_DECLINED,
                        'stat_completed' => Ride::$_STATUS_COMPLETE,
                    ])
                ->orderBy('created_at ASC')
                ->limit(10)
                ->all();
        }
        
        return $this->render('rides', [
            'ridesPending'  => $ridesPending,
            'ridesActive'   => $ridesActive,
            'ridesHistory'  => $ridesHistory,
            
            'context'       => $context
        ]);
    }
}
