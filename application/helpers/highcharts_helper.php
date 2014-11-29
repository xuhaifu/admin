<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function ajax_render_line_highChart($categories, $series, $renderTo='container', $width='950px', $height='500px', $type='spline')
{
	$CI =& get_instance();
	$CI->load->library('highchartsPHP/Highchart.php');

	$chart = new Highchart();
	$chart->credits = array('enabled'=>FALSE);	//隐藏官方网址
	$chart->chart = array('renderTo' => $renderTo, 'type' => $type);
	$chart->chart->width = $width == '100%' ? null : str_replace('px', '', $width);
	$chart->chart->height = $height == '100%' ? null : str_replace('px', '', $height);
	$chart->title 		= array('text' => '', 'x' => -20);
	$chart->subtitle 	= array('text' => '', 'x' => -20);
	//$chart->xAxis->labels = array('step'=>1 , 'align'=>'right' , 'rotation'=>-90	/*倾斜度*/);
	$chart->yAxis = array(
			'title' => array('text' => ''),
			//'plotLines' => array(array('value' => 0,'width' => 1,'color' => '#808080'))
	);
	//$chart->legend = array('layout' => 'vertical', 'align' => 'right','verticalAlign' => 'top', 'x' => -10,'y' => 100, 'borderWidth' => 0);
	$chart->tooltip->formatter = new HighchartJsExpr("function() { return '<b>'+ this.series.name +'</b><br/>'+ this.x +': '+  Highcharts.numberFormat(this.y, 0);}");
	
	$chart->series = new HighchartJsExpr($series);
	$chart->xAxis->categories = new HighchartJsExpr($categories);
	return $chart->render(isset($renderTo) ? str_replace(array('-', '_'), '', $renderTo)."chart":"chart");
}
/**
 * 生成图表
 * 
 * @param array $data  数据库查出来的数组
 * @param string $xField  x轴显示的字段
 * @param string $yField y轴显示的字段
 * @param string $categoryField 分类的字段
 * @param string $width 容器的宽度
 * @param string $height 容器的高度
 * @param string $type 图表类型
 * @param string $renderTo 容器的id
 * @param bool $includeJs 是否要输出js
 * @param string $template 显示图表的模板
 * @param bool $hasJquery 是否已经有jquery了
 * 
 * @return boolean|string
 */
function render_line_highChart(array $data, $xField, $yField, $categoryField, $width='950px', $height='500px', $type='spline' , $renderTo='container', $includeJs=true, $template='base/_chart', $hasJquery=true, $limit=0)
{
	$CI =& get_instance();
	$CI->load->library('highchartsPHP/Highchart.php');
	
	if(empty($data) || empty($xField) || empty($yField) || empty($categoryField)){
		return false;
	}
	
	$chart = new Highchart();
	$chart->credits = array('enabled'=>FALSE);	//隐藏官方网址
	$chart->chart = array('renderTo' => $renderTo, 'type' => $type);
	$chart->chart->width = $width == '100%' ? null : str_replace('px', '', $width);
	$chart->chart->height = $height == '100%' ? null : str_replace('px', '', $height);
	$chart->title 		= array('text' => '', 'x' => -20);
	$chart->subtitle 	= array('text' => '', 'x' => -20);
	//$chart->xAxis->labels = array('step'=>1 , 'align'=>'right' , 'rotation'=>-90	/*倾斜度*/);
	$chart->yAxis = array(
		'title' => array('text' => ''),
		//'plotLines' => array(array('value' => 0,'width' => 1,'color' => '#808080'))
	);
	//$chart->legend = array('layout' => 'vertical', 'align' => 'right','verticalAlign' => 'top', 'x' => -10,'y' => 100, 'borderWidth' => 0);
	$chart->tooltip->formatter = new HighchartJsExpr("function() { return '<b>'+ this.series.name +'</b><br/>'+ this.x +': '+  Highcharts.numberFormat(this.y, 0);}");
	

	$newData = array();
	$categoryArr = array();
	foreach($data as $row){
		$newData[$row[$xField]][$row[$categoryField]] = $row[$yField];
		$categoryArr[$row[$categoryField]] = array();
	}
	ksort($newData);
	foreach($newData as $row){
		foreach($categoryArr as $category => $value){
			if(isset($row[$category])){
				$categoryArr[$category][] = (int)$row[$category];
			} else {
				$categoryArr[$category][] = 0;
			}
		}
	}
	 
	$chart->xAxis->categories = array_keys($newData);

	$CI->load->helper('array');
	$categoryArr = array_field_sort($categoryArr, 0, SORT_DESC);
	$i = 0;
	foreach($categoryArr as $name => $data){
		if(empty($name)){
			$name = '未知项';
		}
		$chart->series[] = array('name' => $name,'data' => $data);
		$i++;
		if($limit > 0 && $i >= $limit){
		    break;
		}
	}
	
	$output = $CI->load->view($template, array('chart' => $chart, 'count' => count($data), 'includeJs' => $includeJs, 'width' => $width, 'height' => $height, 'container' => $renderTo, 'hasJquery' => $hasJquery), true);
	
	return $output;
	
}

