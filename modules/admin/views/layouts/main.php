<?php

use app\models\User;
use yii\helpers\Html;
use app\modules\admin\assets\AssetBundle;
use app\modules\admin\models\base\Campus;
use app\modules\admin\models\WebSetting;
use yii\helpers\Url;
use yii\web\View;

AssetBundle::register($this);


$setting = new WebSetting();

$icon = $setting->getSettingBykey('website_favicon');





$this->beginPage() ?>


<!DOCTYPE html>
<html>



<head>
	<meta charset="<?= Yii::$app->charset ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<link rel="icon" href="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/uploads/<?php echo $icon; ?>" type="image/x-icon">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
	<?php $this->head() ?>
	<meta name="robots" content="noindex, nofollow">
</head>

<?php
$primary = Yii::$app->user->identity->bg_color_preference;
$secondary = Yii::$app->user->identity->button_color_preference;
// var_dump($color);exit;
$this->registerCss("
  /* Light Mode */
  .body{
 font-family: Rubik,sans-serif !important;  
  font-weight: 400;
  }
  h1, h2, h3, h4, h5, h6{
font-family: Rubik,sans-serif !important;  
font-weight: 400 !important;
 
}
.grid-view .card {
    border: none !important;
    border-radius: 21px !important;
}
h1, h2, h3, h4, h5, h6{
 font-family: Rubik,sans-serif !important;  
 font-weight: 400 !important;

}
 .background-animation {
    position: fixed;
    top: 0;
    left: 0;
	background-color: cadetblue;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none; /* Ensures it doesn't interfere with interactions */
}
	.page-title {
	color:#000;
	}
	.page-wrapper>.content {
     padding: 0rem 1rem 0;
}
	.page-header {
	display:none;
	}
.db-info h6 {
font-size:16px;
}
.row.dashind h3 {
font-size:20px;
text-align: center;
}
.dashind{
    border-radius: 15px;
}

	#lightIcon, #darkIcon {
    font-size: 1.5em; /* Adjust size as needed */
}
.header {
    background-color: #14549b  !important;
    // backdrop-filter: blur(3px);
}
	
.header .header-left .logo img {
margin-left: 61px;
    max-height: none !important;
    width: 120px;
}
.header .header-left {
    background-color: #14549b  !important;
    backdrop-filter: blur(3px);
}
	
	.grid-view .card .card-header h5{
	color: black;
	}
.sidebar {
    background: #14549B !important;
	margin-top: -2px;
    // border-right: solid 1px #e4e4e4;
    // overflow: auto;
	// backdrop-filter: blur(3px);
}
.user-img .user-text h6,
.user-img .user-text .text-muted {
    color: #000 !important;
}
.btn-primary {
		background-color: $secondary !important;
		border: 1px solid $secondary !important;
	}
	.btn-success {
		background-color: $secondary !important;
		border: 1px solid $secondary !important;
	}
.sidebar-menu li.active > a {
    background-color: #fff !important;
	font-weight:500 !important;
	color: #24843a !important;
	font-size:.85rem;

}
	.sidebar-menu li ul li {
    list-style: '-';
}
.sidebar .sidebar-menu > ul > li > a span {
    color: rgb(245, 247, 250) !important;
letter-spacing: 1px;
//   font-family: Rubik,sans-serif;  
font-weight: 400 !important;

} 
/* Hover styles */
.sidebar .sidebar-menu > ul > li > a:hover {
    background-color: white !important;
}

.sidebar .sidebar-menu > ul > li > a:hover span,
.sidebar .sidebar-menu > ul > li > a:hover svg {
    color: black !important;
    fill: black !important;
}
.sidebar .sidebar-menu > ul > li.active > a span {
    color: black !important;
}

.sidebar .sidebar-menu > ul > li.active > a svg {
    fill: black !important;
}
	/* Submenu link hover styles */
.sidebar-menu ul ul a:hover {
    background-color: black !important;
}

.sidebar-menu ul ul a:hover span,
.sidebar-menu ul ul a:hover svg {
    color: black !important;
    fill: black !important;
}

