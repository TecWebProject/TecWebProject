<?php

/**
 *
 */
class Paths
{
    public static function getRelativePath($from, $to)
    {
        // Fix path Windows
        $from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
        $to   = is_dir($to)   ? rtrim($to, '\/') . '/'   : $to;
        $from = str_replace('\\', '/', $from);
        $to   = str_replace('\\', '/', $to);

        $from     = explode('/', $from);
        $to       = explode('/', $to);
        $relPath  = $to;

        foreach($from as $depth => $dir) {
            // Find first non matching directory
            if($dir === $to[$depth]) {
                // Ignore this directory
                array_shift($relPath);
            } else {
                // Get number of remaining dirs to $from
                $remaining = count($from) - $depth;
                if($remaining > 1) {
                    // Add traversals up to first matching dir
                    $padLength = (count($relPath) + $remaining - 1) * -1;
                    $relPath = array_pad($relPath, $padLength, '..');
                    break;
                } else {
                    $relPath[0] = './' . $relPath[0];
                }
            }
        }
        return implode('/', $relPath);
    }

}


    ?>
