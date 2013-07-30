<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <title>Chart Manual</title>
    <link href="chart.css" rel="stylesheet" type="text/css">
  </head>

  <body bgcolor=white text=black>
    <h1>Chart Manual</h1>

<?
function example ($file) {
  echo "<a href=\"examples/$file.phps\"><img src=\"examples/$file.php\" border=0 align=right width=300 height=200></a>\n";
}

function examplena ($file) {
  echo "<a href=\"examples/$file.phps\"><img src=\"examples/$file.php\" border=0 width=300 height=200></a>\n";
}
?>

<p><? example("example31"); ?><br>

Chart is a PHP library for generating charts--two dimensional
representations of data sets.

<p>Chart is covered by the <a href="COPYING">GNU GPL</a> and
is written by <a href="http://quimby.gnus.org/lmi/">Lars Magne
Ingebrigtsen</a>.  Development of this library was paid for by <a
href="http://www.netfonds.no/">Netfonds Bank AS</a>.

<h3>Download</h3>

<a href="https://github.com/larsmagne/chart">Get the source from
GitHub</a>.

<h3>A super-quick example</h3>

Before getting down to the business at hand, let's just have a look at
a quick example of what Chart is meant to do, and how it does it.  

<p>
The simplest Chart program imaginable is the one that generated the
image to the right.  The source code is the following: 
<? example("example1"); ?>

<pre>
$chart = new chart(300, 200);
$chart->plot($data);
$chart->stroke();
</pre>

<p>This demonstrates the following:

<ol>
<li>Chart is a class

<li>You create a new chart, and then do stuff with it until you <a
href="#stroke">stroke</a> it

<li><a href="#scaling">Scaling</a> is done automatically by default

<li><a href="#grid">Grids</a> and <a href="#ticks">ticks</a> are
computed in a more-or-less sensible way by default
</ol>

<p>After that mini-introduction, the manual will now continue as
scheduled: 


<h3>Rationale</h3>

I work for an Internet stock broker, and we needed to allow users to
generate charts from our web pages.  One smart way to do that would be
to create Java applets and push the computation out to the clients,
but there are some problems with that (stability, usability and the
difficulty when printing them out -- the latter is very important in
financial circles).  So we wanted to generate the graphs on the web
servers, and just serve them out as images.

<p>The rest of our web site is PHP-based, so it was natural to want to
use PHP for generating the graphs.  One less thing that can go wrong
is one less thing that can go wrong.  

<p>As an olden gnuplot user, my first impulse was to create something
that resembled that, but I quickly realized that my hubris wasn't that
overwhelming -- yet.  Gnuplot is an excellent, flexible, complex
program, but its functionality is overkill for what I need.  I do
not need to plot three-dimensional data sets using esoteric
functions.  I have actual two-dimensional (or de facto
two-dimensional) data sets that I wish to have presented graphically.
Chart attempts to fill that (much simpler) need.

<p>I have tried to emphasize ease of use -- Chart usually computes all
the boring stuff itself.  However, most everything that Chart does can
be overridden or customized if you want a different look.


<h3>About the examples</h3>

All the example charts are, of course, generated by Chart.  The data
sets for the charts come from <a href="data.phps">a static data
file</a>, but normally one would get the data sets from a data base or
something similar.

<p>The actual source code for all the charts can be read by clicking on
the charts.


<h3>PHP</h3>

Chart needs PHP4 or 5.  The only non-standard library Chart relies on
is <a href="rgb.phps">RGB</a>, which makes it much easier to deal with
colors.  RGB is included in the Chart package.


<h3>Function reference</h3>

There are two classes in the Chart library -- <tt>chart</tt> and
<tt>plot</tt>.  Each chart can contain any number of plots.
<? example("example2"); ?>

<p>Here's a chart with two plots:

<h4>Chart Functions</h4>

<h4>chart</h4>

<pre>
chart(int $width, int $height, string $cache = false)
</pre>

Create a chart object of the specified size.  If the optional
<tt>$cache</tt> parameter is supplied, the <a href="#cache">cache</a>
is maintained.


<h4>set_border</h4>

<pre>
set_border(color $color = "black", int $width = 1)
</pre>

Draw a border around the chart using the specified color and width.
If you specify <tt>false</tt> as the color, no border will be drawn.


<? example("example3"); ?>
<h4>set_background_color</h4>

<pre>
set_background_color (color $color = "white", 
                      color $surround = "white")
</pre>

