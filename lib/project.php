<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/resources/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/northcode_api.php");

function unsetValue(array $array, $value, $strict = TRUE)
{
    if(($key = array_search($value, $array, $strict)) !== FALSE) {
        unset($array[$key]);
    }
    return $array;
}

define("PROJECT_FILES_SINGLE_LOCATION", $_SERVER['DOCUMENT_ROOT'] . "/files/project_files/");
define("PROJECT_TMP_LOCATION", $_SERVER['DOCUMENT_ROOT'] . "/files/tmp/");
define("PROJECT_MAP_ZIP_LOCATION", $_SERVER['DOCUMENT_ROOT'] . "/files/project_zips/");

class Project
{
	// Unique Identifier [int]
	public $id;
	// Unique Identifier [string]
	public $idname;
	// Project Name
	public $name;
	// Project Description
	public $desc;
	// Project Type
	public $type;
	public $typename;
	// Amount of total downloads the project has
	public $downloads;
	// Amount of project downloads
	public $downloads_project;
	// Date of when the project was created
	public $date_created;
	// Date of when a map file has been last updated
	public $date_modified;
	// Array of Author objects
	public $authors;
	// Bool value if the project is public (1 = yes)
	public $public;
	// Visible rating
	public $rating;
	// Actual rating [Decreased procedurally]
	public $rating_actual;
	// Url of the thumbnail [First picture in the desc]
	public $thumbnail_url;
	// Array of Files objects
	public $files;
	// Version of the format the map has [0=old system,1=new system]
	public $format_version;
	// Array of comments
	public $comments;

