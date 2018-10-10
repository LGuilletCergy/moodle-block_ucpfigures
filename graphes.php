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

function grapheexpectedpromos() {

    global $DB;

    $listufrs = $DB->get_records('block_ucpfigures_ufr');
    $seriesdata = array();
    $labels = array();

    foreach ($listufrs as $ufr) {

        $seriesdata[] = $ufr->nbvets;
        $labels[] = $ufr->name;
    }

    $chart = new \core\chart_pie();
    $series = new \core\chart_series(get_string('nbvets', 'block_ucpfigures'), $seriesdata);
    $chart->add_series($series);
    $chart->set_labels($labels);
    $chart->set_title(get_string('expectedpromos', 'block_ucpfigures'));

    return $chart;
}

function graphestudents() {

    global $DB;

    $listufrs = $DB->get_records('block_ucpfigures_ufr');
    $seriesdata = array();
    $labels = array();

    foreach ($listufrs as $ufr) {

        $seriesdata[] = $ufr->nbstudents;
        $labels[] = $ufr->name;
    }

    $chart = new \core\chart_pie();
    $series = new \core\chart_series(get_string('nbstudents', 'block_ucpfigures'), $seriesdata);
    $chart->add_series($series);
    $chart->set_labels($labels);
    $chart->set_title(get_string('students', 'block_ucpfigures'));

    return $chart;
}

function graphecourses() {

    global $DB;

    $listufrs = $DB->get_records('block_ucpfigures_ufr');
    $seriesdata = array();
    $labels = array();

    foreach ($listufrs as $ufr) {

        $seriesdata[] = $ufr->nbcourses;
        $labels[] = $ufr->name;
    }

    $chart = new \core\chart_pie();
    $series = new \core\chart_series(get_string('nbcourses', 'block_ucpfigures'), $seriesdata);
    $chart->add_series($series);
    $chart->set_labels($labels);
    $chart->set_title(get_string('courses', 'block_ucpfigures'));

    return $chart;
}

function grapheavailablecourses() {

    global $DB;

    $listufrs = $DB->get_records('block_ucpfigures_ufr');
    $seriesdata1 = array();
    $seriesdata2 = array();
    $labels = array();

    foreach ($listufrs as $ufr) {

        $seriesdata1[] = $ufr->nbavailablecourses;
        $seriesdata2[] = $ufr->nbcourses;
        $labels[] = $ufr->name;
    }

    $chart = new \core\chart_bar();
    $series1 = new \core\chart_series(get_string('nbavailablecourses', 'block_ucpfigures'), $seriesdata1);
    $series2 = new \core\chart_series(get_string('nbcourses', 'block_ucpfigures'), $seriesdata2);
    $chart->add_series($series1);
    $chart->add_series($series2);
    $chart->set_labels($labels);
    $chart->set_title(get_string('availablecourses', 'block_ucpfigures'));

    return $chart;
}

function grapheavailablevets() {

    global $DB;

    $listufrs = $DB->get_records('block_ucpfigures_ufr');
    $seriesdata1 = array();
    $seriesdata2 = array();
    $labels = array();

    foreach ($listufrs as $ufr) {

        $seriesdata1[] = $ufr->nbavailablevets;
        $seriesdata2[] = $ufr->nbvets;
        $labels[] = $ufr->name;
    }

    $chart = new \core\chart_bar();
    $series1 = new \core\chart_series(get_string('nbavailablevets', 'block_ucpfigures'), $seriesdata1);
    $series2 = new \core\chart_series(get_string('nbvets', 'block_ucpfigures'), $seriesdata2);
    $chart->add_series($series1);
    $chart->add_series($series2);
    $chart->set_labels($labels);
    $chart->set_title(get_string('availablevets', 'block_ucpfigures'));

    return $chart;
}