Sets the background color of the chart.  The "surrounding" color is
the color of the area between the chart itself and the outer border.


<h4><a name="ticks">set_x_ticks</a></h4>

<pre>
set_x_ticks (array $ticks, $format = "date")
</pre>

Specify where the X axis tick texts are supposed to come from.
They will be formatted according to the input format specified.  The
following input formats are supported:

<p>
<ul>
<li><tt>"date"</tt>: The date in ISO 8601 format.
<li><tt>"time"</tt>: The time in ISO 8601 format.
<li><tt>"cdate"</tt>: The date in Unix format.
<li><tt>"ctime"</tt>: The time in Unix format.
<li><tt>"text"</tt>: Simple text.
</ul>

<p>ISO 8601 format looks like YYYYMMDDThhmmss.  Unix time format is
the number of seconds since January 1st 1970.


<h4>set_title</h4>

<pre>
set_title (string $title, color $color = "black", string $where = "center")
</pre>

Set the title of the chart.  The positioning parameter can be
"center", "left" or "right", or an array with two elements -- the X
and Y position of the left- and topmost pixel of the title.

<h4>set_axes</h4>

<pre>
set_axes (string $which = "xy", color $color = "black")
</pre>

Specify which axes to draw, and the color of the axes.  You can have
"y", "x" or "xy" axes drawn.


<h4>splot</h4>

<pre>
splot (plot &$plot)
</pre>

Adds plot <tt>$plot</tt> to the chart.  Here's a code snippet to illustrate:

<pre>
$chart = new chart(100, 200);
$plot = new plot($data);
$chart->splot(&$plot);
</pre>

You'd normally not use this function, but use the <tt>plot()</tt>
function instead.

<h4>plot</h4>

<pre>
plot (array $c1, array $c2 = false, color $color = "black",
      string $style = "lines", color $gradient_color = "black",
      int $parameter = 0)
</pre>

Register either a one-dimensional data set or (if <tt>$c2</tt> is an
array) a two-dimensional data set to be plotted.  Both arrays must be
one-dimensional.  

This function returns a <a href="#plot">plot object</a>.  See <a
href="#set_style">set_style()</a> for a listing of possible styles.

<h4><a name="stroke">stroke</a></h4>

<pre>
stroke (function $callback = false)
</pre>

This is the function that does all the work.  None of the other
functions actually compute anything -- they just register things.
This function looks at everything that has been registered, computes
everything, outputs the proper HTTP headers and outputs the resulting
image (in GIF format).

<p>If the plot needs special handling, you can supply a call-back
function.  Then stroke will compute everything, do the grid and the
axes (etc.), and then call the call-back function with the following
signature: 

<pre>
$callback($image, $xmin, $xmax, $ymin, $ymax,
          $xoffset, $yoffset, $width, $height);
</pre>

Then it is up to the call-back function to draw the data set. 


<? example("example4"); ?>
<h4>set_frame</h4>

<pre>
set_frame ($frame = true)
</pre>

Draw a frame around the plotted area, using the same color as the
axes. 


<h4>set_expired</h4>

<pre>
set_expired (bool $expired)
</pre>

If the images you generate are truly dynamic, then you probably want
to prohibit the web browser and any proxies/caches from storing the
images.  Calling this function with a non-false value will make Chart
output headers to discourage caching on the client side.  Note that
this will probably result in much higher traffic.  

<br clear=all>
<? example("example23"); ?>
<h4>set_extrema</h4>

<pre>
set_extrema (int $y_min = false, int $y_max = false, 
             int $x_min = false, int $x_max = false) 
</pre>

<a name="scaling">By default, scaling is done automatically</a>.  Chart
computes all the extrema and adds 1% fudge space.  If you want
to manually set the extrema (for instance, if you're generating
several charts and want to keep the same scaling on all the charts),
you can use this function to specify the extrema.

<p>For instance, the image to the right uses the same data as the one
above, but has the Y extrema set much wider than the default algorithm
would have done.

<h4>set_grid_color</h4>

<pre>
set_grid_color (Color $color)
</pre>

Set the color of the <a name="grid">grid</a>.


<h4>set_margins</h4>

<pre>
set_margins (int $left = 30, int $right = 10, 
             int $top = 20, int $bottom = 23)
</pre>

Set the width of the margins.


<h4>set_tick_distance</h4>

<pre>
set_tick_distance (int $distance)
</pre>

Set the distance (in pixels) between the ticks on the axes.


