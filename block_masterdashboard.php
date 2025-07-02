<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Masterdashboard block definition.
 *
 * @package   block_masterdashboard
 * @copyright 2025 Ralf Hagemeister <ralf.hagemeister@lernsteine.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class block_masterdashboard - provides the main dashboard block.
 */
class block_masterdashboard extends block_base {
    /**
     * Initializes the block with a title.
     */
    public function init() {
        $this->title = '';
    }

    /**
     * Generates the block content.
     *
     * @return stdClass
     */
    public function get_content() {
        global $USER, $CFG, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        include_once($CFG->libdir . '/completionlib.php');
        include_once($CFG->dirroot . '/course/lib.php');

        $this->page->requires->css(new moodle_url('/blocks/masterdashboard/styles.css'));

        $courses = enrol_get_users_courses($USER->id, true, '*');

        uasort($courses, function ($a, $b) {
            return ($b->enddate ?? 0) <=> ($a->enddate ?? 0);
        });

        $fs = get_file_storage();
        $overdue = '';
        $inprogress = '';
        $completed = '';

        foreach ($courses as $course) {
            if (!$course->enablecompletion) {
                continue;
            }

            $completioninfo = new completion_info($course);
            $iscomplete = $completioninfo->is_course_complete($USER->id);
            $context = context_course::instance($course->id, IGNORE_MISSING);

            $imageurl = $OUTPUT->image_url('i/course');
            if ($fs && $context) {
                $files = $fs->get_area_files($context->id, 'course', 'overviewfiles', false, 'itemid, filepath, filename', false);
                foreach ($files as $file) {
                    if (in_array($file->get_mimetype(), ['image/jpeg', 'image/png', 'image/gif'])) {
                        $imageurl = moodle_url::make_pluginfile_url(
                            $file->get_contextid(),
                            $file->get_component(),
                            $file->get_filearea(),
                            null,
                            $file->get_filepath(),
                            $file->get_filename()
                        );
                        break;
                    }
                }
            }

            $imgtag = html_writer::empty_tag('img', [
                'src' => $imageurl,
                'class' => 'course-thumb',
                'alt' => ''
            ]);
            $courselink = html_writer::link(
                new moodle_url('/course/view.php', ['id' => $course->id]),
                format_string($course->fullname),
                ['class' => 'coursename']
            );

            $dateinfo = '';
            $status = '';
            if ($iscomplete) {
                $status = 'completed';
                $dateinfo = get_string('completedon', 'block_masterdashboard') . ': ' .
                    userdate(time(), '%A, %d.%m.%Y');
            } else if (!empty($course->enddate) && time() > $course->enddate) {
                $status = 'overdue';
                $dateinfo = get_string('duedate', 'block_masterdashboard') . ': ' .
                    userdate($course->enddate, '%A, %d.%m.%Y');
            } else {
                $status = 'inprogress';
                $dateinfo = get_string('enddate', 'block_masterdashboard') . ': ' .
                    (!empty($course->enddate) ? userdate($course->enddate, '%A, %d.%m.%Y') : '-');
            }

            $info = html_writer::div($courselink, 'coursename') .
                    html_writer::div($dateinfo, 'date');
            $infowrap = html_writer::div($info, 'course-info');

            $card = html_writer::div(
                html_writer::div($imgtag, 'course-thumb') . $infowrap,
                'course-card ' . $status
            );

            if ($status === 'completed') {
                $completed .= $card;
            } else if ($status === 'inprogress') {
                $inprogress .= $card;
            } else {
                $overdue .= $card;
            }
        }

        $output = html_writer::div(
            html_writer::div(get_string('dashboardintro', 'block_masterdashboard'), 'dashboardintro'),
            'block_masterdashboard'
        );

        if (!empty($overdue)) {
            $output .= html_writer::div(
                html_writer::tag('h3', get_string('overduecourses', 'block_masterdashboard')) .
                html_writer::div($overdue, 'course-grid'),
                'section'
            );
        }
        if (!empty($inprogress)) {
            $output .= html_writer::div(
                html_writer::tag('h3', get_string('inprogresscourses', 'block_masterdashboard')) .
                html_writer::div($inprogress, 'course-grid'),
                'section'
            );
        }
        if (!empty($completed)) {
            $output .= html_writer::div(
                html_writer::tag('h3', get_string('completedcourses', 'block_masterdashboard')) .
                html_writer::div($completed, 'course-grid'),
                'section'
            );
        }

        $this->content = new stdClass();
        $this->content->text = $output;
        $this->content->footer = '';
        return $this->content;
    }
}
