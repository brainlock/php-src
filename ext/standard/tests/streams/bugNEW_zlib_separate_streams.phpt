--TEST--
Bug #new: fflush() on filtered streams
--FILE--
<?php

$h = fopen('php://memory', "r+");

$f1 = stream_filter_append($h, 'zlib.deflate', STREAM_FILTER_WRITE, 9);
if(!is_resource($f1)){
    throw new Exception("cannot apply compress filter");
}

printf("Written %d bytes\n", fwrite($h, "foofoo"));

printf("Written %d bytes\n", fwrite($h, "barbar"));

fseek($h, 0);


$reader = fopen('php://memory', "r+");

$f2 = stream_filter_append($h, 'zlib.inflate', STREAM_FILTER_READ);

if(!is_resource($f2)){
    throw new Exception("cannot apply inflate filter");
}

stream_copy_to_stream($h, $reader);

printf("fseek() returned %d\n", fseek($reader, 0));

var_dump(stream_get_contents($reader));

?>
--EXPECT--
Written 6 bytes
Written 6 bytes
fseek() returned 0
string(12) "foofoobarbar"