function graphecreatedcourses() {

    global $DB;

    $listufrs = $DB->get_records('block_ucpfigures_ufr');
    $seriesdata1 = array();
    $seriesdata2 = array();
    $labels = array();

    foreach ($listufrs as $ufr) {

        $seriesdata1[] = $ufr->nbcreatedcourses;
        $seriesdata2[] = $ufr->nbcourses;
        $labels[] = $ufr->name;
    }

    $chart = new \core\chart_bar();
    $series1 = new \core\chart_series(get_string('nbcreatedcourses', 'block_ucpfigures'), $seriesdata1);
    $series2 = new \core\chart_series(get_string('nbcourses', 'block_ucpfigures'), $seriesdata2);
    $chart->add_series($series1);
    $chart->add_series($series2);
    $chart->set_labels($labels);
    $chart->set_title(get_string('createdcourses', 'block_ucpfigures'));

    return $chart;
}

function grapheenroledstudents() {

    global $DB;

    $listufrs = $DB->get_records('block_ucpfigures_ufr');
    $seriesdata1 = array();
    $seriesdata2 = array();
    $labels = array();

    foreach ($listufrs as $ufr) {

        $seriesdata1[] = $ufr->nbenroledstudents;
        $seriesdata2[] = $ufr->nbstudents;
        $labels[] = $ufr->name;
    }

    $chart = new \core\chart_bar();
    $series1 = new \core\chart_series(get_string('nbenroledstudents', 'block_ucpfigures'), $seriesdata1);
    $series2 = new \core\chart_series(get_string('nbstudents', 'block_ucpfigures'), $seriesdata2);
    $chart->add_series($series1);
    $chart->add_series($series2);
    $chart->set_labels($labels);
    $chart->set_title(get_string('enroledstudents', 'block_ucpfigures'));

    return $chart;
}

function grapheactivestudents() {

    global $DB;

    $listufrs = $DB->get_records('block_ucpfigures_ufr');
    $seriesdata1 = array();
    $seriesdata2 = array();
    $labels = array();

    foreach ($listufrs as $ufr) {

        $seriesdata1[] = $ufr->nbactivestudents;
        $seriesdata2[] = $ufr->nbstudents;
        $labels[] = $ufr->name;
    }

    $chart = new \core\chart_bar();
    $series1 = new \core\chart_series(get_string('nbactivestudents', 'block_ucpfigures'), $seriesdata1);
    $series2 = new \core\chart_series(get_string('nbstudents', 'block_ucpfigures'), $seriesdata2);
    $chart->add_series($series1);
    $chart->add_series($series2);
    $chart->set_labels($labels);
    $chart->set_title(get_string('activestudents', 'block_ucpfigures'));

    return $chart;
}

function graphecreatedvets() {

    global $DB;

    $listufrs = $DB->get_records('block_ucpfigures_ufr');
    $seriesdata1 = array();
    $seriesdata2 = array();
    $labels = array();

    foreach ($listufrs as $ufr) {

        $seriesdata1[] = $ufr->nbcreatedvets;
        $seriesdata2[] = $ufr->nbvets;
        $labels[] = $ufr->name;
    }

    $chart = new \core\chart_bar();
    $series1 = new \core\chart_series(get_string('nbcreatedvets', 'block_ucpfigures'), $seriesdata1);
    $series2 = new \core\chart_series(get_string('nbvets', 'block_ucpfigures'), $seriesdata2);
    $chart->add_series($series1);
    $chart->add_series($series2);
    $chart->set_labels($labels);
    $chart->set_title(get_string('createdvets', 'block_ucpfigures'));

    return $chart;
}

function graphelogins() {

    global $DB;

    $seriesdata = array();
    $labels = array();

    $nblogins = array();

    $now = time();

    for ($i = 0; $i < 7; $i++) {

        $idaysago = $now - $i * 24 * 3600;

        $nblogins[$i] = $DB->get_record('block_ucpfigures_stats',array('name' => 'login'.$i.'daysago'))->value;
        $labels[] = date('d/m/Y', $idaysago);
    }

    for ($i = 0; $i < 7; $i++) {

        $seriesdata[] = $nblogins[$i+1] - $nblogins[$i];
    }

    $chart = new \core\chart_line();
    $series = new \core\chart_series(get_string('nblogin2', 'block_ucpfigures'), $seriesdata);
    $chart->add_series($series);
    $chart->set_labels($labels);
    $chart->set_title(get_string('nblogin2', 'block_ucpfigures'));

    return $chart;
}