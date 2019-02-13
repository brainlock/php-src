--TEST--
Bug #new: fflush() on filtered streams
--FILE--
<?php

$fname = tempnam(sys_get_temp_dir(), "foo");

$h = fopen($fname, "w");

$f1 = stream_filter_append($h, 'zlib.deflate', STREAM_FILTER_WRITE, 9);
if(!is_resource($f1)){
    throw new Exception("cannot apply compress filter");
}

printf("Written %d bytes\n", fwrite($h, "foofoo"));

printf("Written %d bytes\n", fwrite($h, "barbar"));

fclose($h);

$contents = file_get_contents($fname);

var_dump(gzinflate($contents));

?>
--EXPECT--
Written 6 bytes
Written 6 bytes
string(12) "foofoobarbar"
