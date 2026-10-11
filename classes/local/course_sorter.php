<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <https://www.gnu.org/licenses/>.

/**
 * Course sorting helper.
 *
 * @package    block_masterdashboard
 * @copyright  2025 Ralf Hagemeister
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_masterdashboard\local;

/**
 * Sort enrolled courses by nearest due date.
 */
final class course_sorter {
    /**
     * Sort courses in ascending end-date order; courses without an end date come last.
     *
     * @param array $courses Array of course records.
     * @return void
     */
    public static function sort_by_enddate(array &$courses): void {
        uasort($courses, static function ($a, $b): int {
            $datea = !empty($a->enddate) ? (int) $a->enddate : PHP_INT_MAX;
            $dateb = !empty($b->enddate) ? (int) $b->enddate : PHP_INT_MAX;
            return $datea <=> $dateb;
        });
    }
}
