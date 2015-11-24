<?php
// args = front, titleonly

header('Content-Type: application/json');
session_start();
mysql_connect('localhost', 'user', 'password');
mysql_select_db('seed');

$final = array('status' => 'failed', 'posts' => null);
if(isset($_GET['front'])){
	$front = (bool)$_GET['front'];	
}else{
	$front = false;
}

if(isset($_GET['titleonly'])){
	$titleonly = (bool)$_GET['titleonly'];
}else{
	$titleonly = false;
}

if(!isset($_SESSION['email'])){
	$final['status'] = 'err_not_logged';
	
}else{
	if($front){
		$sql = "SELECT
	                `user_name` AS `user`,
	                `post_id` AS `id`,
	                `post_title` AS `title`,
	                `post_body` AS `body`,
	                `url`,
	                DATE_FORMAT(`post_date`, '%m-%d-%Y') AS `date`
	            FROM `posts`
	            ORDER BY `id` DESC
	            LIMIT 0 , 5";
	    if($titleonly){
			$sql = "SELECT
						`post_id` AS `id`,
						`post_title` AS `title`
					FROM `posts`
					ORDER BY `id` DESC
					LIMIT 0 , 5";
		}
	}elseif($titleonly){
		$sql = "SELECT
					`post_id` AS `id`,
					`post_title` AS `title`
				FROM `posts`
				ORDER BY `id` DESC";
	}else{
		$sql = "SELECT
	            `user_name` AS `user`,
	            `post_id` AS `id`,
	            `post_title` AS `title`,
	            `post_body` AS `body`,
	            `url`,
	            DATE_FORMAT(`post_date`, '%m-%d-%Y') AS `date`
	        FROM `posts`
	        ORDER BY `id` DESC";
	}
	if(isset($titleonly)){
		$sql = mysql_query($sql);
		$rows = array();
		while (($row = mysql_fetch_assoc($sql)) !== false){
		    $rows[] = array(
		        'id'        => $row['id'],
		        'title'     => $row['title']
		    );
		}
	}else{
		$sql = mysql_query($sql);
		$rows = array();
		while (($row = mysql_fetch_assoc($sql)) !== false){
		    $rows[] = array(
		        'user'       => $row['user'],
		        'id'        => $row['id'],
		        'title'     => $row['title'],
		        'body'      => $row['body'],
		        'date'      => $row['date'],
		        'url'		=> $row['url']
		    );
		}
		function truncate($text, $length, $suffix = '&hellip;', $isHTML = true) {
		        $i = 0;
		        $simpleTags=array('br'=>true,'hr'=>true,'input'=>true,'image'=>true,'link'=>true,'meta'=>true);
		        $tags = array();
		        if($isHTML){
		            preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
		            foreach($m as $o){
		                if($o[0][1] - $i >= $length)
		                    break;
		                $t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
		                // test if the tag is unpaired, then we mustn't save them
		                if($t[0] != '/' && (!isset($simpleTags[$t])))
		                    $tags[] = $t;
		                elseif(end($tags) == substr($t, 1))
		                    array_pop($tags);
		                $i += $o[1][1] - $o[0][1];
		            }
		        }
		
		        // output without closing tags
		        $output = substr($text, 0, $length = min(strlen($text),  $length + $i));
		        // closing tags
		        $output2 = (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');
		
		        // Find last space or HTML tag (solving problem with last space in HTML tag eg. <span class="new">)
		        $pos = (int)end(end(preg_split('/<.*>| /', $output, -1, PREG_SPLIT_OFFSET_CAPTURE)));
		        // Append closing tags to output
		        $output.=$output2;
		
		        // Get everything until last space
		        $one = substr($output, 0, $pos);
		        // Get the rest
		        $two = substr($output, $pos, (strlen($output) - $pos));
		        // Extract all tags from the last bit
		        preg_match_all('/<(.*?)>/s', $two, $tags);
		        // Add suffix if needed
		        if (strlen($text) > $length) { $one .= $suffix; }
		        // Re-attach tags
		        $output = $one . implode($tags[0]);
		
		        //added to remove  unnecessary closure
		        $output = str_replace('</!-->','',$output); 
		
		        return $output;
		    }
		
		$index = -1;
		foreach($rows AS $row){
		    $index++;
		    if($rows[$index]['url'] == 1){
		    	$rows[$index]['title'] = "<i class='icon-link'></i> ".$rows[$index]['title'];
		    }
		    $rows[$index]['preview'] = truncate($row['body'], 250);
		}
	}
	
	$final['posts'] = $rows;
	$final['status'] = 'success';
	}

echo json_encode($final);

?>