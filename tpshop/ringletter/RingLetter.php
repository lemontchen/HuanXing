<?php 
/*$options = array(
     'client_id'  => 'xxx',   //你的信息 
     'client_secret' => 'xxx',//你的信息
     'org_name' => '123' ,//你的信息
     'app_name' => '123' ,//你的信息
     );
$e = new ApiEasemobAction($options);
 
$groupInfo = array(
    'groupname' => 'leeef',
    'desc'       => 'leeff',
   // 'owner' => 'sy1'
);
 
 //$result = $e->getUserGroups('80983543');
 //$result = $e->getGroupList();
 //$result = $e->getGroupDetial("1423734662380237");
 echo "<pre>";
 //print_r($result);
 print_r($e->getToken());*/
            
            
            
/*
 * --------------强调说明-------------
 * 参数 数字int 最好填 String 如groupId 1423734662380237 ,传参时传getGroupDetial("1423734662380237");
 */          
/**
 * 环信-服务器端REST API
 * @author    limx <limx@xiaoneimimi.com>
 */
class ApiEasemobAction  {
    /**
*获取app管理员token     POST /{dihon}/{loveofgod}/token
*/
public function getToken()
{
    $url="https://a1.easemob.com/dihon/loveofgod/token";
    $body=array(
    "grant_type"=>"client_credentials",
    "client_id"=>"xxx",
    "client_secret"=>"xxx"
    );
    $patoken=json_encode($body);
    $res = $this->postCurl($url,$patoken);
    $tokenResult = array();
    
    $tokenResult =  json_decode($res, true);
    //var_dump($tokenResult);
    return "Authorization:Bearer ". $tokenResult["access_token"];   
}

/**
*获取app管理员token     POST /{dihon}/{loveofgod}/token
*/
public function getTokenForJs()
{
    $url="https://a1.easemob.com/dihon/loveofgod/token";
    $body=array(
    "grant_type"=>"password",
    "username"=>"fengpei",
    "password"=>"123456"
    );
    $patoken=json_encode($body);
    $res = postCurl($url,$patoken);
    $tokenResult = array();
    
    $tokenResult =  json_decode($res, true);
    //var_dump($tokenResult);
    return "Authorization: Bearer ". $tokenResult["access_token"];  
}

/**
 * 授权注册模式 POST /{dihon}/{loveofgod}/users
 */
public  function accreditRegister($nikename,$pwd)
{
    $url="https://a1.easemob.com/dihon/loveofgod/users";
    $body=array(
        "username"=>$nikename,
        "password"=>$pwd,
    );
    $patoken=json_encode($body);
    $header = array(getToken());
    //$res = postCurl($url,$patoken,$header);
    //开发者后台改成授权注册时，不带header不能注册成功，也就是可以无token注册
    $res = postCurl($url,$patoken,$header);
    $arrayResult =  json_decode($res, true);    
    return $arrayResult ;
}
/**
 *重置用户密码  PUT /{dihon}/{loveofgod}/users/{username}/password
 */
public function editPassword($nikename,$newpwd)
{
    $url="https://a1.easemob.com/dihon/loveofgod/users/".$nikename."/password";
    $body=array(
        "newpassword"=>$newpwd,
    );
    $patoken=json_encode($body);
    $header = array(getToken());
    $method = "PUT";
    $res = postCurl($url,$patoken,$header,$method);
    $arrayResult =  json_decode($res, true);    
    return $arrayResult ;
}

/**
 *修改用户昵称  PUT /{dihon}/{loveofgod}/users/{username}
 */
public function editNickName($username,$nickname)
{
    $url="https://a1.easemob.com/dihon/loveofgod/users/".$username;
    $body=array(
        "nickname"=>$nickname,
    );
    $patoken=json_encode($body);
    $header = array(getToken());
    $method = "PUT";
    $res = postCurl($url,$patoken,$header,$method);
    $arrayResult =  json_decode($res, true);    
    return $arrayResult ;
}

//注册用户
public function addUser($username,$password,$nickname){
    $url="https://a1.easemob.com/dihon/loveofgod/users/";
    $body=array(
        "username"=>$username,
        "password"=>$password,
        "nickname"=>$nickname,
    );
    $patoken=json_encode($body);
    $header = array($this->getToken());
    $method = "POST";   //$result =  $this->request('daikequan'.'/app'.'/'.$user,'PUST');
    $res = $this->postCurl($url,$patoken,$header,$method,CURLINFO_HTTP_CODE);
    $arrayResult =  json_decode($res, true);        
    return $arrayResult;
    
    }

/*
 *删除用户 DELETE /{dihon}/{loveofgod}/users/{username}
 */
public function deleteUser($username)
{
    $url="https://a1.easemob.com/dihon/loveofgod/users/".$username;
    $body=array();
    $patoken=json_encode($body);
    $header = array($this->getToken());
    $method = "DELETE";
    $res = $this->postCurl($url,$patoken,$header,$method);
    $arrayResult =  json_decode($res, true);    
    return $arrayResult ;
}

