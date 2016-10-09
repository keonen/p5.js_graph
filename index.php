<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>
<head>
<script src="p5.js"></script>
<script type="text/javascript">
function setup() {

  // Sets the screen to be 720 pixels wide and 400 pixels high
  createCanvas(700, 700);
  fill(0);
  background(250);
  noSmooth();

<?php

if (isset($_POST['function']) and $_POST['function']) {
    include('evalmath.class.php');
    // Range values
    $x_min = $_POST['xmin'];
    $x_max = $_POST['xmax'];
    $y_min = $_POST['ymin'];
    $y_max = $_POST['ymax'];


    // Calculate exact range values
    $test_xmin = new EvalMath;
    $test_xmin->evaluate('y(x) = ' . $x_min);
    $x_min = $test_xmin->e("y($x_min)");

    $test_xmax = new EvalMath;
    $test_xmax->evaluate('y(x) = ' . $x_max);
    $x_max = $test_xmax->e("y($x_max)");

    $test_ymin = new EvalMath;
    $test_ymin->evaluate('y(x) = ' . $y_min);
    $y_min = $test_ymin->e("y($y_min)");

    $test_ymax = new EvalMath;
    $test_ymax->evaluate('y(x) = ' . $y_max);
    $y_max = $test_ymax->e("y($y_max)");   


    $x_total = abs($x_min) + abs($x_max);
    $y_total = abs($y_min) + abs($y_max);

    $x_offset = abs($x_min)/$x_total*700;
    $y_offset = abs($y_max)/$y_total*700;

    // Reset some values
    $x_point = $x_min;
    $x_scale = $x_total/700;
    $y_scale = $y_total/700;

    // Move origo
    echo "translate(".$x_offset.", ".$y_offset.");\n";

    // Draw grid
    echo "drawGrid();\n";

    // Draw black points
    echo "stroke(0);\n";

    $m = new EvalMath;
    $m->suppress_errors = true;
    if ($m->evaluate('y(x) = ' . $_POST['function'])) {
        //print "\t<table border=\"1\">\n";
        //print "\t\t<tr><th>x</th><th>y(x)</th>\n";
        for ($x = $x_min; $x < $x_max; $x+=$x_total/700) {
            $x_point++;
            $y_value = - $m->e("y($x)");
            if (is_finite($y_value)) {
             print "point(".$x/$x_scale.", " .$y_value/$y_scale. ");\n";}
        }
        //print "\t</table>\n";
    } else {
        print "\t<p>Could not evaluate function: " . $m->last_error . "</p>\n";
    }
}

?>

}


function drawGrid() {
	stroke(200);
	fill(5);
        x_fact = <?php echo $x_scale; ?>;
        y_fact = <?php echo $y_scale; ?>;
	for (var x=-width; x < width; x+=50) {
		line(x, -height, x, height);
		text(Math.round10(x_fact * x,-1), x+1, 12);
	}
	for (var y=-height; y < height; y+=50) {
		line(-width, y, width, y);
		text(-Math.round10(y_fact * y,-1), 1, y+12);
	}
}

// Closure
(function() {
  /**
   * Decimal adjustment of a number.
   *
   * @param {String}  type  The type of adjustment.
   * @param {Number}  value The number.
   * @param {Integer} exp   The exponent (the 10 logarithm of the adjustment base).
   * @returns {Number} The adjusted value.
   */
  function decimalAdjust(type, value, exp) {
    // If the exp is undefined or zero...
    if (typeof exp === 'undefined' || +exp === 0) {
      return Math[type](value);
    }
    value = +value;
    exp = +exp;
    // If the value is not a number or the exp is not an integer...
    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
      return NaN;
    }
    // Shift
    value = value.toString().split('e');
    value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
  }

  // Decimal round
  if (!Math.round10) {
    Math.round10 = function(value, exp) {
      return decimalAdjust('round', value, exp);
    };
  }
  // Decimal floor
  if (!Math.floor10) {
    Math.floor10 = function(value, exp) {
      return decimalAdjust('floor', value, exp);
    };
  }
  // Decimal ceil
  if (!Math.ceil10) {
    Math.ceil10 = function(value, exp) {
      return decimalAdjust('ceil', value, exp);
    };
  }
})();

</script>

    <title>Example use of EvalMath</title>
</head>

<body>
    <form method="post" action="<?=$_SERVER['PHP_SELF']?>">
        xmin = <input type="text" name="xmin" value="<?=(isset($_POST['xmin']) ? htmlspecialchars($_POST['xmin']) : '-350')?>" size="5">
        xmax = <input type="text" name="xmax" value="<?=(isset($_POST['xmax']) ? htmlspecialchars($_POST['xmax']) : '350')?>" size="5">
        ymin = <input type="text" name="ymin" value="<?=(isset($_POST['ymin']) ? htmlspecialchars($_POST['ymin']) : '-350')?>" size="5">
        ymax = <input type="text" name="ymax" value="<?=(isset($_POST['ymax']) ? htmlspecialchars($_POST['ymax']) : '350')?>" size="5"><br />
        y(x) = <input type="text" name="function" value="<?=(isset($_POST['function']) ? htmlspecialchars($_POST['function']) : '')?>" size="50">
        <input type="submit" value="Plot">
    </form>
</body>
</html>
