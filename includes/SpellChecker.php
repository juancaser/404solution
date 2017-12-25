<?php

// turn on debug for localhost etc
$whitelist = array('127.0.0.1', '::1', 'localhost', 'wealth-psychology.com', 'www.wealth-psychology.com');
if (in_array($_SERVER['SERVER_NAME'], $whitelist) && is_admin()) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

/* Finds similar pages. 
 * Finds search suggestions. */

class ABJ_404_Solution_SpellChecker {
    /** 
     * @global type $abj404dao
     * @param type $requestedURL
     * @return type
     */
    function getPermalinkUsingRegEx($requestedURL) {
        global $abj404dao;
        
        $regexURLsRows = $abj404dao->getRedirectsWithRegEx();
        
        foreach ($regexURLsRows as $row) {
            $regexURL = $row['url'];
            
            $_REQUEST[ABJ404_PP]['debug_info'] = 'Applying regex \"' . $regexURL . '\" to URL: ' . $requestedURL;
            $preparedURL = str_replace('/', '\/', $regexURL);
            if (preg_match('/' . $preparedURL . '/', $requestedURL)) {
                $_REQUEST[ABJ404_PP]['debug_info'] = 'Cleared after regex.';
                $idAndType = $row['final_dest'] . '|' . $row['type'];
                $permalink = ABJ_404_Solution_Functions::permalinkInfoToArray($idAndType, '0');
                $permalink['matching_regex'] = $regexURL;
                
                return $permalink;
            }
            
            $_REQUEST[ABJ404_PP]['debug_info'] = 'Cleared after regex.';
        }
        return null;
    }

    /** If there is a post that has a slug that matches the user requested slug exactly, then return the permalink for that 
     * post. Otherwise return null.
     * @global type $abj404dao
     * @param type $requestedURL
     * @return type
     */
    function getPermalinkUsingSlug($requestedURL) {
        global $abj404dao;
        global $abj404logging;
        
        $exploded = preg_split('@/@', $requestedURL, -1, PREG_SPLIT_NO_EMPTY);
        $postSlug = end($exploded);
        $postsBySlugRows = $abj404dao->getPublishedPagesAndPostsIDs($postSlug, false);
        if (count($postsBySlugRows) == 1) {
            $post = reset($postsBySlugRows);
            $permalink['id'] = $post->id;
            $permalink['type'] = ABJ404_TYPE_POST;
            // the score doesn't matter.
            $permalink['score'] = 100;
            $permalink['title'] = get_the_title($post->id);
            $permalink['link'] = get_permalink($post->id);
            
            return $permalink;
            
        } else if (count($postsBySlugRows) > 1) {
            // more than one post has the same slug. I don't know what to do.
            $abj404logging->debugMessage("More than one post found with the slug, so no redirect was " .
                    "created. Slug: " . $postSlug);
        } else {
            $abj404logging->debugMessage("No posts or pages matching slug: " . esc_html($postSlug));
        }
        
        return null;
    }
    
    /** Use spell checking to find the correct link. Return the permalink (map) if there is one, otherwise return null.
     * @global type $abj404spellChecker
     * @global type $abj404logic
     * @param type $requestedURL
     * @return type
     */
    function getPermalinkUsingSpelling($requestedURL) {
        global $abj404spellChecker;
        global $abj404logic;
        global $abj404logging;

        $options = $abj404logic->getOptions();

        if (@$options['auto_redirects'] == '1') {
            // Site owner wants automatic redirects.
            $permalinks = $abj404spellChecker->findMatchingPosts($requestedURL, $options['auto_cats'], $options['auto_tags']);
            $minScore = $options['auto_score'];

            // since the links were previously sorted so that the highest score would be first, 
            // we only use the first element of the array;
            $linkScore = reset($permalinks);
            $idAndType = key($permalinks);
            $permalink = ABJ_404_Solution_Functions::permalinkInfoToArray($idAndType, $linkScore);

            if ($permalink['score'] >= $minScore) {
                // We found a permalink that will work!
                $redirectType = $permalink['type'];
                if (('' . $redirectType != ABJ404_TYPE_404_DISPLAYED) && ('' . $redirectType != ABJ404_TYPE_HOME)) {
                    return $permalink;

                } else {
                    $abj404logging->errorMessage("Unhandled permalink type: " . 
                            wp_kses_post(json_encode($permalink)));
                    return null;
                }
            }
        }
        
        return null;
    }