    /**
     * 获取指定用户的详情
     *
     * @param $username 用户名         
     */
public function userDetails($username) {
        $url="https://a1.easemob.com/dihon/loveofgod/users/".$username;
        $access_token=getToken();
        //var_dump($access_token);
        //$header[]='Authorization: Bearer ' . $access_token;
        //注意：获取到的值中本来就有Authorization前缀了。
        $header[]=$access_token;
        $result=postCurl($url,'',$header,$type='GET');
        return $result;
        /*
        $url = $this->url . "users/" . $username;
        $access_token = $this->getToken ();
        $header [] = 'Authorization: Bearer ' . $access_token;
        $result = $this->postCurl ( $url, '', $header, $type = 'GET' );
        return $result;
        */
    }

/**
 *给用户添加一个好友
 */
public function addFriend($owner_username,$friend_username){
    $url="https://a1.easemob.com/dihon/loveofgod/users/".$owner_username."/contacts/users/".$friend_username;
    $access_token = getToken();
    $header[]=$access_token;
    $result=postCurl($url,'',$header);
    return $result;
}

    /**
     * 查看用户的好友
     *
     * @param
     * $owner_username
     */
public function showFriend($owner_username) {
    $url="https://a1.easemob.com/dihon/loveofgod/users/" . $owner_username . "/contacts/users/";
    $access_token = getToken();
    $header[] = $access_token;
    $result = postCurl ( $url, '', $header, $type = "GET" );
    return $result;
}
    /**
     * 删除好友
     *
     * @param
     *          $owner_username
     * @param
     *          $friend_username
     */
public function deleteFriend($owner_username, $friend_username) {
    $url="https://a1.easemob.com/dihon/loveofgod/users/" . $owner_username . "/contacts/users/" . $friend_username;
    $access_token = getToken ();
    $header [] = $access_token;
    $result = postCurl ( $url, '', $header, $type = "DELETE" );
    return $result;
}

    /**
     * 查看用户是否在线
     *
     * @param
     *          $username
     */
public function isOnline($username) {
    $url="https://a1.easemob.com/dihon/loveofgod/users/" . $username . "/status";
    $access_token =getToken ();
    $header [] = $access_token;
    $result = postCurl ( $url, '', $header, $type = "GET" );
    return $result;
}

    /**
     * 查看离线消息数
     *
     * @param
     *          $username
     */
public function getOfflineMessages($username) {
    $url="https://a1.easemob.com/dihon/loveofgod/users/" . $username . "/offline_msg_count";
    $access_token =getToken ();
    $header [] = $access_token;
    $result = postCurl ( $url, '', $header, $type = "GET" );
    return $result;
}


//------------------聊天相关的方法

    /**
     * 发送消息
     *
     * @param string $from_user
     *          发送方用户名
     * @param array $username
     *          array('1','2')
     * @param string $target_type
     *          默认为：users 描述：给一个或者多个用户(users)或者群组发送消息(chatgroups)
     * @param string $content           
     * @param array $ext
     *          自定义参数
     */
public function yy_hxSend($from_user = "admin", $username, $content, $target_type = "users", $ext) {
        $option ['target_type'] = $target_type;
        $option ['target'] = $username;
        $params ['type'] = "txt";
        $params ['msg'] = $content;
        $option ['msg'] = $params;
        $option ['from'] = $from_user;
        $option ['ext'] = $ext;
        $url="https://a1.easemob.com/dihon/loveofgod/messages";
        $access_token = getToken();
        $access_token="Authorization:Bearer YWMtR5C9ugKUEeWF-3GovA5z7wAAAU6-Q0xnJgBx_km5NlCs-9lkSsLiNd5ttTM";
        $header [] = $access_token;
        $result = postCurl ( $url, $option, $header );
        return $result;
    }

    /**
     * 获取app中所有的群组
     */
public function chatGroups() {

    $url="https://a1.easemob.com/dihon/loveofgod/chatgroups";
    $access_token = getToken();
    $header [] = $access_token;
    $result = postCurl ( $url, '', $header, $type = "GET" );
    return $result;
}

/**
     * 创建群组
     *
     * @param $option['groupname'] //群组名称,
     *          此属性为必须的
     * @param $option['desc'] //群组描述,
     *          此属性为必须的
     * @param $option['public'] //是否是公开群,
     *          此属性为必须的 true or false
     * @param $option['approval'] //加入公开群是否需要批准,
     *          没有这个属性的话默认是true, 此属性为可选的
     * @param $option['owner'] //群组的管理员,
     *          此属性为必须的
     * @param $option['members'] //群组成员,此属性为可选的         
     */
public function createGroups($option) {
    $url="https://a1.easemob.com/dihon/loveofgod/chatgroups";
    $access_token = getToken();
    $header [] = $access_token;
    $result = postCurl ( $url, $option, $header );
    return $result;
}
    /**
     * 获取群组详情
     *
     * @param
     *          $group_id
     */
public function chatGroupsDetails($group_id) {
    $url="https://a1.easemob.com/dihon/loveofgod/chatgroups" . $group_id;
    $access_token = getToken ();
    $header [] = $access_token;
    $result = postCurl ( $url, '', $header, $type = "GET" );
    return $result;
}

