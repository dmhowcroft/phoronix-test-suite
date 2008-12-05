<?php

/*
	Phoronix Test Suite
	URLs: http://www.phoronix.com, http://www.phoronix-test-suite.com/
	Copyright (C) 2008, Phoronix Media
	Copyright (C) 2008, Michael Larabel
	phoronix-test-suite.php: The main code for initalizing the Phoronix Test Suite (pts-core) client

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://www.gnu.org/licenses/>.
*/


require("pts-core/functions/pts-functions.php");
pts_init(); // Initalize the Phoronix Test Suite (pts-core) client

$sent_command = strtolower(str_replace("-", "_", $argv[1]));
if(!is_file("pts-core/options/" . $sent_command . ".php"))
{
	exit(3);
}

// Register PTS Process
if(pts_process_active("phoronix-test-suite"))
{
	echo pts_string_header("WARNING: It appears that the Phoronix Test Suite is already running...\nFor proper results, only run one instance at a time.");
}
pts_process_register("phoronix-test-suite");
register_shutdown_function("pts_shutdown");

pts_module_startup_init(); // Initialize the PTS module system

// Read passed arguments
$pass_args = array();
for($i = 3; $i < $argc; $i++)
{
	if(isset($argv[$i]))
	{
		array_push($pass_args, $argv[$i]);
	}
}

pts_run_option_next($sent_command, $pass_args, $argv[2]);

while(($current_option = pts_run_option_next(false)) != false)
{
	pts_run_option_command($current_option->get_command(), $current_option->get_arguments(), $current_option->get_descriptor()); // Run command
}

?>
