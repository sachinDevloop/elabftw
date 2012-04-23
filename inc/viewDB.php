<?php
/********************************************************************************
*                                                                               *
*   Copyright 2012 Nicolas CARPi (nicolas.carpi@gmail.com)                      *
*   http://www.elabftw.net/                                                     *
*                                                                               *
********************************************************************************/

/********************************************************************************
*  This file is part of eLabFTW.                                                *
*                                                                               *
*    eLabFTW is free software: you can redistribute it and/or modify            *
*    it under the terms of the GNU Affero General Public License as             *
*    published by the Free Software Foundation, either version 3 of             *
*    the License, or (at your option) any later version.                        *
*                                                                               *
*    eLabFTW is distributed in the hope that it will be useful,                 *
*    but WITHOUT ANY WARRANTY; without even the implied                         *
*    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR                    *
*    PURPOSE.  See the GNU Affero General Public License for more details.      *
*                                                                               *
*    You should have received a copy of the GNU Affero General Public           *
*    License along with eLabFTW.  If not, see <http://www.gnu.org/licenses/>.   *
*                                                                               *
********************************************************************************/
?>
<script src="js/editinplace.js" type="text/javascript"></script>
<?php
// ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $filter_options = array(
        'options' => array(
            'min_range' => 1
        ));
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT, $filter_options);
} else {
    die("The id parameter in the URL isn't a valid item ID.");
}

// SQL for viewDB
$sql = "SELECT * FROM items WHERE id = :id";
$req = $bdd->prepare($sql);
$req->execute(array(
    'id' => $id
));
$data = $req->fetch();

// Display last 10 items
?>
<section OnClick="document.location='database.php?mode=edit&id=<?php echo $data['id'];?>'" class="item">
<a class='align_right' href='delete_item.php?id=<?php echo $data['id'];?>' onClick="return confirm('Delete this item ?');"><img src='themes/<?php echo $_SESSION['prefs']['theme'];?>/img/trash.png' title='delete' alt='delete' /></a>
<?php
echo "<span class='date'><img src='themes/".$_SESSION['prefs']['theme']."/img/calendar.png' title='date' alt='Date :' />".$data['date']."</span><br />
    <a href='database.php?mode=edit&id=".$data['id']."'><img src='themes/".$_SESSION['prefs']['theme']."/img/edit.png' title='edit' alt='edit' /></a> 
<a href='make_pdf.php?id=".$data['id']."&type=prot'><img src='themes/".$_SESSION['prefs']['theme']."/img/pdf.png' title='make a pdf' alt='pdf' /></a> 
<a href='javascript:window.print()'><img src='themes/".$_SESSION['prefs']['theme']."/img/print.png' title='Print this page' alt='Print' /></a> 
<a href='make_zip.php?id=".$data['id']."'><img src='themes/".$_SESSION['prefs']['theme']."/img/zip.gif' title='make a zip archive' alt='zip' /></a>";
// TAGS
$sql = "SELECT tag FROM items_tags WHERE item_id = ".$id;
$req = $bdd->prepare($sql);
$req->execute();
echo "<span class='tags'><img src='themes/".$_SESSION['prefs']['theme']."/img/tags.gif' alt='' /> ";
while($tags = $req->fetch()){
    echo "<a href='database.php?mode=show&tag=".stripslashes($tags['tag'])."'>".stripslashes($tags['tag'])."</a> ";
}
echo "</span>";
// END TAGS
?>
<?php
echo "<p class='title'>". stripslashes($data['title']) . "</p>";
// BODY (show only if not empty)
if ($data['body'] != ''){
echo "<div class='txt'>".stripslashes($data['body'])."</div>";
}
// Get userinfo
$sql = "SELECT firstname, lastname FROM users WHERE userid = :userid";
$requser = $bdd->prepare($sql);
$requser->execute(array(
    'userid' => $data['userid']
));
$datauser = $requser->fetch();
echo "Last modified by ".$datauser['firstname']." ".$datauser['lastname']." on ".$data['date'];
echo "</section>";
// DISPLAY FILES
require_once('inc/display_file.php');
// KEYBOARD SHORTCUTS
echo "<script type='text/javascript'>
key('".$_SESSION['prefs']['shortcuts']['create']."', function(){location.href = 'create_item.php?type=prot'});
key('".$_SESSION['prefs']['shortcuts']['edit']."', function(){location.href = 'protocols.php?mode=edit&id=".$id."'});
</script>";
?>