    /**
     * 删除群组
     *
     * @param
     *          $group_id
     */
public function deleteGroups($group_id) {
    $url="https://a1.easemob.com/dihon/loveofgod/chatgroups/" . $group_id;
    $access_token = getToken ();
    $header [] = $access_token;
    $result = postCurl ( $url, '', $header, $type = "DELETE" );
    return $result;
}
/**
     * 获取群组成员
     *
     * @param
     *          $group_id
     */
public function groupsUser($group_id) {
    $url="https://a1.easemob.com/dihon/loveofgod/chatgroups/" . $group_id . "/users";
    $access_token = getToken ();
    $header [] = $access_token;
    $result = postCurl ( $url, '', $header, $type = "GET" );
    return $result;
}

/**
     * 群组添加成员
     *
     * @param
     *          $group_id
     * @param
     *          $username
     */
public function addGroupsUser($group_id, $username) {
    $url="https://a1.easemob.com/dihon/loveofgod/chatgroups/" . $group_id . "/users/" . $username;
    $access_token = getToken ();
    $header [] = $access_token;
    $result = postCurl ( $url, '', $header, $type = "POST" );
    return $result;
}
    /**
     * 群组删除成员
     *
     * @param
     *          $group_id
     * @param
     *          $username
     */
public function delGroupsUser($group_id, $username) {
    $url="https://a1.easemob.com/dihon/loveofgod/chatgroups/" . $group_id . "/users/" . $username;
    $access_token = getToken ();
    $header [] = $access_token;
    $result = postCurl ( $url, '', $header, $type = "DELETE" );
    return $result;
}


    /**
     * 聊天消息记录
     *
     * @param $ql 查询条件如：$ql
     *          = "select+*+where+from='" . $uid . "'+or+to='". $uid ."'+order+by+timestamp+desc&limit=" . $limit . $cursor;
     *          默认为order by timestamp desc
     * @param $cursor 分页参数
     *          默认为空
     * @param $limit 条数
     *          默认20
     */
public function chatRecord($ql = '', $cursor = '', $limit = 20) {
    $ql = ! empty ( $ql ) ? "ql=" . $ql : "order+by+timestamp+desc";
    $cursor = ! empty ( $cursor ) ? "&cursor=" . $cursor : '';
    $url="https://a1.easemob.com/dihon/loveofgod/chatmessages?" . $ql . "&limit=" . $limit . $cursor;
    $access_token = getToken ();
    $header [] = $access_token;
    $result = postCurl ( $url, '', $header, $type = "GET " );
    return $result;
}

/*
上传文件：
*/
public function uploadFile(){
    $body['file']="/images/girl.jpg";
    //$option['file']="/Uploads/Lawyer/1-13040G6445U29.jpg";
    $url="http://a1.easemob.com/dihon/loveofgod/chatfiles";
    $access_token=getToken();
    //$access_token="YWMttf6etvPpEeSsLisKR1j8fwAAAU5eJVuAGnvSn9EUMb1kdE8B9sTUNxjqXvA";
    //$header[]='enctype:multipart/form-data';
    //$header[]=$access_token;
    //$header[]='restrict-access:true';
    $header=array('enctype:multipart/form-data',$access_token,'restrict-access:true');
    $option=json_encode($body);
    $result=postCurl($url,$option,$header);
    //$data=json_decode($result,true);
    print_r($result);
    //$uuid=$data['entities'][0]['uuid'];
    //return $uuid;
    return $result;
}



//postCurl方法
public function postCurl($url, $body, $header = array(), $method = "POST")
{
    array_push($header, 'Accept:application/json');
    array_push($header, 'Content-Type:application/json');
    //array_push($header, 'http:multipart/form-data');

    $ch = curl_init();//启动一个curl会话
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, $method, 1);
    
    switch ($method){ 
        case "GET" : 
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        break; 
        case "POST": 
            curl_setopt($ch, CURLOPT_POST,true); 
        break; 
        case "PUT" : 
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
        break; 
        case "DELETE":
            curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
        break; 
    }
    
    curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  //原先是FALSE，可改为2
    if (isset($body{3}) > 0) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    if (count($header) > 0) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }

    $ret = curl_exec($ch);
    $err = curl_error($ch);
    

    curl_close($ch);
    //clear_object($ch);
    //clear_object($body);
    //clear_object($header);

    if ($err) {
        return $err;
    }

    return $ret;
}

}
?>