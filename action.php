<?php
/**
 * Table Plot Action Plugin
 *
 *  Plots a table using JQPlot libraries 
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Tom Cafferty <tcafferty@glocalfocal.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once DOKU_PLUGIN.'action.php';
require_once (DOKU_INC.'inc/parserutils.php');

class action_plugin_tableplot extends DokuWiki_Action_Plugin {

    function getInfo() {
        return array(
            'author' => 'Tom Cafferty',
            'email'  => 'tcafferty@glocalfocal.com',
            'date'   => '2011-12-29',
            'name'   => 'tableplot',
            'desc'   => 'Integrate jquery jqPlot plugin with dokuwiki',
            'url'    => 'http://www.dokuwiki.org/plugin:tableplot',
        );
    }

    /**
     * Register its handlers with the DokuWiki's event controller
     */
    function register(&$controller) {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'tableplot_hookjs');
    }

    /**
     * Hook js script into page headers.
     *
     * @author Tom Cafferty <tcafferty@glocalfocal.com>
     */
    function tableplot_hookjs(&$event, $param) {
        global $INFO;
        global $ID;
        $basePath = DOKU_BASE;
        $basePath = str_replace("dokuwiki/", "", $basePath);
               
        $key = 'keywords';      
        $metadata = p_get_metadata($ID, $key, false);
               
        // metadata check to include javascript files if needed 
        if (p_get_metadata($ID, 'plugin tableplot') || (strpos($metadata, 'table2plot') !== false) ) { 
                $event->data['link'][] = array(
                            'rel' => 'stylesheet',
                            'type'    => 'text/css',
                            '_data'   => '',
                            'href'     => $basePath ."lib/scripts/jqplot/jquery.jqplot.min.css");
            $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => $basePath ."lib/scripts/jquery/jquery.min.js");
            $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => $basePath ."lib/scripts/jqplot/jquery.jqplot.min.js");
            $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => $basePath ."lib/scripts/jqplot/plugins/jqplot.barRenderer.min.js");
            $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => $basePath ."lib/scripts/jqplot/plugins/jqplot.categoryAxisRenderer.min.js");
            $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => $basePath ."lib/scripts/jqplot/plugins/jqplot.dateAxisRenderer.min.js");
            $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => $basePath ."lib/scripts/jqplot/plugins/jqplot.canvasTextRenderer.min.js");
            $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => $basePath ."lib/scripts/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js");
            $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => $basePath ."lib/scripts/jqplot/plugins/jqplot.highlighter.js");                                                      
            $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => $basePath ."lib/scripts/jqplot/plugins/jqplot.pointLabels.min.js");                                                      
            $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => $basePath ."lib/scripts/jqplot/plugins/jqplot.enhancedLegendRenderer.min.js");      
            $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => $basePath ."lib/scripts/jqplot/plugins/jqplot.pieRenderer.min.js");      
            $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => DOKU_BASE."lib/plugins/tableplot/table2Plot.js"); 
       }
    }
}