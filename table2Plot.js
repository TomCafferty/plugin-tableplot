/******************************************************************************
 *
 * jquery.graphTable2-0.1.js
 * by rebecca murphey 
 * http://blog.rebeccamurphey.com
 * rmurphey gmail com
 * License: GPL
 * 17 December 2007
 *
 * table2Plot.js
 * by tom cafferty
 * http://www.glocalfocal.com
 * tcafferty glocalfocal com
 * License: GPL
 * 03 December 2011
 *
 * requires: 
 *
 *   - jquery.js (http://jquery.com) -- tested with 1.4.2
 *   - jquery.jqplot (https://bitbucket.org/cleonello/jqplot/wiki/Home)
 *
 * usage: 
 *
 *   jQuery('#myTable').tablePlot(graphTableOptionsObject,jplotOptionsObject);
 *
 *   - both arguments are optional; defaults will work in most cases
 *     but you'll need to include {series: 'columns'} if your data is
 *     in columns.
 *   - for details on tablePlot options and defaults, see below.
 *   - for details on jqlot options and defaults, see
 *     http://www.jqplot.com/
 *
 * notes:
 *   
 *   - this isn't going to work well with tables that use rowspan or colspan
 *   - make sure to use the transform args to transform your cell contents into
 *     something flot can understand -- especially important if your cells
 *     contain currency or dates
 *
 ******************************************************************************/

(function($) { 
 
 $.fn.tablePlot = function(graphArgs_,plotArgs_) {

    var args = {
      /* 
       * options for reading the table -- defaults will work in most cases except
       * you'll want to override the default args.series if your series are in columns 
       * 
       * note that anywhere the word "index" is used, the count starts from 0 at
       * the top left of the table 
       *
       */
      series: 'rows', // are the series in rows or columns?
      labels: 0, // index of the cell in the series row/column that contains the label for the series
      xaxis: 0, // index of the row/column (whatever args.series is) that contains the x values
      firstSeries: 1, // index of the row/column containing the first series
      lastSeries: null, // index of the row/column containing the last series; will use the last cell in the row/col if not set
      dataStart: 1, // index of the first cell in the series containing data
      dataEnd: null, // index of the last cell in the series containing data; will use the last cell in the row/col if not set

      /* graph size and position */
      position: 'after', // before the table, after the table, or replace the table
      width: null, // set to null to use the width of the table
      height: null, // set to null to use the height of the table
      min: 0, // defaults to minimum y value in the table
      max: 0, // defaults to maximum y value in the table

      /* data transformation before plotting */
      dataTransform: null, // function to run on cell contents before passing to flot; string -> string
      labelTransform: null, // function to run on cell contents before passing to flot; string -> string
      xaxisTransform: null, // function to run on cell contents before passing to flot; string -> string
    }

    // override defaults with user args
    $.extend(true,args,graphArgs_);
    
    /* default to last cell in the row/col for 
     * lastSeries and dataEnd if they haven't been set yet */

    // index of the row/column containing the last series
    if (! args.lastSeries) {
      args.lastSeries = (args.series == 'columns') ? 
        $('tr',$(this)).eq(args.labels).find('th,td').length - 1 : 
        $('tr',$(this)).length - 1;  
    }

    // index of the last cell in the series containing data
    if (! args.dataEnd) {
      args.dataEnd = (args.series == 'rows') ? 
        $('tr',$(this)).eq(args.firstSeries).find('th,td').length - 1:
        $('tr',$(this)).length - 1;
    }

    return $(this).each(function() {
      // use local min/max for y of each graph, based on initial args
      var $table = $(this);

      // make sure the table is a table!
      if (! $table.is('table')) { return; }

      // if no height and width have been set, then set 
      // width and height based on the width and height of the table
      if (! args.width) { args.width = $table.width(); }
      if (! args.height) { args.height = $table.height(); }

      var min = args.min;
      var max = args.max;
      var $rows = $('tr',$table);
      var line1 = new Array();
      var yVal = new Array();

      switch (args.series) {
          //
          // ** WARNING ** 
          // This selection (rows) not supported.  This section of code needs to be updated to translate
          // the original flot parameters into the new plot software jplot parameters.
          //
        case 'rows':

          var $xaxisRow = $rows.eq(args.xaxis);
  
          // iterate over each of the rows in the series
          for (i=args.firstSeries;i<=args.lastSeries;i++) {
            dataIndex = i-args.firstSeries;
            line1[dataIndex] = new Array();

            $dataRow = $('tr',$table).eq(i);

            // get the label for the whole row
            var label = $('th,td',$dataRow).eq(args.labels).text();

            if (args.labelTransform) { label = args.labelTransform(label); }

            for (j=args.dataStart;j<=args.dataEnd;j++) {
              var x = $('th,td',$xaxisRow).eq(j).text();
              var y = $('th,td',$dataRow).eq(j).text();

              if (args.dataTransform) { y = args.dataTransform(y); }
              if (args.xaxisTransform) { x = args.xaxisTransform(x); }
              
              if (args.orient == 'vertical')
                line1[dataIndex][line1[dataIndex].length] = [x, y];
              else {
                yVal[line1[dataIndex].length] = x;
                line1[dataIndex][line1[dataIndex].length] = [y, j];
              }
            }
          }
          break;

        case 'columns':
          // iterate over each of the columns in the series
          var $labelRow = $rows.eq(args.labels);

          for (j=args.firstSeries;j<=args.lastSeries;j++) { // j designates the column
            dataIndex = j-args.firstSeries;
            line1[dataIndex] = new Array();

            var label = $labelRow.find('th,td').eq(j).text();
            if (args.labelTransform) { label = args.labelTransform(label); }

            for (i=args.dataStart;i<=args.dataEnd;i++) { // i designates the row
              $cell = $rows.eq(i).find('th,td').eq(j);
              var y = $cell.text();
              var x = $rows.eq(i).find('th,td').eq(args.xaxis).text();

              if (args.dataTransform) { y = args.dataTransform(y); }
              if (args.xaxisTransform) { x = args.xaxisTransform(x); }
                            
              if (args.orient == 'vertical')
                line1[dataIndex][line1[dataIndex].length] = [x, y];
              else {
                yVal[line1[dataIndex].length] = x;
                line1[dataIndex][line1[dataIndex].length] = [y, i];
              }
            }
          }
          break;
      }

      var divid = 'chartdiv_' + args.id;
      var divstr = '<div id="'+divid+'" class="plot-graph" style="width:'+args.width+'px; height:'+args.height+'px;"></div>';
      if (args.placement) {
          $div = $("#"+args.placement);
      } else 
      switch (args.position) {
        case 'after':
          $div = $table.after(divstr).next('div');
          break;

        case 'replace':
          $div = $table.after(divstr).next('div');
          $table.remove();
          break;

        default:
          $div = $table.before(divstr).prev('div');
          break;
      }
      var plotArgs;
      if (args.orient == 'vertical')
         var plotArgs = {axes: {xaxis: {renderer: $.jqplot.CategoryAxisRenderer, tickOptions: {angle: -30}}, yaxis: {autoscale:true,}}};
      else
         var plotArgs = {axes: {xaxis: {autoscale:true, tickRenderer: $.jqplot.CanvasAxisTickRenderer}, yaxis:{renderer:$.jqplot.CategoryAxisRenderer, ticks: yVal}}};      
      $.extend(true,plotArgs,plotArgs_);
         
      plot1 = $.jqplot(divid, line1, plotArgs);

    });
  };

})(jQuery);
