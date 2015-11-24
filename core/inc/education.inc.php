<?php

function get_posts($front = false){
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

    return $rows;
}

function edit_post($id, $title, $body){
    $id = (int)$id;
    $title = mysql_real_escape_string(html_escape($title));
    $body = mysql_real_escape_string($body);

    mysql_query("UPDATE `posts` SET `post_title` = '{$title}', `post_body` = '{$body}' WHERE `post_id` = '{$id}'");
}

function valid_post($id){
    $id = (int)$id;
    $total = mysql_query("SELECT COUNT(`post_id`) FROM `posts` WHERE `post_id` = '{$id}'");
    return (mysql_result($total, 0) == '1') ? true : false;
}

function get_post($id){
    $sql = "SELECT
                `user_name` AS `user`,
                `post_title` AS `title`,
                `post_body` AS `body`,
                DATE_FORMAT(`post_date`, '%m-%d-%Y') AS `date`,
                `url`
            FROM `posts`
            WHERE `post_id` = '{$id}'";
    $sql = mysql_query($sql);
    return mysql_fetch_assoc($sql);
}

function add_post($id, $alias, $title, $body, $url = 0){
    $id = (int)$id;
    $alias = mysql_real_escape_string(html_escape($alias));
    $title = mysql_real_escape_string(html_escape($title));
    $body = mysql_real_escape_string($body);
    $url = (int)$url;
    
    if($url != 0 && $url != 1){
	    return false;
    }

    mysql_query("INSERT INTO `posts` (`user_id`, `user_name`, `post_title`, `post_body`, `post_date`, `url`) VALUES ('{$id}', '{$alias}', '{$title}', '{$body}', CURDATE(), '{$url}')");
}

?>