function render_bar_highChart($categories, $series, $renderTo='container', $includeJs=true, $height='500px', $width='100%', $type='bar' , $template='base/_chart', $hasJquery=true)
{
    $CI =& get_instance();
    $CI->load->library('highchartsPHP/Highchart.php');
    $chart = getHighchartsPHP();
    $chart->xAxis->categories = $categories;
    if(is_array(current($series))) {
        foreach($series as $val) {
            $chart->series[] = $val;
        }
    } else {
        $chart->series[] = $series;
    }
    $chart->chart->type = $type;
    $chart->chart->width = $width == '100%' ? null : str_replace('px', '', $width);
    $chart->chart->height = $height == '100%' ? null : str_replace('px', '', $height);
    $chart->chart->renderTo = $renderTo;
    return $CI->load->view($template, array('chart' => $chart, 'count' => count($series), 'includeJs' => $includeJs, 'width' => $width, 'container' => $renderTo, 'hasJquery' => $hasJquery), true);
}

function render_pie_highChart(array $data, $xField, $yField, $width='950px', $height='500px', $renderTo='container', $includeJs=true, $template='base/_chart', $hasJquery=true)
{
	$CI =& get_instance();
	$CI->load->library('highchartsPHP/Highchart.php');

	if(empty($data) || empty($xField) || empty($yField) ){
		return false;
	}

	$chart = new Highchart();
	$chart->credits = array('enabled'=>FALSE);	//隐藏官方网址
	$chart->chart = array('renderTo' => $renderTo, 'type' => 'pie');
	if($width !== NULL) {
	    $chart->chart->width = $width == '100%' ? null : str_replace('px', '', $width);
	}
	if($height !== NULL) {
	    $chart->chart->height = $height == '100%' ? null : str_replace('px', '', $height);
	}
	$chart->title 		= array('text' => '', 'x' => -20);
	$chart->subtitle 	= array('text' => '', 'x' => -20);
	//$chart->xAxis->labels = array('step'=>1 , 'align'=>'right' , 'rotation'=>-90	/*倾斜度*/);
	$chart->yAxis = array(
			'title' => array('text' => ''),
			//'plotLines' => array(array('value' => 0,'width' => 1,'color' => '#808080'))
	);
	//$chart->legend = array('layout' => 'vertical', 'align' => 'right','verticalAlign' => 'top', 'x' => -10,'y' => 100, 'borderWidth' => 0);
	$chart->tooltip->formatter = new HighchartJsExpr("function() { return '<b>' + this.point.name + '</b>: ' + Highcharts.numberFormat(this.y, 0) + '<br/>' + this.series.name + ':' + Math.round(this.percentage*100)/100 + ' %';}");
	$chart->plotOptions->pie->allowPointSelect = true;
	$chart->plotOptions->pie->cursor = 'pointer';
	$chart->plotOptions->pie->dataLabels->formatter = new HighchartJsExpr("function() { return '<b>' + this.point.name + '</b>    ' + Math.round(this.percentage*100)/100 + ' %';}");

	$newData = $seriesArr = array();
	foreach($data as $row){
		if(!isset($newData[$row[$xField]])){
			$newData[$row[$xField]] = 0;
		}
		$newData[$row[$xField]] += $row[$yField];
	}
	ksort($newData);
	foreach($newData as $key => $value){
		$seriesArr[] = array($key, $value);
	}

	$chart->series[] = array('name' => '百分比','data' => $seriesArr);
	
	$output = $CI->load->view($template, array('chart' => $chart, 'count' => count($data), 'includeJs' => $includeJs, 'width' => $width, 'height' => $height, 'container' => $renderTo, 'hasJquery' => $hasJquery), true);

	return $output;

}
/**
 * 获取基本的highcharts对象
 * @param string $type
 * @param string $renderTo
 * @return Highchart
 */