    /** Returns a list of 
     * @global type $wpdb
     * @param type $requestedURL
     * @param type $includeCats
     * @param type $includeTags
     * @return type
     */
    function findMatchingPosts($requestedURL, $includeCats = '1', $includeTags = '1') {
        global $abj404dao;
        global $abj404logic;
        global $abj404logging;
        
        $permalinks = array();
        
        $separatingCharacters = array("-", "_", ".", "~");
        $requestedURLSpaces = str_replace($separatingCharacters, " ", $requestedURL);
        $requestedURLCleaned = $this->getLastURLPart($requestedURLSpaces);

        // match based on the slug.
        $rows = $abj404dao->getPublishedPagesAndPostsIDs('', false);
        foreach ($rows as $row) {
            $id = $row->id;
            $the_permalink = get_permalink($id);
            $urlParts = parse_url($the_permalink);
            $existingPageURL = $abj404logic->removeHomeDirectory($urlParts['path']);
            $existingPageURLSpaces = str_replace($separatingCharacters, " ", $existingPageURL);
            $existingPageURLCleaned = $this->getLastURLPart($existingPageURLSpaces);
            
            $levscore = $this->customLevenshtein($requestedURLCleaned, $existingPageURLCleaned, 1, 1, 1);
            $scoreBasis = mb_strlen($existingPageURLCleaned) * 3;

            if ($scoreBasis == 0) {
                continue;
            }
            $score = 100 - ( ( $levscore / $scoreBasis ) * 100 );
            $permalinks[$id . "|" . ABJ404_TYPE_POST] = number_format($score, 4, '.', '');
        }

        // search for a similar tag.
        if ($includeTags == "1") {
            $rows = $abj404dao->getPublishedTags();
            foreach ($rows as $row) {
                $id = $row->term_id;
                $the_permalink = get_tag_link($id);
                $urlParts = parse_url($the_permalink);
                $scoreBasis = mb_strlen($urlParts['path']);
                $levscore = $this->customLevenshtein($requestedURLCleaned, $urlParts['path'], 1, 1, 1);
                $score = 100 - ( ( $levscore / $scoreBasis ) * 100 );
                $permalinks[$id . "|" . ABJ404_TYPE_TAG] = number_format($score, 4, '.', '');
            }
        }

        // search for a similar category.
        if ($includeCats == "1") {
            $rows = $abj404dao->getPublishedCategories();
            foreach ($rows as $row) {
                $id = $row->term_id;
                $the_permalink = get_category_link($id);
                $urlParts = parse_url($the_permalink);
                $scoreBasis = mb_strlen($urlParts['path']);
                $levscore = $this->customLevenshtein($requestedURLCleaned, $urlParts['path'], 1, 1, 1);
                $score = 100 - ( ( $levscore / $scoreBasis ) * 100 );
                $permalinks[$id . "|" . ABJ404_TYPE_CAT] = number_format($score, 4, '.', '');
            }
        }

        // This is sorted so that the link with the highest score will be first when iterating through.
        arsort($permalinks);
        
        return $permalinks;
    }
    
    /** Turns "/abc/defg" into "defg"
     * @param type $url
     * @return type
     */
    function getLastURLPart($url) {
        $newURL = $url;
        
        if (strrpos($url, "/")) {
            $newURL = mb_substr($url, strrpos($url, "/") + 1);
        }
        
        return $newURL;
    }

    /** This custom levenshtein function has no 255 character limit.
     * 
     * It was modified to work with early versions of php. This is copied from 
     * https://github.com/GordonLesti/levenshtein which had a very liberal MIT license at the time of this writing.
     * @param type $str1
     * @param type $str2
     * @param type $costIns
     * @param type $costRep
     * @param type $costDel
     * @return type
     */
    public function customLevenshtein($str1, $str2, $costIns = 1.0, $costRep = 1.0, $costDel = 1.0) {
        $_REQUEST[ABJ404_PP]['debug_info'] = 'customLevenshtein. str1: ' . esc_html($str1) . ', str2: ' . esc_html($str2);
        
        $matrix = array();
        $str1Array = self::multiByteStringToArray($str1);
        $str2Array = self::multiByteStringToArray($str2);
        $str1Length = count($str1Array);
        $str2Length = count($str2Array);
        $row = array();
        $row[0] = 0.0;
        for ($j = 1; $j < $str2Length + 1; $j++) {
            $row[$j] = $j * $costIns;
        }
        $matrix[0] = $row;
        for ($i = 0; $i < $str1Length; $i++) {
            $row = array();
            $row[0] = ($i + 1) * $costDel;
            for ($j = 0; $j < $str2Length; $j++) {
                $minPart1 = $matrix[$i][$j + 1] + $costDel;
                $minPart2 = $row[$j] + $costIns;
                $minPart3 = $matrix[$i][$j] + ($str1Array[$i] === $str2Array[$j] ? 0.0 : $costRep);
                $row[$j + 1] = min($minPart1, $minPart2, $minPart3);
            }
            $matrix[$i + 1] = $row;
        }
        
        $_REQUEST[ABJ404_PP]['debug_info'] = 'Cleared after customLevenshtein.';
        return $matrix[$str1Length][$str2Length];
    }
    
    /** 
     * @param type $str
     * @return type
     */
    private function multiByteStringToArray($str) {
        $length = mb_strlen($str);
        $array = array();
        for ($i = 0; $i < $length; $i++) {
            $array[$i] = mb_substr($str, $i, 1);
        }
        return $array;
    }

}