.sidebar .sidebar-menu > ul > li > a span.menu-arrow {
    color:rgb(255, 255, 255) !important;
letter-spacing: 1px;
 font-family: font awesome 5 free !important;
    font-weight: 600 !important;

} 

.sidebar-menu ul ul a{
 color: rgb(255, 255, 255) !important;
letter-spacing: 1px;
  font-family: Rubik,sans-serif;  
font-weight: 400 !important;
font-size:.85rem;
}
.sidebar-menu li a:hover {
    background: #e4f4ff !important;
}
footer {
    background: #fff !important;
    border-top: solid 1px #e4e4e4;
}
.sidebar-menu li > a > img {
    background: #353535 !important;
    border-radius: 50%;
    padding: 7px;
    height: 29px;
    width: 29px;
    margin-right: 3px;
}
.sidebar-menu li {
    // border-bottom: solid 1px #fff;
}
.dropdown-menu.notifications.show .topnav-dropdown-header,
.dropdown-menu.notifications.show .noti-content,
.dropdown-menu,
#toggle_btn {
    background-color: #14549b !important;
}
.sidebar-menu ul ul a.active {
    background: #fff !important;
font-weight: 600 !important;
color: rgb(49 90 57) !important;
font-size:.85rem;

}
.user-img .user-text h6, .user-img .user-text .text-muted {
    color: #ffffff !important;
}


button.tawk-custom-color.tawk-custom-border-color.tawk-button.tawk-button-circle.tawk-button-large {
    background-color: var(--primary) !important;
}
.sidebar-menu li a {
    color: #000 !important;
    font-weight: 600;
	font-size:.85rem;
}
	.grid-view {
    background-color: #f9f9f9 !important;
    color: #333;
    border: 1px solid #ddd;
}

.grid-view .card-footer {
    background-color: #f9f9f9 !important;
    color: #333;
    border: 1px solid #ddd;
}

.grid-view .card {
    background-color: #ffffff !important;
    color: #333;
    border: 1px solid #ddd;
}

.grid-view .card .card-header {
    background-color: #f0f0f0 !important;
    color: #000 !important;
    border: 1px solid #ddd;
}

.grid-view th a {
    color: #333 !important;
}

.grid-view th {
    background-color: #eaeaea;
    color: #333;
    border-bottom: 2px solid #ccc;
    padding: 12px;
}

.grid-view td {
    background-color: #ffffff;
    color: #555 !important;
    border-bottom: 1px solid #ddd;
    padding: 12px;
}

.grid-view tr:hover {
    background-color: #f1f1f1;
    color: #333;
}

.grid-view table {
    border-collapse: collapse;
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
}

.grid-view .pagination > li > a,
.grid-view .pagination > li > span {
    background-color: #ffffff;
    color: #333;
    border: 1px solid #ddd;
}

.grid-view .pagination > .active > a {
    background-color: #eaeaea;
    color: #333;
    border: 1px solid #ccc;
}
.bg-comman {
    border: 1px #bdbdbdb8 solid !important;
}
.sidebar svg{
   fill:rgb(244, 245, 246) !important;
    stroke: #f5b51233;
    stroke-width: 32px;
	
}
.sidebar-menu>ul>li>a svg {
    width: 16px;
}
	.user-menu.nav .header-nav-list {
    width: 30px;
    height: 30px;
}

/* Dark Mode */
html.darkmode body {
    background: linear-gradient(to right, #434343 0%, black 100%) !important;
    color: #eaeaea;
	background-image:
}
	.darkmode .background-animation{
	background-color:#000;
	}
.darkmode .header,
.darkmode .header .header-left,
.darkmode .sidebar {
    background: #2c2929  !important;
    backdrop-filter: blur(10px);
}
	.darkmode .sidebar svg{
    fill: #fff  !important;
   
}
	.darkmode .bg-comman {
	border: 1px #000000b8 solid !important;
}
	}
	.darkmode .views-personal h4{
	color:#fff;
	}
