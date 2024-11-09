<?php
    exec('TERM=xterm /usr/bin/top n 1 b i', $top, $error);
    echo nl2br(implode("\n", $top));
    if ($error) {
        exec('TERM=xterm /usr/bin/top n 1 b 2>&1', $error);
        echo "Error: ";
        exit($error[0]);
    }

?>