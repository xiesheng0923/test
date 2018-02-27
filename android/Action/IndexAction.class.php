<?php
class IndexAction extends CommonAction {
    /**
     * @title 作文添加
     * @action https://ssl.guoxue.com/composition_write
     * @param  Id
     * @method get
     */
    public function composition_write(){
        $composition = D('composition');
        $data['compositionFull'] = I('get.Full');
        // $data['compositionContent'] = I('get.content');
        $data['compositionContent'] = $_GET['content'];
        $data['userId'] = I('get.userId');
        $data['longitude'] = I('get.longitude');
        $data['latitude'] = I('get.latitude');
        $data['mapName'] = I('get.mapName');
        $data['createtime'] = time();
        $res = $composition->data($data)->add();
        if(!empty($res)){
            echo   json_encode ( array (
                    'status' => 'success',
                    'Id' =>$res
            ) );
        }else{
            echo  json_encode ( array (
                    'status' => 'error'
            ) );
        }
    }
    /**
     * @title 作文删除
     * @action https://ssl.guoxue.com/composition_del
     * @param  Id
     * @method get
     */
    public function composition_del(){
        $composition = D('composition');
        $picture = D('picture');
        $Id = I('get.Id');
        // $Id = 37;
        $where['belongTable'] = 'Composition';
        $where['belongtable_id'] = $Id;
        $pic = $picture->where($where)->select();
        for ($i=0; $i < count($pic); $i++) { 
            $pictureId= $pic[$i]['pictureId'];
            $this->pic_del($pictureId);
            $picture->where(array('pictureId'=>$pictureId))->delete();
        }
        $res = $composition->where(array('Id'=>$Id))->delete();
        // var_dump($composition->getlastsql());die;
        if(!empty($res)){
            echo   json_encode ( array (
                    'status' => 'success',
            ) );
        }else{
            echo  json_encode ( array (
                    'status' => 'error'
            ) );
        }
    }
    public function school_province(){
        $city = D('nobook_city');
        $id = I('get.id');
        $level = I('get.level');
        $ismunicipality = I('get.ismunicipality');
        //是否是直辖市
        if ($ismunicipality==1) {
            (int)$level += 1; 
            $ismunicipality = 0;
            if ($id == 2) {
                $id = 52;
            }elseif ($id == 25) {
                $id = 321;
            }elseif ($id == 27) {
                $id = 343;
            }elseif ($id == 32) {
                $id = 394;
            }
        }
        //id  $level为空时为一级省级目录
        if (empty($id)&&empty($level)) {
            $where['parentid'] = 1;
            $where['level'] = 1;
        }else{
            $where['parentid'] = $id;
            $where['level'] = (int)$level+1;
            $where['ismunicipality'] = $ismunicipality;
        }
        
        $res = $city->field('id,name,level,ismunicipality')->where($where)->select();
        echo json_encode($res);
    }
    public function school_list(){
        $school = D('nobook_school');
        // $province_id = I('get.province_id');
        $city_id = I('get.city_id');
        // $city_id = 500;
        // $province_id = 2;
        // $where['province_id'] = $province_id;
        $where['county_id'] = $city_id;
        $res = $school->field('id,schoolname')->where($where)->group('first_py')->select();
        // var_dump($res);
        echo json_encode($res);
    }
    public function pic_upload(){
        import ( 'ORG.Net.UploadFile' );
        $upload = new UploadFile ();
        $picture = D ( 'Picture' );
        $com = D ( 'composition' );
        $id = $_POST ['Id'];
        // file_put_contents('id', $id);
            $base_path = "../webroot/Public/Img/"; // 接收文件目录
            $full = date('YmdHis',time()).substr(microtime(),2,5).'.png' ;
            $target_path = $base_path .$full;
            if (move_uploaded_file ( $_FILES ['file'] ['tmp_name'], $target_path )) {
                $array = array (
                        "code" => "1",
                        "message" => $_FILES ['file'] ['name'] 
                );
                $this->uploadFile ( 'webroot/Public/Img/', $full );//上传到云服
                $pic ['belongTable'] = 'Composition';
                $pic ['belongtable_id'] = $id;
                $pic ['pictureName'] = $full;
                $pic ['create_time'] = NOW_TIME;
                $pictureId = $picture->add ( $pic );
                $compositionPic=$com->field('compositionPic')->where(array('Id'=>$id))->find();
                if (!empty($compositionPic["compositionPic"])) {
                    $pictureId = $compositionPic["compositionPic"].'#'.$pictureId;
                }
                $Data ['compositionPic'] = $pictureId;
                $com->where ( array (
                        'Id' => $id 
                ) )->save ( $Data );
                unlink($base_path.$full);//删除本地图片
                echo json_encode ( $array );
            } else {
                $array = array (
                        "code" => "0",
                        "message" => "There was an error uploading the file, please try again!" . $_FILES ['file'] ['error'] 
                );
                echo json_encode ( $array );
            }
    }
    /**
     * @title 图片删除
     * @action https://ssl.guoxue.com/pic_del
     */
    public function pic_del($id){
        $picture = D ( 'Picture' );
        $id = $id;
        $picname = $picture->field('pictureName')->where(array('pictureId'=>$id))->find();
        $path = '/zuowenxia/'.$picname['pictureName'];
        $this->deleteFile($path);
    }
    /**
     * @title 图片上传到云服务器
     * @action http://android.shici.com/uploadFile
     */
    public function uploadFile($path, $file) {
        $rootPath = dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) );
        $upyun = new UpYunModel ( 'shici', 'shici', 'guoxue123' );
        $newFilePath = $rootPath.'/' . str_replace ( '.', '', $path ) . $file;
        $fh = fopen ( $newFilePath, 'rb' );
        $uploadPath = str_replace ( '.', '', $path ) . $file;
        $rsp = $upyun->writeFile ( '/zuowenxia/' . $file, $fh, True ); // 上传图片，自动创建目录
        fclose ( $fh );
        if (! empty ( $rsp ['x-upyun-width'] )) {
            unlink ( $newFilePath );
            return true;
        } else {
            return false;
        }
    }
    /**
     * @title 云服务器图片删除
     * @action http://android.shici.com/uploadFile
     */
    public function deleteFile($path) {
        $upyun = new UpYunModel ( 'shici', 'shici', 'guoxue123' );
        $rsp = $upyun->deleteFile ( $path ); 
        // fclose ( $fh );
        if (! empty ( $rsp )) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * @title 首页用户信息
     * @action https://ssl.guoxue.com/index
     * @param  Id
     * @method get
     */
    Public function index(){
        $user = D('user');
        $where['u.Id'] = I('get.Id');
        $nickname = I('get.nickName');
        $data['nickname'] = $nickname;
        $where1['Id'] = I('get.Id');
        $user->where($where1)->save($data);
        // $where['u.Id'] = 9;
        $user_info = $user->alias('u')->field('u.Id,u.enrollment,s.schoolname')->join('nobook_school s on s.id=u.school')->where($where)->find();
        $day = floor((NOW_TIME-$user_info['enrollment'])/86400);
        $lev = ceil($day/365);
        $array = array('一','二','三','四','五','六');
        // $lev = ;
        if ($lev<7) {
            $user_info['lev'] = '小学'.$array[(int)$lev-1].'年级';
        } elseif ($lev<10) {
            $user_info['lev'] = '初'.$array[(int)$lev-7];
        } elseif ($lev<13) {
            $user_info['lev'] = '高'.$array[(int)$lev-10];
        }
        if (isset($user_info)) {
                $array1['works'] = $user_info;
                $array1['status'] = 'success';
                echo json_encode($array1);
            }else{
                echo json_encode(array(
                        'status' => 'error' 
                    ));
            }
    }
    public function composition_list(){
        $composition = D('composition');
        $where['userId'] = I('get.Id');
        // $where['userId'] = 9;
        $res = $composition->field('Id,compositionFull,compositionContent,createtime,mapName')->where($where)->order('createtime desc')->select();
        for ($i=0; $i <count($res) ; $i++) { 
            $res[$i]['createtime'] = date('Y年m月d日',$res[$i]['createtime']);
            $res[$i]['compositionContent'] = mb_substr($res[$i]['compositionContent'], 0, 70,"UTF-8").'……';
        }
        if (isset($res)) {
                $array['works'] = $res;
                $array['status'] = 'success';
                echo json_encode($array);
            }else{
                echo json_encode(array(
                        'status' => 'error' 
                    ));
            }
    }
    /**
     * @title 作文正文
     * @action https://ssl.guoxue.com/comp_content
     * @param  Id
     * @method get
     */
    public function comp_content(){
        $composition = D('composition');
        $picture = D('picture');
        $where['c.Id'] = I('get.Id');
        $Id = $where['c.Id'];
        // $where['Id'] = 44;
        $res = $composition->alias('c')->field('c.Id,c.compositionFull,c.compositionContent,c.compositionPic,c.createtime,c.mapName,u.nickname')->join('user u on u.Id=c.userId')->where($where)->find(); 
        $res['createtime'] = date('Y年m月d日',$res['createtime']);
        $res['compositionContent'] = str_replace(' ','&nbsp;',$res['compositionContent'] );
        // $res['Pic'] = explode('#', $res['compositionPic']);
        $where1['belongTable'] = 'Composition';
        $where1['belongtable_id'] = $Id;
        $pic = $picture->field('pictureName')->where($where1)->select();
        for ($i=0; $i < count($pic); $i++) { 
            $res['Pic'][$i]= 'http://picture.guoxue.com/zuowenxia/'.$pic[$i]['pictureName'];
        }
        // var_dump($res);die;
        if (isset($res)) {
                $array['works'] = $res;
                $array['status'] = 'success';
                echo json_encode($array);
            }else{
                echo json_encode(array(
                        'status' => 'error' 
                    ));
            }
    }
    /**
     * @title 删除作文
     * @action https://ssl.guoxue.com/comp_del
     * @param  Id
     * @method get
     */
    public function comp_del(){
        $composition = D('composition');
        $where['Id'] = I('get.Id');
        // $where['Id'] = 12;
        $res = $composition->field('Id,compositionFull,compositionContent,compositionPic,createtime,mapName')->where($where)->find(); 
        $res['createtime'] = date('Y年m月d日',$res['createtime']);
        $res['Pic'] = explode('#', $res['compositionPic']);
        if (isset($res)) {
                $array['works'] = $res;
                $array['status'] = 'success';
                echo json_encode($array);
            }else{
                echo json_encode(array(
                        'status' => 'error' 
                    ));
            }
    }
    public function getLogincode(){
        $user = D('User');
        $code = I('get.code');
        // $code ='003wr8s90nh9mv1yxRu90CJfs90wr8sM';
        if ($code !== 'CODE') {
            //获取CODE换得access_token
            $url = 'https://api.weixin.qq.com/sns/jscode2session';
            $APPID = 'wx2406a9442ee5500b';
            $SECRET = '1d55366523dbf07447f748a835a736ed';
            $curlPost = 'appid='.$APPID.'&secret='.$SECRET.'&js_code='.$code.'&grant_type=authorization_code';
            $json = $this->curl_set($url,$curlPost);
            $user_info = json_decode($json, true);
            $this->getLogin($user_info);
        }
    }
    Public function curl_set($url,$curlPost){
            // 1. 初始化
            $ch = curl_init();
            // 2. 设置选项，包括URL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
            // curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
            //运行curl
            $data = curl_exec($ch);
            // libxml_disable_entity_loader(true);
            // $data = json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);  
            //返回结果
            if($data){
                curl_close($ch);
                return $data;
            } else { 
                $error = curl_errno($ch);
                curl_close($ch);
                return false;
            }
    }
    Public function getLogin($info){
        $user = D('user');
        $data['weixin'] = $info['openid'];
        $user_info = $user->field('Id')->where($data)->find();
        if (!empty($data['weixin'])&&empty($user_info)) {
            $data['createtime'] = NOW_TIME;
            $res = $user->data($data)->add();
            if (isset($res)) {
                $array['Id'] = $res;
                $array['status'] = 'success';
                echo json_encode($array);
            }else{
                echo json_encode(array(
                        'status' => 'error' 
                    ));
            }
        }elseif (!empty($data['weixin'])&&!empty($user_info)) {
            $array['Id'] = $user_info;
            $array['status'] = 'success';
            echo json_encode($array);
        }    
    }
    /**
     * @title 用户信息添加
     * @action http://ssl.guoxue.com/user/info_add
     * @param  title(school enrollment)   param   Id type(高中、初中、小学)
     * @method get
     */
    Public function info_add(){
        $user = D('user');
        $param = I('get.param');
        $title = I('get.title');
        $id = I('get.Id');
        $type = I('get.type');
        // $param = '2015';
        // $title = 'enrollment';
        // $id = 9;
        // $type = '初中';
        switch ($type) {
            case '初中':
                $param = (int)$param-6;
                break;
            case '高中':
                $param = (int)$param-9;
                break;
        }
        if ($title == 'enrollment') {
            $param = $param.'-9-1';
            $param = strtotime($param);
        }
        if (isset($param)&&isset($title)) {
            $data[$title] = $param;
            $data['type'] = $type;
            $where['Id'] = $id;
            $res = $user->where($where)->save($data);
            if (!empty($res)) {
                echo json_encode(array(
                        'status' => 'success'
                    ));
            }else{
                echo json_encode(array(
                        'status' => 'error'
                    ));
            }
        }else{
            echo json_encode(array(
                        'status' => 'error'
                    ));
        }
    }
}
?>
