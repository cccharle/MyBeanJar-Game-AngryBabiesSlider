<?php

namespace MyBeanJar\CoreBundle\Model;

use Doctrine\DBAL\Connection;

use Limelyte\Bundle\CoreBundle\Model\Sql\Common\User;

use MyBeanJar\CoreBundle\Exception\InvalidZipCodeException;
use MyBeanJar\CoreBundle\Exception\UserNameExistsException;
use MyBeanJar\CoreBundle\Exception\UserNameLengthException;
use MyBeanJar\CoreBundle\Exception\EmailExistsException;
use MyBeanJar\CoreBundle\Exception\LimitBeansPerHourException;
use MyBeanJar\CoreBundle\Exception\NoBeanInventoryException;

class UserModel extends User
{

    const SORT_BY_AWARD_DATE        = 0;
    const SORT_BY_EXPIRATION_DATE   = 1;
    const SORT_BY_USER_PROXIMITY    = 2;
    const SORT_BY_SPONSOR_NAME      = 3;
    const SORT_BY_CATEGORY_NAME     = 4;

    public function __construct(Connection $conn, $id = null)
    {
        $this->fields['timestamp'] = $conn->convertToDatabaseValue(new \DateTime('now'), 'datetime');
        parent::__construct($conn,$id);
    }

    public static function getUserFromUsernamePass(Connection $conn,$username,$password)
    {
        //first get a user by username
        $ret = null;

        $ret = UserModel::staticFetchManyBy($conn,'username=:username AND password=:password',array(':username'=>$username,':password'=>$password));
        if(sizeof($ret) > 0){

          return array_shift($ret);
        }else{
            return null;

        }
    }

    /**
     * getRole: return my associated role
     * 
     * @access public
     * @return mixed
     */
    public function getRole()
    {
		$ret = null;
		if($this->getField('role_id') > 0)
		{
			$ret = new RoleModel($this->conn,$this->getField('role_id'));
		}
		return $ret;
    }

	/**
	 * getUserBeans: Return ALL beans associated to this user.
	 * 
	 * @access public
	 * @return mixed
	 */
	public function getUserBeans()
	{
		$ret = array();
        //$ret = array('bean' => $this->getField('id'));
        $ret = UserBeanModel::staticFetchManyBy($this->conn,'user_id=:user_id',array(':user_id' => $this->getField('id')));
		return $ret;	
	}
	
	/**
	 * getActiveUserBeans: Return only active beans (not expired and not redeemed
	 * @todo: this should be refactored to use a query instead of loop on all beans
	 * @access public
	 * @return void
	 */
	public function getActiveUserBeans($limit = 0, $sortby = '')
	{
		$ret = array();

        $field = '';

        if($sortby == self::SORT_BY_AWARD_DATE ) {
            $field = 'userbeans.awarded DESC';
        } elseif ($sortby == self::SORT_BY_EXPIRATION_DATE) {
            $field = 'beans.enddate';
        } elseif ($sortby == self::SORT_BY_USER_PROXIMITY) {
            $field = ''; //Not yet implemented
        } elseif ($sortby == self::SORT_BY_SPONSOR_NAME) {
            $field = 'sponsors.name ASC';
        } elseif ($sortby == self::SORT_BY_CATEGORY_NAME) {
            $field = 'categories.name ASC';
        } else {
            $field = '';
        }

        $sql = "
                SELECT
                userbeans.id
                FROM beans
                INNER JOIN
                userbeans ON
                beans.id = userbeans.bean_id
                INNER JOIN
                categories ON
                categories.id = beans.category_id
                INNER JOIN
                sponsors ON sponsors.id = beans.sponsor_id
                WHERE userbeans.user_id = " . $this->getId() ;

        if($field != '') {
           $sql .= " ORDER BY " . $field ;
        }

        if($limit > 0) {
            $sql .= " LIMIT " . $limit;
        }

        $result = $this->conn->fetchAll($sql);

        foreach($result AS $rec){
            $bean = new UserBeanModel($this->conn,$rec['id']);
            if($bean->isActive()) {
                $ret[] = $bean;
            }
        }

		return $ret;
	}

    public function getNumBeansAwardedInLastHour()
    {
        $ret = 0;
        foreach($this->getActiveUserBeans() AS $bean){
            if(strtotime($bean->getField('awarded')) > time()-3600){
                $ret++;
            }
        }
        return $ret;
    }

    /**
     * return number of matching beans this user has that are active
     * @param $beanid
     * @return int
     */
    public function countActiveBeanById($beanid){
        $ret = 0;
        foreach($this->getActiveUserBeans() AS $bean){
            if($bean->getField('bean_id') == $beanid){
                $ret++;
            }
        }
        return $ret;
    }

    public function hasBeanById($beanid){
        $ret = false;
        foreach($this->getActiveUserBeans() AS $bean){
            if($bean->getField('bean_id') == $beanid){
                $ret = true;
            }
        }
        return $ret;
    }

