<?php
/**
 * Table Plot Syntax Plugin
 *
 *  Plots a table using JQPlot libraries 
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Tom Cafferty <tcafferty@glocalfocal.com>
 */
if(!defined('DOKU_INC')) define('DOKU_INC',(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once (DOKU_INC.'inc/parserutils.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_tableplot extends DokuWiki_Syntax_Plugin {

    function getInfo() {
        return array(
            'author' => 'Tom Cafferty',
            'email'  => 'tcafferty@glocalfocal.com',
            'date'   => '2011-12-29',
            'name'   => 'tableplot',
            'desc'   => 'Integrate JQPlot to plot a table ',
            'url'    => 'http://www.dokuwiki.org/plugin:tableplot'
        );
    }
    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'substition';
    }

    function getPType(){
        return 'block';
    }

    /**
     * Where to sort in?
     */
    function getSort(){
        return 160;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
      $this->Lexer->addSpecialPattern('<tableplot>.*?</tableplot>',$mode,'plugin_tableplot');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
        parse_str($match, $return);   
        return $return;
    }

/**
 *
 * Create timeline output 
 *
 * @author   Tom Cafferty <tcafferty@glocalfocal.com>
 *
 */
    function render($mode, &$renderer, $data) {
      global $INFO;
      global $ID;
      global $conf;

      // store meta info for this page
      if($mode == 'metadata'){
        $renderer->meta['plugin']['tableplot'] = true;
        return true;
      }

      if($mode != 'xhtml') return false;
      
      $series      = "series:'";
      $idstr       = "id:'";
      $orient      = "orient:'";
      $firstSeries = "firstSeries:'";   
      $lastSeries  = "lastSeries:'";   
      $labels      = "labels:'";   
      $xaxis       = "xaxis:'";   
      $dataStart   = "dataStart:'";   
      $dataEnd     = "dataEnd:'";   
      $position    = "position:'";   
      $width       = "width:'";   
      $height      = "height:'";   
      $placement   = "placement:'";   
      $dataTransform  = "dataTransform:";   
      $labelTransform = "labelTransform:";   
      $xaxisTransform = "xaxisTransform:";   
   
      // Initialize settings from user input or conf file
      if (isset($data['orient'])) 
        $orient .= $data['orient'] . "',";
      else
        $orient .= $this->getConf('orient'). "',";

      if (isset($data['series'])) 
        $series .= $data['series'] . "',";
      else
        $series .= $this->getConf('series'). "',";
        
      if (isset($data['firstSeries'])) 
        $firstSeries .= $data['firstSeries'] . "',";
      else
        $firstSeries = '';
        
       if (isset($data['lastSeries'])) 
        $lastSeries .= $data['lastSeries']. "',";
      else
        $lastSeries = '';
        
      if (isset($data['labels'])) 
        $labels .= $data['labels']. "',";
      else
        $labels = '';      
        
      if (isset($data['xaxis'])) 
        $xaxis .= $data['xaxis']. "',";
      else
        $xaxis = '';
        
       if (isset($data['dataStart'])) 
        $dataStart .= $data['dataStart']. "',";
      else
        $dataStart = '';
        
       if (isset($data['dataEnd'])) 
        $dataEnd .= $data['dataEnd']. "',";
      else
        $dataEnd = '';
        
       if (isset($data['position'])) 
        $position .= $data['position']. "',";
      else
        $position = '';
        
       if (isset($data['width'])) 
        $width .= $data['width']. "',";
      else
        $width = '';
        
       if (isset($data['height'])) 
        $height .= $data['height']. "',";
      else
        $height = '';
        
       if (isset($data['placement'])) 
        $placement .= $data['placement']. "',";
      else
        $placement = '';
        
       if (isset($data['dataTransform'])) 
        $dataTransform .= $data['dataTransform']. ",";
      else
        $dataTransform = '';
        
       if (isset($data['labelTransform'])) 
        $labelTransform .= $data['labelTransform']. ",";
      else
        $labelTransform = '';
        
       if (isset($data['xaxisTransform'])) 
        $xaxisTransform .= $data['xaxisTransform']. ",";
      else
        $xaxisTransform = '';
        
       if (isset($data['plotArgs'])) 
        $plotArgs = $data['plotArgs'];
      else
        $plotArgs = '';
                
       if (isset($data['id'])) {
           $id = $data['id'];
           $idstr .= $data['id']."'";
       }
      else {
          $id = $this->getConf('id');
          $idstr .= $id."'";
      }

    // invoke graph Table
      $grafArgs = '<script type="text/javascript"> jQuery(\'#'.$id.'\').tablePlot({'.$orient .$series. $firstSeries . $lastSeries . $labels . $xaxis .$dataStart .$dataEnd . $position .$width .$height .$dataTransform .$labelTransform .$xaxisTransform .$placement .$idstr;
      $allArgs = rtrim($grafArgs,',') . '},' .$plotArgs;
      $cmd = rtrim($allArgs,',') . '); </script>';

      $renderer->doc .= $cmd; 
	  return true;
    }
}