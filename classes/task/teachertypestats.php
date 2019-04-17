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
        $DB->delete_records('block_ucpfigures_statstype', array());

        $xmldocteachers = new \DOMDocument();
        $xmldocteachers->load('/home/referentiel/sefiap_personnel_composante.xml');
        $xpathvarteachers = new \Domxpath($xmldocteachers);
        $listteachers = $xpathvarteachers->query('//Composante/Service/Individu');

        $timestatbeginningtemp = strptime('01/07/' . $CFG->thisyear, '%d/%m/%Y');
        $timestatbeginning = mktime(0, 0, 0, $timestatbeginningtemp['tm_mon'] + 1,
                $timestatbeginningtemp['tm_mday'], $timestatbeginningtemp['tm_year'] + 1900);

        $roleteacherid = $DB->get_record('role', array('shortname' => 'editingteacher'))->id;
        $rolelocalteacher = $DB->get_record('role', array('shortname' => 'localteacher'))->id;

        $contextid = \context_system::instance()->id;

        $yearcategory = $DB->get_record('course_categories', array('idnumber' => $CFG->yearprefix));
        $yearcategorycontext = $DB->get_record('context',
                array('contextlevel' => CONTEXT_COURSECAT, 'instanceid' => $yearcategory->id));
        $pathyearcategorycontext = $yearcategorycontext->path."/";
        $teacherstreated = array();

        foreach ($listteachers as $teacher) {

            $service = $teacher->parentNode;
            $composante = $service->parentNode;

            if ($composante->getAttribute('LL_COMPOSANTE') != "") {

                $hascourse = 0;
                $isactive = 1;

                if ($teacher->hasAttribute('UID')) {

                    $teacherlogin = $teacher->getAttribute('UID');
                    $teacheractivity = $teacher->getAttribute('POSITION');

                    if ($DB->record_exists('user', array('username' => $teacherlogin))) {

                        $teacherrecord = $DB->get_record('user', array('username' => $teacherlogin));

                        if ($DB->record_exists('role_assignments',
                                array('roleid' => $rolelocalteacher, 'contextid' => $contextid,
                                    'userid' => $teacherrecord->id))) {


                            // Version normale
//                            $sqldistinctcourses = "SELECT COUNT(DISTINCT contextid) AS nbdistinctcourses "
//                                    . "FROM {role_assignments} WHERE roleid = $roleteacherid AND "
//                                    . "timemodified > $timestatbeginning AND userid = $teacherrecord->id "
//                                    . "AND contextid IN (SELECT id FROM {context} WHERE"
//                                    . " path LIKE '$pathyearcategorycontext%')";
//                            $nbdistinctcourses = $DB->get_record_sql($sqldistinctcourses)->nbdistinctcourses;
//
//                            if ($nbdistinctcourses) {
//
//                                $hascourse = 1;
//                            }
                            // Fin de la version normale.

                            // Version des profs avec cours vide, à désactiver en fonctionnement normal.

                            echo "Test 1\n";

                            $sqldistinctcoursescontext = "SELECT DISTINCT contextid "
                                    . "FROM {role_assignments} WHERE roleid = $roleteacherid AND "
                                    . "timemodified > $timestatbeginning AND userid = $teacherrecord->id "
                                    . "AND contextid IN (SELECT id FROM {context} WHERE"
                                    . " path LIKE '$pathyearcategorycontext%')";
                            $distinctcoursescontext = $DB->get_record_sql($sqldistinctcoursescontext);
                            echo "Test 2\n";

                            foreach ($distinctcoursescontext as $coursecontext) {

                                echo "Test 3\n";

                                $courseid = $DB->get_record('context', array('id' => $coursecontext))->id;

                                echo "Test 4\n";

                                $sqlnbmodules = "SELECT COUNT(DISTINCT id) AS nbmodules FROM {course_modules}"
                                        . " WHERE course = $courseid";

                                $nbmodules = $DB->get_record_sql($sqlnbmodules)->nbmodules;

                                echo "Test 5\n";

                                echo "Course id : $courseid\n";
                                echo "Nombre modules : $nbmodules\n";


                                if ($nbmodules > 1) {

                                    echo "Test 5.5\n";

                                    $hascourse = 1;
                                    break;
                                }
                            }

                            echo "Test 6\n";

                            // Fin de la version des profs avec cours vide.

                            if ($teacheractivity == "Sursitaire" || $teacheractivity == "Détachement") {

                                $isactive = 0;
                            }

                            $teachercomposante = $composante->getAttribute('LL_COMPOSANTE');
                            $teacherservice = $service->getAttribute('LL_SERVICE');
                            $teachercorps = $teacher->getAttribute('LIBELLE_CORPS');

                            if (isset($teachercorps) && isset($teachercomposante) && isset($teacherservice)) {

                                if (!isset($teacherstreated[$teacherrecord->id])) {

                                    $teacherstreated[$teacherrecord->id] = 1;

                                    if ($DB->record_exists('block_ucpfigures_teachertype',
                                            array('composantename' => $teachercomposante,
                                                'servicename' => $teacherservice, 'teachertype' => $teachercorps))) {

                                        $teachertyperecord = $DB->get_record('block_ucpfigures_teachertype',
                                            array('composantename' => $teachercomposante,
                                                'servicename' => $teacherservice, 'teachertype' => $teachercorps));
                                        $teachertyperecord->coursecreated += $hascourse;
                                        $teachertyperecord->totalusers++;

                                        $DB->update_record('block_ucpfigures_teachertype', $teachertyperecord);
                                    } else {

                                        $teachertyperecord = new \stdClass();
                                        $teachertyperecord->composantename = $teachercomposante;
                                        $teachertyperecord->servicename = $teacherservice;
                                        $teachertyperecord->teachertype = $teachercorps;
                                        $teachertyperecord->coursecreated = $hascourse;
                                        $teachertyperecord->totalusers = 1;

                                        $DB->insert_record('block_ucpfigures_teachertype', $teachertyperecord);
                                    }
                                }

                                if ($isactive || $hascourse) {

                                    $teachername = $teacherrecord->lastname;
                                    $teacherfirstname = $teacherrecord->firstname;
                                    $teachermail = $teacherrecord->email;

                                    $teacherinforecord = new \stdClass();
                                    $teacherinforecord->userid = $teacherrecord->id;
                                    $teacherinforecord->composantename = $teachercomposante;
                                    $teacherinforecord->servicename = $teacherservice;
                                    $teacherinforecord->teachertype = $teachercorps;
                                    $teacherinforecord->lastname = $teachername;
                                    $teacherinforecord->firstname = $teacherfirstname;
                                    $teacherinforecord->email = $teachermail;
                                    $teacherinforecord->hascourse = $hascourse;

                                    $DB->insert_record('block_ucpfigures_teacherinfo', $teacherinforecord);
                                }
                            }
                        }
                    }
                }
            }
        }

        $listteachersunassigned = $xpathvarteachers->query('//Composante[@LL_COMPOSANTE=""]/Service/Individu');

        foreach ($listteachersunassigned as $teacher) {

            $service = $teacher->parentNode;
            $composante = $service->parentNode;

            $hascourse = 0;
            $isactive = 1;

            if ($teacher->hasAttribute('UID')) {

                $teacherlogin = $teacher->getAttribute('UID');
                $teacheractivity = $teacher->getAttribute('POSITION');

                if ($DB->record_exists('user', array('username' => $teacherlogin))) {

                    $teacherrecord = $DB->get_record('user', array('username' => $teacherlogin));

                    if ($DB->record_exists('role_assignments',
                            array('roleid' => $rolelocalteacher, 'contextid' => $contextid,
                                'userid' => $teacherrecord->id))) {

                        // Version normale
//                        $sqldistinctcourses = "SELECT COUNT(DISTINCT contextid) AS nbdistinctcourses "
//                                . "FROM {role_assignments} WHERE roleid = $roleteacherid AND "
//                                . "timemodified > $timestatbeginning AND userid = $teacherrecord->id "
//                                . "AND contextid IN (SELECT id FROM {context} WHERE"
//                                . " path LIKE '$pathyearcategorycontext%')";
//                        $nbdistinctcourses = $DB->get_record_sql($sqldistinctcourses)->nbdistinctcourses;
//
//                        if ($nbdistinctcourses) {
//
//                            $hascourse = 1;
//                        }
                        // Fin de la version normale.

                        // Version des profs avec cours vide, à désactiver en fonctionnement normal.

                        echo "Test 8\n";

                        $sqldistinctcoursescontext = "SELECT DISTINCT contextid "
                                . "FROM {role_assignments} WHERE roleid = $roleteacherid AND "
                                . "timemodified > $timestatbeginning AND userid = $teacherrecord->id "
                                . "AND contextid IN (SELECT id FROM {context} WHERE"
                                . " path LIKE '$pathyearcategorycontext%')";
                        $distinctcoursescontext = $DB->get_record_sql($sqldistinctcoursescontext);

                        echo "Test 9\n";

                        foreach ($distinctcoursescontext as $coursecontext) {

                            echo "Test 10\n";

                            $courseid = $DB->get_record('context', array('id' => $coursecontext))->id;

                            echo "Test 11\n";

                            $sqlnbmodules = "SELECT COUNT(DISTINCT id) AS nbmodules FROM {course_modules}"
                                    . " WHERE course = $courseid";

                            $nbmodules = $DB->get_record_sql($sqlnbmodules)->nbmodules;

                            echo "Test 12\n";

                            if ($nbmodules > 1) {

                                $hascourse = 1;
                                break;
                            }
                        }

                        echo "Test 13\n";

                        // Fin de la version des profs avec cours vide.

                        if ($teacheractivity == "Sursitaire" || $teacheractivity == "Détachement") {

                            $isactive = 0;
                        }

                        $teachercomposante = $composante->getAttribute('LL_COMPOSANTE');
                        $teacherservice = $service->getAttribute('LL_SERVICE');
                        $teachercorps = $teacher->getAttribute('LIBELLE_CORPS');

                        if (isset($teachercorps) && isset($teachercomposante) && isset($teacherservice)) {

                            if (!isset($teacherstreated[$teacherrecord->id])) {

                                $teacherstreated[$teacherrecord->id] = 1;

                                if ($DB->record_exists('block_ucpfigures_teachertype',
                                        array('composantename' => $teachercomposante,
                                            'servicename' => $teacherservice, 'teachertype' => $teachercorps))) {

                                    $teachertyperecord = $DB->get_record('block_ucpfigures_teachertype',
                                        array('composantename' => $teachercomposante,
                                            'servicename' => $teacherservice, 'teachertype' => $teachercorps));
                                    $teachertyperecord->coursecreated += $hascourse;
                                    $teachertyperecord->totalusers++;

                                    $DB->update_record('block_ucpfigures_teachertype', $teachertyperecord);
                                } else {

                                    $teachertyperecord = new \stdClass();
                                    $teachertyperecord->composantename = $teachercomposante;
                                    $teachertyperecord->servicename = $teacherservice;
                                    $teachertyperecord->teachertype = $teachercorps;
                                    $teachertyperecord->coursecreated = $hascourse;
                                    $teachertyperecord->totalusers = 1;

                                    $DB->insert_record('block_ucpfigures_teachertype', $teachertyperecord);
                                }
                            }

                            if ($isactive || $hascourse) {

                                $teachername = $teacherrecord->lastname;
                                $teacherfirstname = $teacherrecord->firstname;
                                $teachermail = $teacherrecord->email;

                                $teacherinforecord = new \stdClass();
                                $teacherinforecord->userid = $teacherrecord->id;
                                $teacherinforecord->composantename = $teachercomposante;
                                $teacherinforecord->servicename = $teacherservice;
                                $teacherinforecord->teachertype = $teachercorps;
                                $teacherinforecord->lastname = $teachername;
                                $teacherinforecord->firstname = $teacherfirstname;
                                $teacherinforecord->email = $teachermail;
                                $teacherinforecord->hascourse = $hascourse;

                                $DB->insert_record('block_ucpfigures_teacherinfo', $teacherinforecord);
                            }
                        }
                    }
                }
            }
        }

        // Ici, rassembler les stats pour avoir les stats par type de prof.

        $sqltypeteachers = "SELECT DISTINCT teachertype FROM {block_ucpfigures_teacherinfo} WHERE 1";
        $listtypeteachers = $DB->get_records_sql($sqltypeteachers);

        foreach ($listtypeteachers as $typeteacher) {

            $sqlallteacherswithcourse = "SELECT COUNT(DISTINCT userid) AS nbteacherswithcourses "
                    . "FROM {block_ucpfigures_teacherinfo} WHERE teachertype LIKE ? "
                    . "AND hascourse = 1";
            $sqlallteachers = "SELECT COUNT(DISTINCT userid) AS nbteachers "
                    . "FROM {block_ucpfigures_teacherinfo} WHERE teachertype LIKE ?";

            $nbteacherswithcourses = $DB->get_record_sql($sqlallteacherswithcourse,
                    array($typeteacher->teachertype))->nbteacherswithcourses;
            $nbteachers = $DB->get_record_sql($sqlallteachers, array($typeteacher->teachertype))->nbteachers;

            $statstyperecord = new \stdClass();
            $statstyperecord->teachertype = $typeteacher->teachertype;
            $statstyperecord->nbteacherswithcourse = $nbteacherswithcourses;
            $statstyperecord->nbtotalteachers = $nbteachers;

            $DB->insert_record('block_ucpfigures_statstype', $statstyperecord);
        }
    }
}