.darkmode .personal-icons img {
    filter: invert(1) brightness(2); /* Invert colors and brighten to make SVG white */
}
	.darkmode .heading-detail h4 {
	color:#107f22 ;
	}
	.darkmode h4 {
	color:#ffff;
	}
	.darkmode .names-profiles h4{
	color:#000;
	}
	.darkmode .db-info h6 {
font-size:16px;
}
.bg-comman .db-icon {
    background-color: #edf4ff;
    padding: 30px;
}
.darkmode .card-body,
.darkmode .card .card-header,
.darkmode .card.card-chart,
.darkmode .card.comman-shadow {
    background-color: #222020;
    color: #eaeaea;
}
.darkmode .card .card-header .card-title,
.darkmode label,
.darkmode .db-info h6 {
    color: #fff;
}
.darkmode .db-info h3 {
    color: #2ac14a;
}
	.darkmode  h3,h5 {
    color: #fff;
}
	.darkmode .activity-feed .feed-item .feed-text1 a {
    color: #ffffff;
	}
.darkmode .stats .card-text {
color:#fff;
}
.darkmode .grid-view .card .card-header h5{
	color: #ffff;
	}
.darkmode .stats .card-title {
color:#cbcbcb;
}
.darkmode .modern-card {
background-color:#1e1d1d;
}
.darkmode .modern-card-title {
color:#cbcbcb;
}
.darkmode .user-img .user-text h6,
.darkmode .user-img .user-text .text-muted {
    color: #fff !important;
}
.darkmode.btn-primary {
		background-color: $secondary !important;
		border: 1px solid $secondary !important;
	}
	.darkmode.btn-success {
		background-color: $secondary !important;
		border: 1px solid $secondary !important;
	}
.darkmode .sidebar-menu li.active > a {
    background-color: #24843a !important;
}
.darkmode .sidebar .sidebar-menu > ul > li > a span {
    color: #fff !important;
    font-weight: 600;
	font-size:.85rem;
}
.darkmode .sidebar-menu li a:hover {
    background: #24843a !important;
}
.darkmode footer {
    background: #000 !important;
    border-top: solid 1px #e4e4e4;
}
.darkmode .sidebar-menu li > a > img {
    background: #353535 !important;
    border-radius: 50%;
    padding: 7px;
    height: 29px;
    width: 29px;
    margin-right: 3px;
}
.darkmode .sidebar-menu li {
    border-bottom: solid 1px #fff;
}
.darkmode .dropdown-menu.notifications.show .topnav-dropdown-header,
.darkmode .dropdown-menu.notifications.show .noti-content,
.darkmode .dropdown-menu,
.darkmode #toggle_btn {
    background-color: $secondary !important;
}
.darkmode .sidebar-menu ul ul a.active {
    background: #24843a !important;
}
.darkmode button.tawk-custom-color.tawk-custom-border-color.tawk-button.tawk-button-circle.tawk-button-large {
    background-color: var(--primary) !important;
}
.darkmode .sidebar-menu li a {
    color: #fff !important;
    font-weight: 600;
	font-size:.85rem;
}

/* Modern Dark Mode for Yii2 GridView */
.darkmode .grid-view {
    background-color: #2b2b2b !important;
    color: #ffffff;
    border: 1px solid #444;
}

.darkmode .grid-view .card-footer {
    background-color: #2b2b2b !important;
    color: #ffffff;
    border: 1px solid #444;
}

.darkmode .grid-view .card {
    background-color: #2b2b2b !important;
    color: #ffffff;
    border: 1px solid #444;
}

.darkmode .grid-view .card .card-header {
    background-color: #000 !important;
    color: #ffffff;
    border: 1px solid #444;
}

.darkmode .grid-view th a {
    color: #fff !important;
}
.darkmode .dashind{
background:#000 !important;
border-radius:15px;
padding:10px 0px;
margin:18px 0px
}
.darkmode .grid-view th {
    background-color: #444;
    color: #fff;
    border-bottom: 2px solid #555;
    padding: 12px;
}

.darkmode .grid-view td {
    background-color: #333;
    color: #ddd !important;
    border-bottom: 1px solid #555;
    padding: 12px;
}

.darkmode .grid-view tr:hover {
    background-color: #555;
    color: #fff;
}

.darkmode .grid-view table {
    border-collapse: collapse;
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
}

.darkmode .grid-view .pagination > li > a,
.darkmode .grid-view .pagination > li > span {
    background-color: #444;
    color: #ddd;
    border: 1px solid #555;
}