<? example("example5"); ?>
<h4>set_labels</h4>

<pre>
set_labels (string $x = false, string $y = false)
</pre>

Set the X and Y labels of the chart.


<h4>set_output_size</h4>

<pre>
set_output_size (int $width, int $height)
</pre>

Set the output size to something else than the size you create the
chart at.  This can be useful if you want to create a very small
chart, and want to plot it at a bigger size, and then resize it down
to a small size, so that you can get antialiased resampling.  This
only works if you have PHP compiled with the 2.0 version of the gd
library (and set the <tt>$gd2</tt> variable to <tt>true</tt>).  If
not, the resizing will be very, very ugly.


<h4>set_grid_color</h4>

<pre>
set_grid_color (color $grid_color, 
                bool $grid_under_plot = true)
</pre>

Set the color of the grid.  Optionally say whether the grid should be
under the data plot (which is the default), or over it (which is
useful if plotting using the <tt>"fill"</tt> or <tt>"gradient"</tt>
plot styles.


<br clear=all>
<? example("example26"); ?>
<h4>set_font</h4>

<pre>
set_font ($font, $type, $size = false)
</pre>

Set the font used for the title and labels.  Three font types can
be used:

<p>
<ol>
<li><tt>"internal"</tt>: The internal PHP fonts.  The <tt>$font</tt>
parameter should be an integer between 0 and 8.
<li><tt>"type1"</tt>: A type1 PS font.  The <tt>$font</tt>
parameter should be the path to a file containing the PFB font
file for the type1 font.  To use this, your PHP has to be compiled
with support for type1 fonts.  (I.e., the t1lib library has to be compiled
in.)
<li><tt>"ttf"</tt>: A TrueType PS font.  The <tt>$font</tt>
parameter should be the path to a file containing the TTF font
file for the ttf font.  To use this, your PHP has to be compiled
with support for TTF fonts.  (I.e., the freetype library has to
be compiled in.)
</ol>

<p>
Most Unixoid people will want to use Type1 fonts, as these are
included in all TeX distributions.  Just say something like

<pre>
$chart->set_font("/usr/share/texmf/fonts/type1/adobe/utopia/putb8a.pfb",
		 "type1");
</pre>

to get the chart above.

<br clear=all>
<? example("example27"); ?>
<p>If you specify the optional <tt>$size</tt> parameter, the
font will be scaled using that point size.  The parameter has no
effect for internal fonts.

<p>Using Type1 or TTF fonts will make Chart a lot slower.
Caching is an absolute must is you don't use the internal fonts.



<br clear=all>
<? example("example22"); ?>
<h4>add_legend</h4>

<pre>
add_legend ($string, $color)
</pre>

Add a legend to the chart.

The following variables can be set to tune the legend:

<p>
<ul>
<li><tt>$legend_background_color</tt>: The background color of 
the legend.  If set to <tt>false</tt>, the background of the legend
will be transparent.
<li><tt>$legend_border_color</tt>: The border color of 
the legend.  If set to <tt>false</tt>, no border will be 
drawn.
<li><tt>$legend_margin</tt>: The size of the margin, in pixels, between
the legend text and the legend border.
</ul>


<h4>Plot Functions</h4>

<h4>set_color</h4>

<pre>
set_color (color $color)
</pre>

Set the color of the plot.


<a name="set_style"><h4>set_style</h4></a>

<pre>
set_style ($style)
</pre>

Set the style of the plot.  Valid values are <tt>"lines"</tt>, 
<tt>"points"</tt>,  <tt>"impulse"</tt>,  <tt>"circle"</tt>, 
<tt>"cross"</tt>, <tt>"fill"</tt>, <tt>"square"</tt>, 
<tt>"triangle"</tt> , <tt>"box"</tt> 
and <tt>"gradient"</tt>.

<p>
<ul>
<li><tt>"lines"</tt>: Draw angled lines between data points.
<li><tt>"square"</tt>: Draw square lines between data points.
<li><tt>"points"</tt>: Plot the data points with dots.
<li><tt>"impulse"</tt>: Draw a line upwards from the bottom
to the data point.
<li><tt>"circle"</tt>: Plot the data points using circles.  Takes 
an optional circle size parameter.
<li><tt>"cross"</tt>: Plot the data points using crosses.  Takes
an optional cross size parameter.
<li><tt>"fill"</tt>: Take two data sets and fill the area
between them.  The data points themselves will be drawn using 
<tt>"square"</tt>.
<li><tt>"fillgradient"</tt>: The same as <tt>fill</tt>,
but uses a gradient, as explained below.
<li><tt>"triangle"</tt>: Plot the data points using triangles.  Takes
an optional "shadow color" paramater.
<li><tt>"box"</tt>: Plot the data points using boxes.  Takes an optional
gradient color parameter -- three colors will be used, in total.
<li><tt>"gradient"</tt>: Fill the area under/over the data plot
with a gradient color.  A secondary color is given, and a parameter
can also be used to control the gradient even further.  The parameter
is a bit mask, and the following bits are defined:
<p>
<ul>
<li>1 (style): If set, draw using a dynamic style.  If not set,
each Y value will have the same color.
<li>2 (over/under): If set, draw over the data plot.  If not set,
draw under the data plot.
<li>4 (from/to): Reverse the direction of the gradient.
<li>8 (horizontal): Graduate horizontally instead of vertically.
</ul>
</ul>

<p>Below is the same data set plotted using different styles.

<p>
<? examplena("example6"); ?>
<? examplena("example7"); ?><br>
<? examplena("example8"); ?>
<? examplena("example9"); ?><br>
<? examplena("example10"); ?>
<? examplena("example11"); ?><br>
<? examplena("example24"); ?>
<? examplena("example25"); ?><br>

<p>Below is the same data set plotted using the <tt>"gradient"</tt>
style, but with different parameters.

<p>
<? examplena("example12"); ?>
<? examplena("example13"); ?><br>
<? examplena("example14"); ?>
<? examplena("example15"); ?><br>
<? examplena("example16"); ?>
<? examplena("example17"); ?><br>
<? examplena("example36"); ?>
<? examplena("example37"); ?><br>
<? examplena("example38"); ?>
<? examplena("example39"); ?><br>

<p>And, of course, you can go completely wild and plot several different
gradients in the same chart:

<p>
<? examplena("example18"); ?>
<? examplena("example19"); ?><br>

<p>The usefulness of these charts may be rather questionable.  Placing the 
grid over the plot helps some:

<p><? examplena("example20"); ?>
<? examplena("example21"); ?><br>

<p>And finally, <tt>fill</tt> and <tt>fillgradient</tt> plots:

<p><? examplena("example29"); ?>
<? examplena("example28"); ?><br>

<p>Plotting the upper and lower bounds separately often makes
the filling plots look nicer:

<p><? examplena("example30"); ?><br>

<p>Using gradients means slowing down chart generation somewhat.
Dynamic gradients are no slower that non-dynamic gradients, but using
them means that the charts will be quite a bit larger, since they
won't compress as well as non-dynamic gradients.  If you have a heavy
load, or you have a slow web server, you should try to cache the
gradient charts as aggressively as possible.

<h3>Caching</h3>

PHP is not the most efficient language in the world, and if you have a
busy web site, generating masses of images may bog your server down.
Chart therefore has a caching mechanism.

<p>If you supply a third parameter to the chart function, then Chart
will first check to see if that file exists before doing anything.  If
it does, it will output that file and exit.  If it does not exist,
Chart will compute the chart as usual, but before outputting the
newly-generated chart, it will save it to the cache first, using that
supplied file name.

<p>PHP scripts that use this would have something like the following
at the start of the script:

<pre>
$chart = new chart(200, 100, "nice-plot");
# ... The rest of the program.
</pre>

<p>Then PHP would only create the image once, and every other access
would be dealt with from the cache.

<p>In this case, this would produce a totally static plot, and you
might as well just generate the GIF and use that instead of the PHP
script.  One reason to still do it this way is that it's often just
simpler.  For instance, all the example plots you're seeing on this
page most probably came from the cache.

<p>Most real dynamic plots usually have some input values, though.  A
recommended way to deal with that would be:

<pre>
$chart = new chart(200, 100, 
                   sprintf("other-plot/stuff=%d/thing=%s/gif",
                           $stuff, $thing);
# ... The rest of the program.
</pre>

<p>This chart depends on two parameters -- <tt>stuff</tt> and
<tt>thing</tt>.  If these vary wildly, many GIFs will be generated and
stored in the cache.  In that case, setting up a cron job to delete
images that haven't been accessed in, say, a few days, would be
necessary.  Something like the following would probably do the trick;
it first deletes all files that haven't been accessed in three days, and
then it removes all empty directories:

<pre>
#!/bin/sh
find /var/tmp/cache -type f -atime +3 -exec ls -l {} \;
find /var/tmp/cache -type d -empty -exec ls -ld {} \;
</pre>

<p>This should, of course, be run as the same user that generated the
files, which would normally be <tt>nobody</tt>.

<p>It is probably not a good idea to generate a flat cache.  If many
thousand images are cached, accessing files in such a big directory
will be slow.  Therefore it is probably usually better to generate a
tree structure, as shown in the example above.

<p>If your plot is not uniquely determined by the parameters, caching
becomes problematic, and will give you bad results.  If, for instance,
you have a chart that displays different data depending on the date,
you could get around this problem by including the date in the cache
file name.  Then the chart would only be generated once per day.

<p>While developing new charts, the cache usually gets in the way.  If
that's the case, set the global <tt>$chart_debug</tt> variable to
<tt>true</tt>.  This will override the cache and force Chart to
re-generate the images every time.

<p>The global <tt>$chart_cache_directory</tt> variable says what the
root directory of the cache is.  It defaults to
<tt>"/var/tmp/cache"</tt>. 



<h3>GIF and PNG</h3>

As of version 0.4, Chart defaults to outputting PNG images instead of
GIF images.  This is because newer versions of the gd library (which
PHP uses to create images) doesn't include GIF creating functions,
since the patent holders to the GIF algorithm demand money for the
usage of that algorithm.  If you absolutely have to generate GIF
images (and there's really no need, since all modern web browsers
parse PNG images just fine), you need to link PHP with gd version 1.3
or earlier, and set the global <tt>$chart_use_png</tt> variable to
<tt>false</tt>. 


<h3>Grids and ticks</h3>

Chart uses heuristiscs developed over the years to find pleasing
numbers to put on the X and Y axes, as well as spacing the grid lines
in a sensible way.  More work needs to be done, but the following 
seems to work quite well:

<p>
<ul> 

<li>On the Y axes, numbers divisible by 1, 2 and 5 are used.  (Divided
and multiplied by 10, etc.)

<li>If the X axes is a clock axes (i. e., <tt>time</tt>), Chart tries
to use whole hours, half hours, quarter hours, etc.

<li>For dates on the X axes, Chart tries to use years, months, weeks,
etc.

</ul>


<h3>Output data</h3>

To allow interacting with the data from Javascript, Chart can output
the data it plots in JSON format.  To do that, call <tt>new
chart()</tt> with the optional <tt>jsonpart</tt> parameter set.



<h3>Bugs</h3>

<p>Chart hasn't been optimized for speed at all.  There are probably
many things one can do to make it run faster.  Patches are welcome.


<h3>Possible coming features</h3>

Putting text and other images into the charts might be nice.  Naming the 
plots would be convenient.  And just about anything else that gnuplot 
does with two-dimensional plots would be spiffy.


<h3>Other PHP Packages</h3>

At the time when I started writing Chart, there wasn't anything like
it available.  Now there are several other packages that does similiar
things, using different approaches.  One interesting package is <a
href="http://www.aditus.nu/jpgraph/">JpGraph</a>, which seems to be
able to generate very nice charts indeed.  That page also has links to
other packages.


<h3>Contributors</h3>

<ul>

<li>Christoph Lameter supplied patches for cdate, ctime, box and
triangle extensions.

<li>Ho Siaw Ping, Ryan fixed the x_ticks code.

</ul>


<h3>Bugs</h3>

<ul>

<li>If you use a different locale than english, stuff will probably
not work well.  <tt>setlocale(LC_ALL, "english");</tt> to set the
right locale.

</ul>


<h3>Contact</h3>

Chart is &copy; 1999-2013 Lars Magne Ingebrigtsen.

<p>Chart is released under the <a href="COPYING">GNU General Public
License</a>.  This means that Chart is <a
href="http://www.fsf.org/philosophy/free-sw.html">free</a>.  This web
page is the documentation to Chart and is covered by the same license.

<p>Patches, new features, bug reports and other stuff can be sent to
<a href="mailto:larsi@gnus.org">Lars Magne Ingebrigtsen</a>.

<h4></h4>

<pre>
</pre>

    <p><hr noshade>

    <div align=right>
      <small>
<!-- Created: Sun Sep  4 19:27:03 CET 1999 -->
<!-- hhmts start -->
2013-07-29 21:44:14
<!-- hhmts end -->
      </small>
    </div>


</body>
