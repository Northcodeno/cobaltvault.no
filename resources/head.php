<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Northcode">
<meta name="keywords" content="Cobalt,Oxeye,Mojang,Games,Maps,Mods,Central,Share">

<?php
$style = "/style/bootstrap.flatly.min.css";
if(isset($_GET['style']))
{
	$style = htmlspecialchars($_GET['style']);
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo $style; ?>">
<link rel="stylesheet" type="text/css" href="/style/main.css">
<link rel="icon" 
      type="image/png" 
      href="/images/logo2.png">
<link rel="stylesheet" type="text/css" href="/style/fuelux.min.css">
<script src="/scripts/jquery.min.js"></script>
<script src="/scripts/bootstrap.min.js"></script>
<script src="/scripts/fuelux.min.js"></script>
<script src="/scripts/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
	selector: ".tinymce",
	height: 200,
	plugins: [
	"advlist autolink lists link image charmap print preview anchor",
	"searchreplace visualblocks code",
	"insertdatetime media table contextmenu paste"
	],
	toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/resources/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/lib.php");
$login;
if(isset($_SESSION['id']))
{
	$login = true;
}
else
{
	$login = false;
}
?>


<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-48571713-1', 'cobaltvault.no');
  ga('send', 'pageview');

</script>
