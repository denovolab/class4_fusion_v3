<?php

$name = $_GET['name'];

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Content-type:text/xml");

echo <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<settings>
  <!-- [10] (Number) the distance between the border and content (plot area, period selector, etc -->
  <margins>15</margins>                                                   
  
  <!-- [Arial] (font name) use device fonts, such as Arial, Verdana, Time New Roman, etc  -->
  <font></font>                                                         
  
  <!-- [11] (Number) -->
  <text_size></text_size>                                               
  
  <!-- [#000000] (hex code) -->
  <text_color></text_color>
  
  <!-- [false] (true / false) if your chart's width or height is set in percents, and redraw is set 
  to true, the chart will fit then screen when resizing it -->  
  <redraw></redraw>     
  
  <!-- [false] (true / false) if set to true, a unique timestamp will be added to data file name 
  everytime when loading data. This helps to avoid data caching -->
  <add_time_stamp></add_time_stamp>                                                

  <!-- [150] (Number) The maximum number of series. If in the selected period there are more series
  then is set here, the chart will show data for a longer period - week, month, year. You can 
  specify which value of the period should be used separately for every graph (look for
  <period_value>) in graph's settings. If you want to show all series, set 0. Using a lot of series
  will slow down scrolling and other interactivity -->                                                                     
  <max_series></max_series>
  
  <!-- [8] (Number) The maximum number of vertical grid lines and x axis values. The wider your
  chart is, the bigger number can be set here. If you set too big number, the x axis values may
  overlap. -->
  <max_grid_count></max_grid_count>  
  
  <!-- [true] (true / false) If this setting is set to true, series will be placed next to each 
  other at equal intervals, even your data is not periodical. If you values are at irregular 
  intervals and you want this to be visualized on the chart, set this setting to false -->                                        
  <equal_spacing>false</equal_spacing>
  
  <!-- [true] (true / false) In case <equal_spacing> is set to true, and one of your graph's type is
  "column", then you might want first and last columns to be fully visible. Then set this setting to
  "false". Otherwise only half of first and last column will be visible -->
  <start_on_axis></start_on_axis>    

  <!-- [true] (true / false) In case you don't use any flash - JavaScript communication, you shuold
  set this setting to false - this will save some CPU. -->
  <js_enabled></js_enabled>
  
  <!-- [true] (true / false) In case you don't want the chart to be interactive, set this to false -->
  <interactive></interactive>
  
  <!-- [top] (top / left / right) The position of the legend -->
  <legend_position></legend_position>
  
  <!-- [150] (Number) Width of a legend in case it is positioned to the left or right -->
  <legend_width></legend_width>
    
  <!-- [false] (true / false) Whether hand and resize cusrsors should be used. -->
  <disable_custom_cursors></disable_custom_cursors>
  
  <!-- [false] (true / false) This setting affects the chart only if your data contains minutes or seconds.
  If this is set to false, the chart will try to group data to 10 and 30 sec or min intervals. Otherwise 
  the chart will group to full perios only (seconds -> minutes -> hours...) -->
  <group_to_full_periods_only></group_to_full_periods_only>
  
  <!-- [true] (true / false) If you set this to flase, the chart won't group data into weeks -->
  <group_to_weeks></group_to_weeks>
  
  <data_reloading>
    <!-- [0] (Number) In case you want chart's data to be reloaded at some interval, set number
    bigger then 0 here. Data will be reloaded every x seconds. Note, you can also trigger data 
    reload using javascript function -->
    <interval></interval> 
    
    <!-- [true] (true / false) if set to true, preloader and "processing data" text will be 
    displayed while reloading and processing data -->
    <show_preloader></show_preloader>
    
    <!-- [false] (true / false) if set to true, after every reload default time period (defined in
    <period_selector>) will be selected. Otherwise currently selected period will remain. -->
    <reset_period></reset_period>
  </data_reloading>  


  <!-- these settings affects numbers which are displayed on the chart, not the one in data file -->
  <number_format>  
    <!-- [,] (separator) -->
	  <decimal_separator></decimal_separator>
	  
	  <!-- [ ] (separator) use "none" if you don't want thousands to be separated -->
	  <thousand_separator></thousand_separator>
	  
    <!-- defines how many numbers should be left after comma --> 	  
	  <digits_after_decimal>
       <!-- [2] (Number) the number of digits after decimal for percent values -->                                                  
	     <percents></percents>
	     
	     <!-- [] (Number) number of digits after decimal for values from data file. In case you don't 
       set any number here (this is default) then values from data file will not be rounded -->
	     <data></data>
	     
	     <!-- [2] (Number) Then the number of series in the selected period exceeds <max_series>, you
       can show averages of the period. This setting sets the number of decimals for averages -->
       <averages></averages>       
    </digits_after_decimal>

    <!-- Then you have a really big numbers, it might be comfortable to replace zeros with letters 
    In case you don't want letters at all, delete the whole setting group. You can delete the line
    you don't need or add more lines with bigger numbers. Letters are added only for axis' values -->    
    <letters>
       <letter number="1000">K</letter>
       <letter number="1000000">M</letter>
       <letter number="1000000000">B</letter>
    </letters>      
    
    <!-- [0.000001] (Number) If absolute value of your number is equal or less then scientific_min, 
    this number will be formatted using scientific notation, for example: 0.0000023 -> 2.3e-6 -->
    <scientific_min></scientific_min>
    
    <!-- [1000000000000000] If absolute value of your number is equal or bigger then scientific_max,
    this number will be formatted using scientific notation, for example: 15000000000000000 -> 1.5e16 -->
    <scientific_max></scientific_max>    
  </number_format>

  <!-- the chart can have any number of data sets. Data set defines your data source. You can have 
  lot's of different data sets. Your user will be able to choose desired data set from a drop down
  or to compare one data set with another. -->
  <data_sets> 
    <!-- start of data set settings -->    
    <data_set did="0">
       <!-- [] (text) data set title is displayed in a drop down for selecting data set, in a 
       list box for comparing one data set with another and may be displayed in a chart's header -->
       <title>East Stock</title>
       
       <!-- [] (text) short title can be displayed in the legend of a chart -->
       <short>ES</short>
       
       <!-- [] (hex code) this color will be used for chart's graphs, in case you don't set different
       color in graphs' settings. The color keys in the drop down, list box and in the legend will 
       use this color. In case you don't set any color here, the colors from this array will be used:
       #ff3300,#3366cc,#00cc33,#ffcc00,#ff0099,#003366,#669933,#cc99cc,#333333,#99ff00,[random color]  -->
       <color></color>
       
       <!-- [] (text) description can be displayed in chart's header. you can use some html tags -->		  
       <description><![CDATA[]]></description> 
       
       <!-- [] (file name) file name of your data file. You can use any extension, generate this 
       data dynamicaly. The output of this file should be data in CSV format -->
       <file_name>{$name}.csv</file_name>
       
       <!-- [] (file name) Using events you can have custom bullets with descriptions on your charts.
       Event properties are defined separately for every chart. Check examples/events/ example. 
       You can also include the events directly to this settings file. If you do this, the events
       from the file will not be loaded. -->
       <events_file_name></events_file_name>
       
       <!-- [] (file name) Name of file with trend lines xml. You can also include the trend lines
       directly to this settings file. If you do this, the trend lines from the file will not be 
       loaded. Check trend_lines.xml file for the trend line xml structure -->
       <trend_lines_file_name></trend_lines_file_name>       
      
       <!-- [true] (true / false) if you have more then one data set, the user can switch between
       them using drop down for selecting data sets. If you don;t want this data set to appear in 
       this dropdown, set this setting to "false". "selected" attribute defines whether you want 
       this data set to be selected when the chart is loaded (if no data sets are selected, then 
       the first data set will be) -->
       <main_drop_down selected="true"></main_drop_down> 	    	  
       
       <!-- [true] (true / false) if you have more then one data set, the user can compare selected
       data set with others. If you don't want this data set to appear in a "compare" list box, set
       this setting to false. "selected" attribute defines whether you want this data set to be 
       selected for comparing when the chart is loaded -->
       <compare_list_box selected="false"></compare_list_box>
       
       <!-- configuration of data csv file. Using these settings you can make the chart to accept
       almost any configuration of csv file -->   
       <csv>
         <!-- [false] (true / false) If this is set to false, then the oldest date should be on the
         top and increase when going down. If you newest date is on the top, set this to "false" -->
         <reverse>true</reverse>
         
         <!-- [;] (separator) Column separator of csv file -->
         <separator>,</separator>
         
         <!-- [0] (Number) In case you have some header in your csv file, or you have some rows
         which shouldn't be included, you can set how many rows should be skipped -->
         <skip_first_rows></skip_first_rows>
         
         <!-- [] some csv files contains double quotes, or numbers are exported with thousands separators.
         The data parser of stock chart can't parse such numbers. This setting will help you to get rid
         of unnecessary symbols. Simply add them (do not separate in any way) here. -->
         <strip_symbols></strip_symbols>         
         
         <!-- [0] (Number) You can set how many rows from the bottom of data csv should be skipped -->         
         <skip_last_rows></skip_last_rows>
         
         <!-- [] (Number) You can set the number of rows, counting from the bottom of the data csv
         should be used for your data-->         
         <show_last_rows></show_last_rows>
         
         <!-- [YYYY-MM-DD] (date format) The valid symbols are: YYYY, MM, DD, hh, mm, ss, fff. Any order
         and separators can be used, for example: DD-MM-YYYY, YYYY-MM, YYYY-DD-MM hh, 
         DD-MM-YY hh:mm:ss, etc. fff means milliseconds. In case you use miliseconds, you must provide the
         3 digit number -->
         <date_format>YYYY-MM-DD hh:mm:ss</date_format>
         
         <!-- [.] (separator) Decimal separator in your csv file -->
         <decimal_separator>.</decimal_separator>
         
         <!-- columns define columns of your data file. There should be at least two columns, and 
         one of them should be defined as "date". For simple line chart it will be enough to have
         these two columns, for a candlestick chart you should have 5 columns (date, open, close,
         high, low) Any names can be used (only "date" column name can't be different) -->
         <columns>
           <column>date</column>
           <column>close</column>
         </columns>
         
         <!-- [] (data in csv format) If you fon't want the data to be loaded from a different file,
         you can place it here -->
         <data></data>      
       </csv>         
       <!-- EVENTS can be included here or loaded form a separate XML file. To check events syntax,
       open events.xml file -->
       <events></events>
       
       <!-- TREND LINES can be included here or loaded form a separate XML file. To check trend lines 
       XML syntax, open trend_lines.xml file -->
       <trend_lines></trend_lines> 
             
    </data_set>
    <!-- end of first data set-->
    
    <!-- more data sets can be added here -->
                                                                                     
  </data_sets>

  <!-- You can have one, two, three or even more charts. For example, one chart shows stock price
  and another - stock volume. -->
  <charts>
  
    <!-- first chart-->
  	<chart cid="0">
		<!--[] (Number) the relative height of your chart. If you have one chart, it will take 100% of
		plot area. If you do not set any number, the space for your charts will be divided equally -->
		<height>60</height>

		<!-- [] (text) chart's title is displayed above your chart, in the left -->
		<title>Value</title>

		<!-- [#FFFFFF] (hex code) Chart background color. Separate color codes with commas if you want
		to have gradient background-->
		<bg_color></bg_color>

		<!-- [100] (0 - 100) background opacity -->
		<bg_alpha></bg_alpha>

		<!-- [#CCCCCC] (hex code) Chart border color -->
		<border_color>#CCCCCC</border_color>

		<!-- [100] (0 - 100) border opacity -->
		<border_alpha>100</border_alpha>
      <!-- GRID -->
      <grid>
        <x>
          <!-- [true] (true / false) whether to show vertical grid or not -->
          <enabled></enabled>
          
          <!-- [#000000] (hex code) vertical grid color -->
          <color></color>
          
          <!-- [15] (0 - 100) vertical grid opacity -->
          <alpha></alpha>
          
          <!-- [false] (true / false) note, dashed lines affects performance -->
          <dashed></dashed>    
          
          <!-- [5] (Number) -->
          <dash_length></dash_length>
        </x>
        
        <y_left>
          <!-- [true] (true / false) whether to show grid on this axis or not. Grid will be shown 
          only if at least one graph is assigned to this axis -->                  
          <enabled></enabled>
          
          <!-- [#000000] (hex code) grid color -->
          <color></color>
          
          <!-- [15] (0 - 100) grid opacity -->
          <alpha></alpha>
          
          <!-- [false] (true / false) note, dashed lines affects performance -->
          <dashed></dashed>
          
          <!-- [5] (Number) -->    
          <dash_length></dash_length>
          
          <!-- [5] (Number) Approximate number of grid lines -->   
          <approx_count></approx_count>
          
          <!-- [#FFFFFF] (hex code) Every second area between grid lines will be filled with this color (remember to set fill_alpha > 0) -->
          <fill_color></fill_color>
          
          <!-- [0] (0 - 100) opacity of fill -->
          <fill_alpha></fill_alpha>
        </y_left>
        
        <!-- y_right settings are identical to y_left -->
        <y_right>
          <enabled></enabled>
          <color></color>
          <alpha></alpha>
          <dashed></dashed>    
          <dash_length></dash_length>  
          <approx_count></approx_count>
          <fill_color></fill_color>
          <fill_alpha></fill_alpha>    
        </y_right>    
      </grid>	
      <!-- END OF GRID -->       
       
       
      <!-- VALUES are displayed near grid lines -->
      <values>
        <x>
          <!-- [true] (true / false) whether to display x axis values. If you have more then one
          chart, it would be wise to enable x axis values for one chart only -->
          <enabled>false</enabled>
          
          <!-- [text_color] (hex code) -->
          <text_color></text_color>
          
          <!-- [text_size] (hex code) -->
          <text_size></text_size>
          
          <!-- [#FFFFFF] (hex code) x axis values background color -->
          <bg_color></bg_color>
          
          <!-- [100] (0 - 100) x axis values background opacity -->
          <bg_alpha></bg_alpha>
        </x>
        
        <y_left>
          <!-- [true] (true / false) whether to show values on this axis or not. Values will be 
          shown only if at least one graph is assigned to this axis -->          
          <enabled></enabled>
          
          <!-- [] (Number / min) If you do not set any number here, the min value will be calculated 
          automatically. In case you set some value here, but the actual min value will be less,
          then this setting will be ignored. If you want to avoid "jumping" while dragging the chart,
          you can set "min" here, then the min value will be calculated using all the data, not only
          the selected period. -->
          <min></min>
          <!-- [] (Number / min) If you do not set any number here, the max value will be calculated 
          automatically. In case you set some value here, but the actual max value will be bigger,
          then this setting will be ignored. If you want to avoid "jumping" while dragging the chart,
          you can set "max" here, then the max value will be calculated using all the data, not only
          the selected period. -->          
          <max></max>
          
          <!-- [false] (true / false) by default, if your values are bigger then defined max (or 
          smaller then defined min), max and min is changed so that all the chart would fit to chart 
          area. If you don't want this, set this option to true. -->
          <strict_min_max></strict_min_max>
          
          <!-- [false] (true / false) Set this to true, if you don't want values with decimals to be
          shown-->
          <integers_only></integers_only>
          
          <!-- [text_color] (hex code) -->
          <text_color></text_color>
          
          <!-- [text_size] (Number) -->
          <text_size></text_size>
          
          <!-- [#FFFFFF] (hex code) background color of a value -->
          <bg_color></bg_color>
          
          <!-- [0] (0 - 100) background opacity -->
          <bg_alpha></bg_alpha>
          
          <!-- [] (text) unit which will be added to values on y axis-->
          <unit></unit>
          
          <!-- [right] (left / right) -->
          <unit_position></unit_position>                         
          
          <!-- defines how many numbers should be displayed after comma --> 	  
      	  <digits_after_decimal>
      	     <!-- [number_format.digits_after_decimal.data] (Number) number of digits after decimal -->
      	     <data></data>      	  
      	  
             <!-- [number_format.digits_after_decimal.percents] (Number) the number of digits after 
             decimal when the values are recalculated to percents -->                                               
      	     <percents></percents>
          </digits_after_decimal>      
          
          <!-- [] (ss/mm/hh/DD) In case you want your axis to display formatted durations instead of
          numbers, you have to set the unit of the duration in your data file. For example, if your 
          values in data file represents seconds, set "ss" here.-->
          <duration></duration>  
          
          <!-- [false] (true / false) specifies whether this value axis should use logarithmic scale.
          Note, your data can't contain values less or equal to zero if you use logarithmic scale. 
          When data sets are compared and values are recalculated to percents, logarithmic scale is not used-->
          <logarithmic></logarithmic>            
          
        </y_left>
        
        <!-- y_right settings are identical to y_left -->        
        <y_right>        
          <enabled></enabled>
          <!-- you can synchronize right axis values with left axis. for example, if you set <min>left*0.1</min> 
          your right axis' min and max values will always be 10 times less then left axis' values. -->
          <min></min>
          <max></max>
          <strict_min_max></strict_min_max>
          <integers_only></integers_only>
          <text_color></text_color>
          <text_size></text_size>
          <bg_color></bg_color>
          <bg_alpha></bg_alpha>   
          <unit></unit>
          <unit_position></unit_position>                 
      	  <digits_after_decimal>
      	     <data></data>      	  
      	     <percents></percents>
          </digits_after_decimal>
          <duration></duration> 
          <logarithmic></logarithmic>                
        </y_right>
      </values>
      <!-- END OF VALUES -->
      
     
      <!-- LEGEND legend is displayed above the chart, next to chart title -->      
      <legend>
        <!-- [true] (true / false) whether to show legend or not -->
        <enabled></enabled>
        
        <!-- [text_size] (Number) -->
        <text_size></text_size>
        
        <!-- [text_color] (hex code) -->
        <text_color></text_color>
        
        <!-- [legend.text_color] (hex code) Values in the legend can use different color -->
        <value_color></value_color>
        
        <!-- [#00CC00] (hex code) If percent change or value change is displayed in the legend, and 
        the change is positive this color will be used -->
        <positive_color></positive_color>
        
        <!-- [#CC0000] (hex code) If percent change or value change is displayed in the legend, and 
        the change is negative this color will be used -->
        <negative_color></negative_color>        
    
        <!-- [false] (true / false) whether to show active date in the legend. If you have more then 
        one chart it would be wise to set only one chart to show this date -->            
        <show_date>true</show_date>
        <!-- [false] (true / false) If you don't want the date to be shown when the mouse is not 
        over the plot area, set this to true -->
        <hide_date_on_period></hide_date_on_period>
        
        <!-- [10] (Number) the size of legend key -->
        <key_size></key_size>
        
        <!-- [round] (round / square / line) the shape of legend key -->
        <key_type></key_type>
        
        <!-- [true] (true / false) Whether it is possible to turn show/hide the graph by clicking on
        the legend key -->
        <graph_on_off></graph_on_off>
        
        <!-- [false] (true / false) Whether to reverse the order of the items in the legend -->
        <reverse_order></reverse_order>
        
        <!-- [100] (0 - 100) When the user rolls-over a legend entry key, the other graphs will
        fade to this setting so that the rolled over graph would be more visible. 10-20 is recommended  -->
        <fade_others_to></fade_others_to>
        
        <!-- [false] (true / false) Specifies if the balloon with data set title will be shown when user
        rolls-over the legend entry -->
        <show_balloon></show_balloon>
      </legend>
      <!-- END OF LEGEND -->
       	
       	
      <!-- settings of a graph line which is showed when comparing one data set with other -->
      <comparing>
        <!-- [true] (true / false) in stock charts, when comparing one stock with another it is used
        to recalculate chart values to percents, as the difference between values of different
        stocks can be very big. However, if you compare close values, for example unique visitors 
        with new visitors, it would be better not to recalculate values -->
        <recalculate></recalculate>
        
        <!-- [false] (true / false) If the <recalculate> setting is set to true, the chart recalculates
        values to percents. If this setting is set to false, then it takes the first value of a selected
        period as a base value. If you set this setting to "true", the first value of a graph will be used
        as base value. -->
        <recalculate_from_start></recalculate_from_start>
        
        <!-- [] (date in date format, specified in data set settings) You can set the base date, 
        using which value the data will be recalculated when comparing data sets. This might be useful for
        structured products. -->
        <base_date></base_date>
        
        <!-- [0] (Number) with of graph line of comparing data sets -->
        <width></width>
        
        <!-- [false] (true / false) whether the comparing graph should be dashed -->
        <dashed></dashed>
        
        <!-- [false] (true / false) whether the comparing graph should be use the same color as the one you are
        comparing to. This makes sense if you set <dashed>true</dashed> -->
        <use_graph_color></use_graph_color>
        
        <!-- [100] (0 - 100) opacity of graph line of comparing data sets -->
        <alpha></alpha>
        
        <!-- [false] (true / false) whether to smooth line or not -->
        <smoothed></smoothed>
        
        <!-- [false] (true / false) When graphs are compared and values are recalculated to percents and
        data is grouped into periods, value set in <period_value> is used as a base value to calculate
		percents. This might be a problem - imagine you want to show percental change of a stock since
		the stock started, and you use "close" for <period_value>. This means that the chart will show
		percental change since the end of first month, not since the first date of the fund. To avoid
		this, set this setting to true
		-->
		<use_open_value_as_base></use_open_value_as_base>
        
      </comparing>
      
      
      <!-- If you want to mark some of your data points with flags, signs or other bullets, you should
      define them in a separate file. Open events.xml file to see how it is done.
      When user roll-overs the flag, JS function amRolledOverEvent(date, descrition, evend_id) is called
      When user clicks the flag, JS function amClickedOnEvent(date, descrition, evend_id, url) is called
      -->
      <events>
        
        <!-- [true] (true / false) Whether to show balloon with description on roll-over the flag or not.  -->
        <show_balloon></show_balloon>
        
        <!-- [true] (true / false) Whether to show date of the event in the balloon.  -->
        <show_date></show_date>        
        
        <!-- [false] (true / false) Whether to change mouse cursor to hand on roll-over or not. -->
        <use_hand_cursor>true</use_hand_cursor>
        
        <!-- [] (_blank, _top...) If you set url for this flag, you can define target of the window in which
        this url will be opened. -->
        <url_target></url_target>
        
        <!-- [sign] (sign/flag/pin/round/square/round_outlined/square_outlined/round_outline/square_outline/
        /horizontal_line/vertical_line/horizontal_line_dashed/vertical_line_dashed) Only sign, flag and pin
        bullets can display letters inside. horizontal_line and horizontal_line_dashed will display the letter 
        (it can be some longer text) above the line -->
        <bullet></bullet>
        
        <!-- [#FFFFFF] (hex code) bullet color -->            
        <color></color>
        
        <!-- [#CC0000] (hex code) bullet color -->            
        <color_hover></color_hover>        
        
        <!-- [#ABABAB] (hex code) bullet border color -->
        <border_color></border_color>
        
        <!-- [100] (0 - 100) bullet border opacity -->
        <border_alpha></border_alpha>        
        
        <!-- [#000000] (hex code) flag and sign bullets can have letter inside. This setting defines text 
        color of the letter -->
        <text_color></text_color>
        
        <!-- [15] (Number) size of a bullet -->          
        <size></size>
        
        <!-- [] (mm / hh / DD / WW / MM / YYYY) You might want to hide the bullets if your data
        is grouped into longer periods -->
        <hide_period></hide_period>
        
        <!-- [close] (open/low/high/close) This is useful when showing events on graphs like
        candlestick or ohlc. For example, if you want your events to appear on the top of candlestick, 
        set this to "high". -->
        <show_at></show_at>
      </events>      
       	
      <!-- Chart users can have a possibility to draw trend lines on a chart. -->
      <trend_lines>
        <!-- [false] (true / false) If drawing trend lines is enabled, user will see two icons - 
        a pencil and eraser on top right corner of a chart. -->
        <drawing_enabled></drawing_enabled>
        
        <!-- [false] (true / false) erase icon can erase all trend lines at once or one at a time. 
				(when user clicks on the trend line) -->
        <erase_all></erase_all>
        
        <!-- [#000000] (hex code) color of mouse pointer which is visible when pencil icon is pressed -->
        <pointer_color></pointer_color>
        
        <!-- [#CC0000] (hex code) color of a line while drawing it -->
        <active_line_color></active_line_color>
        
        <!-- [#00CC00] (hex code) color of trend lines -->
        <line_color></line_color>
        
        <!-- [100] (0 - 100) opacity of trend lines -->
        <line_alpha></line_alpha>
        
        <!-- [0] (Number) trend line thickness -->
        <line_width></line_width>
        
        <!-- [0] (Number) the length of a dash -->
        <dash_length></dash_length>
        
        <!-- [left] (left / right) axis of the chart to which trend lines will be assigned -->
        <axis></axis>
      </trend_lines>
      
      <!-- [90] (0 - 100) if you have "column", "candlestick" or "ohlc" graphs, you should set 
      relative width of a column. If your setting <equal_spacing> is set to false, it would be
      better to set this setting to 100. If you want to have thin vertical lines, set 0 -->
      <column_width></column_width>
         	
         	
      <!-- GRAPHS. One chart can have a lot of graphs -->
  		<graphs>
				<!-- first graph -->
				<graph gid="0">
				
					<!-- [left] (left / right) axis of this graph -->
					<axis></axis>
					
					<!-- [line] (line / column / candlestick / ohlc / step / step_no_risers ) the type of this graph -->
					<type></type>
					
					<!-- [true] (true / false) whether to connect data points if there are some missing data between
					them. This applies for line and step line graph types only. -->
					<connect></connect>
					
					<!-- data sources define which column from data CSV file will be used. 
					line, column  and step charts requires <close> data source only
					candlestick and ohlc charts requires <open>, <close>, <low> and <high> data sources -->
					<data_sources>
						<close>close</close>
					</data_sources>
					
					<!-- [close] (close / open / low / high / sum / average) if in the selected period there
					are more series then it is set in <max_series>, then the chart will show data for a longer
					period. This setting sets which value of this longer period should be used. In stock
					charting it is used that "close" value is shown for value chart, and "average" value for
					volume chart. If you use this chart for showing statistics, such as unique visitors, then
					you would probably want to show total number of visitors. Set "sum" for this case. Sum and
					average  are calculated only for <close> data source -->
					<period_value></period_value>
					
					<!-- [] (the name of column) you should set which column of your data sets will be used
					for "comparing" graph. Do not set anything here if this graph doesn't need to be compared -->
					<compare_source>close</compare_source>
					
					<!-- [] (text) title of the graph can be displayed in the legend -->
					<title></title>
					
					<!-- [data_set.color] (hex code) by default, graph uses data set color. However, as you
					can have more then one graph from one data set, you can set different color here -->
					<color></color>
					
					<!-- [data_set.color]  (hex code) -->
					<cursor_color></cursor_color>
					
					<!-- [100]  (0 - 100) opacity of the cursor-->
					<cursor_alpha></cursor_alpha>  				
					
					<!-- [#00CC00] (hex code) "candlestick" and "ohlc" chart types do not use graph color, they
					use positive and negative colors to show whether value of the period increased or decreased -->
					<positive_color></positive_color>
					<!-- [#CC0000] (hex code) -->
					<negative_color></negative_color>
					
					<!-- [0] (Number) width of a line (in case type is "line", "step" or "ohlc"), also for
					high/low of "candlestick". If you set column_width to 0, vertical lines will also use this
					width -->
					<width></width>
					
					<!-- [100] (0 - 100) graph opacity -->
					<alpha></alpha>
					
					<!-- [0] (0 - 100) opacity of fill -->
					<fill_alpha></fill_alpha>
					
					<!-- [false] (true / false) whether the graph should be dashed -->
					<dashed></dashed>
					
					<!-- [0] (Number, Number%) corner radius of the columns -->
					<corner_radius></corner_radius>
					
					<!-- [] (round / square / round_outline / square_outline / round_outlined / square_outlined
					only "line" and "step" graph types can have bullets -->
					<bullet></bullet>
					
					<!-- [6] (Number) -->
					<bullet_size></bullet_size>
					
					<!-- [graph.color] (hex color code) -->
					<bullet_color></bullet_color>  		
					
					<!-- [graph.alpha] (0 - 100) -->
					<bullet_alpha></bullet_alpha>
					
					<!-- [middle] (start / middle / end) Position of the bullet. This affects only "step" and 
					"step_no_risers" graphs. -->
					<bullet_position></bullet_position>  				
					
					<!-- [] (Number) column width (if type is "column", in pixels (you should set this only if you need the
					column to be of a fixed width) -->
					<column_width></column_width>
					
					<!-- [false] (true / false) whether to smooth line or not -->
					<smoothed></smoothed>
					
					<!-- [] (gid of another graph) You can stack this graph to another. Graphs can not be smoothed.
					The graph to which you are stacking must be described above the graph which you are stacking. -->
					<stack_to></stack_to>
					
					<!-- [false] (true / false) whether the graph is visible or not (the user can show / hide the
					graph by clicking on the legend key) -->
					<hidden></hidden>
					
					<!-- [true] (true / false) whether to include this graph when calculating min and max values or not -->
					<min_max></min_max>
					
					<!-- the following section describes what information should be shown in the legend. There
					are 4 different states:
					1) mouse is over plot area and no data sets for comparing are selected - <date>
					2) mouse is away from plot area and no data sets for comparing are selected - <period>
					3) mouse is over plot area and at data set for comparing is selected: - <date_comparing>
					4) mouse is away plot area and at data set for comparing is selected: - <period_comparing>
					
					"key" attribute defines whether color key should be displayed near the legend entry;
					"title" defines whether short title of the data set should be displayed near the legend entry
					
					Use names of <data_sources> to display value of a date or period:
					{open}, {close}, {high}, {low}
					
					You can aslo use {sum} and {average} to display the sum or average.
					
					If you want to show percent change of a value use the following syntax:
					{close.percents}
					
					If you want to show percent change of a value comparing to a previous value, use:
					{close.percents.single}
					
					If you want to show value change since the beginning of selection, use:
					{close.change}
					
					If you want to show value change comparing with previous value, use:
					{close.change.single}
					
					Note, that the legend is not wrapped, so you should think about the length of your legend -->
					<legend>
						<date key="false" title="false"><![CDATA[{close}]]></date>
						<period key="false" title="false"><![CDATA[open:<b>{open}</b> low:<b>{low}</b> high:<b>{high}</b> close:<b>{close}</b>]]></period>
						<date_comparing key="false" title="false"><![CDATA[]]></date_comparing>
						<period_comparing key="false" title="false"><![CDATA[]]></period_comparing>
						
						<!-- [false] (true / false) If you set this to true, this graph will not have legend
						entry, only comparing graphs will. -->
						<comparing_only></comparing_only>
						
						<!-- [false] (true / false) If you set it to true, values in the legend will be formatted
						using <number_format><letters>. This allows formatting big numbers with letters and save
						some space in the legend. Do not forget to set <digits_after_decimal><data> so that the numbers
						would be rounded. -->
						<use_letters></use_letters>
						
						<!-- defines how many numbers should be displayed after comma -->
						<digits_after_decimal>
							<!-- [number_format.digits_after_decimal.data] (Number) number of digits after decimal -->
							<data></data>
							
							<!-- [number_format.digits_after_decimal.percents] (Number) the number of digits after
							decimal when the values are recalculated to percents -->
							<percents></percents>
						</digits_after_decimal>
					</legend>
				</graph>  			
  		</graphs>  
  	</chart>  
    <!-- end of first chart -->
    
    <!-- more charts can be added here -->    
 
  </charts>
  
  
  <!-- DATE FORMATS define how dates should be formatted. There are three places where dates are 
  displayed: on x axis, in the legend and in the event balloon. Different time intervals require different 
  date formatting, that's why you need to set a separate date format for every interval for x axis and for
  the legend. "month" will be replaced with name of the month. Names of the months can be defined in <strings>
  section. "WW" will be replaced with a name of a weekday. Names of the weekdays can be defined in <strings>
  section. For milliseconds, you can use fff or FFF. FFF format will cut the zeroes from the end of the number
  so you will be able to display milliseconds like: 55.1 instead of 55.100 -->
  
  <date_formats>
  
    <!-- [1] (0 - 6) the day when week starts. 0 - Sunday, 1 - Monday, 2 - Tuesday.... -->
    <first_week_day></first_week_day>
  
    <!-- [24] (12 / 24) The time in the legend and x axis might be displayed using 12 or 24 hour format -->
    <hour_format></hour_format>        
    
     <x_axis>
       <!-- [hh:mm:ss] -->
       <seconds></seconds>
       
       <!-- [hh:mm] -->
       <minutes></minutes>
       
       <!-- [hh:mm] -->   
       <hours></hours>
       
       <!-- [month DD] -->
       <days></days>
       
       <!-- [month] -->
       <months></months>
       
       <!-- [YYYY] -->
       <years></years>
     </x_axis>
     <legend>
       <!-- [hh:mm:ss.FFF] -->
       <milliseconds></milliseconds>     
       
       <!-- [hh:mm:ss] -->
       <seconds>hh:mm:ss</seconds>
       
       <!-- [hh:mm] -->  
       <minutes></minutes>
       
       <!-- [hh:mm] -->   
       <hours></hours>
       
       <!-- [month DD, YYYY] -->
       <days>month DD, YYYY</days>
       
       <!-- [week of month DD, YYYY] -->
       <weeks></weeks>
       
       <!-- [month YYYY] -->
       <months></months>
       
       <!-- [YYYY] -->       
       <years></years>
     </legend>
     <!-- [uses data_set date format] -->
     <events></events>
  </date_formats>  

 
  
  
  
  <!-- PERIOD SELECTOR defines style, position and predefined periods for period selector -->
  <period_selector>
  
    <!-- [true] (true / false) whether to show period selector or not -->
    <enabled></enabled>
    
    <!-- [] (combination of YYYY, MM, DD, hh, mm, ss) The date format of date input fields.
    The data set date format will be used if not set here. -->
    <date_format></date_format>
    
    <!-- [true] (true / false) whether to show custom period input fields or not -->
    <custom_period_enabled></custom_period_enabled>    
    
    <!-- [bottom] (top / bottom) period selector position -->
    <position>bottom</position>
   
    <!-- [text_size] (Number) -->
    <text_size></text_size>
    
    <!-- [text_color] (hex code) -->
    <text_color></text_color>
    
    <!-- [true] (true / false) whether to hide predefined periods if there is no data for such period -->
    <hide_longer_periods></hide_longer_periods>   
    
    <button>
      <!-- [#FFFFFF] (hex code) -->
      <bg_color></bg_color>
      
      <!-- [100] (0 - 100) opacity of background -->
      <bg_alpha></bg_alpha>      
      
      <!-- [#ABABAB] (hex code) -->
      <bg_color_hover></bg_color_hover>
      
      <!-- [#ABABAB] (hex code) -->
      <bg_color_selected></bg_color_selected>
      
      <!-- [period_selector.text_size] (Number) -->
	    <text_size></text_size>
	    
	    <!-- [period_selector.text_color] (hex code) -->
      <text_color></text_color>
      
      <!-- [period_selector.text_color] (hex code) -->
      <text_color_hover></text_color_hover>
      
      <!-- [period_selector.text_color] (hex code) -->
      <text_color_selected></text_color_selected>
      
      <!-- [#ABABAB] (hex code) -->
      <border_color></border_color>
      
      <!-- [#ABABAB] (hex code) -->
      <border_color_hover></border_color_hover>
      
      <!-- [#ABABAB] (hex code) -->
      <border_color_selected></border_color_selected>
      
      <!-- [0] (Number) -->
      <border_width></border_width>
      
      <!-- [3] (Number) -->
      <corner_radius></corner_radius>     
    </button>
  
    <input>
    
      <!-- [#FFFFFF] () -->
      <bg_color></bg_color>
      
      <!-- [period_selector.text_size] (Number) -->
      <text_size></text_size>
      
      <!-- [period_selector.text_color] (hex code) -->
      <text_color></text_color>
      
      <!-- [#ABABAB] (hex code) -->
      <border_color></border_color>
      
      <!-- [0] (Number) -->
	    <border_width></border_width>
	    
      <!-- [3] (Number) -->
      <corner_radius></corner_radius>
    </input>
  	
  	<!-- The following section defines predefined periods. You can have any number of predefined 
    periods (buttons are not wrapped, so check whether they fit). 
    "type" attribute defines period type. Available period types are: ss, mm, hh, DD, MM, YYYY, 
    YTD (Year to date) and MAX. "count" defines how many periods this button will select. For example:
    <period type="MM" count="3">3M</period> this button will show data for last 3 months. 
    "MAX" period shows full range. If you set count="0" for "YTD" type, then data will be shown from 
    the last available date of previous year. If you set count="1" - from the first date of this year.
    If you set longer period when you have in the data file, this button will not be shown. One of the 
    periods can have selected="true" attribute - this period will be selected when chart is loaded.    
    -->
		<periods>		
      <period type="DD" count="10">10D</period>
    	<period type="MM" count="1">1M</period>
    	<period type="MM" count="3">3M</period>
    	<period selected="true" type="MM" count="6">6M</period>
    	<period type="YYYY" count="1">1Y</period>
    	<period type="YYYY" count="3">3Y</period>
    	<period type="YTD" count="0">YTD</period>
    	<period type="MAX">MAX</period>
		</periods>
		
		<!-- [true] (true / false) If you set this to false, then the period buttons, except the one with 
    type="YTD", will select the period from the beginning, otherwise - from the end -->
		<zoom_to_end></zoom_to_end>
		
		<!-- [] (text) -->
		<periods_title>Zoom:</periods_title>
		
		<!-- [] (text) -->
		<custom_period_title>Custom period:</custom_period_title>
	
    <!-- [] (date) You can set start date from which the chart will be shown when loaded 
    (the selected predefined period will be ignored if the date is set here) -->	
		<from></from>
		
    <!-- [] (date) You can set end date util which the chart will be shown when loaded 
    If you set "from" date and do not set "to" date, the last date of the data set will be used -->	
		<to></to>	
  
  </period_selector>
  
  <!-- HEADER can display data set title, short title and description -->
  <header>
    <!-- [true] (true / false) -->
    <enabled>false</enabled>
    
    <!-- [] (text) -->
    <text><![CDATA[<b>{title}</b> ({short}) {description}]]></text>
    
    <!-- [0] (Number) -->
    <margins></margins>
    
    <!-- [text_size] (Number) -->
    <text_size>13</text_size>
    
    <!-- [text_color] (hex code) -->
    <text_color></text_color>
    
    <!-- [#FFFFFF] (hex code) -->
    <bg_color></bg_color>
    
    <!-- [0] (Number) -->
    <bg_alpha></bg_alpha>
    
    <!-- [0] (Number) -->
    <border_alpha></border_alpha>
    
    <!-- [#ABABAB] (hex code) -->
    <border_color></border_color>
    
    <!-- [0] (Number) -->
    <border_width></border_width>
    
    <!-- [0] (Number) -->
    <corner_radius></corner_radius>
  </header>

  
  <!-- Balloon is displayed on roll-over the custom bullet (event) -->
  <balloon>
  
    <!-- [#FFFFFF] (hex code) -->
    <bg_color></bg_color>
    
    <!-- [90] (hex code) -->
    <bg_alpha></bg_alpha>
    
    <!-- [#000000] (hex code) -->
    <text_color></text_color>
    
    <!-- [text_size] (Number) -->
    <text_size></text_size>
    
    <!-- [#b81d1b] (hex code) -->
    <border_color></border_color>
    
    <!-- [2] (Number) -->
    <border_width></border_width>
    
    <!-- [100] (0 - 100) -->
    <border_alpha></border_alpha>
    
    <!-- [5] (Number) -->
    <corner_radius></corner_radius>  
  </balloon>


  <!-- BACKGROUND -->
  <background>    
    <!-- [#FFFFFF] (hex code) -->                                
    <color></color>
    
    <!-- [0] (Number) -->
    <alpha></alpha>      
    
    <!-- [#000000] -->       
    <border_color></border_color>
    
    <!-- [0] (Number)-->
    <border_alpha></border_alpha>
    
    <!-- [] (file name) The file name of a custom swf, jpg, gif or png image which
    will be loaded for the background. This file must be located in the "path" folder -->
    <file></file>
               
  </background>    

  <!-- PLOT AREA -->
  <plot_area>
    <!-- [0] (Number) -->
    <margins></margins>
    
    <!-- [#FFFFFF] (hex code) -->
    <bg_color></bg_color>
    
    <!-- [0] (Number) -->
    <bg_alpha></bg_alpha>
    
    <!-- [0] (Number) -->
    <border_alpha></border_alpha>
    
    <!-- [0xABABAB] (hex code) -->
    <border_color></border_color>
    
    <!-- [0] (Number) -->
    <border_width></border_width>
    
    <!-- [0] (Number) -->
    <corner_radius></corner_radius>
  </plot_area>
  
  <!-- CURSOR -->
  <cursor>
  	<!-- [true] (true / false) if set to false, a vertical cursor will appear,
		and instead of panning user will be able to select and zoom-in desired time period. 
		There is a separate javascript method to change this: flashMovie.setCursorMode("pan"/"zoom"). -->
		<pan></pan>
		
		<!-- [#CC0000] (hex code) color of vertical line and selection ( visible only when pan is set to false) -->
		<color></color>
		
		<!--[20] (Number) Opacity of a selected area (visible when pan is set to false and user is selecting 
		some period to zoom-in) -->
		<selection_alpha></selection_alpha>
  </cursor>
  
  <!-- SCROLLER -->
  <scroller>
    <!-- [true] (true / false) -->
    <enabled>true</enabled>
    
    <!-- [true] (true / false) -->
    <mouse_wheel_enabled></mouse_wheel_enabled>
    
    <!-- [true] (true / false) whether to connect the data points with a line when some data is missing
    between them -->
    <connect></connect>    
    
    <!-- [1] (Number) If your data set has a lot of values the scroller might significantly slow down 
    the whole chart. (Provided you have some graph in the scroller). This setting can help solve the 
    problem. I.e. if you set this setting to 5, the graph in the scroller will take only every 5th 
    data point. Other values will be skipped. -->
    <frequency></frequency>
        
    <!-- [45] (Number) -->
    <height></height>    
    
    <!-- [] (column name) scrollers graph's data source -->
    <graph_data_source>close</graph_data_source>

    <!-- [#EEEEEE] (hex code) -->                                       
    <bg_color></bg_color>
    
    <!-- [100] (0 - 100) -->                          
    <bg_alpha></bg_alpha>
    
    <!-- [#FFFFFF] (hex code)  -->
    <selected_color></selected_color>
    
    <!-- [line / step] The scroller graph type -->
    <graph_type></graph_type>
    
    <!-- [#ABABAB] (hex code) color of not selected graph -->
    <graph_color></graph_color>
    
    <!-- [50] (Number) -->
    <graph_alpha></graph_alpha>
    
    <!-- [0] (Number) -->
    <graph_width></graph_width>
    
    <!-- [20] (Number) -->
    <graph_fill_alpha></graph_fill_alpha>
    
    <!-- [data_set.color] (hec code) -->    
    <graph_selected_color></graph_selected_color>
    
    <!-- [50] (Number) -->
    <graph_selected_alpha></graph_selected_alpha>
    
    <!-- [20] (Number) -->
    <graph_selected_fill_alpha></graph_selected_fill_alpha>
    
    <!-- [graph_selected_color] (hex code) The selected part of a graph can be filled with some color. 
    If you separate two color codes with a comma here, the first one will be used to fill the part above 
    the 0 and the second one - below the 0 -->
    <graph_selected_fill_color></graph_selected_fill_color> 
    
    <!-- [arrow] (arrow / dragger) -->
    <resize_button_style></resize_button_style>    
    
    <!-- [#000000] (hex code) this setting is effective only if resize_button_style is set to "arrow" -->
    <resize_button_color></resize_button_color>
    
    <!-- [#000000] (hex code) -->
    <resize_pointer_color></resize_pointer_color> 
    
    <!-- [false] (true / false) If you set it to true, the chart will not update while dragging or resizing 
    scrollbar, but do this only when you release mouse button. This is useful if you work with large data 
    sets and want to make your chart work faster.  -->
    <update_on_release_only></update_on_release_only>   
    
    <grid>
      <!-- [true] (true / false) -->
      <enabled></enabled>
      
      <!-- [5] (Number) -->
      <max_count></max_count>
      
      <!-- [#FFFFFF] (hex code) -->
      <color></color>
      
      <!-- [40] (Number) -->
      <alpha></alpha>
      
      <!-- [false] (true / false) -->
      <dashed></dashed>
      
      <!-- [5] (Number) -->
      <dash_lenght></dash_lenght>
    </grid>
    <values>
      <!-- [true] (true / false) -->
      <enabled></enabled>
      
      <!-- [text_color] (hex code) -->
      <text_color></text_color>
      
      <!-- [text_size] (Number) -->
      <text_size></text_size>
    </values>
    
    <!-- PLAYBACK defines settings for a play and speed controls -->
    <playback>
      <!-- [false] (true / false) -->
      <enabled>true</enabled>
      
      <!-- [#000000] (hex code) -->
      <color></color>
      
      <!-- [#CC0000] (hex code) -->
      <color_hover></color_hover>
      
      <!-- [5] (Number) -->
      <speed>1</speed>
      
      <!-- [10] (Number) -->
      <max_speed></max_speed>
      
      <!-- [true] (true / false) -->
      <loop></loop>
      
      <speed_indicator>
        <!-- [true] (true / false) -->
        <enabled></enabled>
        
        <!-- [#000000] (hex code) -->
        <color></color>
        
        <!-- [#ABABAB] (hex code) -->
        <bg_color></bg_color>
      </speed_indicator>
    </playback>
  </scroller>

  <!-- CONTEXT MENU allows you to controll right-click menu items. You can add custom menu items to
  create custom controls -->  
  <context_menu>                                                                  
     <!-- "function_name" specifies JavaScript function which will be called when user clicks on 
     this menu. You can pass variables, for example: function_name="alert('something')".
     "title" sets menu item text. Do not use for title: Show all, Zoom in, Zoom out, Print, Settings...     
     You can have any number of custom menus. Uncomment the line below to enable this menu and add
     apropriate JS function to your html file. -->
     
     <menu function_name="printChart" title="Print chart"></menu>
     
     <default_items>
       <!-- [false] (true / false) to show or not flash player zoom menu-->
       <zoom></zoom>
       <!-- [true] (true / false) to show or not flash player print menu-->                                    
       <print></print>                                  
     </default_items>
  </context_menu>  
  
  <!-- EXPORT AS IMAGE allows you to save chart as an image. This feature works only on a web server -->
  <export_as_image>
    <!-- [] (filename) if you set filename here, context menu (then user right clicks on flash movie)
    "Export as image" will appear. This will allow user to export chart as an image. Collected image
    data will be posted to this file name (use amstock/export.php or amstock/export.aspx) -->                                         
    <file></file>
                                                 
    <!-- [] (_blank, _top ...) target of a window in which export file must be called -->
    <target></target>    
                                         
    <!-- [#54b40a] (hex code) background color of "Collecting data" text -->
    <color></color>                                           
    
    <!-- [0] (0 - 100) background alpha -->
    <alpha></alpha>                                           
    
    <!-- [text_color] (hex color code) -->
    <text_color></text_color>                                 
    
    <!-- [text_size] (Number) -->
    <text_size></text_size>                                   
  </export_as_image>  

  <strings>    
    <!-- [Processing data] (text) -->    
    <processing_data></processing_data>
    <!-- [Loading data] (text) -->    
    <loading_data></loading_data>    
    <!-- [Check date format] (text) -->    
    <wrong_date_format></wrong_date_format>      
    <!-- [Export as image] (text) -->    
    <export_as_image></export_as_image>
    <!-- [Collecting data] (text) -->    
    <collecting_data></collecting_data>
    <!-- [No data] (text) -->  
    <no_data></no_data>  
    <!-- [Logarithmic axis can't display values less or equal zero] (text) -->  
    <logarithm_scale_error></logarithm_scale_error>            
    
    <!-- In case your axis values display duration instead of numbers, these units
    will be used to format duration -->
    <duration_units>    
      <!-- [] unit of seconds -->
      <ss></ss>
      <!-- [:] unit of minutes -->
      <mm></mm>
      <!-- [:] unit of hours -->
      <hh></hh>
      <!-- [d. ] unit of days -->
      <DD></DD>    
    </duration_units>
    
 	  <months>
    	<jan>Jan</jan>
    	<feb>Feb</feb>
    	<mar>Mar</mar>
    	<apr>Apr</apr>
    	<may>May</may>
    	<jun>Jun</jun>
    	<jul>Jul</jul>
    	<aug>Aug</aug>
    	<sep>Sep</sep>
    	<oct>Oct</oct>
    	<nov>Nov</nov>
    	<dec>Dec</dec>    
  	</months>
  	
  	<weekdays>
  	   <sun>Sun</sun>
  	   <mon>Mon</mon>
  	   <tue>Tue</tue>
  	   <wed>Wed</wed>
  	   <thu>Thu</thu>
  	   <fri>Fri</fri>
  	   <sat>Sat</sat>
  	</weekdays>
  </strings>
  
  <!-- ERROR MESSAGES settings will be applied for all error messages except the one which is showed
  if settings file wasn't found -->
  <error_messages>
    <!-- [true] (true / false) -->
    <enabled></enabled>    
    <!-- [#B81D1B] (hex color code) background color of error message -->
    <color></color>                
    <!-- [100] (0 - 100) background opacity -->                           
    <alpha></alpha>              
    <!-- [#FFFFFF] (hex color code) -->
    <text_color></text_color>
    <!-- [text_size] (Number)-->       
    <text_size></text_size>       
  </error_messages>   
  
</settings>

EOT;

?>
