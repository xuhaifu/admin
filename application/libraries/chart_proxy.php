<?php
include_once APPPATH.'third_party/highchartsPHP/Highchart.php';

class Chart_proxy extends Highchart
{
	public $autoStep = true;

	public function __construct()
	{
		parent::__construct();

		$this->credits = array('enabled' => false);
		$this->title = array('text' => '', 'x' => -20);
		$this->subtitle = array('text' => '', 'x' => -20);
		$this->xAxis = array(
			'reversed' => false
		);
		$this->yAxis = array(
				'title' => array('text' => ''),
		);

	}
	
	public function HighDatarr($name,$data,$categories,$draw_type='spline'){
	    $series = array();
        if(is_array($data) && is_array($name)){
            foreach($data as $k=>$v){
                $series[] = array('name'=>$name[$k],'data'=>$v);
            }
        }else{
            $series[] = array('name'=>$name,'data'=>$data);

        }
		return $data_chart = json_decode($this->draw($series,$categories,$draw_type));
	}
	
    public function draw($series, $categories, $draw_type='spline')
    {
        if(method_exists($this, $draw_type)) {
            return $this->$draw_type($series, $categories);
        }
        return null;
    }

    /**
     * 线图
     * 
     * @param array $series
     * @param array $categories
     * @return string
     */
    public function spline($series, $categories)
    {
        $this->xAxis->categories = $categories;
        $this->chart->type = 'spline';
        if(!is_array($series)) {
            return null;
        }
        foreach($series as $val) {
            $this->series[] = $val;
        }
        return $this->renderOptions();
    }
    /**
     * 柱形图
     *
     * @param array $series
     * @param array $categories
     * @return string
     */
    public function column($series, $categories)
    {
        $this->xAxis->categories = $categories;
        $this->chart->type = 'column';
        if(!is_array($series)) {
            return null;
        }
        $this->plotOptions = array(
            "column"=>array(
                "stacking"=>'normal',
            )
        );
        foreach($series as $val) {
            $this->series[] = $val;
        }
        return $this->renderOptions();
    }
    /**
     * 饼状图
     *
     * @param array $series
     * @param array $categories
     * @return string
     */
    public function pie($series, $categories)
    {
        $array = array();
        foreach($categories as $key=>$value){
           $array[$key][] = $value;
           $array[$key][] = $series[0]['data'][$key];
        }
        $series[0]['data'] = $array;
        $this->xAxis->categories = $categories;
        $this->chart->type = 'pie';
        $this->plotOptions->pie = array(
                'allowPointSelect' => true,
                'dataLabels' => array(
                    'format' => '<b>{point.name}</b>:{point.percentage:.1f} %',
                ),
            );
        $this->plotOptions->pie->cursor = 'pointer';
        if(!is_array($series)) {
            return null;
        }
        foreach($series as $val) {
            $this->series[] = $val;
        }
        return $this->renderOptions();
    }
}