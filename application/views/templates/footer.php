    </div>
   
<?php 
$url = $_SERVER['REQUEST_URI'];
if(strpos($url, 'contact')||strpos($url, 'create')||strpos($url, 'edit'))
{
    echo "<script>CKEDITOR.replace( 'editor1' );</script>";
}
?>    

</body>
</html>

