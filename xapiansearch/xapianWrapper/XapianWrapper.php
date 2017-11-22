<?php
// includes
require_once 'xapian.php';

// main class
class XapianWrapper {
  const XAPIAN_FIELD_URL = 0;
  const XAPIAN_FIELD_NAME = 1;
  const XAPIAN_FIELD_DATE = 2;
  const XAPIAN_FIELD_UID = 3;
  const XAPIAN_FIELD_SUMMARY = 4;
  const XAPIAN_PREFIX_UID = "UID:";
  
  const SETTINGS_XAPIAN_DB = './xapian_db';

  const SETTINGS_MYSQL_HOST = 'localhost';
  const SETTINGS_MYSQL_USER = 'root';
  const SETTINGS_MYSQL_PASS = 'root';
  const SETTINGS_MYSQL_DB = 'demo';
  const SETTINGS_MYSQL_TABLE = 'demo';

  const DEFAULT_COUNT = 10;

  private $mysql_link;
  private $category_cache;
  
  private $xapian_read_db;
  private $xapian_write_db;
  private $xapian_stemmer;
  private $xapian_indexer;
  private $xapian_enquire;

  private function xapian_init_readonly() {
    try{
      $this->xapian_read_db = new XapianDatabase(self::SETTINGS_XAPIAN_DB);
      $this->xapian_stemmer = new XapianStem("english");
      $this->xapian_enquire = new XapianEnquire($this->xapian_read_db);
    } catch(Exception $e) {
      throw new Exception('Could initialize Xapian: ' . $e->getMessage());
    } 
  }
  
  private function xapian_init_writable() {
    try{
      $this->xapian_write_db = new XapianWritableDatabase(self::SETTINGS_XAPIAN_DB, Xapian::DB_CREATE_OR_OPEN);  
      $this->xapian_indexer = new XapianTermGenerator();
      $this->xapian_stemmer = new XapianStem("english");
      $this->xapian_indexer->set_stemmer($this->xapian_stemmer);
    } catch(Exception $e) {
      throw new Exception('Could initialize Xapian: ' . $e->getMessage());
    } 
  }
  
  private function mysql_init() {
    $this->mysql_link = mysql_connect(self::SETTINGS_MYSQL_HOST, self::SETTINGS_MYSQL_USER, self::SETTINGS_MYSQL_PASS);
    if (!$this->mysql_link) {
      throw new Exception('Could not connect: ' . mysql_error());
    }

    $db_selected = mysql_select_db(self::SETTINGS_MYSQL_DB, $this->mysql_link);
    if (!$db_selected) {
      throw new Exception('Can\'t use db : ' . mysql_error());
    }
  }
  
  /**
   * Index method
   *
   */
  public function index($params) {
    $this->xapian_init_writable();
    $this->mysql_init();
    
    $start = microtime(true);

    $response = new stdClass();
    $response->indexed = array();

    $offset = (isset($params['offset'])) ? intval($params['offset']) : 0;
    $count = (isset($params['count'])) ? intval($params['count']) : self::DEFAULT_COUNT;
      $sql = 'SELECT * FROM '.self::SETTINGS_MYSQL_TABLE.' LIMIT ' . $offset . ', ' . $count . ';';

    $result = mysql_query($sql);

    if (!$result) {
      throw new Exception('Invalid query: ' . mysql_error());
    }
    
    $this->xapian_write_db->begin_transaction();

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
       $response->indexed[] = $this->index_row($row);      
    }

    $this->xapian_write_db->commit_transaction();
    mysql_free_result($result);
    mysql_close($this->mysql_link);