.darkmode .grid-view .pagination > .active > a {
    background-color: #666;
    color: #fff;
    border: 1px solid #888;
}



");
$id = User::getCampusesByUser(Yii::$app->user->identity->id);
$campus = Campus::find()->where(['id' => $id])->one();

if ($campus && $campus->id == 90) {
	$this->registerCss("
        .background-animation {
            background-color: white !important; /* Apply white background */
			display:none;
        }
    ");
}

?>

</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
	body {
		/* background-image: url("../web/bg3.jpg") !important; */
		background-position: center center;
		background: fixed;
		background-size: cover;
		background-repeat: no-repeat;
		background-color: #f1f5f9 !important;

	}
</style>

<body id="canvas">

	<div class="main-wrapper">

		<?php $this->beginBody() ?>
		<div class="wrapper">
			<?= $this->render('../partials/header'); ?>
			<?= $this->render('@app/modules/admin/views/partials/header'); ?>

			<?= $this->render('@app/modules/admin/views/partials/nav'); ?>
			<div class="page-wrapper">
				<div class="content container-fluid">
					<?= $this->render('@app/modules/admin/views/partials/content-header'); ?>
					<div class="row">
						<div class="col-sm-12">


							<?= $content ?>
						</div>
					</div>
				</div>
				<?= $this->render('@app/modules/admin/views/partials/footer'); ?>
			</div>
		</div>
		<?php $this->endBody() ?>



	</div>

	<link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>


</body>
<?php
$this->registerJsFile('@web/modules/admin/assets/plugin/apexchart/apexcharts.min.js', ['position' => View::POS_END]);
$this->registerJsFile('@web/modules/admin/assets/plugin/apexchart/chart-data.js', ['position' => View::POS_END]);
$this->registerJsFile('@web/modules/admin/assets/plugin/c3-chart/d3.v5.min.js', ['position' => View::POS_END]);
$this->registerJsFile('@web/modules/admin/assets/plugin/c3-chart/c3.min.js', ['position' => View::POS_END]);
$this->registerJsFile('@web/modules/admin/assets/plugin/c3-chart/chart-data.js', ['position' => View::POS_END]);
$this->registerJsFile('@web/modules/admin/assets/plugin/timeline/horizontal-timeline.js', ['position' => View::POS_END]);


?>


<script>
	// when animating on canvas, it is best to use requestAnimationFrame instead of setTimeout or setInterval
	// not supported in all browsers though and sometimes needs a prefix, so we need a shim
	window.requestAnimFrame = (function() {
		return window.requestAnimationFrame ||
			window.webkitRequestAnimationFrame ||
			window.mozRequestAnimationFrame ||
			function(callback) {
				window.setTimeout(callback, 1000 / 60);
			};
	})();

	// now we will setup our basic variables for the demo
	var canvas = document.getElementById('canvas'),
		ctx = canvas.getContext('2d'),
		// full screen dimensions
		cw = window.innerWidth,
		ch = window.innerHeight,
		// firework collection
		fireworks = [],
		// particle collection
		particles = [],
		// starting hue
		hue = 120,
		// when launching fireworks with a click, too many get launched at once without a limiter, one launch per 5 loop ticks
		limiterTotal = 5,
		limiterTick = 0,
		// this will time the auto launches of fireworks, one launch per 80 loop ticks
		timerTotal = 80,
		timerTick = 0,
		mousedown = false,
		// mouse x coordinate,
		mx,
		// mouse y coordinate
		my;

	// set canvas dimensions
	canvas.width = cw;
	canvas.height = ch;

	// now we are going to setup our function placeholders for the entire demo

	// get a random number within a range
	function random(min, max) {
		return Math.random() * (max - min) + min;
	}

	// calculate the distance between two points
	function calculateDistance(p1x, p1y, p2x, p2y) {
		var xDistance = p1x - p2x,
			yDistance = p1y - p2y;
		return Math.sqrt(Math.pow(xDistance, 2) + Math.pow(yDistance, 2));
	}

	// create firework
	function Firework(sx, sy, tx, ty) {
		// actual coordinates
		this.x = sx;
		this.y = sy;
		// starting coordinates
		this.sx = sx;
		this.sy = sy;
		// target coordinates
		this.tx = tx;
		this.ty = ty;
		// distance from starting point to target
		this.distanceToTarget = calculateDistance(sx, sy, tx, ty);
		this.distanceTraveled = 0;
		// track the past coordinates of each firework to create a trail effect, increase the coordinate count to create more prominent trails
		this.coordinates = [];
		this.coordinateCount = 3;
		// populate initial coordinate collection with the current coordinates
		while (this.coordinateCount--) {
			this.coordinates.push([this.x, this.y]);
		}
		this.angle = Math.atan2(ty - sy, tx - sx);
		this.speed = 2;
		this.acceleration = 1.05;
		this.brightness = random(50, 70);
		// circle target indicator radius
		this.targetRadius = 1;
	}

	// update firework
	Firework.prototype.update = function(index) {
		// remove last item in coordinates array
		this.coordinates.pop();
		// add current coordinates to the start of the array
		this.coordinates.unshift([this.x, this.y]);

		// cycle the circle target indicator radius
		if (this.targetRadius < 8) {
			this.targetRadius += 0.3;
		} else {
			this.targetRadius = 1;
		}

		// speed up the firework
		this.speed *= this.acceleration;

		// get the current velocities based on angle and speed
		var vx = Math.cos(this.angle) * this.speed,
			vy = Math.sin(this.angle) * this.speed;
		// how far will the firework have traveled with velocities applied?
		this.distanceTraveled = calculateDistance(this.sx, this.sy, this.x + vx, this.y + vy);

		// if the distance traveled, including velocities, is greater than the initial distance to the target, then the target has been reached
		if (this.distanceTraveled >= this.distanceToTarget) {
			createParticles(this.tx, this.ty);
			// remove the firework, use the index passed into the update function to determine which to remove
			fireworks.splice(index, 1);
		} else {
			// target not reached, keep traveling
			this.x += vx;
			this.y += vy;
		}
	}

	// draw firework
	Firework.prototype.draw = function() {
		ctx.beginPath();
		// move to the last tracked coordinate in the set, then draw a line to the current x and y
		ctx.moveTo(this.coordinates[this.coordinates.length - 1][0], this.coordinates[this.coordinates.length - 1][1]);
		ctx.lineTo(this.x, this.y);
		ctx.strokeStyle = 'hsl(' + hue + ', 100%, ' + this.brightness + '%)';
		ctx.stroke();

		ctx.beginPath();
		// draw the target for this firework with a pulsing circle
		ctx.arc(this.tx, this.ty, this.targetRadius, 0, Math.PI * 2);
		ctx.stroke();
	}

	// create particle
	function Particle(x, y) {
		this.x = x;
		this.y = y;
		// track the past coordinates of each particle to create a trail effect, increase the coordinate count to create more prominent trails
		this.coordinates = [];
		this.coordinateCount = 5;
		while (this.coordinateCount--) {
			this.coordinates.push([this.x, this.y]);
		}
		// set a random angle in all possible directions, in radians
		this.angle = random(0, Math.PI * 2);
		this.speed = random(1, 10);
		// friction will slow the particle down
		this.friction = 0.95;
		// gravity will be applied and pull the particle down
		this.gravity = 1;
		// set the hue to a random number +-50 of the overall hue variable
		this.hue = random(hue - 50, hue + 50);
		this.brightness = random(50, 80);
		this.alpha = 1;
		// set how fast the particle fades out
		this.decay = random(0.015, 0.03);
	}

	// update particle
	Particle.prototype.update = function(index) {
		// remove last item in coordinates array
		this.coordinates.pop();
		// add current coordinates to the start of the array
		this.coordinates.unshift([this.x, this.y]);
		// slow down the particle
		this.speed *= this.friction;
		// apply velocity
		this.x += Math.cos(this.angle) * this.speed;
		this.y += Math.sin(this.angle) * this.speed + this.gravity;
		// fade out the particle
		this.alpha -= this.decay;

		// remove the particle once the alpha is low enough, based on the passed in index
		if (this.alpha <= this.decay) {
			particles.splice(index, 1);
		}
	}

	// draw particle
	Particle.prototype.draw = function() {
		ctx.beginPath();
		// move to the last tracked coordinates in the set, then draw a line to the current x and y
		ctx.moveTo(this.coordinates[this.coordinates.length - 1][0], this.coordinates[this.coordinates.length - 1][1]);
		ctx.lineTo(this.x, this.y);
		ctx.strokeStyle = 'hsla(' + this.hue + ', 100%, ' + this.brightness + '%, ' + this.alpha + ')';
		ctx.stroke();
	}

	// create particle group/explosion
	function createParticles(x, y) {
		// increase the particle count for a bigger explosion, beware of the canvas performance hit with the increased particles though
		var particleCount = 30;
		while (particleCount--) {
			particles.push(new Particle(x, y));
		}
	}

	// main demo loop
	function loop() {
		// this function will run endlessly with requestAnimationFrame
		requestAnimFrame(loop);

		// increase the hue to get different colored fireworks over time
		//hue += 0.5;

		// create random color
		hue = random(0, 360);

		// normally, clearRect() would be used to clear the canvas
		// we want to create a trailing effect though
		// setting the composite operation to destination-out will allow us to clear the canvas at a specific opacity, rather than wiping it entirely
		ctx.globalCompositeOperation = 'destination-out';
		// decrease the alpha property to create more prominent trails
		ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
		ctx.fillRect(0, 0, cw, ch);
		// change the composite operation back to our main mode
		// lighter creates bright highlight points as the fireworks and particles overlap each other
		ctx.globalCompositeOperation = 'lighter';

		// loop over each firework, draw it, update it
		var i = fireworks.length;
		while (i--) {
			fireworks[i].draw();
			fireworks[i].update(i);
		}

		// loop over each particle, draw it, update it
		var i = particles.length;
		while (i--) {
			particles[i].draw();
			particles[i].update(i);
		}

		// launch fireworks automatically to random coordinates, when the mouse isn't down
		if (timerTick >= timerTotal) {
			if (!mousedown) {
				// start the firework at the bottom middle of the screen, then set the random target coordinates, the random y coordinates will be set within the range of the top half of the screen
				fireworks.push(new Firework(cw / 2, ch, random(0, cw), random(0, ch / 2)));
				timerTick = 0;
			}
		} else {
			timerTick++;
		}

		// limit the rate at which fireworks get launched when mouse is down
		if (limiterTick >= limiterTotal) {
			if (mousedown) {
				// start the firework at the bottom middle of the screen, then set the current mouse coordinates as the target
				fireworks.push(new Firework(cw / 2, ch, mx, my));
				limiterTick = 0;
			}
		} else {
			limiterTick++;
		}
	}

	// mouse event bindings
	// update the mouse coordinates on mousemove
	canvas.addEventListener('mousemove', function(e) {
		mx = e.pageX - canvas.offsetLeft;
		my = e.pageY - canvas.offsetTop;
	});

	// toggle mousedown state and prevent canvas from being selected
	canvas.addEventListener('mousedown', function(e) {
		e.preventDefault();
		mousedown = true;
	});

	canvas.addEventListener('mouseup', function(e) {
		e.preventDefault();
		mousedown = false;
	});

	// once the window loads, we are ready for some fireworks!
	window.onload = loop;
</script>

<!-- <script>
	// Fade-out effect before navigation
	document.addEventListener("DOMContentLoaded", function() {
		// Add event listeners to all links
		document.querySelectorAll('a').forEach(function(link) {
			link.addEventListener('click', function(event) {
				// Ensure the link has an href and isn't an anchor or external link
				if (link.href && link.target !== "_blank" && !link.href.startsWith('#') && link.origin === location.origin) {
					event.preventDefault(); // Prevent default navigation
					document.body.classList.add('fade-out'); // Add fade-out effect
					setTimeout(function() {
						window.location.href = link.href; // Navigate to the link
					}, 500); // Match the transition duration
				}
			});
		});
	});

	// Fade-in effect after page load
	window.addEventListener('pageshow', function() {
		document.body.classList.remove('fade-out');
	});
</script> -->


</html>
<?php $this->endPage() ?>