<?php
/**
 * 物业新闻管理数据层
 */
class Dao_News extends Dao_Base {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 查询hotel_news列表
     *
     * @param
     *            array 入参
     * @return array
     */
    public function getNewsList(array $param): array {
        $limit = $param['limit'] ? intval($param['limit']) : 0;
        $page = $this->getStart($param['page'], $limit);
        
        $paramSql = $this->handlerNewsListParams($param);
        $sql = "select * from hotel_news {$paramSql['sql']} order by id DESC";
        if ($limit) {
            $sql .= " limit {$page},{$limit}";
        }
        $result = $this->db->fetchAll($sql, $paramSql['case']);
        return is_array($result) ? $result : array();
    }

    /**
     * 查询hotel_news数量
     *
     * @param
     *            array 入参
     * @return array
     */
    public function getNewsCount(array $param): int {
        $paramSql = $this->handlerNewsListParams($param);
        $sql = "select count(1) as count from hotel_news {$paramSql['sql']}";
        $result = $this->db->fetchAssoc($sql, $paramSql['case']);
        return intval($result['count']);
    }

    /**
     * 列表和数量获取筛选参数处理
     * @param $param
     * @return array
     */
    private function handlerNewsListParams($param) {
        $whereSql = array();
        $whereCase = array();
        if (isset($param['hotelid'])) {
            $whereSql[] = 'hotelid = ?';
            $whereCase[] = $param['hotelid'];
        }
        if (isset($param['tagid'])) {
            $whereSql[] = 'tagid = ?';
            $whereCase[] = $param['tagid'];
        }
        if (isset($param['status'])) {
            $whereSql[] = 'status = ?';
            $whereCase[] = $param['status'];
        }
        if (isset($param['id'])) {
            $whereSql[] = 'id = ?';
            $whereCase[] = $param['id'];
        }
        if (isset($param['title'])) {
        	$whereSql[] = '(title_lang1 = ? or title_lang2 = ? or title_lang2 = ?)';
        	$whereCase[] = $param['title'];
        	$whereCase[] = $param['title'];
        	$whereCase[] = $param['title'];
        }

        if (array_key_exists('enable_lang1', $param)) {
            $whereSql[] = 'enable_lang1 = ?';
            $whereCase[] = $param['enable_lang1'];
        }
        if (array_key_exists('enable_lang2', $param)) {
            $whereSql[] = 'enable_lang2 = ?';
            $whereCase[] = $param['enable_lang2'];
        }
        if (array_key_exists('enable_lang3', $param)) {
            $whereSql[] = 'enable_lang3 = ?';
            $whereCase[] = $param['enable_lang3'];
        }
        $whereSql = $whereSql ? ' where ' . implode(' and ', $whereSql) : '';
        return array(
            'sql' => $whereSql,
            'case' => $whereCase
        );
    }

    /**
     * 根据id查询hotel_news详情
     *
     * @param
     *            int id
     * @return array
     */
    public function getNewsDetail(int $id): array {
        $result = array();
        
        if ($id) {
            $sql = "select * from hotel_news where id=?";
            $result = $this->db->fetchAssoc($sql, array(
                $id
            ));
        }
        
        return $result;
    }

    /**
     * 根据id更新hotel_news
     *
     * @param
     *            array 需要更新的数据
     * @param
     *            int id
     * @return array
     */
    public function updateNewsById(array $info, int $id) {
        $result = false;
        
        if ($id) {
            $result = $this->db->update('hotel_news', $info, array( 'id' => $id) );
        }
        
        return $result;
    }

    /**
     * 单条增加hotel_news数据
     *
     * @param
     *            array
     * @return int id
     */
    public function addNews(array $info) {
        $this->db->insert('hotel_news', $info);
        return $this->db->lastInsertId();
    }
}