    public function getAvailableBeans()
    {
        $beans = array();


        //get this user's lat/lon
        $zip = new ZipcodeModel($this->conn,$this->getField('zip'));
        $lat = $zip->getField('z_latitude');
        $lon = $zip->getField('z_longitude');

        $start = $this->conn->convertToDatabaseValue(new \DateTime('now'), 'datetime');
        $end = $this->conn->convertToDatabaseValue(new \DateTime('now'), 'datetime');


        $sql = "select
            beans.id AS beanid,
            beans.radius,
            sponsors.id AS sponsorid,
            zipcodes.z_latitude,
            zipcodes.z_longitude,
            ( 3959 * acos( cos( radians(".$lat.") ) * cos( radians( z_latitude ) ) * cos( radians( z_longitude ) - radians(".$lon.") ) + sin( radians(".$lat.") ) * sin( radians( z_latitude ) ) ) ) AS distance
            from beans
            INNER JOIN sponsors ON sponsors.id = beans.sponsor_id
            INNER JOIN zipcodes ON zipcodes.z_zipcode = sponsors.zip
            WHERE beans.enddate > '".$end."' AND beans.startdate < '".$start."' AND beans.qty > 0
            HAVING (distance < beans.radius) OR (beans.radius = 0) ORDER BY distance LIMIT 0 , 100;";

        $result = $this->conn->fetchAll($sql);

        foreach($result AS $rec){
            $beans[] = new BeanModel($this->conn,$rec['beanid']);
        }

        return $beans;
    }

    public function getRandomPassword() {

        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;

        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;

    }

    public function awardBean($appid=0){
        $ret = null;
        $awarded = null;

        //get all active beans
       // $beans = BeanModel::getAvailableBeans($this->conn);


        if($this->getNumBeansAwardedInLastHour() > 5){
            throw new LimitBeansPerHourException();
        }

        $beans = $this->getAvailableBeans();
        //make sure they're all active

        //randomize
        shuffle($beans);

        //@todo: need to match on user's category first, then whatever is available
        //@todo: total beans per user should be configurable
        //@todo: need to make sure they only win <x> per hour
        //@todo: this should raise exceptions to return meaningful info to caller


        foreach($beans AS $abean){
            if($this->countActiveBeanById($abean->getId()) < 1 && $this->getNumBeansAwardedInLastHour() < 6 && in_array($abean->getField('category_id'),$this->getUserCategoryIds())){
                $awarded = $abean;
                break;
            }
        }
        //no match on cat, now go outside cat
        if($awarded == null){
            foreach($beans AS $abean){
                if($this->countActiveBeanById($abean->getId()) < 1 && $this->getNumBeansAwardedInLastHour() < 6){
                    $awarded = $abean;
                    break;
                }
            }
        }

        if($awarded != null){
            $ret = new UserBeanModel($this->conn);
            $ret->setField('user_id',$this->getId());
            $ret->setField('bean_id',$awarded->getId());
            $ret->setField('app_id',$appid);
            $ret->setField('awarded',$this->conn->convertToDatabaseValue(new \DateTime('now'), 'datetime'));
            $ret->save();

            //decrement inventory
            $awarded->setField('qty',$awarded->getField('qty')-1);
            $awarded->save();
        }else{
            throw new NoBeanInventoryException();

        }

        return $ret;
    }

    public function getUsername()
    {
        return $this->fields['username'];
    }

    /**
     * Return an array of all UserCategory models associated to this user
     * @return array
     */
    public function getUserCategories()
    {
        $ret = array();
        //@todo: very weird, this only returns one model. Why???
        $ret = UserCategoryModel::staticFetchManyBy($this->conn,'user_id=:userid',array(':userid' => (int)$this->getId()));

        return $ret;
    }

    public function getUserCategoryIds()
    {
        $ret = array();

        foreach($this->getUserCategories() AS $cat){
            $ret[] = (int)$cat->getField('category_id');
        }

        return $ret;
    }

    public function getUserCategoryKeys()
    {
        $ret = array();

        foreach($this->getUserCategories() AS $cat){
            $ret[] = md5($cat->getField('category_id'));
        }

        return $ret;
    }

    public function removeCategoryByKey($categoryKey)
    {
        return $this->conn->delete('usercategories', array(
            'user_id' => $this->getId(),
            'md5(category_id)' => $categoryKey,
        ));
    }

    public function removeCategoryById($categoryid)
    {
        return $this->conn->delete('usercategories', array(
            'user_id' => $this->getId(),
            'category_id' => $categoryid
        ));
    }

    public function addCategoryByKey($categoryKey)
    {
        if(!$this->getId()) {
            $this->save();
        }

        $category = CategoryModel::staticFetchManyBy($this->conn, 'md5(id)=:key', array(':key' => $categoryKey));
        if (sizeof($category) > 0) {

            $category = array_shift($category);
            $this->removeCategoryById($category->getId());

            return $this->conn->insert('usercategories', array(
                'user_id' => $this->getId(),
                'category_id' => $category->getId(),
            ));
        }
    }
    public function addCategoryById($categoryid)
    {
        if (!$this->getId()) {
            $this->save();
        }

        //first remove and then re-add
        $this->removeCategoryById($categoryid);

        return $this->conn->insert('usercategories', array(
            'user_id' => $this->getId(),
            'category_id' => $categoryid,
        ));
    }

