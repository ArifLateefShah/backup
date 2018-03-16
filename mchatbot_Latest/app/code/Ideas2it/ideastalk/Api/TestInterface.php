<?php
namespace Ideas2it\ideastalk\Api;

interface TestInterface {
	/**
	 * Test function
	 *
	 * @api
	 * @return string
	 */
	public function test();

	/**
	 * Test function
	 *
	 * @api
	 * @param $param string
	 * @return string
	 */
	public function test1($param);

	/**
	 * info function
	 *
	 * @api
	 *
	 * @return string
	 */
	public function info();

}
