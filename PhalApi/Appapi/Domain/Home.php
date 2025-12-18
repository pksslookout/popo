<?php

class Domain_Home {

    public function getSlide($where) {
        $rs = array();
        $model = new Model_Home();
        $rs = $model->getSlide($where);
        return $rs;
    }
		
	public function getRecommendLive($p, $last) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getRecommendLive($p, $last);
				
        return $rs;
    }
	
	public function getHot($p,$last) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getHot($p,$last);
				
        return $rs;
    }
		
	public function getFollow($uid,$p,$last) {
        $rs = array();
				
        $model = new Model_Home();
        $rs = $model->getFollow($uid,$p,$last);
				
        return $rs;
    }
		
	public function getNew($lng,$lat,$p,$last) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getNew($lng,$lat,$p,$last);
				
        return $rs;
    }
		
	public function search($uid,$key,$p,$last) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->search($uid,$key,$p,$last);
				
        return $rs;
    }

	public function searchVideo($uid,$key,$p) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->searchVideo($uid,$key,$p);

        return $rs;
    }
	
	public function getNearby($lng,$lat,$p) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getNearby($lng,$lat,$p);
				
        return $rs;
    }
	
	public function getRecommend() {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getRecommend();
				
        return $rs;
    }
	
	public function attentRecommend($uid,$touid) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->attentRecommend($uid,$touid);
				
        return $rs;
    }

    public function profitList($uid,$type,$p){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->profitList($uid,$type,$p);
                
        return $rs;
    }

    public function consumeList($uid,$type,$p){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->consumeList($uid,$type,$p);
                
        return $rs;
    }

    public function getClassLive($liveclassid,$p,$last){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getClassLive($liveclassid,$p,$last);
                
        return $rs;
    }
	
	
	public function getShopList($p){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getShopList($p);
                
        return $rs;
    }
	
	public function getShopClassList($shopclassid,$sell,$price,$isnew,$p){
        $rs = array();

        $model = new Model_Home();
        $rs = $model->getShopClassList($shopclassid,$sell,$price,$isnew,$p);
                
        return $rs;
    }
	
	public function searchShop($key,$sell,$price,$isnew,$p) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->searchShop($key,$sell,$price,$isnew,$p);
				
        return $rs;
    }

	public function liveStreamingList($uid,$type,$p) {
        $rs = array();

        $model = new Model_Home();
        $rs = $model->liveStreamingList($uid,$type,$p);

        return $rs;
    }
	

}
