<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsController extends Controller{
    public function showlist(){
        
        $model=M('goods');
        $recordcount=$model->count();   //总记录数
        $page=new \Think\Page($recordcount,5);
        
       
        $page->lastSuffix=false;    //最后一页是否显示总页数
        $page->rollPage=4;          //分页栏每页显示的页数
        $page->setConfig('prev', '【上一页】');
        $page->setConfig('next', '【下一页】');
        $page->setConfig('first', '【首页】');
        $page->setConfig('last', '【末页】');        
        
        $page->setConfig('theme', '共 %TOTAL_ROW% 条记录,当前是 %NOW_PAGE%/%TOTAL_PAGE% %FIRST% %UP_PAGE%  %DOWN_PAGE% %END%');
        
        $startno=$page->firstRow;   //起始行数
        $pagesize=$page->listRows;  //页面大小
        $list=$model->limit("$startno,$pagesize")->select();
        
        $pagestr=$page->show(); //组装分页字符串

        $this->assign('list',$list);
        $this->assign('pagestr',$pagestr);
        $this->display();
    }
    public function add(){
        //方法一
        /*
        if(IS_POST){
            $data['goods_name']=$_POST['aa'];
            $data['goods_category_id']=$_POST['bb'];
            $data['goods_price']=$_POST['goods_price'];
            $data['goods_introduce']=$_POST['goods_introduce'];
            $msg='添加失败';
            if(M('goods')->add($data)){
                $msg='添加成功';
            }
            $this->redirect('showlist', array(), 3, $msg);
        }
         * 
         */
        //方法二；
       
        $goods=M('Goods');
        if(IS_POST){
            if($data=$goods->create()){
                if($_FILES['goods_image']['error']==0){
                    //文件上传部分
                    $config=array(
                            'rootPath'  =>  './Application/public/uploads/',
                    );
                    $upload=new \Think\Upload($config);
                    $info=$upload->uploadOne($_FILES['goods_image']);
                    //var_dump($info);
                    $data['goods_big_img']=$info['savepath'].$info['savename'];
                    
                    //生成缩略图
                    $img=new \Think\Image();
                    //1、打开图片
                    $big_img=   $upload->rootPath.$data['goods_big_img'];
                    $img->open($big_img);
                    //2、生成缩略图
                    $img->thumb(200, 300,2);
                    //3、保存
                    $small_img=$upload->rootPath.$info['savepath'].'small_'.$info['savename'];
                    $img->save($small_img);
                    $data['goods_small_img']=$info['savepath'].'small_'.$info['savename'];
                    
                    if($goods->add($data)){
                        $this->success('添加成功', 'showlist', '3');
                    }else{
                        $this->error('添加失败');
                      
                    }
                }else{
                    var_dump($upload->getError());  //显示上传错误
                    exit();
                }
            }
        }
        //方法三
        /*
        if(IS_POST){
              if(M('goods')->add(I('post.' ))){
                    $this->success('添加成功', 'showlist', '3');
                }else{
                    $this->error('添加失败');
                }
        }
         * 
         */
        $category=M('category')->select();
        $this->assign('category',$category);
        $this->display();
    }
    public function update($goods_id){
        if(IS_POST){
            $goods=M('goods');
            $data=$goods->create();
            $data['goods_create_time']=time();
            if($goods->save($data))
            {
                $this->success('修改成功', U('showlist'), 3);
            }else{
                $this->error('修改失败');
            }
            exit;
        }
        $category=M('category')->select();
        $data=M('goods')->find($goods_id);
        $this->assign('category',$category);
        $this->assign('data',$data);
        $this->display();
    }
    public function del($goods_id){
        if(M('goods')->delete($goods_id))
            $this->success ('成功', U('showlist'), 3);
        else
            $this->error('失败',U('showlist'),3);
    }
    
    public function test()
    {
        echo U('showlist');
    }
    public function send(){
        $obj=new \Components\EmailTool();
        $obj->send();
        
        
    }
}