    /*
     * same as below, just remove all cats first
     *
     */
    public function setCategoriesById($categories){
        $this->conn->delete('usercategories', array(
            'user_id' => $this->getId()
        ));

        $this->addCategoriesById($categories);
    }

    public function addCategoriesById($categories)
    {
        if(is_array($categories)){
            foreach($categories AS $catid){
                $categoryid = CategoryModel::staticFetchManyBy($this->conn, 'id=:key', array(':key' => $catid));
                if (sizeof($categoryid) > 0) {
                    $categoryid = array_shift($categoryid);
                    $this->addCategoryById($categoryid);
                }
            }
        }
    }

    public function addCategoriesByKey($categories)
    {
        if(is_array($categories)){
            foreach($categories AS $catid){
                $categoryid = CategoryModel::staticFetchManyBy($this->conn, 'md5(id)=:key', array(':key' => $catid));
                if (sizeof($categoryid) > 0) {
                    $categoryid = array_shift($categoryid);
                    $this->addCategoryById($categoryid);
                }
            }
        }
    }

    /**
     * validate all user info
     */
    public function validate()
    {

        //user exists
        if (sizeof(UserModel::staticFetchManyBy($this->conn,'username=:username',array(':username' => $this->getField('username')))) > 0){
            throw new UserNameExistsException();
        }

        //email exists
        if (sizeof(UserModel::staticFetchManyBy($this->conn,'email=:email',array(':email' => $this->getField('email')))) > 0){
          throw new EmailExistsException();
        }

        if(strlen($this->getField('username')) < 6){
          throw new UserNameLengthException();
        }

        if (strlen($this->getField('zip')) < 5) {
            throw new InvalidZipCodeException();
        }



        //$userName = trim($params->request->username);
        //$userNameAvail = $UserServices->doUserExist($userName);
        /*
        if (strlen($userName) < 6) {
            $error ['code'] = LESS_USERNAME_LENGTH;
            $error ['desc'] = "Username must be at least 6 characters long";
            $var_error = 1;
            returnError($error);
        } elseif ($userNameAvail != 0) { //Return duplicate user
            $error ['code'] = USER_ALREADY_EXISTS;
            $error ['desc'] = "Duplicate user name.";
            $var_error = 1;
            returnError($error);
        }  elseif (strlen($params->request->password) < 6) {//password validation
            $error ['code'] = LESS_PASSWORD_LENGTH;
            $error ['desc'] = "Password must be at least 6 characters long";
            $var_error = 1;
            returnError($error);
        } elseif ($params->request->phone != '') {//phone validation
            if (!$UserServices->doPhoneExist($params->request->phone))
            {
                $error ['code'] = PHONE_EXISTS_DUPLICATE;
                $error ['desc'] = "Duplicate phone number";
                $var_error = 1;
                returnError($error);
            }
        } elseif ($params->request->phone != '') {//phone validation
            if (validatePhone($params->request->phone) == false)
            {
                $error ['code'] = INVALID_PHONE;
                $error ['desc'] = "Please enter a valid 10-digit US mobile phone number";
                $var_error = 1;
                returnError($error);
            }
        } elseif ($params->request->email != '') {//email validation
            if (verifyEmail($params->request->email))
            {
                $error ['code'] = EMAIL_EXISTS_DUPLICATE;
                $error ['desc'] = "Duplicate or invalid email";
                $var_error = 1;
                returnError($error);
            }
        } elseif ($params->request->zipcode != '') {//zipcode validation
            if (!verifyZip($params->request->zipcode))
            {
                $error ['code'] = INVALID_ZIP;
                $error ['desc'] = "Please enter a valid 5-digit US zip code";
                $var_error = 1;
                returnError($error);
            }
        }

        */

    }

    public function getCrudControllerName()
    {
        return 'users';
    }

    public function getBreadcrumbParent($returnEmpty = false)
    {

    }

    public function getLabel()
    {
        return 'Users';
    }

    public function getBreadcrumbParentIdentifer()
    {

    }

    public function getInstanceTableName()
    {
        return UserModel::getTableName();

    }
    public function addUserImage($userid,$appid,$image)
    {
        $datetime = date('Y-m-d h:i:s');
        $result = $this->conn->insert('userimages', array(
            'user_id' => $userid,
            'app_id' => $appid,
            'imagename' => $image,
            'status' => 'pending        ',
            'datetime' => $datetime,
        ));
        if($result)
        {
            return 'success';
        }
        else
        {
            return null;
        }
    }
    
    public function getUserImage($userid,$appid)
	{
		$ret = array();


        $sql = "
                SELECT `imagename` FROM `userimages`
                WHERE status = 'completed' and user_id = " . $userid ;
        $sql.= " GROUP BY `imagename`";
        $sql .= " ORDER BY `datetime` " ;

        $result = $this->conn->fetchAll($sql);

        foreach($result AS $rec){
                $ret[] = $rec;
            
        }

		return $ret;
	}
    
}