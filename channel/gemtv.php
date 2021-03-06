<?php
echo '<channel id="gemtvasia">' . PHP_EOL;
echo '<display-name>GEM TV Asia</display-name>' . PHP_EOL;
echo '<icon src="https://www.gemtvasia.com/sites/hk.gemtv/files/gem_logo_new.png" />' . PHP_EOL;
echo '<url>http://www.gemtvasia.com</url>' . PHP_EOL;
echo '</channel>' . PHP_EOL;
$content = file_get_html('https://www.gemtvasia.com/schedule/id');
$arr = $content->find('li.listing');
foreach ($arr as $key => $element) {
    $next = $arr[$key + 1];
    if (!empty($next)) {
        $name = $element->find('.content .title', 0)->plaintext;
        $name = htmlspecialchars($name);
        $dateStart = date("Ymd", strtotime($element->find('.time-date', 0)->plaintext));
        $dateStop = date("Ymd", strtotime($next->find('.time-date', 0)->plaintext));
        $clockStart = date("His", strtotime($element->find('.date', 0)->plaintext));
        $clockStop = date("His", strtotime($next->find('.date', 0)->plaintext));
        $meta = $element->find('.meta', 0)->plaintext;
        $epnum = preg_match_all("/\d+/", $meta, $epnumArr);
        $synopsis = $element->find('.synopsis', 0)->plaintext;
        $metasyn = $meta . ' | ' . trim($synopsis);
        $desc=preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $metasyn);
        echo '<programme start="' . $dateStart . $clockStart . ' +0700" stop="' . $dateStop . $clockStop . ' +0700" channel="gemtvasia">' . PHP_EOL;
        echo '<title>' . trim($name) . '</title>' . PHP_EOL;
        if (isset($epnumArr[0][1])) {
            echo '<episode-num system="xmltv_ns">' . ($epnumArr[0][0] - 1) . '.' . ($epnumArr[0][1] - 1) . '</episode-num>' . PHP_EOL;
        }else{
            echo '<episode-num system="xmltv_ns">0.' . ($epnumArr[0][0] - 1) . '</episode-num>' . PHP_EOL;
        };
        echo '<desc>' . $desc . '</desc>' . PHP_EOL;
        echo '</programme>' . PHP_EOL;
    };
};
?>
