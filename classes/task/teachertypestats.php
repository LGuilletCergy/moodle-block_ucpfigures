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
 * Initially developped for :
 * Université de Cergy-Pontoise
 * 33, boulevard du Port
 * 95011 Cergy-Pontoise cedex
 * FRANCE
 *
 * Block displaying stats about the site.
 *
 * @package    block_ucpfigures
 * @author     Laurent Guillet <laurent.guillet@u-cergy.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 *
 * File : classes/task/teachertypestats.php
 * Collect statistics.
 *
 */

namespace block_ucpfigures\task;

defined('MOODLE_INTERNAL') || die;

class teachertypestats extends \core\task\adhoc_task {

    public function execute() {

        global $DB;

        $DB->delete_records('ucpfigures_teachertype');

        $xmldocteachers = new \DOMDocument();
        $xmldocteachers->load('/home/referentiel/DOKEOS_Enseignants_Affectations.xml');
        $xpathvarteachers = new \Domxpath($xmldocteachers);
        $listteachers = $xpathvarteachers->query('//Teacher');

        $timestatbeginningtemp = strptime('01/07/' . $CFG->thisyear, '%d/%m/%Y');
        $timestatbeginning = mktime(0, 0, 0, $timestatbeginningtemp['tm_mon'] + 1,
                $timestatbeginningtemp['tm_mday'], $timestatbeginningtemp['tm_year'] + 1900);

        $roleteacherid = $DB->get_record('role', array('shortname' => 'editingteacher'))->id;

        foreach ($listteachers as $teacher) {

            $hascourse = 0;

            $teacherlogin = $teacher->getAttribute('StaffUID');

            if ($DB->record_exists('user', array('username' => $teacherlogin))) {

                $teacherrecord = $DB->get_record('user', array('username' => $teacherlogin));

                if ($DB->record_exist('role_assignments', array('roleid' => $roleteacherid,
                    'userid' => $teacherrecord->id, 'timemodified' => $timestatbeginning))) {

                    $hascourse = 1;
                }

                if ($DB->record_exists('ucpfigures_teachertype',
                        array('teachertype' => $teacher->getAttribute('LC_CORPS')))) {

                    $teachertyperecord = $DB->get_record('ucpfigures_teachertype',
                        array('teachertype' => $teacher->getAttribute('LC_CORPS')));
                    $teachertyperecord->coursecreated += $hascourse;
                    $teachertyperecord->totalusers++;

                    $DB->update_record('ucpfigures_teachertype', $teachertyperecord);
                } else {

                    $teachertyperecord = new \stdClass();
                    $teachertyperecord->teachertype = $teacher->getAttribute('LC_CORPS');
                    $teachertyperecord->coursecreated = $hascourse;
                    $teachertyperecord->totalusers = 1;

                    $DB->insert_record('ucpfigures_teachertype', $teachertyperecord);
                }
            }
        }
    }
}