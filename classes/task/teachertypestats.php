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

class teachertypestats extends \core\task\scheduled_task {

    public function get_name() {
        // Shown in admin screens.

        return get_string('teachertypestats', 'block_ucpfigures');
    }

    public function execute() {

        global $DB, $CFG;

        $DB->delete_records('block_ucpfigures_teachertype', array());
        $DB->delete_records('block_ucpfigures_teacherinfo', array());

        $xmldocteachers = new \DOMDocument();
        $xmldocteachers->load('/home/referentiel/sefiap_personnel_composante.xml');
        $xpathvarteachers = new \Domxpath($xmldocteachers);
        $listteachers = $xpathvarteachers->query('//Composante/Service/Individu');

        $timestatbeginningtemp = strptime('01/07/' . $CFG->thisyear, '%d/%m/%Y');
        $timestatbeginning = mktime(0, 0, 0, $timestatbeginningtemp['tm_mon'] + 1,
                $timestatbeginningtemp['tm_mday'], $timestatbeginningtemp['tm_year'] + 1900);

        $roleteacherid = $DB->get_record('role', array('shortname' => 'editingteacher'))->id;

        foreach ($listteachers as $teacher) {

            $composante = $teacher->parentNode->parentNode;

            $hascourse = 0;
            $isactive = 1;

            if ($teacher->hasAttribute('UID')) {

                $teacherlogin = $teacher->getAttribute('UID');
                $teacheractivity = $teacher->getAttribute('POSITION');

                echo $teacherlogin."\n";

                if ($DB->record_exists('user', array('username' => $teacherlogin))) {

                    echo "Test 1\n";

                    $teacherrecord = $DB->get_record('user', array('username' => $teacherlogin));

                    $sqldistinctcourses = "SELECT COUNT(DISTINCT contextid) AS nbdistinctcourses FROM {role_assignments}"
                            . " WHERE roleid = $roleteacherid AND timemodified > $timestatbeginning AND"
                            . " userid = $teacherrecord->id";
                    $nbdistinctcourses = $DB->get_record_sql($sqldistinctcourses)->nbdistinctcourses;

                    if ($nbdistinctcourses) {

                        echo "Test 2\n";

                        $hascourse = 1;
                    }

                    if ($teacheractivity == "Sursitaire" || $teacheractivity == "Détachement") {

                        echo "Test 3\n";

                        $isactive = 0;
                    }

                    echo "Test 4\n";

                    $teachercorps = $teacher->getAttribute('LIBELLE_CORPS');
                    $teacherservice = $composante->getAttribute('LL_COMPOSANTE');

                    echo "Test 5\n";

                    if (isset($teachercorps) && isset($teacherservice)) {

                        echo "Test 6\n";

                        if ($DB->record_exists('block_ucpfigures_teachertype',
                                array('teachertype' => $teachercorps, 'servicename' => $teacherservice))) {

                            echo "Test 7\n";

                            $teachertyperecord = $DB->get_record('block_ucpfigures_teachertype',
                                    array('teachertype' => $teachercorps, 'servicename' => $teacherservice));
                            $teachertyperecord->coursecreated += $hascourse;
                            $teachertyperecord->totalusers++;

                            $DB->update_record('block_ucpfigures_teachertype', $teachertyperecord);
                        } else {

                            echo "Test 8\n";

                            $teachertyperecord = new \stdClass();
                            $teachertyperecord->servicename = $teacherservice;
                            $teachertyperecord->teachertype = $teachercorps;
                            $teachertyperecord->coursecreated = $hascourse;
                            $teachertyperecord->totalusers = 1;

                            $DB->insert_record('block_ucpfigures_teachertype', $teachertyperecord);
                        }

                        echo "Test 9\n";

                        if ($hascourse) {

                            echo "Test 10\n";

                            $teachername = $teacherrecord->lastname;
                            $teacherfirstname = $teacherrecord->firstname;
                            $teachermail = $teacherrecord->email;

                            $teacherinforecord = new \stdClass();
                            $teacherinforecord->servicename = $teacherservice;
                            $teacherinforecord->teachertype = $teachercorps;
                            $teacherinforecord->lastname = $teachername;
                            $teacherinforecord->firstname = $teacherfirstname;
                            $teacherinforecord->email = $teachermail;

                            $DB->insert_record('block_ucpfigures_teacherinfo', $teacherinforecord);
                        }

                        echo "Test 11\n";
                    }
                }
            }
        }
    }
}