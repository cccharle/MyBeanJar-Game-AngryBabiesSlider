<?php

namespace MyBeanJar\ApiBundle\ApiService;

/**
 * This model provides the main interface for all API business logic
 *
 * This is version 2, as yet unimplemented in the client api but used
 * to plan the future enhancements to API.
 */

use Doctrine\DBAL\Connection;
use MyBeanJar\CoreBundle\Exception\BeanNoPermissionsException;
use MyBeanJar\CoreBundle\Exception\InvalidBeanException;
use MyBeanJar\CoreBundle\Exception\InvalidPasswordException;
use MyBeanJar\CoreBundle\Exception\InvalidSponsorException;
use MyBeanJar\CoreBundle\Exception\InvalidUserAppException;
use MyBeanJar\CoreBundle\Exception\InvalidZipCodeException;
use MyBeanJar\CoreBundle\Exception\NoBeanInventoryException;
use MyBeanJar\CoreBundle\Exception\UserAuthenticationException;
use MyBeanJar\CoreBundle\Model\AppModel;
use MyBeanJar\CoreBundle\Model\UserBeanModel;
use MyBeanJar\CoreBundle\Model\UserModel;
use MyBeanJar\CoreBundle\Model\CategoryModel;
use MyBeanJar\CoreBundle\Model\SponsorModel;
use MyBeanJar\CoreBundle\Model\SponsorLocationModel;

use MyBeanJar\CoreBundle\Exception\UserNameExistsException;
use MyBeanJar\CoreBundle\Exception\UserNameLengthException;
use MyBeanJar\CoreBundle\Exception\EmailExistsException;
use Symfony\Component\DependencyInjection\ContainerAware;

class ApiServiceVersion2 extends ContainerAware
{
    public function __construct(Connection $conn )
    {
        $this->conn = $conn;

    }

