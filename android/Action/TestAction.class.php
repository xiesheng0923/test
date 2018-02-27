<?php
class TestAction extends CommonAction {
    /**
     * @title 书名作者接口
     * @action http://app.guoxue.com/bookFull_ceshi
     * @param  phone
     * @param  password
     * @method post
     */
    Public function info(){
        $match = D('match_poetry');//写诗
        $praise = D('praise');//点赞
        $comment = D('comment');//点赞
        $fenlei = D('fenlei');
        $where['m.Id'] = I('post.Id');
        $where['m.Id'] = 32;
        $res = $match->alias('m')->field('m.*,u.nickname,u.photo,a.matchFull')->join('user u on u.userid=m.UserId')->join('`match` a on a.Id=m.MatchId')->where($where)->find();
        // var_dump( $res);
        $columnName = $fenlei->field('columnName')->where(array('id'=>$res['Fenlei']))->find();
        //点赞
        $map['param'] = $where['m.Id'];
        $map['userId'] = I('post.userId');
        $map['userId'] = 6;
        $map['about'] = 'matchpoetry';
        $praise_info = $praise->field('Id')->where($map)->find();
        $map1['param'] = $where['m.Id'];
        $map1['about'] = 'matchpoetry';
        $praise_num =  $praise->field('Id')->where($map1)->count();
        //评论数量
        $map2['mid'] = $where['m.Id'];
        $map2['type'] = 'matchpoetry';
        $comment_num =  $comment->where($map2)->count();
        $res1['columnName'] = $columnName['columnName'];
        $data['poetryFull'] = $res['M_P_Full'];
        $res1['matchId'] = $res['Id'];
        $res1['matchFull'] = $res['matchFull'];
        $res1['poetryFull'] = preg_replace("{\r\n}","",$data['poetryFull']);
        $res['content'] = preg_replace("{(\r\n)|(战国·)|(\[K2\])}","",$res['M_P_Content']);
            $keyword=str_replace("，","，\n",$res['content']);
            $keyword=str_replace("。","。\n",$keyword);
            $keyword=str_replace("？","？\n",$keyword);
            $keyword=str_replace("！","！\n",$keyword);
            // $keyword=str_replace("、","、\n",$keyword);
            // $keyword=str_replace("）","）\n",$keyword);
            // $keyword=str_replace("   ","\n",$keyword); 
            // $keyword=str_replace("：","\n",$keyword);
            // $keyword=str_replace("¤","。\n\n",$keyword);  
        $res1['content'] = $keyword;
         // $res1['content'] = $res['M_P_Content'];
        $res1['zhu'] = empty($res['M_P_Zhu'])?'':$res['M_P_Zhu'];
        $res1['mapName'] = empty($res['MapName'])?'':$res['MapName'];
        $res1['Review'] = empty($res['Review'])?'':$res['Review'];
        $res1['nickname'] = $res['nickname'];
        $res1['photo'] = $res['photo'];
        $res1['CreateTime'] = date('Y年n月d日',$res['CreateTime']);
        $res1['praise_info'] = empty($praise_info)?0:1;
        $res1['praise_num'] = empty($praise_num)?0:$praise_num;
        $res1['comment_num'] = empty($comment_num)?'0':$comment_num;//评论个数
        // var_dump($res1);die;
        if (!empty($res)) {
            $res['status'] = 'success';
            echo json_encode($res1);
        }else{
            $res['status'] = 'error';
            echo json_encode($res1);
        }
    }
    Public function bookFull_ceshi() {
        echo 111;die;
        $test = D('test111');
        $book = D('book');
        $poet = D('poet');
        $res = $test->field('LLRR')->select();
        // $res = array_column($res,'LLRR');
        $pattern = '/│/';
        $str=preg_split ($pattern, $res[1]["LLRR"]);//array(3) { [0]=> string(22) " " [1]=> string(18) "王文成公全集" [2]=> string(19) " 明·王阳明" } 
        $str1 = explode('·', $str[2]);//array(2) { [0]=> string(8) " 明" [1]=> string(9) "王阳明" } 
        $where['poetname'] = $str1[1];
        $name = $poet->field('id')->where($where)->find();
        // var_dump($name);die;
        import('ORG.Util.String');
        $string = new String();
        $k=$string->randString2(8,1);
        $Code=$k;
        $data['BookCode'] = $Code;
        $data['BookFull']= $str[1];
        $data['Dynasty']=$str1[0];
        $data['AuthorId']= $name['id'];
        $data['Type1']= '集';//6、大类（经/史/子/集/）
        $data['Type2']= '';//7、小类（诗/词/曲/文言/辞赋）
        $data['Zhu']= '';
        // $result = $book->add($data);
    }
	/**
	 * @title 添加数据接口
	 * @action http://app.guoxue.com/test_ceshi
	 * @param  phone
	 * @param  password
	 * @method post
	 */
    Public function test(){
         $match = D('match_poetry');//写诗
        $praise = D('praise');//点赞
        $comment = D('comment');//点赞
        $fenlei = D('fenlei');
        $where['m.Id'] = I('post.Id');
        $where['m.Id'] = 50;
        $res = $match->alias('m')->field('m.*,u.nickname,u.photo,a.matchFull')->join('user u on u.userid=m.UserId')->join('`match` a on a.Id=m.MatchId')->where($where)->find();
        // var_dump( $res);
        $columnName = $fenlei->field('columnName')->where(array('id'=>$res['Fenlei']))->find();
        //点赞
        $map['param'] = $where['m.Id'];
        $map['userId'] = I('post.userId');
        $map['userId'] = 6;
        $map['about'] = 'matchpoetry';
        $praise_info = $praise->field('Id')->where($map)->find();
        $map1['param'] = $where['m.Id'];
        $map1['about'] = 'matchpoetry';
        $praise_num =  $praise->field('Id')->where($map1)->count();
        //评论数量
        $map2['mid'] = $where['m.Id'];
        $map2['type'] = 'matchpoetry';
        $comment_num =  $comment->where($map2)->count();
        $res1['columnName'] = $columnName['columnName'];
        $data['poetryFull'] = $res['M_P_Full'];
        $res1['matchId'] = $res['Id'];
        $res1['matchFull'] = $res['matchFull'];
        $res1['poetryFull'] = preg_replace("{\r\n}","",$data['poetryFull']);
        // var_dump(str_replace("\n","",$res['M_P_Content']));die;
        $res['content'] = preg_replace("{(\r\n)|(战国·)|(\[K2\])}","",$res['M_P_Content']);
            $keyword=str_replace("，","，\n",$res['content']);
            $keyword=str_replace("。","。\n",$keyword);
            $keyword=str_replace("？","？\n",$keyword);
            $keyword=str_replace("！","！\n",$keyword);
            // $keyword=str_replace("、","、\n",$keyword);
            $keyword=str_replace("）","）\n",$keyword);
            $keyword=str_replace("；","；\n",$keyword);
            // $keyword=str_replace("   ","\n",$keyword); 
            // $keyword=str_replace("：","\n",$keyword);
            // $keyword=str_replace("¤","。\n\n",$keyword);  
        $res1['content'] = $keyword;
        var_dump($keyword);
        $res1['zhu'] = empty($res['M_P_Zhu'])?'':$res['M_P_Zhu'];
        $res1['mapName'] = empty($res['MapName'])?'':$res['MapName'];
        $res1['Review'] = empty($res['Review'])?'':$res['Review'];
        $res1['nickname'] = $res['nickname'];
        $res1['photo'] = $res['photo'];
        $res1['CreateTime'] = date('Y年n月d日',$res['CreateTime']);
        $res1['praise_info'] = empty($praise_info)?0:1;
        $res1['praise_num'] = empty($praise_num)?0:$praise_num;
        $res1['comment_num'] = empty($comment_num)?'0':$comment_num;//评论个数
        var_dump($res1);die;
    }
	// Public function test() {
 //        $test = D('test222');
 //        $book_info = D('book_info');
 //        $where['content'] = array('like','%●%');
 //        $res = $test->where($where)->select();
 //        $arrId = array_column($res,'Id');
 //        $arrcon = array_column($res,'content');
 //        // var_dump($arrId);die;
 //        for ($i=5; $i < count($arrcon); $i++) { 
 //            $pattern = '/●/';
 //            $tur1 = preg_match($pattern, $arrcon[$i]);
 //            if ($tur1 = 1) {
 //               $Jun = $arrcon[$i];
 //               $pattern = '/●/';
 //                $str1=preg_split ($pattern, $Jun);
 //                $Jun = $str1[1];//卷
 //                $arrJun[$i]=$Jun;//卷数组
 //            }
 //            $where1['Id'] = array(array('gt',$arrId[$i]),array('lt',$arrId[$i+1]));
 //            $res1 = $test->where($where1)->select();
 //            $arrId1 = array_column($res1,'Id');
 //            $arrcon1 = array_column($res1,'content');
 //            for ($i=0; $i < count($res1); $i++) { 
 //                $pattern = '/◎/';
 //                $tur2 = preg_match($pattern, $res1[$i]["content"]);
 //                if ($tur2 ==1) {//当前字段是章节
 //                    // $Zhang = array_shift($res1);
 //                   $content = $res1[$i]['content'];
 //                   $pattern = '/◎/';
 //                    $str2=preg_split ($pattern, $content);
 //                    $Zhang = $str2[1];
 //                }else{//当前字段不是章节，选择固定章节字段
 //                    $content = $res1[0]['content'];
 //                    $pattern = '/◎/';
 //                    $str2=preg_split ($pattern, $content);
 //                    $Zhang = $str2[1];
 //                }
 //            }
 //            // var_dump($res1);
 //            // for ($i=0; $i < count($arrcon1); $i++) { 
 //            //     $pattern = '/○/';
 //            //     $tur3 = preg_match($pattern, $arrcon1[$i]);
 //            //     var_dump($tur3);
 //            //     if ($tur3 = 1) {
 //            //        $Jie = $arrcon1[$i];
 //            //        $pattern = '/○/';
 //            //         $str3=preg_split ($pattern, $Jie);
 //            //         $Jie = $str3[1];//卷
 //            //         $arrJie[$i]=$Jie;//卷数组
 //            //     }
 //            // }
 //            var_dump($Jun);
 //            var_dump($Zhang);
 //            var_dump($Jie);
 //        }die;
 //        // $where1['Id'] = array(array('gt',4),array('lt',763));
 //        // $res1 = $test->where($where1)->select();
 //        // $arrId1 = array_column($res1,'Id');
 //        // $arrcon1 = array_column($res1,'content');
 //        // var_dump($arrId1);
 //        // var_dump($arrcon1);die;//章名（二级）（◎放置此行文字）
 //        var_dump($arrcon);die;
 //        $content = array_slice($res,5,763);
 //        var_dump($resc);die;
 //        $res = $test->field('content')->select();
 //        $res = array_column($res,'content');
 //        $str = implode($res);
 //        $str = explode('●',$str);
 //        $bookfull = array_shift($str);
 //        var_dump($str);
 //        // for ($i=0; $i < count($str); $i++) { 
 //        //     $str[$i] = explode('◎',$str[$i]);
 //        //     for ($j=1; $j < count($str[$i]); $j++) { 
 //        //         // $pattern = '/○\w.*?\s{2}/';
 //        //         // $str1=preg_split ($pattern, $str[$i][$j]);
 //        //         // var_dump($str1);
 //        //         // $str[$i][$j] = explode('○',$str[$i][$j]);
 //        //         var_dump($str[$i][0]);
 //        //     }
 //        // }
 //        // $str = explode('○',$str);
 //        // var_dump($str[]);die;
 //        // array_merge($a3,$a4)
	// }
    public function info1(){
        $test = D('test222');
        $book_info = D('book_info');
        $res = $test->limit(3)->select();
        // var_dump($res);
        $arrId = array_column($res,'Id');
        $arrcon = array_column($res,'content');
        // var_dump($arrcon);
        $pattern = '/│/';
        $str=preg_split ($pattern, $arrcon[1]);//array(3) { [0]=> string(22) " " [1]=> string(18) "王文成公全集" [2]=> string(19) " 明·王阳明" } 
        // var_dump($str);die;
        $str1 = explode('·', $str[2]);//array(2) { [0]=> string(8) " 明" [1]=> string(9) "王阳明" } 
        $data['BookFull']= $str[1];
        $data['Dynasty']=$str1[0];
        $data['Author']= $str1[1];
        $count = $test->count();
        $res1 = $test->limit(3,$count)->select();
        $arrcon1 = array_column($res1,'content');
        $str = implode($arrcon1);
        $str = explode('●',$str);
        // var_dump($str);die;
        array_shift($str);//q去掉数组第一个数据
        // for ($i=0; $i <count($str) ; $i++) { 
            // $pattern = '/◎/';
            // $tur = preg_match($pattern, $str[$i]);//是否存在二级目录章节 
            // if ($tur == 1) {
            //     $pattern1 = '/(◎)/';
            //     $str1=preg_split ($pattern1, $str[$i]);
            //     $zhang = $str1[0];
            // }else{
            //     $pattern1 = '/(○)/';
            //     $str1=preg_split ($pattern1, $str[$i]);
            // }
            
            // var_dump($zhang);
            // // $zhang = explode('◎',$str[$i]);
            // // var_dump($zhang);
        // }
        $pattern1 = '/(◎)/';
        // for ($a=20; $a < 25; $a++) { 
        //     $str1=preg_split ($pattern1, $str[$a]);


        $str1=preg_split ($pattern1, $str[37]);
        // var_dump($str1);
        if (count($str1)>1) {
                $juan = array_shift($str1);
                for ($i=0; $i < count($str1); $i++) { 
                // var_dump($str1);
                $arr1[$i] = explode('  ', $str1[$i],2);
                $pattern2 = '/(○)/';
                $str2=preg_split ($pattern2, $arr1[$i][1]);
                // var_dump($str2);die;
                if (count($str2)>1) {
                   array_shift($str2);
                    for ($j=0; $j < count($str2); $j++) { 
                        $arr2[$j] = explode('  ', $str2[$j],2);
                        $data1[$j]['BookId'] = 1;
                        $data1[$j]['Jun'] = $juan;
                        $data1[$j]['Zhang'] = $arr1[$i][0];
                        $data1[$j]['Jie'] = $arr2[$j][0];
                        $data1[$j]['Content'] = $arr2[$j][1];
                    }
                }else{
                    $data1[$i]['BookId'] = 1;
                    $data1[$i]['Jun'] = $juan;
                    $data1[$i]['Zhang'] = $arr1[$i][0];
                    $data1[$i]['Content'] = $str2[0];
                }
                // var_dump($data1);
                
            }
        }else{
                //三十六卷
                // $str2 = explode('  ', $str2[0],2);
                //      $juan = $juan[0];
                //      var_dump($str2);


                $pattern2 = '/(○)/';
                $str2=preg_split ($pattern2, $str1[0]);
                // var_dump($str2);
                //是否存在节>1存在1<不存在
                if (count($str2)>1) {

                    // $juan = '卷三十六';
                    $juan = $str2[0];
                   array_shift($str2);
                   $zhang = array_shift($str2);
                   // var_dump($zhang);
                    for ($j=0; $j < count($str2); $j++) { 
                        $arr2[$j] = explode('  ', $str2[$j],2);
                        $data1[$j]['BookId'] = 1;
                        $data1[$j]['Jun'] = $juan;
                        $data1[$j]['Zhang'] = '';
                        $data1[$j]['Jie'] = $arr2[$j][0];
                        $data1[$j]['Content'] = $arr2[$j][1];
                    }
                }else{
                    // var_dump($str2);
                    $arr2 = explode('  ', $str2[0],3);
                    // var_dump($arr2);die;
                    // var_dump($str2);die;
                    $juan = $arr2[0];
                    $data1['BookId'] = 1;
                    $data1['Jun'] = $juan;
                    $data1['Zhang'] = $arr2[1];
                    $data1['Content'] = $arr2[2];
                }
        }
        // }
        // var_dump($arr2);die;
        // var_dump($data1);die;
        $book_info->add($data1);die;
        // $book_info->addALL($data1);
    }
 
}
?>
