<?php
namespace app\admin\model;

use think\Model;
use app\admin\model\CurrencyTreeModel;
class ImageModel extends Model
{
    protected $ImageRootNode = 2;
    protected $table = 'xx_image';
    protected $pk ='id';
    public function initialize(){
        parent::initialize();
    }
    /**
     * [getAllImage 返回所有图片]
     * @return [type] [description]
     * @author
     */
    public function getAllImage(){
        $dic = $this->select();
        return $dic;
    }
    /**
     * [getImageByParent 返回所有指定父id的图片]
     * @return [type] [description]
     * @author
     */
    public function getImageByParent($id,$limit,$start){
        $start = ($start-1)*$limit;
        $dic = $this->where(['nodeID'=>$id,'IsDelete'=>0])->limit($start,$limit)
        ->select();
        return $dic;
    }
    /**
     * [addImage 添加图片]
     * @return [type] [description]
     * @author
     */
    public function addImage($data){
        $result = $this->validate("ImageValidate")->save($data);
        return $result;
    }
    /**
     * [updateImagetion 更新图片]
     * @return [type] [description]
     * @author
     */
    public function updateImagetion($data,$id){
        $dic = $this->where(["bianma"=>$data['bianma'],"parentID"=>$data['parentID']])
        ->find();
        if($dic != null){
            if($dic->getdata()['bz'] != trim($data['bz'])){
                $result = $this->validate("ImageValidate")->save($data,['id'=>$id]);
                return $result;
            }else{return null;}
        }
        $result = $this->validate("ImageValidate")->save($data,['id'=>$id]);
        return $result;
    }
    /**
     * [deleImagetion 删除图片]
     * @return [type] [description]
     * @author
     */    
    public function deleImagetion($data){
        $image = $this->get(["id"=>$data]);
        if($image == null){
            return null;
        }else{
            unlink(ROOT_PATH . $image['path']);
        }
        $result = $this->save(['IsDelete'=>'1'],['id'=>$data]);
        return $result;
    }
    /**
     * [getAllImageSize 获取指点父id的字节的数量]
     * @return [type] [description]
     * @author
     */
    public function getAllImageSize($parentID){
        $count = $this->where(['nodeID'=>$parentID,'IsDelete'=>0])->count();
        return $count;
    }
    /**
     * [getImageParent 获取所有的图片选项]
     * @return [type] [description]
     * @author
     */
    public function getImageParent(){
    
        $reault = $this->where(['parentID'=>0,'IsDelete'=>0])->select();
        //$data =array();
        foreach ($reault as $key => &$value){
    
            $value['pid'] = $value['parentID'];
            $value['title'] = $value['name'];
        }
        return $reault;
    }
    /**
     * [getImagetree 获取图片树]
     * @return [type] [description]
     * @author
     */
    public function getImagetree($id=0){
        if($id == 0){
            
            $id = $this->ImageRootNode;
        }
        $Currency = new CurrencyTreeModel();
        $result = $Currency->getbufenTree($id);
        return $result;
    }
    /**
     * [initTree 初始化图片树]
     * @return [type] [description]
     * @author
     */
    public function initTree($id=0){
        if($id == 0){
    
            $id = $this->ImageRootNode;
        }
        $Currency = new CurrencyTreeModel();
        $result = $Currency->getTreebyID($id);
        return $result;
    }
}

?>