    public function appsAction($request)
    {
        /*

        request:
        {
            "resource": "v2services",
            "method": "apps",
            "params": {
                "limit": 0,
                "username": "grant.whitaker@limelyte.com",
                "password": "792c6ff53511244e270d41c282aeb00c"
            }
        }


        response:
        {
            "status": 1,
            "response": {
                "apps": [
                    {
                        "appKey": "c4ca4238a0b923820dcc509a6f75849b",
                        "name": "Theseus",
                        "publisherKey": "c81e728d9d4c2f636f067f89cc14862c",
                        "publisher": "Jason Fieldman",
                        "publisherurl": "",
                        "description": "Description",
                        "iconurl": "http://api.mybeanjar.com/resources/appicon/1",
                        "appstoreurl": "http://itunes.apple.com/us/app/theseus-beanjar-enabled/id475217016?ls=1&mt=8 "
                    }
            }
        }
        */

        $user = null;
        try{
            if(isset($request->params->username) && isset($request->params->password)){
                $username = $request->params->username;
                $password = $request->params->password;
                $user = UserModel::getUserFromUsernamePass($this->conn, $username, $password);
            } else {
                throw new \InvalidArgumentException('Username and/or Password Undefined');
            }

            if (!isset($request->params->limit)) {
                throw new \InvalidArgumentException('Limit Undefined.');
            }

            if($user != null && $user->getId() > 0) {

                //@todo: once api passes in a limit, we need to limit our items here as well
                $apps = AppModel::staticFetchManyBy($this->conn,'active=:active',array(':active'=>1), null, 'ASC',$request->params->limit);

               $records = array();

                if (sizeof($apps) == 1) { //Limit was passed in as one
                    $records[] = array(
                        'appkey' => md5($apps->getId()),
                        'name' => $apps->getField('name'),
                        'publisherkey' => md5($apps->getPublisher()->getId()),
                        'publisher' => $apps->getPublisher()->getField('name'),
                        'publisherurl' => $apps->getPublisher()->getField('url'),
                        'description' => $apps->getField('description'),
                        'iconurl' => $apps->getIconURL(),
                        'appstoreurl' => $apps->getField('downloadurl'),
                    );
                } else {
                    foreach($apps AS $app){
                        $records[] = array(
                            'appkey' => md5($app->getId()),
                            'name' => $app->getField('name'),
                            'publisherkey' => md5($app->getPublisher()->getId()),
                            'publisher' => $app->getPublisher()->getField('name'),
                            'publisherurl' => $app->getPublisher()->getField('url'),
                            'description' => $app->getField('description'),
                            'iconurl' => $app->getIconURL(),
                            'appstoreurl' => $app->getField('downloadurl'),
                        );
                    }
                }
                //get all active games
                $ret = array(
                    'status' => 1,
                    'response' => array(
                        'apps' => $records,
                    ),
                );
                return $ret;
            } else {
                throw new UserAuthenticationException();
            }
        } catch (UserAuthenticationException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => 'Invalid Username and/or Password',
                ),
            );
        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => $e->getMessage(),
                ),
            );
        }

        return $ret;
    }

    public function winnersAction($request)
    {
        //@todo: for some reason limit 1 returns none, but limit 2 and above return fine

        /*
        {
            "resource": "v2services",
            "method": "winners",
            "params": {
                "limit": "0"
            }
        }

        $response = array(
            'status' => 1,
            'response' => array(
                'winners' => array(
                    array(
                        'username' => 'jimbob',
                        'appid' => 1,
                        'appname' => 'WhompAWeasel',
                        'sponsorlogourl' => 'http://www.mybeanjar.com/blah/logo.path',
                    ),
                ),
            ),
        );
        */
        $winners = array();
        $beans = array();
        try{
            $beans = UserBeanModel::getRecentAwardedBeans($this->conn,$request->params->limit);
            //loop through and create winner details
            if(sizeof($beans) == 1) {
                $winners[] = array(
                    'username'          => $beans->getUser()->getField('username'),
                    'appkey'            => md5($beans->getApp()->getId()),
                    'appname'           => $beans->getApp()->getField('name'),
                    'sponsorlogourl'    => $beans->getBean()->getSponsor()->getLogoURL(),
                );
            }
            foreach($beans AS $bean){
                    $winners[] = array(
                        'username'          => $bean->getUser()->getField('username'),
                        'appkey'            => md5($bean->getApp()->getId()),
                        'appname'           => $bean->getApp()->getField('name'),
                        'sponsorlogourl'    => $bean->getBean()->getSponsor()->getLogoURL(),
                    );


            }

            $ret = array(
                'status'    => 1,
                'response'  => array(
                    'totalbeans'    => UserBeanModel::getTotalAwardedBeanCount($this->conn),
                    'winners'       => $winners
                ),
            );
        }catch(\Exception $e){
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => 'There was an error retrieving data.',
                ),
            );
        }

        return $ret;
    }

    public function beansAction($request)
    {
        /*
        request:
        {
            "resource": "v2services",
            "method": "beans",
            "params": {
                "username": "david.phillips",
                "password": "16c446b89d5131deec07d2273de5fd16",
                "sortby": "fieldid"
                "limit": "0"
            }
        }

        response:
        {
            "status": 1,
            "response": {
                "beans": [
                    {
                        "id": "3196",
                        "sponsorkey": "eecca5b6365d9607ee5a9d336962c534",
                        "sponsorname": "Harry & David",
                        "sponsorurl": "http://www.harryanddavid.com",
                        "sponsorlogourl": "http://api.mybeanjar.com/resources/sponsorlogo/185",
                        "shortdescription": "30% Off Fruit Clubs with 12-Month Fruit-of-the-Month Clubs",
                        "longdescription": "30% Off Fruit Clubs with 12-Month Fruit-of-the-Month Clubs",
                        "expirationdate": "2013-12-31 00:00:00 -0800",
                        "wondate": "2013-07-25 14:43:53 -0700",
                        "game": "BeanjarTheseus",
                        "redeembarcodeurl": "http://api.mybeanjar.com/resources/beanredeemimg/258",
                        "geocodekey": "",
                        "redemptionvalidation": 0,
                        "redemptionurl": "https://api.mybeanjar.com/resources/beanredeem/3196"
                    }
                ]
            }
        }
        */
        $user = null;
        try {
            if(isset($request->params->username) && isset($request->params->password) && isset($request->params->limit)) {
                $username = $request->params->username;      //@todo: don't hash password here, needs to salt
                $password = $request->params->password; //@todo: future api versions should send a hashed password over the wire
                $user = UserModel::getUserFromUsernamePass($this->conn,$username,$password);
            } else {
                throw new \InvalidArgumentException("Username and/or Password Undefined");
            }
            if($user != null && $user->getId() > 0){

                if(isset($request->params->sortby)) {
                    $records = $user->getActiveUserBeans($request->params->limit, $request->params->sortby);
                } else {
                    $records = $user->getActiveUserBeans($request->params->limit);
                }


                $beans = array();
                foreach($records AS $bean){
                    $expires = date_create($bean->getBean()->getField('enddate'));
                    $wondate = date_create($bean->getField('awarded'));

                    $beans[] = array(
                        'beankey' => md5($bean->getField('id')),
                        'sponsorkey' => $bean->getBean()->getSponsor()->getKey(),
                        'sponsorname' => $bean->getBean()->getSponsor()->getField('name'),
                        'sponsorurl' => $bean->getBean()->getSponsor()->getField('url'),
                        'sponsorlogourl' => $bean->getBean()->getSponsor()->getLogoURL(),
                        'shortdescription' => $bean->getBean()->getField('name'),
                        'longdescription' => $bean->getBean()->getField('name'), //@todo: do we set a longer description / offer here?
                        'expirationdate' => $expires->format('Y-m-d H:i:s O'),
                        'wondate' => $wondate->format('Y-m-d H:i:s O'),
                        'game' => $bean->getApp()->getField('name'),
                        'redeembarcodeurl' => $bean->getBean()->getRedemptionImageURL(),
                        'geocodekey' => '',
                        'redemptionvalidation' => 0,
                        'redemptionurl' => $bean->getRedemptionURL(),
                    );
                }

                $ret = array(
                    'status' => 1,
                    'response' => array(
                        'beans' => $beans,
                    ),
                );
            } else {
                throw new UserAuthenticationException();
            }

        } catch (UserAuthenticationException $e) {
            $ret = array(
                'status'    => 0,
                'response'  => array(
                    'message'   => 'Invalid Username and/or Password',
                ),
            );
        } catch (\InvalidArgumentException $e) {
            $ret = array(
              'status'      => 0,
               'response'   => array(
                   'message'    => 'Insufficient Arguments',
               )
            );
        }

        return $ret;
    }

    public function authenticateuserAction($request)
    {
        
        /*
         request:
         {
            "resource": "v2services",
            "method": "authenticateuser",
            "params": {
                "password": "792c6ff53511244e270d41c282aeb00c",
                "username": "grant.whitaker@limelyte.com"
            }
        }

        response:
        {
            "status": 1,
            "response": {
                "message": "successfully authenticated"
            }
        }
        */
        $user = null;
        try {
            if(isset($request->params->username) && isset($request->params->password)) {
                $username = $request->params->username;      //@todo: don't hash password here, needs to salt
                $password = $request->params->password; //@todo: future api versions should send a hashed password over the wire

                //first, authenticate this user
                $user = UserModel::getUserFromUsernamePass($this->conn,$username,$password);
                
            } else {
                throw new \InvalidArgumentException();
            }

            if($user != null && $user->getId() > 0){
                $ret = array(
                    'status' => 1,
                    'response' => array(
                        'message' => 'successfully authenticated',
                    ),
                );
            } else {
                throw new UserAuthenticationException();
            }

        } catch (UserAuthenticationException $e) {
            $ret = array(
                'status'    => 0,
                'response'  => array(
                    'message'   => 'Invalid Username and/or Password',
                ),
            );
        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status'      => 0,
                'response'   => array(
                    'message'    => 'Insufficient Arguments',
                )
            );
        }
        return $ret;
    }

    public function registeruserAction($request)
    {
        /*
        request:
        {
            "resource": "v2services",
            "method": "registeruser",
            "params": {
                "email": "grant2@limelyte.com",
                "username": "grant2@limelyte.com",
                "password": "testpass",
                "hardwareid": "1234567",
                "zipcode":"99215",
                "categories": [
                    "c4ca4238a0b923820dcc509a6f75849b",
                    "c81e728d9d4c2f636f067f89cc14862c",
                    "eccbc87e4b5ce2fe28308fd9f2a7baf3",
                    "a87ff679a2f3e71d9181a67b7542122c"
                ]
            }
        }


        response:
        {
            "status": 1,
            "response": {
                "message": "User Successfully Registered"
            }
        }
0
        */

        try{
            if (isset($request->params->email) && isset($request->params->username) && isset($request->params->password)
                && isset($request->params->zipcode) && isset($request->params->categories)){

                $user = new UserModel($this->conn);
                $user->setField('email',$request->params->email);
                $user->setField('username',$request->params->username);
                $user->setField('password',$request->params->password);
                $user->setField('role_id',2); //set to game player
                $user->setField('zip',$request->params->zipcode);

                //validate the user
                $user->validate();
                //save the user if they validated
                $user->save();
                //add categories to this user
                $user->addCategoriesByKey($request->params->categories);

                $ret = array(
                    'status'    => 1,
                    'response'  => array(
                        'message'   => "User Successfully Registered",
                    ),
                );
            } else {
                throw new \InvalidArgumentException('Missing Argument(s)');
            }
        } catch (UserNameExistsException $e){
            $errcode = 'USER_ALREADY_EXISTS';
            $errmsg = $e->getMessage();
        } catch (UserNameLengthException $e){
            $errcode = 'LESS_USERNAME_LENGTH';
            $errmsg = $e->getMessage();
        } catch (EmailExistsException $e){
            $errcode = 'EMAIL_ALREADY_EXISTS';
            $errmsg = $e->getMessage();
        } catch (InvalidZipCodeException $e) {
            $errcode = 'INVALID ZIP CODE';
            $errmsg = $e->getMessage();
        } catch (\InvalidArgumentException $e) {
            $errcode = 'Insufficient Arguments';
            $errmsg  = $e->getMessage();
        }

        if(isset($errcode) && isset($errmsg)){
            $ret = array(
                'status' => 0,
                'response' => array(
                    'code' => $errcode,
                    'message' => $errmsg,
                ),
            );
        }

        return $ret;

    }

    public function getuserinformationAction($request)
    {
        /*
         *
         Request:
        {
            "resource": "v2services",
            "method": "getuserinformation",
            "params": {
                "username": "grant.whitaker@limelyte.com",
                "password": "792c6ff53511244e270d41c282aeb00c"
            }
        }
        Response:
       {
            "status": 1,
            "response": {
                "userkey": "0fcbc61acd0479dc77e3cccc0f5ffca7",
                "rolekey": "c4ca4238a0b923820dcc509a6f75849b",
                "username": "grant.whitaker@limelyte.com",
                "fname": "Grant",
                "lname": "Whitaker",
                "zipcode": "99217",
                "gender": "M",
                "email": "grant.whitaker@limelyte.com",
                "phone": "5092410138",
                "dob": "05/22/1988",
                "categories": [
                    "cfcd208495d565ef66e7dff9f98764da",
                    "c4ca4238a0b923820dcc509a6f75849b",
                    "c81e728d9d4c2f636f067f89cc14862c",
                    "eccbc87e4b5ce2fe28308fd9f2a7baf3",
                    "a87ff679a2f3e71d9181a67b7542122c"
                ]
            }
        }
         */

        try{
            if (isset($request->params->username) && isset($request->params->password)){

                $user = UserModel::getUserFromUsernamePass($this->conn, $request->params->username, $request->params->password);

                if($user && $user->getField('id') > 0) {

                    if($user->getField('dob') != null && $user->getField('dob') != '0000-00-00'){
                        $dob = date('m/d/Y',strtotime($user->getField('dob')));
                    }else{
                        $dob = null;
                    }

                    $ret = array(

                        'status' => 1,
                        'response' => array(
                            'userkey' => md5($user->getField('id')),
                            'rolekey' => md5($user->getField('role_id')),
                            'username' => $user->getField('username'),
                            'fname' => $user->getField('fname'),
                            'lname' => $user->getField('lname'),
                            'zipcode' => $user->getField('zip'),
                            'gender' => ucFirst($user->getField('gender')),
                            //'password' => $user->getField('password'),
                            'email' => $user->getField('email'),
                            'phone' => $user->getField('phone'),
                            'dob' => $dob,
                            'categories' => $user->getUserCategoryKeys(),
                        ),
                    );


                } else {
                    throw new InvalidUserAppException();
                }

            } else {
                throw new \InvalidArgumentException('Arguments missing from request.');
            }
        } catch (InvalidUserAppException $e){
            $errcode = 'Invalid User\App';
            $errmsg = $e->getMessage();
        } catch (\InvalidArgumentException $e) {
            $errcode = 'Insufficient Arguments';
            $errmsg  = $e->getMessage();
        }

        if(isset($errcode) && isset($errmsg)){
            $ret = array(
                'status' => 0,
                'response' => array(
                    'code' => $errcode,
                    'message' => $errmsg,
                ),
            );
        }

        return $ret;

    }
    public function setuserinformationAction($request)
    {
        /*
        request:
        {
            "resource": "v2services",
            "method": "setuserinformation",
            "params": {
                "username": "grant.whitaker@limelyte.com",
                "fname": "Grant",
                "lname": "lname",
                "password": "792c6ff53511244e270d41c282aeb00c",
                "hardwareid": "1234567",
                "zipcode": "99217",
                "categories": [
                    "c4ca4238a0b923820dcc509a6f75849b",
                    "c81e728d9d4c2f636f067f89cc14862c",
                    "eccbc87e4b5ce2fe28308fd9f2a7baf3",
                    "a87ff679a2f3e71d9181a67b7542122c"
                ]
            }
        }

        response:
        {
            "status": 1,
            "response": {
                "message": "User info successfully updated"
            }
        }
        */
        try{
            if (isset($request->params->username) && isset($request->params->password)){

                $user = UserModel::getUserFromUsernamePass($this->conn, $request->params->username, $request->params->password);

                if($user && $user->getField('id') > 0) {

                    if(isset($request->params->email)) {
                       $user->setField('email', $request->params->email);
                    }

                    if(isset($request->params->fname)) {
                        $user->setField('fname', $request->params->fname);
                    }

                    if(isset($request->params->lname)) {
                        $user->setField('lname', $request->params->lname);
                    }

                    if(isset($request->params->zipcode)) {
                        if (strlen($request->params->zipcode) < 5) {
                            throw new InvalidZipCodeException();
                        } else {
                            $user->setField('zip', $request->params->zipcode);
                        }

                    }

                    if(isset($request->params->gender)) {
                        $user->setField('gender', $request->params->gender);
                    }

                    if(isset($request->params->phone)) {
                        $user->setField('phone', $request->params->phone);
                    }

                    if(isset($request->params->dob)) {
                        if($request->request->dob != null){
                            $user->setField('dob',date('Y-m-d',strtotime($request->request->dob)));
                        }
                    }

                    if(isset($request->params->categories)) {
                        foreach($request->params->categories as $categoryKey) {
                            $user->addCategoryByKey($categoryKey);
                        }
                    }

                    $user->save();

                    $ret = array(
                        'status' => 1,
                        'response' => array(
                            'message' => "User info successfully updated",
                        ),
                    );
                } else {
                    throw new InvalidUserAppException();
                }

            } else {
                throw new \InvalidArgumentException();
            }
        } catch (UserNameExistsException $e){
            $errcode = 'EMAIL_ALREADY_EXISTS';
            $errmsg = $e->getMessage();
        } catch (InvalidZipCodeException $e) {
            $errcode = "INVALID_ZIP_CODE";
            $errmsg = $e->getMessage();
        } catch (EmailExistsException $e){
            $errcode = 'EMAIL_ALREADY_EXISTS';
            $errmsg = $e->getMessage();
        } catch (\InvalidArgumentException $e) {
            $errcode = 'Insufficient Arguments';
            $errmsg  = $e->getMessage();
        }

        if(isset($errcode) && isset($errmsg)){
            $ret = array(
                'status' => 0,
                'response' => array(
                    'code' => $errcode,
                    'message' => $errmsg,
                ),
            );
        }

        return $ret;
    }

    public function categoriesAction($request)
    {
        /*
        request:
        {
            "resource": "v2services",
            "method": "categories",
            "params": {
                "password": "792c6ff53511244e270d41c282aeb00c",
                "username": "grant.whitaker@limelyte.com"
            }
        }
       response:
        {
            "status": 1,
            "response": {
                "categories": [
                    {
                        "categorykey": "c4ca4238a0b923820dcc509a6f75849b",
                        "name": "Apparel"
                    },
                    {
                        "categorykey": "c81e728d9d4c2f636f067f89cc14862c",
                        "name": "Automotive"
                    },
                    {
                        "categorykey": "eccbc87e4b5ce2fe28308fd9f2a7baf3",
                        "name": "Food and Beverage"
                    },
                    {
                        "categorykey": "a87ff679a2f3e71d9181a67b7542122c",
                        "name": "Personal Care"
                    },
                    {
                        "categorykey": "e4da3b7fbbce2345d7772b0674a318d5",
                        "name": "Health and Fitness"
                    },
                    {
                        "categorykey": "1679091c5a880faf6fb5e6087eb1b2dc",
                        "name": "Entertainment"
                    },
                    {
                        "categorykey": "8f14e45fceea167a5a36dedd4bea2543",
                        "name": "Flowers and Gifts"
                    },
                    {
                        "categorykey": "c9f0f895fb98ab9159f51fd0297e236d",
                        "name": "Grocery or Packaged Goods"
                    },
                    {
                        "categorykey": "45c48cce2e2d7fbdea1afc51c7c6ad26",
                        "name": "Home and Garden"
                    },
                    {
                        "categorykey": "d3d9446802a44259755d38e6d163e820",
                        "name": "Pets"
                    },
                    {
                        "categorykey": "6512bd43d9caa6e02c990b0a82652dca",
                        "name": "Sports"
                    },
                    {
                        "categorykey": "c20ad4d76fe97759aa27a0c99bff6710",
                        "name": "Tech"
                    },
                    {
                        "categorykey": "c51ce410c124a10e0db5e4b97fc2af39",
                        "name": "Travel and Leisure"
                    },
                    {
                        "categorykey": "aab3238922bcc25a6f606eb525ffdc56",
                        "name": "Office"
                    },
                    {
                        "categorykey": "9bf31c7ff062936a96d3c8bd1f8f2ff3",
                        "name": "Grab Bag"
                    },
                    {
                        "categorykey": "c74d97b01eae257e44aa9d5bade97baf",
                        "name": "Charitable Donation"
                    },
                    {
                        "categorykey": "70efdf2ec9b086079795c442636b55fb",
                        "name": "Other"
                    },
                    {
                        "categorykey": "1f0e3dad99908345f7439f8ffabdffc4",
                        "name": "Education"
                    },
                    {
                        "categorykey": "98f13708210194c475687be6106a3b84",
                        "name": "Games"
                    }
                ]
            }
        }
        */
        $options = array();
        $user = null;

        try {
            if(isset($request->params->username) && isset($request->params->password)) {
                $username = $request->params->username;
                $password = $request->params->password;
                $user = UserModel::getUserFromUsernamePass($this->conn, $username, $password);
            } else {
                throw new \InvalidArgumentException('Username and/or Password Undefined');
            }

            if ($user != null && $user->getId() > 0){
                $categories = CategoryModel::staticFetchManyBy($this->conn,'active=:active',array(':active'=>1));
                foreach($categories AS $cat){
                    $options[] = array(
                        'categorykey'   => md5($cat->getField('id')),
                        'name' => $cat->getField('name'),
                    );
                }

                $ret = array(
                    'status' => 1,
                    'response' => array(
                        'categories' => $options,
                    ),
                );

            } else {
                throw new UserAuthenticationException();
            }
        } catch (UserAuthenticationException $e) {
            $ret = array(
                'status'    => 0,
                'response'  => array(
                    'message'   => 'Invalid Username and/or Password',
                ),
            );
        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status'      => 0,
                'response'   => array(
                    'message'    => 'Insufficient Arguments',
                )
            );
        }

        return $ret;
    }

    public function termsAction($request)
    {
        /*
        $request = array(
            'resource' => 'v2services',
            'method' => 'terms',
            'params' => array( //not required
            ),
        );

        $response = array(
            'status' => 1,
            'response' => array(
                'terms' => 'long details of terms and conditions',
            ),
        );
        */
    }

    public function validateuserAction($request)
    {
        /*
        request:
        {
            "resource": "v2services",
            "method": "validateuser",
            "params":
            {
                "username": "grant.whitaker@limelyte.com"
            }
        }

        response:
        {
            "status": 1,
            "response":
            {
                "message": "This is a valid user"
            }
        }
        */
        try {
            if (isset($request->params->username)) {
                $users = UserModel::staticFetchManyBy($this->conn,'username=:username',array(':username'=>$request->params->username));
                if(sizeof($users) > 0){
                    $ret = array(
                        'status' => 1,
                        'response' => array(
                            'message' => 'This is a valid user',
                        ),
                    );
                }else{
                    $ret = array(
                        'status' => 0,
                        'response' => array(
                            'message' => 'This is not a valid user',
                        ),
                    );
                }
            } else {
                throw new \InvalidArgumentException();
            }
        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => 'Insufficient Arguments',
                ),
            );
        }

        return $ret;
    }

    public function awardcouponAction($request)
    {
        /*
        request:
        {
            "resource": "v2services",
            "method": "awardcoupon",
            "params": {
                "password": "792c6ff53511244e270d41c282aeb00c",
                "username": "grant.whitaker@limelyte.com",
                "appkey":"c4ca4238a0b923820dcc509a6f75849b"
            }
        }


        response:
        {
            "status": 1,
            "response":
                {
                    "beankey": "3454",
                    "awarded": 1,
                    "imageurl": "https://api.mybeanjar.com/resources/beanimg/3454",
                    "message": "Bean successfully awarded."
                }
        }
        */

        $user = null;
        try {
            if (isset($request->params->username) && isset($request->params->password) && isset($request->params->appkey)) {
                $user = UserModel::getUserFromUsernamePass($this->conn, $request->params->username, $request->params->password);
                $app = AppModel::staticFetchManyBy($this->conn,'md5(id)=:key',array(':key'=>$request->params->appkey));

                if (sizeof($app) > 0) {

                    $app = array_shift($app);
                } else {
                    $app = null;
                }
            } else {
                throw new \InvalidArgumentException();
            }

            //if bean is active, redeem it
            if($user != null && $app != null && $user->getId() > 0 && $app->getId() > 0){
                $bean = $user->awardBean($app->getId());

                if($bean != null){
                    $ret = array(
                        'status' => 1,
                        'response' => array(
                            'beankey' => md5($bean->getId()),
                            'awarded' => 1,
                            'imageurl' => $bean->getImageURL(),
                            'message' => 'Bean successfully awarded.',
                        ),
                    );
                }else{
                    $ret = array(
                        'status' => 0,
                        'response' => array(
                            'awarded' => 0,
                            'couponurl' => 'http://api.mybeanjar.com/resources/beanimg/0', //bean jar full
                            'message' => 'Bean jar full or no inventory.',
                        ),
                    );

                }
            }else{
                throw new InvalidUserAppException();
            }
        } catch (InvalidUserAppException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => 'Invalid user/app.',
                ),
            );
        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => 'Insufficient Arguments',
                ),
            );
        } catch(NoBeanInventoryException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => $e->getMessage(),
                )
            );
        }

        return $ret;
    }

    public function sendpasswordAction($request)
    {

        /*
        request:
        {
            "resource": "v2services",
            "method": "sendpassword",
            "params": {
                "username": "grant.whitaker@limelyte.com"
            }
        }

        {
            "status": 1,
            "response": {
                "message": "Instructions for changing your password have been mailed to your registered email address."
            }
        }
        */


        try {
            if (isset($request->params->username)) {
                $user = UserModel::staticFetchManyBy($this->conn, 'username=:username', array(':username' => $request->params->username));
                if (sizeof($user) > 0) {
                    $user = array_shift($user);

                } else {
                    $user = null;
                }

                if(!is_null($user)){

                    $user->setField('password_reset_hash', md5(uniqid($user->getField('id') . time('Y/m/d H:i:s'))));
                    $user->save();

                    $body = "Please click the link below for further instructions on how to reset your password.";
                    $body .= 'http://www.mybeanjar.com/reset-password?key='. $user->getField('password_reset_hash');
                    // $message = \Swift_Message::newInstance()
                    //     ->setSubject('My Bean Jar Password Recovery')
                    //     ->setFrom(array('info@mybeanjar.com' => 'MyBeanJar'))
                    //     ->setTo($user->getField('email'))
                    //     ->setBody($body);
                    // $message = \Swift_Message::newInstance()
                    // ->setSubject('My Bean Jar Password Recovery')
                    //     ->setFrom(array('info@mybeanjar.com' => 'MyBeanJar'))
                    //     ->setTo('nitin.sharma@optimusinfo.com')
                    //     ->setBody($body);
                    //
                    // \Swift_Mailer::newInstance(new \Swift_MailTransport('smtp'))->send($message);
                    $to = $user->getField('email');
                    $subject = "My Bean Jar Password Recovery";
                    $txt = $body;
                    $headers = "From: info@mybeanjar.com" . "\r\n";
                    if(mail($to,$subject,$txt,$headers)){
                        $ret = array(
                            'status' => 1,
                            'rsp'=>$user->getField('email'),
                            'response' => array(
                                'message' => 'Instructions for changing your password have been mailed to your registered email address.',
                            ),
                        );
                    }else{
                        $ret = array(
                            'status' => 1,
                            'rsp'=>'error',
                            'response' => array(
                                'message' => 'error',
                            ),
                        );
                    }

                }

            } else {
                throw new \InvalidArgumentException('Must Pass User Name');
            }

        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => 'Insufficient Arguments',
                ),
            );
        }

        return $ret;
    }

    public function deletebeanAction($request)
    {
        /*
         * When testing use awardCoupon method to generate some beans.
        request:
        {
            "resource": "v2services",
            "method": "deletebean",
            "params": {
                "username": "david.phillips",
                "password": "16c446b89d5131deec07d2273de5fd16",
                "beankey": "2e1b24a664f5e9c18f407b2f9c73e821"
            }
        }

        response:
        {
            "status": 1,
            "response": {
                "message": "Bean successfully deleted."
            }
        }
        */
        $user = null;
        try {
            if (isset($request->params->username) && isset($request->params->password) && isset($request->params->beankey)) {
                $username = $request->params->username;
                $password = $request->params->password;
                $beanKey = $request->params->beankey;
                $user = UserModel::getUserFromUsernamePass($this->conn, $username, $password);
            } else {
                throw new \InvalidArgumentException();
            }


            if($user != null && $user->getId() > 0) {
                //first, see if this bean exists
                $bean = UserBeanModel::loadBeanByHashedId($this->conn, $beanKey);

                if(is_null($bean)) {
                    throw new InvalidBeanException();
                }

                if($bean->getUser()->getField('username') == $user->username &&
                            $bean->getUser()->getField('password') == $user->password){
                    $bean->setField('deleted',1);
                    $bean->save();
                    $ret = array(
                        'status' => 1,
                        'response' => array(
                            'message' => 'Bean successfully deleted.',
                        ),
                    );

                } else {
                    throw new BeanNoPermissionsException();
                }

            } else {
                throw new UserAuthenticationException();
            }
        } catch(UserAuthenticationException $e){
            $ret = array(
               'status' => 0,
               'response' => array(
                   'message' => 'Invalid Username and/or Password',
               ),
            );
        } catch(\InvalidArgumentException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => 'Insufficient Arguments',
                ),
            );
        } catch(InvalidBeanException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => 'Invalid Bean',
                ),
            );
        } catch (BeanNoPermissionsException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => 'Bean Not Owned By User',
                ),
            );
        }

        return $ret;
    }

    public function redeembeanAction($request)
    {
        /*
         * When testing use awardCoupon method to generate some beans.
        request:
        {
            "resource": "v2services",
            "method": "redeembean",
            "params": {
                "password": "792c6ff53511244e270d41c282aeb00c",
                "username": "grant.whitaker@limelyte.com",
                "beankey": "2e1b24a664f5e9c18f407b2f9c73e821",
                "lat": "123.000000",
                "lon": "123.000000"
            }
        }

        response:
        {
            "status": 1,
            "response": {
                "message": "Redeem date successfully updated."
            }
        }
        */
        $user = null;
        try {
            if (isset($request->params->username) && isset($request->params->password) && isset($request->params->beankey)){
                $username = $request->params->username;
                $password = $request->params->password;
                $beanKey = $request->params->beankey;
                $user = UserModel::getUserFromUsernamePass($this->conn, $username, $password);
            } else {
                throw new \InvalidArgumentException();
            }

            if ($user != null && $user->getId() > 0) {
                    $bean = UserBeanModel::loadBeanByHashedId($this->conn, $beanKey);

                    if (is_null($bean)) {
                        throw new InvalidBeanException();
                    }
                    //if bean is active, redeem it
                    if($bean->isActive()){
                        if($bean->getUser()->getField('username') == $user->username &&
                            $bean->getUser()->getField('password') == $user->password){
                            //set redeemed
                            $bean->setField('redeemed',$this->conn->convertToDatabaseValue(new \DateTime('now'), 'datetime'));
                            $bean->setField('lat',$request->params->lat);
                            $bean->setField('lon',$request->params->lon);
                            $redeemcode = uniqid();
                            $bean->setField('redemptioncode',$redeemcode);
                            $bean->save();

                            $ret = array(
                                'status' => 1,
                                'response' => array(
                                    'message' => 'Redeem date successfully updated.',
                                ),
                                'data'=> array('redemptioncode',$redeemcode),
                            );
                        }else{
                            $ret = array(
                                'status' => 0,
                                'response' => array(
                                    'message' => 'No permissions to user bean.',
                                ),
                            );

                        }
                    } else{
                        $ret = array(
                            'status' => 0,
                            'response' => array(
                                'desc' => 'Inactive bean.',
                            ),
                        );
                    }

            } else {
               throw new UserAuthenticationException();
            }
        } catch (UserAuthenticationException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => 'Invalid Username and/or Password',
                ),
            );
        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => 'Insufficient Arguments',
                ),
            );
        } catch (InvalidBeanException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => 'Invalid Bean',
                ),
            );
        }



        return $ret;
    }

    public function sponsorlocationsAction($request)
{
    /*
    request:
    {
        "resource": "v2services",
        "method": "sponsorlocations",
        "params": {
            "password": "792c6ff53511244e270d41c282aeb00c",
            "username": "grant.whitaker@limelyte.com",
            "sponsorkey": "c81e728d9d4c2f636f067f89cc14862c"
        }
    }

    response:
    {
        "status": 1,
        "response": {
            "locations": [
                {
                    "locationKey": "c4ca4238a0b923820dcc509a6f75849b",
                    "name": "Downtown",
                    "address": "608 W Second",
                    "city": "Spokane",
                    "state": "WA",
                    "zip": null,
                    "country": "1",
                    "lat": "47.000000",
                    "lon": "-117.000000"
                },
                {
                    "locationKey": "c81e728d9d4c2f636f067f89cc14862c",
                    "name": "Slimelyte",
                    "address": "1001 Main St",
                    "city": "Spokane",
                    "state": "WA",
                    "zip": null,
                    "country": "1",
                    "lat": "47.000000",
                    "lon": "-117.000000"
                }
            ]
        }
    }
    */

    try {
        if(isset($request->params->username) && isset($request->params->password) && isset($request->params->sponsorkey)){
            $username = $request->params->username;
            $password = $request->params->password;
            $sponsorKey = $request->params->sponsorkey;
            $user = UserModel::getUserFromUsernamePass($this->conn, $username, $password);
        } else {
            throw new \InvalidArgumentException();
        }

        if ($user != null && $user->getId() > 0) {
            if ($user != null && $user->getId() > 0){
                $sponsor = SponsorModel::getSponsorFromKey($this->conn, $sponsorKey);

                if(is_null($sponsor)){
                    throw new InvalidSponsorException();
                } else {
                    $locs = array();
                    foreach($sponsor->getLocations() AS $loc){
                        $locs[] = array(
                            'locationkey' => md5($loc->getField('id')),
                            'name' => $loc->getField('name'),
                            'address' => $loc->getField('address'),
                            'city' => $loc->getField('city'),
                            'state' => $loc->getField('state'),
                            'zip' => $loc->getField('zip'),
                            'country' => $loc->getField('country'),
                            'lat' => $loc->getField('lat'),
                            'lon' => $loc->getField('lon'),
                        );
                    }
                    $ret = array(
                        'status' => 1,
                        'response' => array(
                            'locations' => $locs,
                        ),
                    );
                }
            }

        } else {
            throw new UserAuthenticationException();
        }
    } catch (UserAuthenticationException $e) {
        $ret = array(
            'status'    => 0,
            'response'  => array(
                'message'   => 'Invalid Username and/or Password',
            ),
        );
    } catch (\InvalidArgumentException $e) {
        $ret = array(
            'status'      => 0,
            'response'    => array(
                'message'    => 'Insufficient Arguments',
            )
        );
    } catch (InvalidSponsorException $e) {
        $ret = array(
            'status'    => 0,
            'response'  => array(
                'message'   => 'Invalid Sponsor',
            )
        );
    }
    return $ret;

}

    /**
     * Get nearby Sponsor's Locations
     * created-by : Narendra Kumar @ Agicent Technologies Pvt. Ltd. 21/01/2015 04:17PM
     */
    public function nearbySponsorlocationsAction($request)
    {
        try {
            if(isset($request->params->username) && isset($request->params->password) && isset($request->params->sponsorname)){
                $username           = $request->params->username;
                $password           = $request->params->password;
                $sponsorName        = urlencode($request->params->sponsorname);
                $radius             = $request->params->radius;
                $current_latitude   = $request->params->current_latitude;
                $current_longitude  = $request->params->current_longitude;

                $user = UserModel::getUserFromUsernamePass($this->conn, $username, $password);
            } else {
                throw new \InvalidArgumentException();
            }

            if ($user != null && $user->getId() > 0) {
                if ($user != null && $user->getId() > 0){
                    $current_location   =   "$current_latitude,$current_longitude";
                    $api_key            =   "AIzaSyC6AV2DC4-K21VDP3lziq33Er-v4ujYOOM";

                $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=$current_location&radius=$radius&name=$sponsorName&sensor=false&key=$api_key";

                    $locations = array();

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    $response = curl_exec($ch);
                    curl_close($ch);

                    $geoloc = json_decode($response, true);
                    $i=1;
                    foreach($geoloc['results'] as $loc){
                        $newarray = array();
                        $newarray[] = $loc["vicinity"];
                        $newarray[] = $loc["geometry"]["location"]["lat"];
                        $newarray[] = $loc["geometry"]["location"]["lng"];
                        $newarray[] = $i;
                        array_push($locations,$newarray);
                        $i++;
                    }
                    $ret = array(
                        'status' => 1,
                        'response' => array(
                            'locations' => $locations,
                        ),
                    );
                }

            } else {
                throw new UserAuthenticationException();
            }
        } catch (UserAuthenticationException $e) {
            $ret = array(
                'status'    => 0,
                'response'  => array(
                    'message'   => 'Invalid Username and/or Password',
                ),
            );
        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status'      => 0,
                'response'    => array(
                    'message'    => 'Insufficient Arguments',
                )
            );
        } catch (InvalidSponsorException $e) {
            $ret = array(
                'status'    => 0,
                'response'  => array(
                    'message'   => 'Invalid Sponsor',
                )
            );
        }

        return $ret;

    }



    public function sponsorsAction($request)
    {
        /*

        request:
        {
            "resource": "v2services",
            "method": "sponsors",
            "params": {
                "password": "792c6ff53511244e270d41c282aeb00c",
                "username": "grant.whitaker@limelyte.com"
                "sortby": 0
            }
        }

        response:
        {
            "status": 1,
            "response": {
                "sponsors": [
                     {
                        "sponsorkey": "c4ca4238a0b923820dcc509a6f75849b",
                        "name": "Omaha Steaks",
                        "siteurl": "http://www.omahasteaks.com/",
                        "logourl": "http://api.mybeanjar.com/resources/sponsorlogo/1",
                        "description": "Omaha Steaks",
                        "geocodekey": "address city, NE 99208"
                    }
                ]
            }
        }
         */
        $user = null;

        try {
            if(isset($request->params->username) && isset($request->params->password)) {
                $username = $request->params->username;
                $password = $request->params->password;

                $user = UserModel::getUserFromUsernamePass($this->conn, $username, $password);
            } else {
                throw new \InvalidArgumentException();
            }

            if ($user != null && $user->getId() > 0) {
                if(isset($request->params->sortby)) {
                    $ret = SponsorModel::getSortedSponsors($this->conn, $request->params->sortby);
                } else {
                    $ret = SponsorModel::staticFetchManyBy($this->conn,'active=:active',array(':active'=>'1'));
                }

                foreach($ret AS $sponsor){
                    if(sizeof($sponsor->getActiveBeans()) > 0){
                        $sponsors[] = array(
                            'sponsorkey' => $sponsor->getKey(),
                            'name' => $sponsor->getField('name'),
                            'siteurl' => $sponsor->getField('url'),
                            'logourl' => $sponsor->getLogoURL(),
                            'description' => $sponsor->getField('description'),
                            'geocodekey' => $sponsor->getField('address1').' '.$sponsor->getField('city').', '
                            .$sponsor->getField('state').' '.$sponsor->getField('zip'),
                        );
                    }
                }

                $ret = array(
                    'status' => 1,
                    'response' => array(
                        'sponsors' => $sponsors,
                    ),
                );
            } else {
                throw new UserAuthenticationException();
            }
        } catch (UserAuthenticationException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => 'Invalid Username and/or Password',
                ),
            );
        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => 'Insufficient Arguments',
                ),
            );
        }

        return $ret;

    }

    public function metadataAction($request)
    {
        /*
        request:
        {
            "resource": "v2services",
            "method": "metadata",
            "params": {
                "password": "792c6ff53511244e270d41c282aeb00c",
                "username": "grant.whitaker@limelyte.com",
                "appkey":"eccbc87e4b5ce2fe28308fd9f2a7baf3"
            }
        }


        response:
        {
            "status": 1,
            "response": {
                "appkey": "eccbc87e4b5ce2fe28308fd9f2a7baf3",
                "metadata": {
                    "genre": "Board Game",
                    "rating": "E"
                }
            }
        }
        */

        $user = null;
        try {
            if (isset($request->params->username) && isset($request->params->password) && isset($request->params->appkey)) {
                $user = UserModel::getUserFromUsernamePass($this->conn, $request->params->username, $request->params->password);
                $app = AppModel::staticFetchManyBy($this->conn,'md5(id)=:key',array(':key'=>$request->params->appkey));

                if (sizeof($app) > 0) {

                    $app = array_shift($app);
                } else {
                    $app = null;
                }
            } else {
                throw new \InvalidArgumentException();
            }

            //if bean is active, redeem it
            if($user != null && $app != null && $user->getId() > 0 && $app->getId() > 0){

                $metaData = $app->getField('metadata');

                if(!is_null($metaData) && $metaData != ''){
                    $ret = array(
                        'status' => 1,
                        'response' => array(
                            'appkey' => $app->getKey(),
                            'metadata' => json_decode($metaData),
                        ),
                    );
                }else{
                    $ret = array(
                        'status' => 0,
                        'response' => array(
                            'appkey' => $app->getKey(),
                            'metadata' => "No meta data is available.",
                        ),
                    );

                }
            }else{
                throw new InvalidUserAppException();
            }
        } catch (InvalidUserAppException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => 'Invalid user/app.',
                ),
            );
        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => 'Insufficient Arguments',
                ),
            );
        }

        return $ret;
    }

    public function setuserpasswordAction($request)
    {
        /*
        request:
        {
            "resource": "v2services",
            "method": "setuserpassword",
            "params": {
                "password": "49130949998dd88f70a988e9d2e323af",
                "username": "grant.whitaker@limelyte.com",
                "newpassword": "792c6ff53511244e270d41c282aeb00c",
                "confirmnewpassword": "792c6ff53511244e270d41c282aeb00c"
            }
        }


        response:
        {
            "status": 1,
            "response": {
                "message": "Password successfully updated."
            }
        }
        */
        $user = null;
        try {
            if (isset($request->params->username) && isset($request->params->password) && isset($request->params->newpassword) && isset($request->params->confirmnewpassword) ) {
                $user = UserModel::getUserFromUsernamePass($this->conn, $request->params->username, $request->params->password);

            } else {
                throw new \InvalidArgumentException();
            }

            if($user != null ){

                if($request->params->newpassword == $request->params->confirmnewpassword) {
                    $user->setField('password', $request->params->newpassword);
                    $user->save();
                } else {
                    throw new InvalidPasswordException();
                }

                $ret = array(
                    'status' => 1,
                    'response' => array(
                        'message' => 'Password successfully updated.'
                    ),
                );

            } else{
                throw new InvalidUserAppException();
            }
        } catch (InvalidUserAppException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => 'Invalid user/app.',
                ),
            );
        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => 'Insufficient Arguments',
                ),
            );
        } catch(InvalidPasswordException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => $e->getMessage(),
                )
            );
        }

        return $ret;
    }
    
     public function imagebuyAction($request)
    {
        
        $user = null;
        try {
            if (isset($request->params->username) && isset($request->params->password) && isset($request->params->appkey) && isset($request->params->image)) {
                $user = UserModel::getUserFromUsernamePass($this->conn, $request->params->username, $request->params->password);
                $app = AppModel::staticFetchManyBy($this->conn,'md5(id)=:key',array(':key'=>$request->params->appkey));

                if (sizeof($app) > 0) {

                    $app = array_shift($app);
                } else {
                    $app = null;
                }
            } else {
                throw new \InvalidArgumentException();
            }

            //if bean is active, redeem it
            if($user != null && $app != null && $user->getId() > 0 && $app->getId() > 0){
                $userimage = $user->addUserImage($user->getId(),$app->getId(),$request->params->image);
                if($userimage != null){
                    $order_id = $this->conn->lastInsertId();
                    $ret = array(
                        'status' => 1,
                        'response' => array(
                            'message' => 'Image Purchased sucessfully.',
                            'orderid' => $order_id,
                        ),
                    );
                }else{
                    $ret = array(
                        'status' => 0,
                        'response' => array(
                            'message' => 'Error in Image Purchase.',
                            'orderid' => '',
                        ),
                    );

                }
            }else{
                throw new InvalidUserAppException();
            }
        } catch (InvalidUserAppException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => 'Invalid user/app.',
                ),
            );
        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => 'Insufficient Arguments',
                ),
            );
        } catch(NoBeanInventoryException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => $e->getMessage(),
                )
            );
        }

        return $ret;
    }
    
    public function imagegetAction($request)
    {
        
        $user = null;
        try {
            if (isset($request->params->username) && isset($request->params->password) && isset($request->params->appkey)) {
                $user = UserModel::getUserFromUsernamePass($this->conn, $request->params->username, $request->params->password);
                $app = AppModel::staticFetchManyBy($this->conn,'md5(id)=:key',array(':key'=>$request->params->appkey));

                if (sizeof($app) > 0) {

                    $app = array_shift($app);
                } else {
                    $app = null;
                }
            } else {
                throw new \InvalidArgumentException();
            }

            //if bean is active, redeem it
            if($user != null && $app != null && $user->getId() > 0 && $app->getId() > 0){
                $userimage = $user->getUserImage($user->getId(),$app->getId());
                if($userimage != null){
                    $ret = array(
                        'status' => 1,
                        'response' => array(
                            'message' => 'Image get sucessfully.',
                            'userimages' => $userimage,
                        ),
                    );
                }else{
                    $ret = array(
                        'status' => 0,
                        'response' => array(
                            'message' => 'Error in Image get.',
                            'userimages' => '',
                        ),
                    );

                }
            }else{
                throw new InvalidUserAppException();
            }
        } catch (InvalidUserAppException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => 'Invalid user/app.',
                ),
            );
        } catch (\InvalidArgumentException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message'   => 'Insufficient Arguments',
                ),
            );
        } catch(NoBeanInventoryException $e) {
            $ret = array(
                'status' => 0,
                'response' => array(
                    'message' => $e->getMessage(),
                )
            );
        }

        return $ret;
    }
    


}
