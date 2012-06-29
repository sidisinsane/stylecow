<?php
/**
 * Stylecow PHP library
 *
 * Math plugin
 * To execute math operations.
 *
 * Examples:
 * font-size: math(3 + 8)em
 *
 * PHP version 5.3
 *
 * @author Oscar Otero <http://oscarotero.com> <oom@oscarotero.com>
 * @license GNU Affero GPL version 3. http://www.gnu.org/licenses/agpl-3.0.html
 * @version 1.0.0 (2012)
 */

namespace Stylecow\Plugins;

use Stylecow\Stylecow;

class Math extends Plugin implements PluginsInterface {
	static protected $position = 4;


	/**
	 * Resolve all math functions
	 *
	 * @param array $array_code The piece of the parsed css code
	 *
	 * @return array The transformed code
	 */
	public function transform (array $array_code) {
		return Stylecow::valueWalk($array_code, function ($value) {
			return Stylecow::executeFunctions($value, 'math', function ($parameters) {
				$units = '';
				$operations = $parameters[0];

				if (strpos($operations, 'px')) {
					$units = 'px';
					$operations = str_replace('px', '', $operations);
				} else if (strpos($operations, '%')) {
					$units = '%';
					$operations = str_replace('%', '', $operations);
				} else if (strpos($operations, 'em')) {
					$units = 'em';
					$operations = str_replace('em', '', $operations);
				} else if (strpos($operations, 'rem')) {
					$units = 'rem';
					$operations = str_replace('rem', '', $operations);
				} else if (strpos($operations, 'pt')) {
					$units = 'pt';
					$operations = str_replace('pt', '', $operations);
				}

				if (preg_match('/^[\+\*\/\.\(\)0-9- ]*$/', $operations)) {
					$calculate = create_function('', 'return('.$operations.');');

					return round($calculate(), 2).$units;
				}
			});
		});
	}
}