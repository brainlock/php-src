--TEST--
Bug #new: it should be possible to read back what was written to a filtered stream (zlib)
--FILE--
<?php

$h = fopen('php://memory', "r+");

$f1 = stream_filter_append($h, 'zlib.deflate', STREAM_FILTER_WRITE, 9);
if(!is_resource($f1)){
    throw new Exception("cannot apply compress filter");
}

$f2 = stream_filter_append($h, 'zlib.inflate', STREAM_FILTER_READ);
if(!is_resource($f2)){
    throw new Exception("cannot apply inflate filter");
}

printf("Written %d bytes\n", fwrite($h, "foofoo"));

printf("Written %d bytes\n", fwrite($h, "barbar"));

printf("fseek() returned %d\n", fseek($h, 0));

var_dump(stream_get_contents($h));

?>
--EXPECT--
Written 6 bytes
Written 6 bytes
fseek() returned 0
string(12) "foofoobarbar"
