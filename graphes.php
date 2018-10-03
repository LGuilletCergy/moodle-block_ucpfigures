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
 * File : graphes.php
 * Graphs page
 *
 */

defined('MOODLE_INTERNAL') || die;

function graphevets() {

    global $DB;

    $listufrs = $DB->get_records('block_ucpfigures_ufr');
    $nbvets = array();
    $labels = array();

    foreach ($listufrs as $ufr) {

        $nbvets[] = $ufr->nbvets;
        $labels[] = $ufr->name;
    }

    $chart = new \core\chart_pie();
    $series = new \core\chart_series(get_string('nbvets', 'block_ucpfigures'), $nbvets);
    $chart->add_series($series);
    $chart->set_labels($labels);
    $chart->set_title(get_string('expectedpromos', 'block_ucpfigures'));

    return $chart;
}