	public function __construct($id)
	{
		global $mysql;


		/// Project Data
		$q; if(is_numeric($id)) $q = "id"; else $q = "idname";

		$stmt = $mysql->prepare("SELECT
			projects.id,
			projects.idname,
			projects.name,
			projects.desc,
			projects.type,
			projects_maptypes.name,
			projects.downloads,
			DATE_FORMAT(projects.date_created,'%d.%m.%Y'),
			DATE_FORMAT(projects.date_modified,'%d.%m.%Y'),
			projects.public,
			projects.rating,
			projects.rating_actual,
			projects.thumbnail_url,
			projects.format_version
			FROM projects
			LEFT JOIN projects_maptypes ON projects.type = projects_maptypes.id
			WHERE projects.$q = ?");
		$stmt->bind_param(($q == "id" ? 'i' : 's'),$id);
		$stmt->execute();
		$stmt->store_result();
		if($stmt->num_rows < 1)
			throw new Exception("Project not found!");

		$stmt->bind_result(
			$_id,
			$_idname,
			$_name,
			$_desc,
			$_type,
			$_typename,
			$_downloads,
			$_date_created,
			$_date_modified,
			$_public,
			$_rating,
			$_rating_actual,
			$_thumbnail_url,
			$_format_version
			);
		$stmt->fetch();
		$this->id = $_id;
		if(isset($_idname) && $_idname != "")
			$this->idname = $_idname;
		else
			$this->idname = $this->id;
		$this->name = $_name;
		$this->desc = $_desc;
		$this->type = $_type;
		$this->typename = $_typename;
		$this->downloads = $_downloads;
		$this->downloads_project = $_downloads;
		$this->date_created = $_date_created;
		$this->date_modified = $_date_modified;
		$this->public = $_public;
		$this->filename = $_public;
		$this->rating = $_rating;
		$this->rating_actual = $_rating_actual;
		$this->thumbnail_url = $_thumbnail_url;
		$this->format_version = $_format_version;
		$stmt->close();


		/// Author Data
		$this->authors = array();
		$stmt = $mysql->prepare("SELECT pid,uid FROM projects_authors WHERE pid = ?");
		$stmt->bind_param('i',$this->id);
		$stmt->execute();
		$stmt->bind_result($_pid,$_uid);
		while($stmt->fetch())
		{
			$udata = nc_api::get_user_info($_uid);
			$this->authors[] = new Author($_uid,$udata['username']);
		}
		$stmt->close();

		/// File data
		$this->files = array();
		$stmt = $mysql->prepare("SELECT
			projects_files.id,
			projects_files.filename,
			projects_files.type,
			projects_files_types.name
			FROM projects_files
			LEFT JOIN projects_files_types ON projects_files_types.id = projects_files.type
			WHERE projects_files.pid = ?");
		$stmt->bind_param('i',$this->id);
		$stmt->execute();
		$stmt->bind_result($_id,$_filename,$_type,$_typename);
		while($stmt->fetch())
		{
			$this->files[] = new File($_id,$_filename,$_type,$_typename);
		}
		$stmt->close();

		/// Comments
		$this->comments = array();
		$tmpcomments = array();
		$stmt = $mysql->prepare("SELECT id, title, `message`, `date`, author, reply FROM comments WHERE project = ? ORDER BY `date` DESC");
		$stmt->bind_param('i', $this->id);
		$stmt->execute();
		$stmt->bind_result($id,$title,$msg,$date,$author,$reply);
		while($stmt->fetch())
		{
			$tmpcomments[] = array('id' => $id, 'title' => $title, 'msg' => $msg, 'date' => $date, 'author' => $author, 'reply' => $reply);
		}

		foreach($tmpcomments as $comment)
		{
			if($comment['reply'] == NULL)
			{
				$this->comments[] = new Comment($comment, $tmpcomments);
			}

		}

	}

	/*
	Editable:
	name,
	desc,
	type,
	public
	*/
	public function edit($array = array())
	{
		/* TODO: Add validation to vars */
		if(isset($array['name']))
			$this->name = $array['name'];
		if(isset($array['desc']))
			$this->desc = $array['desc'];
		if(isset($array['type']))
			$this->type = $array['type']; // Warning: The type_name will not be up to date anymore!
		if(isset($array['public']))
			$this->public = $array['public'];

		global $mysql;

		$stmt = $mysql->prepare("UPDATE projects SET name = ?, `desc` = ?, type = ?, public = ? WHERE id = ?");
		$stmt->bind_param('ssiii',$this->name,$this->desc,$this->type,$this->public,$this->id);
		$stmt->execute();
		$stmt->close();
	}

	public function delete()
	{
		foreach($this->files as $f)
		{
			$this->delFile($f->id);
		}

		// Just to make sure
		$stmt = $mysql->prepare("DELETE FROM projects_files WHERE pid = ?");
		$stmt->bind_param('i',$this->id);
		$stmt->execute();
		$stmt->close();

		$stmt = $mysql->prepare("DELETE FROM projects WHERE id = ?");
		$stmt->bind_param('i',$this->id);
		$stmt->execute();
		$stmt->close();

		$this->destroy();
	}


	// INFO: Not tested
	public function rate($user, $val, $text = "")
	{
		global $mysql;

		$stmt = $mysql->prepare("SELECT * FROM projects_rating WHERE pid = ? AND uid = ?");
		$stmt->bind_param('ii',$this->id, $user);
		$stmt->execute();
		$stmt->store_result();
		if($stmt->num_rows > 0)
			throw new Exception("You already rated this project!");
		$stmt->close();

		$stmt = NULL;
		if($text = "")
		{
			$stmt = $mysql->prepare("INSERT INTO projects_rating (uid, pid, value) VALUES (?,?,?)");
			$stmt->bind_param('iii', $user, $this->id, $val);
		}
		else
		{
			$stmt = $mysql->prepare("INSERT INTO projects_rating (uid, pid, value, `text`) VALUES (?,?,?,?)");
			$stmt->bind_param('iiis', $user, $this->id, $val, $text);
		}

		$stmt->execute();
		$stmt->close();
	}

	public function comment($author,$content, $reply = 0)
	{
		global $mysql;

		$stmt;
		if($reply == 0)
		{
			$stmt = $mysql->prepare("INSERT INTO comments (message,`date`,author,project) VALUES (?,NOW(),?,?)");
			$stmt->bind_param('sii',$content,$author,$this->id);
		}
		else
		{
			// TODO: Validate reply
			$stmt = $mysql->prepare("INSERT INTO comments (message,`date`,author,project,reply) VALUES (?,NOW(),?,?,?)");
			$stmt->bind_param('siii',$content,$author,$this->id,$reply);
		}

		$stmt->execute();
		$stmt->close();
	}

	public function delComment($cid, $uid)
	{
		global $mysql;

		$stmt = $mysql->prepare("SELECT author, project FROM comments WHERE id = ?");
		$stmt->bind_param('i', $cid);
		$stmt->execute();
		$stmt->store_result();
		if($stmt->num_rows < 1)
			throw new Exception("Comment not found!");
		$stmt->bind_result($author, $project);
		$stmt->fetch();
		if($project != $this->id)
			throw new Exception("This comment does not belong to this project");
		if($author != $uid)
			throw new Exception("You are not author of this comment!");
		$stmt->close();

		$stmt = $mysql->prepare("DELETE FROM comments WHERE id = ?");
		$stmt->bind_param('i', $cid);
		$stmt->execute();
		$stmt->close();
	}

	public function displayComments($comments = "")
	{
		global $NC_LOGGED_IN, $NC_USERINFO;

		if($comments == "")
			$comments = $this->comments;

		foreach($comments as $comment)
		{
			$userinfo = nc_api::get_user_info($comment->author);
			if($NC_LOGGED_IN)
			{
				if($NC_USERINFO['uid'] == $userinfo['uid'])
					$cauthor = true;
				else
					$cauthor = false;
			}

			$pimage = "/images/profile.png";
			$pimgdir = "files/pimages/";
			if(file_exists($pimgdir . $userinfo['username'] . ".png"))
			  $pimage = "/files/pimages/" . $userinfo['username'] . ".png";
			if(file_exists($pimgdir . $userinfo['username'] . ".jpg"))
			  $pimage = "/files/pimages/" . $userinfo['username'] . ".jpg";
			?>
			<div class="media comment">
				<a class="pull-left" href="/user/<?php echo $userinfo['uid']; ?>">
					<img class="media-object" src="<?php echo $pimage; ?>" alt="<?php echo $userinfo['username']; ?>" height="64px" width="64px">
				</a>
				<div class="media-body">
					<h4 class="media-heading"><?php echo $userinfo['username']; ?></h4>
					<?php echo $comment->text; ?>
					<?php if($NC_LOGGED_IN) { echo "<a class='reply-button' comment='" . $comment->id . "'>[reply]</a> ";  if($cauthor) echo "<a href='/scripts/project.php?a=delcomment&id=" . $this->id . "&cid=" . $comment->id . "'>[delete]</a> "; } ?><em><?php echo $comment->date; ?></em>
					<div class="reply-container" style="display:none;">
					<form method="post" action="/scripts/project.php?id=<?php echo $this->id; ?>&a=comment&reply=<?php echo $comment->id; ?>" class="form" role="form">
						<textarea class="reply-text" name="text"></textarea><br>
						<input type="submit" class="btn btn-primary" value="Reply" />
					</form>
					</div>

					<?php $this->displayComments($comment->replies); ?>

				</div>
			</div>
			<?php
		}
	}

	public function download()
	{
		global $mysql;

		if(sizeof($this->files) == 0)
			throw new Exception("No file to be downloaded!");

		if(sizeof($this->files) == 1)
		{
			$file = $this->files[0];

			// Update Db
			$stmt = $mysql->prepare("UPDATE projects SET downloads = downloads + 1 WHERE id = ?");
			$stmt->bind_param('i',$this->id);
			$stmt->execute();
			$stmt->close();

			$file->download();
		}
		else
		{

			$zip = new ZipArchive();
			$file = tempnam(PROJECT_TMP_LOCATION, "projectdownload");
			$zip->open($file,ZipArchive::OVERWRITE);
			foreach ($this->files as $f)
			{
				if(file_exists($f->getFile()))
				{
					if($f->type == 1 || $f->type == 2)
						$zip->addFile($f->getFile(), "maps/" . $f->filename);
					elseif($f->type == 3)
						$zip->addFile($f->getFile(), "daisyMoon/music/" . $f->filename);
					else
						$zip->addFile($f->getFile(), "other/" . $f->filename);
				}
			}
			$zip->close();



			// Update Db
			$stmt = $mysql->prepare("UPDATE projects SET downloads = downloads + 1 WHERE id = ?");
			$stmt->bind_param('i',$this->id);
			$stmt->execute();
			$stmt->close();

			header('Content-Type: application/zip');
			header('Content-Length: ' . filesize($file));
			header('Content-Disposition: attachment; filename="' . $this->name . '.zip"');
			readfile($file);
			unlink($file);
		}
	}

	public function prettyAuthors($f = ", ", $url = true)
	{
		$anames = array();
		foreach($this->authors as $a)
		{
			if($url)
			{
				$anames[] = "<a href='/user/" . $a->uid . "'>" . $a->username . "</a>";
			}
			else
			{
				$anames[] = $a->username;
			}
		}
		return implode($f,$anames);
	}

	public function shortDesc($len = 200)
	{
		$desc = strip_tags($this->desc);
		$str = substr($desc,0,$len);
		if ($str != $desc)
			$str .= "...";
		return $str;
	}

	public function addAuthor($uid)
	{
		global $mysql;

		if(isAuthor($uid))
		{
			throw new Exception("This user is already an Author");
		}

		$stmt = $mysql->prepare("INSERT INTO projects_authors (`pid`,`uid`) VALUES (?,?)");
		$stmt->bind_param('ii',$this->id, $uid);
		$stmt->execute();
		$stmt->close();
	}

	public function delAuthor($uid)
	{
		global $mysql;

		if(!isAuthor($uid))
			throw new Exception("This user isn't an Author");

		if(sizeof($this->authors) == 1)
			throw new Exception("You can't remove the last Author");

		$stmt = $mysql->prepare("DELETE FROM projects_authors WHERE pid = ? AND uid = ?");
		$stmt->bind_param('ii', $this->id, $uid);
		$stmt->execute();
		$stmt->close();
	}

	public function isAuthor($uid)
	{
		foreach($this->authors as $a)
		{
			if($a->uid == $uid)
				return 1;
		}
		return 0;
	}

	public function findFileById($fileid)
	{
		foreach($this->files as $file)
		{
			if($file->id == $fileid)
				return $file;
		}
		throw new Exception("File not found!");
	}

  public function getFilesByType($type)
  {
    $files = array();
    foreach($this->files as $f)
    {
      if($f->type == $type)
        $files[] = $f;
    }

    return $files;
  }

	/*
	 $data contains:
	 "name",
	 "desc",
	 "type"
	*/
	public static function create($data, $uid)
	{
		global $mysql;

		if(!isset($data['name']) || $data['name'] == "")
			throw new Exception("No name defined!");
		if(!isset($data['idname']) || $data['idname'] == "")
			throw new Exception("No idname provided!");
		if(!isset($data['desc']) || $data['desc'] == "")
			throw new Exception("No description defined!");
		if(!isset($data['type']))
			throw new Exception("No type provided");
		if(!isset($uid))
			throw new Exception("No author provided");

		// Refining
		$data['name'] = htmlentities($data['name']);
		$data['idname'] = htmlentities($data['idname']);
		$data['desc'] = stripcslashes($data['desc']);
		$type = (int) $data['type'];

		$img = getFirstImage($data['desc']);
		if($img)
			$img = str_replace("\\\"","",$img);
		else
			$img = "";

		$i = 0;
		$format_version = 1;

		$stmt = $mysql->prepare("INSERT INTO projects (`name`,`idname`,`desc`,`type`,`author`,`public`,`thumbnail_url`,`date_created`,`date_modified`,`format_version`) VALUES (?,?,?,?,?,?,?,NOW(),NOW(),?)");
		$stmt->bind_param('sssiiisi',$data['name'],$data['idname'],$data['desc'],$data['type'],$uid, $i, $img, $format_version);
		$stmt->execute();
		$iid = $stmt->insert_id;
		$stmt->close();

		$stmt = $mysql->prepare("INSERT INTO projects_authors (`uid`,`pid`) VALUES (?,?)");
		$stmt->bind_param('ii',$uid,$iid);
		$stmt->execute();
		$stmt->close();

		return $iid;
	}

	/*
	Example Input
	"projects.id = 2 OR projects.id = 3", "projects.id", 30
	*/
	public static function getProjects($w = "", $order = "projects.date_modified DESC", $limit = 50)
	{
		global $mysql;

		$data = array();
		$ids = array();
		$where = "";
		if($w != "")
		{
			$where = "WHERE " . $w;
		}
		$q = "SELECT projects.id FROM projects $where ORDER BY $order LIMIT $limit";
		$stmt = $mysql->prepare($q);
		$stmt->execute();
		$stmt->bind_result($id);
		while($row = $stmt->fetch())
		{
			$ids[] = $id;
		}
		$stmt->close();

		foreach($ids as $id)
		{
			$data[] = new Project($id);
		}

		return $data;
	}


	public static function getProjectFromFile($fileid)
	{
		global $mysql;

		$stmt = $mysql->prepare("SELECT projects.id FROM projects LEFT JOIN projects_files ON projects_files.id = ? WHERE projects_files.pid = projects.id");
		$stmt->bind_param('i', $fileid);
		$stmt->execute();
		$stmt->bind_result($id);
		$stmt->fetch();
		$PID = $id;
		$stmt->close();

		return new Project($PID);
	}


	public function addFile($POST_FILE)
	{
		global $mysql;

		$returnText = "Sucessfully added File";

		// Check if the file upload hasn't any errors
		if ($POST_FILE["error"] > 0)
			throw new Exception("File upload error: " . $POST_FILE["error"]);

		$File = new File();

		// Get filetype
		$tmp = explode(".",$POST_FILE['name']);
		$ext = end($tmp);

		// Check if filetype is allowed
		switch($ext)
		{
			case "map":
				$File->type = 1;
				//if($this->containsFileType(1))
				//	throw new Exception("Map can only contain one map file. Please use the updateFile method to update it.");
				break;
			case "localization":
				$File->type = 2;
				//if($this->containsFileType(2))
				//	throw new Exception("Map can only contain one localization file. Please use the updateFile method to update it.");
				break;
			case "ogg":
				$File->type = 3;
				break;
			case "zip":
				$File->type = 4;
				break;
			default:
				throw new Exception("Invalid file type! only use .map, .localization, .ogg or .zip");
				break;
		}

		$File->filename = $POST_FILE['name'];

		// Check if File already exists
		foreach($this->files as $f)
		{
			if($f->filename == $File->filename)
			{
				$this->delFile($f->id);
				$returnText = "Sucessfully updated File";
				break;
			}
		}

		// Insert the record to db
		$stmt = $mysql->prepare("INSERT INTO projects_files (pid,filename,type) VALUES (?,?,?)");
		$stmt->bind_param('isi',$this->id,$File->filename,$File->type);
		$stmt->execute();
		$File->id = $stmt->insert_id;
		$stmt->close();

		if($mysql->error)
			throw new Exception($mysql->error);

		// Define filename and target
		$File->filename = $POST_FILE["name"];
		$target = $File->getFile();

		// Move the uploaded file to the target
		move_uploaded_file($POST_FILE["tmp_name"],$target);

		// Add file to array for further use
		$this->files[] = $File;

		return $returnText;
	}

	public function delFile($id)
	{
		global $mysql;

		// Find the file object
		$File;
		foreach ($this->files as $f)
		{
			if($f->id == $id)
				$File = $f;
		}

		// Check if it exists
		if(!isset($File))
			throw new Exception("The file was not found in this map!");

		// Delete the actual file
		unlink($f->getFile());

		// Remove it from database
		$stmt = $mysql->prepare("DELETE FROM projects_files WHERE id = ?");
		$stmt->bind_param('i',$f->id);
		$stmt->execute();
		$stmt->close();

		$this->files = unsetValue($this->files, $File, TRUE);
	}

	public function updateFile($id, $POST_FILE)
	{
		// Check if the file upload hasn't any errors
		if ($POST_FILE["error"] > 0)
			throw new Exception("File upload error: " . $POST_FILE["error"]);

		delFile($id);
		addFile($POST_FILE);
	}

}

class File
{
	public $id;
	public $filename;
	public $type;
	public $type_name;

	private $file_location;

	public function __construct()
	{
		$this->file_location = PROJECT_FILES_SINGLE_LOCATION;


		$a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
	}

	public function __construct4($id,$filename,$type,$type_name)
	{
		$this->id = $id;
		$this->filename = $filename;
		$this->type = $type;
		$this->type_name = $type_name;
	}

	public function getFile()
	{
		return $this->file_location . $this->id;
	}

	public function getGlyphicon()
	{
		$s = "<span class='glyphicon glyphicon-%s'></span>";
		switch($this->type)
		{
			case 1:
			return sprintf($s,"map-marker");
			break;
			case 2:
			return sprintf($s,"paperclip");
			break;
			case 3:
			return sprintf($s,"music");
			break;
			case 4:
			return sprintf($s,"compressed");
			break;
			default:
			return sprintf($s,"file");
		}
		return "";
	}

	public function getBtnColor()
	{
		switch($this->type)
		{
			case 1:
			return "success";
			break;
			case 2:
			return "warning";
			break;
			case 3:
			return "default";
			break;
			case 4:
			return "info";
		}
	}

	public function setContentType()
	{
		$path_parts = pathinfo($this->filename);
		$ext = strtolower($path_parts["extension"]);
		switch ($ext) {
			case "pdf":
				header("Content-type: application/pdf");
				break;
			case "zip":
				header("Content-type: application/zip");
				break;
			default:
				header("Content-type: application/octet-stream");
				break;
		}
	}

	public function download()
	{
		$file = $this->getFile();
		$this->setContentType();
		header("Content-Lenght: " . filesize($file));
		header("Content-Disposition: attachment; filename=\"" . $this->filename . "\"");
		readfile($file);
		exit;
	}

	public function download_zip()
	{
		throw new Exception("Not implemented");
	}
}

class Author
{
	public $uid;
	public $username;
	//public $roles;

	public function __construct($uid,$username)
	{
		$this->uid = $uid;
		$this->username = $username;
		//$this->roles = $roles;
	}
}

class Comment
{
	public $id;
	public $author;
	public $title;
	public $text;
	public $date;
	public $replies;

	public function __construct($c, &$arr)
	{
		//$arr = unsetValue($c, $arr);

		$this->id = $c['id'];
		$this->author = $c['author'];
		$this->title = $c['title'];
		$this->text = $c['msg'];
		$this->date = $c['date'];
		$this->replies = array();

		foreach($arr as $comment)
		{
			if($comment['reply'] == $this->id)
				$this->replies[] = new Comment($comment, $arr);
		}
	}

}