function getHighchartsPHP($type='spline' , $renderTo='container'){
	$CI =& get_instance();
	$CI->load->library('highchartsPHP/Highchart.php');

	$chart = new Highchart();
	$chart->credits = array('enabled'=>FALSE);	//隐藏官方网址

	$chart->chart = array('renderTo' => $renderTo, 'type' => $type);



	$chart->title 		= array('text' => '', 'x' => -20);
	$chart->subtitle 	= array('text' => '', 'x' => -20);

	//$chart->xAxis->labels = array('step'=>1 , 'align'=>'right' , 'rotation'=>-90	/*倾斜度*/);

	$chart->yAxis = array(
		'title' => array('text' => ''),
		//'plotLines' => array(array('value' => 0,'width' => 1,'color' => '#808080'))
	);


	#$chart->legend = array('layout' => 'vertical', 'align' => 'right','verticalAlign' => 'top', 'x' => -10,'y' => 100, 'borderWidth' => 0);

	//$chart->tooltip->formatter = new HighchartJsExpr("function() { return this.x +': '+ Highcharts.numberFormat(this.y, 0);}");
	
	return $chart;

}

function render_column_highChart($categories, $series, $renderTo='container', $includeJs=true, $height='500px', $width='100%', $type='column' , $template='base/_chart', $hasJquery=true)
{
	$CI =& get_instance();
	$CI->load->library('highchartsPHP/Highchart.php');

	$chart = new Highchart();
	$chart->credits = array('enabled'=>FALSE);	//隐藏官方网址
	$chart->chart = array('renderTo' => $renderTo, 'type' => $type);
	$chart->title 		= array('text' => '', 'x' => -20);
	$chart->subtitle 	= array('text' => '', 'x' => -20);

	//$chart->xAxis->labels = array('step'=>1 , 'align'=>'right' , 'rotation'=>-90	/*倾斜度*/);

	$chart->yAxis = array(
		'title' => array('text' => ''),
		//'plotLines' => array(array('value' => 0,'width' => 1,'color' => '#808080'))
	);
	$chart->xAxis->categories = $categories;
	if(is_array(current($series))) {
		foreach($series as $val) {
			$chart->series[] = $val;
		}
	} else {
		$chart->series[] = $series;
	}
	$chart->chart->width = $width == '100%' ? null : str_replace('px', '', $width);
	$chart->chart->height = $height == '100%' ? null : str_replace('px', '', $height);
	$chart->tooltip->shared = true;
	$chart->tooltip->useHTML = true;
	unset($chart->tooltip->formatter);
	$chart->tooltip->headerFormat = '<span style="font-size:10px">{point.key}</span><table>';
	$chart->tooltip->pointFormat = '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y}次</b></td></tr>';
	$chart->tooltip->footerFormat = '</table>';
	
	return $CI->load->view($template, array('chart' => $chart, 'count' => count($series), 'includeJs' => $includeJs, 'width' => $width, 'container' => $renderTo, 'hasJquery' => $hasJquery), true);
}