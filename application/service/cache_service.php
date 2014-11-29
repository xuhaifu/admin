<?php
/**
 * 缓存模块
 *
 */
class Cache_service extends MY_Service
{
    /**
     * 获取管理员最大权限
     * 
     * @param int $source_id
     * @return array
     */
    public function getAdminSourceResource($source_id)
    {
        $cache_key = 'rbac_admin_source_resource_'.$source_id;
        $this->load->driver('cache');
        $admin_resource_cache = $this->cache->file->get($cache_key);
        if(! $admin_resource_cache) {
            $this->load->model('admin_source_resource_model');
            $admin_resource_cache = $this->admin_source_resource_model->findAll(array('source_id' => $source_id));
            if(is_array($admin_resource_cache) && count($admin_resource_cache) > 0) {
                $admin_resource_cache = array_field_select($admin_resource_cache, 'resource_id', true);
                if(CACHE_USERD) {
                    $this->cache->file->save($cache_key, $admin_resource_cache, 86400);
                }
            } else {
                $admin_resource_cache = array();
            }
        }
        return $admin_resource_cache;
    }

    /**
     * 获取所有资源列表
     * @return array
     */
    public function getAllResourceList()
    {
        $cache_key = 'rbac_all_resouce_list';
        $this->load->driver('cache');
        $resouce_cache = $this->cache->file->get($cache_key);
        if(! $resouce_cache) {
            $this->load->model('admin_resource_model');
            $resouce_list = $this->admin_resource_model->findAll(array(), NULL, NULL, 'sort DESC');
            $resouce_cache = array();
            if(is_array($resouce_list) && count($resouce_list) > 0) {
                foreach($resouce_list as $key => $val) {
                    $resouce_cache[$val['resource_id']] = $val;
                    unset($resouce_list[$key]);
                }
                if(CACHE_USERD) {
                    $this->cache->file->save($cache_key, $resouce_cache, 86400);
                }
            }
        }
        return $resouce_cache;
    }

    /**
     * 删除所有权限缓存
     * @return Cache_service
     */
    public function reloadAllResourceList()
    {
        if($this->isAllResourceCacheExists()) {
            $this->load->driver('cache');
            $this->cache->file->delete('rbac_all_resouce_list');
        }
        return $this;
    }

    /**
     * 检测所有权限缓存文件是否存在
     * @return boolean
     */
    public function isAllResourceCacheExists()
    {
        if(file_exists(APPPATH.'cache/rbac_all_resouce_list')) {
            return true;
        }
        return false;
    }

    /**
     * 删除管理员最大权限
     * @param string $source_id
     * @return Cache_service
     */
    public function reloadAdminSourceResource($source_id = NULL)
    {
        $this->load->driver('cache');
        if($source_id) {
            $cache_key = 'rbac_admin_source_resource_'.$source_id;
            if(file_exists(APPPATH.'cache/'.$cache_key)) {
                $this->cache->file->delete($cache_key);
            }
        } else {
            foreach(lang_setting('admin_source_id') as $key => $val) {
                $cache_key = 'rbac_admin_source_resource_'.$key;
                if(file_exists(APPPATH.'cache/'.$cache_key)) {
                    $this->cache->file->delete($cache_key);
                }
            }
        }
        return $this;
    }
}