    return $response;
  }
    

  private function index_row($row) {
    $doc = new XapianDocument();

    $this->xapian_indexer->set_document($doc);
    $this->xapian_indexer->index_text($row['name'],50);
    $this->xapian_indexer->index_text($row['summary'], 1);

    $GUID = self::XAPIAN_PREFIX_UID . $row['unique_key'];
    $doc->add_term($GUID);

    $doc->add_value(self::XAPIAN_FIELD_URL, $row['url']);
    $doc->add_value(self::XAPIAN_FIELD_DATE, date('Ymd', strtotime($row['date'])));
    $doc->add_value(self::XAPIAN_FIELD_UID, $row['unique_key']);
    $doc->add_value(self::XAPIAN_FIELD_NAME, $row['name']);
    $doc->add_value(self::XAPIAN_FIELD_SUMMARY, $row['summary']);
    
      $this->xapian_write_db->replace_document(strval($GUID), $doc);

    $row_response = array();
    $row_response['name'] = $row['name'];
    $row_response['guid'] = $row['unique_key'];
    $row_response['url'] = $row['url'];
    return $row_response; 
  }
  
  /**
   * Delete method
   *
   */
  public function delete($params) {
    $this->xapian_init_writable();

    $this->xapian_write_db->begin_transaction();

    $response = array();

    foreach($params['items'] as $param_guid) {      
      $GUID = self::XAPIAN_PREFIX_UID . $param_guid;
      $this->xapian_write_db->delete_document(strval($GUID));
      $response[] = $param_guid;
    }
    
    $this->xapian_write_db->commit_transaction();
    return $response;
  }

  /**
   * Search method
   *
   */
  public function search($params) {
    $this->xapian_init_readonly();

    $start = microtime(true);

    // queries array to later construct full query
    $arr_queries = array();

    // from date
    if(!empty($params['date_from'])) {
      $arr_queries[] = new XapianQuery(XapianQuery::OP_VALUE_GE, 6, date('Ymd', strtotime($params['date_from'])));
    }

    // to date
    if(!empty($params['date_to'])) {
      $arr_queries[] = new XapianQuery(XapianQuery::OP_VALUE_LE, 6, date('Ymd', strtotime($params['date_to'])));
    }

    // unique key
    if(!empty($params['unique_key'])) {
      $arr_queries[] = new XapianQuery(self::XAPIAN_PREFIX_UID . $params['unique_key']);
    }

    // normal search query parsed
    if(!empty($params['search'])) {
      $qp = new XapianQueryParser();
      $qp->set_stemmer($this->xapian_stemmer);
      $qp->set_database($this->xapian_read_db);
      $qp->set_stemming_strategy(XapianQueryParser::STEM_SOME);
      $arr_queries[] = $qp->parse_query($params['search']);
    }

    // Find the results for the query.
        // construct final query
    $query = array_pop($arr_queries);

    foreach($arr_queries as $sq) {
      $query = new XapianQuery(XapianQuery::OP_AND, $query, $sq);
    }    
    $this->xapian_enquire->set_query($query);
  
    // set the count to the specified params
    $offset = (isset($params['offset'])) ? intval($params['offset']) : 0;
    $count = (isset($params['count'])) ? intval($params['count']) : self::DEFAULT_COUNT;
    $matches = $this->xapian_enquire->get_mset($offset, $count);

    $response = new stdClass();
    $response->result_count = $matches->get_matches_estimated();
    $results = array();

    $i = $matches->begin();
    while (!$i->equals($matches->end())) {
      $m = array();

      $n = $i->get_rank() + 1;
      $doc = $i->get_document();

      $m['position'] = $n;
      $m['url'] = $doc->get_value(self::XAPIAN_FIELD_URL);
      $m['name'] = $doc->get_value(self::XAPIAN_FIELD_NAME);
      $m['summary'] = $doc->get_value(self::XAPIAN_FIELD_SUMMARY);
      $m['date'] = $doc->get_value(self::XAPIAN_FIELD_DATE);
      $m['unique_key'] = $doc->get_value(self::XAPIAN_FIELD_UID);
      $m['percent'] = $i->get_percent();

      $results[count($results)] = $m;
      $i->next();
    }

    $response->results = $results;
    $end = microtime(true);
    
        // runtime info
    $response->execute = new stdClass();
    $response->execute->call = 'search';
    $response->execute->offset = $offset;
    $response->execute->count = $count;
    $response->execute->start = $start;
    $response->execute->end = $end;
    $response->execute->time = $end - $start;

        // debug stuff
    $response->execute->debug = $query->get_description();

    return $response;
  }
}
