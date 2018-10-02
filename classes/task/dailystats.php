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
 * @author     Brice Errandonea <brice.errandonea@u-cergy.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 *
 * File : classes/task/dailystats.php
 * Collect statistics.
 *
 */

namespace block_ucpfigures\task;

class dailystats extends \core\task\scheduled_task {

    public function get_name() {
        // Shown in admin screens.

        return get_string('dailystats', 'block_ucpfigures');
    }

    public function execute() {

        global $DB, $CFG;

        $xmldocpedago = new \DOMDocument();
        $xmldocpedago->load('/home/referentiel/dokeos_offre_pedagogique.xml');
        $xpathvarpedago = new \Domxpath($xmldocpedago);
        $listdiplomes = $xpathvarpedago->query('//Diplome');

        foreach ($listdiplomes as $diplome) {

            $startcomposante = substr($diplome->getAttribute('Composante'), 0, 1);

            $codediplome = $CFG->yearprefix.'-'.$startcomposante;

            if ($DB->record_exists('block_ucpfigures_ufr', array('code' => $codediplome))) {

                $recordstatsufr = $DB->get_record('block_ucpfigures_ufr', array('code' => $codediplome));

                $DB->update_record('block_ucpfigures_ufr', $recordstatsufr);
            } else {

                if ($DB->record_exists('course_categories', array('idnumber' => $codediplome))) {

                    $category = $DB->get_record('course_categories', array('idnumber' => $codediplome));

                    $recordstatsufr = new \stdClass();
                    $recordstatsufr->categoryid = $DB->get_record('course_categories',
                            array('idnumber' => $codediplome))->id;
                    $recordstatsufr->code = $codediplome;
                    $recordstatsufr->name = $category->name;


                    $DB->insert_record('block_ucpfigures_ufr', $recordstatsufr);
                }
            }
        }

        $ufrarray = array();

        $listufrs = $DB->get_records('block_ucpfigures_ufr', array());

        foreach ($listufrs as $ufr) {

            // On réinitialise les statistiques.

            $ufr->nbvets = 0;
            $ufr->nbstudents = 0;
            $ufr->nbcourses = 0;
            $ufr->nbavailablecourses = 0;
            $ufr->nbavailablevets = 0;
            $ufr->nbcreatedcourses = 0;
            $ufr->nbenroledstudents = 0;
            $ufr->nbactivestudents = 0;
            $ufr->nbcreatedvets = 0;

            $composantecode = substr($ufr->code, 6);

            $ufrarray[$composantecode] = $ufr;
        }

        // Statistiques de vets disponibles dans Offre_pédagogiques.

        $listetapes = $xpathvarpedago->query('//Diplome/Version_diplome/Etape');

        foreach ($listetapes as $etape) {

            $composante = $etape->parentNode->parentNode->getAttribute('Composante');
            $startcomposante = substr($composante, 0, 1);

            $ufrarray[$startcomposante]->nbvets++;
        }

        // Statistiques d'étudiants dans Etudiants_Inscriptions.

        $xmldocstudents = new \DOMDocument();
        $xmldocstudents->load('/home/referentiel/DOKEOS_Etudiants_Inscriptions.xml');
        $xpathvarstudents = new \Domxpath($xmldocstudents);
        $listunivyear = $xpathvarstudents->query("//Student/Annee_universitaire[@AnneeUniv=$CFG->thisyear]");

        foreach ($listunivyear as $univyear) {

            $startcomposante = substr($univyear->getAttribute('CodeComposante'), 0, 1);
            $ufrarray[$startcomposante]->nbstudents++;
        }

        // Statistiques de cours dans Offre_pédagogiques.

        $listcourses = $xpathvarpedago->query('//Diplome/Version_diplome/Etape/Version_etape/ELP');

        foreach ($listcourses as $course) {

            $composante = $course->parentNode->parentNode->parentNode->parentNode->getAttribute('Composante');
            $startcomposante = substr($composante, 0, 1);

            $ufrarray[$startcomposante]->nbcourses++;
        }

        // Nombre de cours avec une cohorte.

        $xmldocelpetu = new \DOMDocument();
        $xmldocelpetu->load('/home/referentiel/dokeos_elp_etu_ens.xml');
        $xpathvarelpetu = new \Domxpath($xmldocelpetu);
        $listcourseselpetu = $xpathvarelpetu->query("//Structure_diplome/Cours");

        foreach ($listcourseselpetu as $courseelpetu) {

            $composante = $courseelpetu->parentNode->getAttribute('Libelle_composante_superieure');
            $startcomposante = substr($composante, 0, 1);

            if ($courseelpetu->hasChildNodes()) {

                $ufrarray[$startcomposante]->nbavailablecourses++;
            }
        }

        // Nombre de vets avec une cohorte.

        $listvetcohorts = $DB->get_records('local_cohortmanager_info', array('typecohort' => 'vet'));

        foreach ($listvetcohorts as $vetcohort) {

            if ($DB->record_exists('cohort', array('id' => $vetcohort->cohortid))) {

                $maincohort = $DB->get_record('cohort', array('id' => $vetcohort->cohortid));
                $startcomposante = substr($maincohort->idnumber, 6, 1);

                $ufrarray[$startcomposante]->nbavailablevets++;
            }
        }

        // Nombre de cours créés et d'étudiants inscrits.

        foreach ($listufrs as $ufr) {

            $startcomposante = substr($ufr->code, 6, 1);

            $combinedufrcode = '\''.$ufr->code.'%\'';
            $combinedufrcodecommonspace = '\''.$ufr->code.'COMMON-%\'';

            $sqlcourses = "SELECT * FROM {course} WHERE idnumber LIKE $combinedufrcode";

            $listcourses = $DB->get_records_sql($sqlcourses);

            foreach ($listcourses as $course) {

                $ufrarray[$startcomposante]->nbcreatedcourses++;
            }

            $studentrole = $DB->get_record('role', array('shortname' => 'student'))->id;
            $contextcourselevel = CONTEXT_COURSE;

            $sqlstudents = "SELECT distinct userid FROM {role_assignments} WHERE roleid = $studentrole AND "
                    . "contextid IN (SELECT id FROM {context} WHERE contextlevel = $contextcourselevel AND"
                    . " instanceid IN (SELECT id FROM {course} WHERE idnumber LIKE $combinedufrcode AND "
                    . "idnumber NOT LIKE $combinedufrcodecommonspace))";

            $liststudentsid = $DB->get_records_sql($sqlstudents);
            $localstudentroleid = $DB->get_record('role', array('shortname' => 'localstudent'))->id;

            $lastmonth = time() - 30*24*3600;

            foreach ($liststudentsid as $studentid) {

                // Vérifier que c'est un étudiant et dans le bon ufr.

                if ($DB->record_exists('local_usercreation_ufr',
                        array('userid' => $studentid->userid, 'ufrcode' => $ufr->code))) {

                    if ($DB->record_exists('role_assignments',
                            array('roleid' => $localstudentroleid, 'userid' => $studentid->userid))) {

                        $ufrarray[$startcomposante]->nbenroledstudents++;

                        // Vérifier qu'il est actif.

                        $sqlactivity = "SELECT * FROM {logstore_standard_log} WHERE"
                                . " timecreated > $lastmonth AND userid = $studentid->userid";

                        if ($DB->record_exists_sql($sqlactivity)) {

                            $ufrarray[$startcomposante]->nbactivestudents++;
                        }
                    }
                }
            }

            // Nombre de vets avec au moins un cours.

            $sqlusedvets = "SELECT * FROM {course_categories} WHERE idnumber LIKE $combinedufrcode AND"
                    . " coursecount > 0 AND depth = 4";

            echo $sqlusedvets."<br>";

            foreach ($sqlusedvets as $usedvet) {

                $ufrarray[$startcomposante]->nbcreatedvets++;
            }
        }

        foreach ($listufrs as $ufr) {

            $composantecode = substr($ufr->code, 6);

            $DB->update_record('block_ucpfigures_ufr', $ufrarray[$composantecode]);
        }
    }
}
