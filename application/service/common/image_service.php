<?php
/**
 * 图片处理
 * 
 * @author pengbotao
 *
 */
class Asm_image_service extends MY_Service
{
    /**
     * 头像保存地址
     * @var string
     */
    private $avatarPath;

    /**
     * 评论图片地址
     * @var string
     */
    private $commentPath;

    /**
     * 美甲师图片地址
     * @var string
     */
    private $artisanPath;

    /**
     * 作品图片地址
     * @var string
     */
    private $productPath;

    /**
     * 首页轮播图片地址
     * @var string
     */
    private $bannerPath;

    public function __construct()
    {

        parent::__construct();

        $this->avatarPath = SYS_UPLOAD_PATH.'avatar/';
        $this->commentPath = SYS_UPLOAD_PATH.'comment/';
        $this->artisanPath = SYS_UPLOAD_PATH.'artisan/';
        $this->productPath = SYS_UPLOAD_PATH.'product/';
        $this->bannerPath = SYS_UPLOAD_PATH.'banner/';
    }

    /**
     * 保存头像
     * 
     * @param binnary $file_data
     * @param string $prefix
     * @return boolean|string
     */
    public function saveAvatar($file_data, $prefix = '')
    {
        $file_path = $this->avatarPath.date("Ymd").'/'.md5($prefix.'_'.uniqid()).'.png';
        if($this->writeFile($file_path, $file_data)) {
            list($width, $height) = getimagesize($file_path);
            if(! $width || ! $height) {
                @unlink($file_path);
                return false;
            }
            return $this->getFilePath($file_path);
        }
        return false;
    }

    /**
     * 保存美甲师图片
     *
     * @param binnary $file_data
     * @param string $prefix
     * @return boolean|string
     */
    public function saveArtisan($file_data, $prefix = '')
    {
        $file_path = $this->artisanPath.date("Ymd").'/'.md5($prefix.'_'.uniqid()).'.png';
        if($this->writeFile($file_path, $file_data)) {
            list($width, $height) = getimagesize($file_path);
            if(! $width || ! $height) {
                @unlink($file_path);
                return false;
            }
            return $this->getFilePath($file_path);
        }
        return false;
    }
    
    /**
     * 保存评论图片
     * 
     * @param binnary $file_data
     * @param int $comment_id
     * @return Ambigous <boolean, multitype:string number unknown multitype: >
     */
    public function saveCommentImage($file_data, $comment_id)
    {
        return $this->saveBasicImage('comment', $file_data, array(
            'img_source' => 1,
            'object_id' => $comment_id
        ));
    }

    /**
     * 保存美甲师图片
     * 
     * @param binnary $file_data
     * @param int $artisan_id
     * @return Ambigous <boolean, multitype:string number unknown multitype: >
     */
    public function saveArtisanImage($file_data, $artisan_id)
    {
        return $this->saveBasicImage('artisan', $file_data, array(
            'img_source' => 3,
            'object_id' => $artisan_id
        ));;
    }

    /**
     * 保存作品图片
     * @param binnary $file_data
     * @param string $type
     * @param string $product_id
     * @param string $product_id updata
     * @return Ambigous <boolean, multitype:string number unknown multitype: >
     */
    public function saveProductImage($file_data,$type,$product_id = '')
    {
        if($type == 'img_cover'){
            $file_path = $this->productPath.date("Ymd").'/'.md5('product_'.uniqid()).'.png';
            if($this->writeFile($file_path, $file_data)) {
                list($width, $height) = getimagesize($file_path);
                if(! $width || ! $height) {
                    @unlink($file_path);
                    return false;
                }
                return $this->getFilePath($file_path);
            }
            return false;
        }elseif($type == 'img_work'){
            return $this->saveBasicImage('product', $file_data, array(
                'img_source' => 2,
                'object_id' => $product_id,
           ));
        }
    }
    

    /**
     * 保存轮播图片
     * 
     * @param binnary $file_data 
     * @param string $prefix
     * @return boolean|string
     */
    public function saveBannerImage($file_data, $prefix = '')
    {
        $file_path = $this->bannerPath.date("Ymd").'/'.md5($prefix.'_'.uniqid()).'.png';
        if($this->writeFile($file_path, $file_data)) {
            list($width, $height) = getimagesize($file_path);
            if(! $width || ! $height) {
                @unlink($file_path);
                return false;
            }
            return $this->getFilePath($file_path);
        }
        return false;
    }

    /**
     * 保存图片并记录到basic_image_model表
     * 
     * @param string $img_source
     * @param array $file_data
     * @param array $attr
     * @return boolean|multitype:string number unknown multitype:
     */
    private function saveBasicImage($img_source, $file_data, $attr = array())
    {
        $path_var = $img_source.'Path';
        $file_path = $this->$path_var.date("Ymd").'/'.md5($img_source.'_'.uniqid()).'.png';
        if($this->writeFile($file_path, $file_data)) {
            list($width, $height) = getimagesize($file_path);
            if(! $width || ! $height) {
                @unlink($file_path);
                return false;
            }
            $this->load->model('basic_image_model');
            $image_data = array(
                'img_path' => $this->getFilePath($file_path),
                'img_title' => '',
                'img_desc' => '',
                'img_width' => $width,
                'img_height' => $height,
                'img_source' => 0,
                'object_id' => 0,
                'img_stat' => 1,
                'is_verify' => 1
            );
            if($attr) {
                $image_data = array_merge($image_data, $attr);
            }
            $image_id = $this->basic_image_model->save($image_data);
            if(! $image_id) {
                @unlink($file_path);
                return false;
            }
            $image_data['img_id'] = $image_id;
            return $image_data;
        }
        return false;
    }
    
    /**
     * 获取文件地址
     * @param string $file_path
     * @return string
     */
    public function getFilePath($file_path)
    {
        return substr($file_path, strlen(FCPATH));
    }

    /**
     * 写文件，地址设置好后自动创建不存在的目录
     * 
     * @param string $file_path
     * @param binnary $data
     * @return number
     */
    public function writeFile($file_path, $data)
    {
        $last_slash_pos = strrpos($file_path, '/');
        $folder = substr($file_path, 0, $last_slash_pos);
        if(! file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        return move_uploaded_file($data['tmp_name'],$file_path);
    }
}