<?php
	function binsearch($handle, $key, $begin, $end) {
		$offset = intdiv(($begin + $end), 2);
		fseek($handle, $offset);
		while ($offset > 0 && fgetc($handle) != "\x0a") {
			fseek($handle, --$offset);
		}
		$line = fgets($handle);
		$buffer = explode("\t", $line);
		if ($buffer[0] == $key) {
			return str_replace("\x0a", "", $buffer[1]);
		}
		if (($begin == $offset) || ($end == $offset+strlen($line)))
			return NULL;
		if (strcmp($buffer[0], $key) < 0) {
			return binsearch($handle, $key, $offset, $end);
		}
		else {
			return binsearch($handle, $key, $begin, $offset+strlen($line));
		}
	}
	
	function search($path, $key) {
	if (!is_readable($path))
		die ("can't read " . $path);
	$handle = fopen($path, 'rb');
	$value = binsearch($handle, $key, 0, filesize($path));
	fclose($handle);
	return $value;
	}
?>