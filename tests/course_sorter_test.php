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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Tests for course due date ordering.
 *
 * @package    block_masterdashboard
 * @category   test
 * @copyright  2025 Ralf Hagemeister
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_masterdashboard;

use block_masterdashboard\local\course_sorter;

/**
 * Tests that urgent courses are displayed first.
 *
 * @covers \block_masterdashboard\local\course_sorter
 */
final class course_sorter_test extends \advanced_testcase {
    /**
     * Dates are ascending, with missing due dates last.
     */
    public function test_nearest_enddate_first(): void {
        $courses = [
            1 => (object) ['enddate' => 2000],
            2 => (object) ['enddate' => 0],
            3 => (object) ['enddate' => 1000],
            4 => (object) ['enddate' => null],
            5 => (object) ['enddate' => 1500],
        ];

        course_sorter::sort_by_enddate($courses);

        $this->assertSame([3, 5, 1, 2, 4], array_keys($courses));
    }

    /**
     * Equal dates remain in the same sequence.
     */
    public function test_equal_enddates_preserve_order(): void {
        $courses = [
            10 => (object) ['enddate' => 1000],
            11 => (object) ['enddate' => 1000],
            12 => (object) ['enddate' => 2000],
        ];

        course_sorter::sort_by_enddate($courses);

        $this->assertSame([10, 11, 12], array_keys($courses));
    }
}
