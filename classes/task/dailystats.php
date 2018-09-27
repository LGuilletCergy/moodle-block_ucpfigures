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
        // Shown in admin screens

        return get_string('dailystats', 'block_ucpfigures');
    }

    public function execute() {

        global $DB, $CFG;

        $xmldoc = new \DOMDocument();
        $xmldoc->load('/home/referentiel/dokeos_offre_pedagogique.xml');
        $xpathvar = new \Domxpath($xmldoc);
        $listdiplomes = $xpathvar->query('//Diplome');

        foreach ($listdiplomes as $diplome) {

            $codediplome = $CFG->thisyear . '-' . $diplome->getAttribute('Composante');

            if ($DB->record_exists('block_ucpfigures_ufr', array('code' => $codediplome))) {

                $recordstatsufr = $DB->get_record('block_ucpfigures_ufr', array('code' => $codediplome));

                $DB->update_record('block_ucpfigures_ufr', $recordstatsufr);
            } else {

                if ($DB->record_exists('course_categories', array('idnumber' => $codediplome))) {

                    $recordstatsufr = new stdClass();
                    $recordstatsufr->categoryid = $DB->get_record('course_categories',
                            array('idnumber' => $codediplome))->id;
                    $recordstatsufr->code = $codediplome;
                    $recordstatsufr->name = $diplome->getAttribute('Lib_composante');


                    $DB->insert_record('block_ucpfigures_ufr', $recordstatsufr);
                }
            }
        }

        $listufrs = $DB->get_records('block_ucpfigures_ufr', array());

        foreach ($listufrs as $ufr) {

            $composantecode = substr($ufr, 5);

            $nbvets = 0;

            $listdiplomesufr = $xpathvar->query("//Diplome[@Composante=$composantecode]/Version_diplome/Etape");

            foreach ($listdiplomesufr as $ufr) {

                $nbvets++;
            }

            $ufr->nbvets = $nbvets;

            $DB->update_record('block_ucpfigures_ufr', $ufr);
        }
    }
}
