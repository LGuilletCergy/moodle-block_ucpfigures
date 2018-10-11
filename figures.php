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
 * File : figures.php
 * Stats page
 *
 */

require('../../config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/report/log/locallib.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('graphes.php');
require_once($CFG->libdir . '/csvlib.class.php');

$csv = optional_param('csv', null, PARAM_TEXT);

global $CFG;

if (!$csv) {

    ?>
    <script type='text/javascript'>
    function flipflop(id) {
        if (document.getElementById(id).style.display == 'none') document.getElementById(id).style.display = 'block';
        else document.getElementById(id).style.display = 'none';
    }
    </script>
    <?php

    $thisyear = $CFG->yearprefix;

    $PAGE->set_url('/blocks/ucpfigures/figures.php');
    $PAGE->set_pagelayout('standard');
    $context = context_system::instance();
    $PAGE->set_context($context);


    // Navigation node.
    $settingsnode = $PAGE->settingsnav->add(get_string('sitepages'));
    $editurl = new moodle_url('/blocks/ucpfigures/figures.php');
    $title = get_string('pluginname', 'block_ucpfigures');
    $editnode = $settingsnode->add($title, $editurl);
    $editnode->make_active();

    require_login();

    require_capability('block/ucpfigures:viewinfo', $context);


    $PAGE->set_pagetype('site-index');
    $PAGE->set_docs_path('');
    $PAGE->set_pagelayout('report');
    $PAGE->set_title($title);
    $PAGE->set_heading($title);
    echo $OUTPUT->header();

    $totalexpectedpromos = 0;
    $totalexpectedstudents = 0;
    $totalexpectedcourses = 0;
    $totalavailablecourses = 0;
    $totalavailablevets = 0;
    $totalcreatedcourses = 0;
    $totalenroledstudents = 0;
    $totalactivestudents = 0;
    $totalcreatedvets = 0;

    $listufrs = $DB->get_records('block_ucpfigures_ufr');

    foreach ($listufrs as $ufr) {

        $totalexpectedpromos += $ufr->nbvets;
        $totalstudents += $ufr->nbstudents;
        $totalcourses += $ufr->nbcourses;
        $totalavailablecourses += $ufr->nbavailablecourses;
        $totalavailablevets += $ufr->nbavailablevets;
        $totalcreatedcourses += $ufr->nbcreatedcourses;
        $totalenroledstudents += $ufr->nbenroledstudents;
        $totalactivestudents += $ufr->nbactivestudents;
        $totalcreatedvets += $ufr->nbcreatedvets;
    }

    // Section 1.

    echo "<div onclick=flipflop('section1'); style='text-align:center;width:100%;
        font-weight:bold;padding:5px;color:white;background-color:#731472;border-radius:5px 5px 0 0'>"
    .get_string('textsection1', 'block_ucpfigures')."</div>"
            . "<div id =section1 class=content style=width:100%;display:none><br>";

    echo get_string('introsection1', 'block_ucpfigures');

    // Promotions déclarées.

    echo get_string('introexpectedpromos', 'block_ucpfigures', $totalexpectedpromos);

    echo $OUTPUT->render(grapheexpectedpromos());

    echo "<div><a class='btn btn-secondary' href='figures.php?csv=expectedpromos'>".
            get_string('csvexport', 'block_ucpfigures')."</a></div><br>";

    // Etudiants déclarées.

    echo get_string('introstudents', 'block_ucpfigures', $totalstudents);

    echo $OUTPUT->render(graphestudents());

    echo "<div><a class='btn btn-secondary' href='figures.php?csv=students'>".
            get_string('csvexport', 'block_ucpfigures')."</a></div><br>";

    // Cours déclarées.

    echo get_string('introcourses', 'block_ucpfigures', $totalcourses);

    echo $OUTPUT->render(graphecourses());

    echo "<div><a class='btn btn-secondary' href='figures.php?csv=courses'>".
            get_string('csvexport', 'block_ucpfigures')."</a></div><br>";

    echo "</div><br>";

    // Section 2.

    echo "<div onclick=flipflop('section2'); style='text-align:center;width:100%;font-weight:bold;padding:5px;
        color:white;background-color:#731472;border-radius:5px 5px 0 0'>"
        .get_string('textsection2', 'block_ucpfigures').
        "</div><div id =section2 class=content style=width:100%;display:none><br>";

    echo get_string('introsection2', 'block_ucpfigures');

    // Cours disponibles/Cours déclarés.

    echo get_string('introavailablevets', 'block_ucpfigures', $totalavailablecourses);

    echo $OUTPUT->render(grapheavailablecourses());

    echo "<div><a class='btn btn-secondary' href='figures.php?csv=availablecourses'>".
            get_string('csvexport', 'block_ucpfigures')."</a></div><br>";

    // Promotions disponibles/Promotions déclarées.

    echo get_string('introavailablevets', 'block_ucpfigures', $totalavailablevets);

    echo $OUTPUT->render(grapheavailablevets());

    echo "<div><a class='btn btn-secondary' href='figures.php?csv=availablevets'>".
            get_string('csvexport', 'block_ucpfigures')."</a></div><br>";

    echo "</div><br>";

    // Section 3.

    echo "<div onclick=flipflop('section3'); style='text-align:center;width:100%;font-weight:bold;padding:5px;
        color:white;background-color:#731472;border-radius:5px 5px 0 0'>"
        .get_string('textsection3', 'block_ucpfigures').
        "</div><div id =section3 class=content style=width:100%;display:none><br>";

    // Cours créés sur la plateforme.

    echo get_string('introcreatedcourses', 'block_ucpfigures', $totalcreatedcourses);

    echo $OUTPUT->render(graphecreatedcourses());

    echo "<div><a class='btn btn-secondary' href='figures.php?csv=createdcourses'>".
            get_string('csvexport', 'block_ucpfigures')."</a></div><br>";

    // Étudiants inscrits.

    echo get_string('introenroledstudents', 'block_ucpfigures', $totalenroledstudents);

    echo $OUTPUT->render(grapheenroledstudents());

    echo "<div><a class='btn btn-secondary' href='figures.php?csv=enroledstudents'>".
            get_string('csvexport', 'block_ucpfigures')."</a></div><br>";

    // Étudiants actifs.

    echo get_string('introactivestudents', 'block_ucpfigures', $totalactivestudents);

    echo $OUTPUT->render(grapheactivestudents());

    echo "<div><a class='btn btn-secondary' href='figures.php?csv=activestudents'>".
            get_string('csvexport', 'block_ucpfigures')."</a></div><br>";

    echo get_string('commentactivestudents', 'block_ucpfigures');

    // Promotions concernées.

    echo get_string('introcreatedvets', 'block_ucpfigures', $totalcreatedvets);

    echo $OUTPUT->render(graphecreatedvets());

    echo "<div><a class='btn btn-secondary' href='figures.php?csv=createdvets'>".
            get_string('csvexport', 'block_ucpfigures')."</a></div><br>";

    echo "</div><br>";

    // Section 4.

    echo "<div onclick=flipflop('section4'); style='text-align:center;width:100%;font-weight:bold;padding:5px;
        color:white;background-color:#731472;border-radius:5px 5px 0 0'>"
        .get_string('textsection4', 'block_ucpfigures').
        "</div><div id =section4 class=content style=width:100%;display:none><br>";

    $timestatbeginningtemp = strptime('01/07/'.$CFG->thisyear, '%d/%m/%Y');
    $timestatbeginning = mktime(0, 0, 0, $timestatbeginningtemp['tm_mon']+1,
            $timestatbeginningtemp['tm_mday'], $timestatbeginningtemp['tm_year']+1900);


    $nbdistinctteachers = $DB->get_record('block_ucpfigures_stats', array('name' => 'distinctteachers'))->value;
    $datastringdistinctteachers = new stdClass();
    $datastringdistinctteachers->value = $nbdistinctteachers;
    $datastringdistinctteachers->startdate = '01/07/'.$CFG->thisyear;
    echo get_string('distinctteachers', 'block_ucpfigures', $datastringdistinctteachers);

    $nblogin = $DB->get_record('block_ucpfigures_stats', array('name' => 'login'))->value;
    echo get_string('nblogin', 'block_ucpfigures', $nblogin);

    echo $OUTPUT->render(graphelogins());

    $nbgrades = $DB->get_record('block_ucpfigures_stats', array('name' => 'grades'))->value;
    $datastringgrades = new stdClass();
    $datastringgrades->value = $nbgrades;
    $datastringgrades->startdate = '01/07/'.$CFG->thisyear;
    echo get_string('nbgrades', 'block_ucpfigures', $datastringgrades);

    $nbfiles = $DB->get_record('block_ucpfigures_stats', array('name' => 'files'))->value;
    $datastringfiles = new stdClass();
    $datastringfiles->value = $nbfiles;
    $datastringfiles->startdate = '01/07/'.$CFG->thisyear;
    echo get_string('nbfiles', 'block_ucpfigures', $datastringfiles);

    $nbviews = $DB->get_record('block_ucpfigures_stats', array('name' => 'views'))->value;
    $datastringviews = new stdClass();
    $datastringviews->value = $nbviews;
    $datastringviews->startdate = '01/07/'.$CFG->thisyear;
    echo get_string('nbviews', 'block_ucpfigures', $datastringviews);

    $nbactions = $DB->get_record('block_ucpfigures_stats', array('name' => 'actions'))->value;
    $datastringactions = new stdClass();
    $datastringactions->value = $nbactions;
    $datastringactions->startdate = '01/07/'.$CFG->thisyear;
    echo get_string('nbactions', 'block_ucpfigures', $datastringactions);

    echo "</div>";

    echo $OUTPUT->footer();
} else if ($csv == 'expectedpromos') {

    $csvwriter = new csv_export_writer();
    $csvwriter->set_filename(get_string('expectedpromos', 'block_ucpfigures'));
    $header = array(utf8_decode(get_string('ufr', 'block_ucpfigures')),
        utf8_decode(get_string('expectedpromos', 'block_ucpfigures')));
    $csvwriter->add_data($header);

    $listufrs = $DB->get_records('block_ucpfigures_ufr');

    $total = 0;

    foreach ($listufrs as $ufr) {

        $data = array();

        $data[] = utf8_decode($ufr->name);
        $data[] = $ufr->nbvets;
        $total += $ufr->nbvets;

        $csvwriter->add_data($data);
    }

    $footer = array(get_string('total', block_ucpfigures), $total);
    $csvwriter->add_data($footer);

    $csvwriter->download_file();
} else if ($csv == 'students') {

    $csvwriter = new csv_export_writer();
    $csvwriter->set_filename(get_string('students', 'block_ucpfigures'));
    $header = array(utf8_decode(get_string('ufr', 'block_ucpfigures')),
        utf8_decode(get_string('students', 'block_ucpfigures')));
    $csvwriter->add_data($header);

    $listufrs = $DB->get_records('block_ucpfigures_ufr');

    $total = 0;

    foreach ($listufrs as $ufr) {

        $data = array();

        $data[] = utf8_decode($ufr->name);
        $data[] = $ufr->nbstudents;
        $total += $ufr->nbstudents;

        $csvwriter->add_data($data);
    }

    $footer = array(get_string('total', block_ucpfigures), $total);
    $csvwriter->add_data($footer);

    $csvwriter->download_file();
} else if ($csv == 'courses') {

    $csvwriter = new csv_export_writer();
    $csvwriter->set_filename(get_string('courses', 'block_ucpfigures'));
    $header = array(utf8_decode(get_string('ufr', 'block_ucpfigures')),
        utf8_decode(get_string('courses', 'block_ucpfigures')));
    $csvwriter->add_data($header);

    $listufrs = $DB->get_records('block_ucpfigures_ufr');

    $total = 0;

    foreach ($listufrs as $ufr) {

        $data = array();

        $data[] = utf8_decode($ufr->name);
        $data[] = $ufr->nbcourses;
        $total += $ufr->nbcourses;

        $csvwriter->add_data($data);
    }

    $footer = array(get_string('total', block_ucpfigures), $total);
    $csvwriter->add_data($footer);

    $csvwriter->download_file();
} else if ($csv == 'availablecourses') {

    $csvwriter = new csv_export_writer();
    $csvwriter->set_filename(get_string('availablecourses', 'block_ucpfigures'));
    $header = array(utf8_decode(get_string('ufr', 'block_ucpfigures')),
        utf8_decode(get_string('availablecourses', 'block_ucpfigures')));
    $csvwriter->add_data($header);

    $listufrs = $DB->get_records('block_ucpfigures_ufr');

    $total1 = 0;
    $total2 = 0;

    foreach ($listufrs as $ufr) {

        $data = array();

        $data[] = utf8_decode($ufr->name);
        $data[] = $ufr->nbavailablecourses."/".$ufr->nbcourses." (".
                round($ufr->nbavailablecourses *100/$ufr->nbcourses, 1)."%)";
        $total1 += $ufr->nbavailablecourses;
        $total2 += $ufr->nbcourses;

        $csvwriter->add_data($data);
    }

    $total = $total1."/".$total2." (".round($total1 *100/$total2, 1)."%)";
    $footer = array(get_string('total', block_ucpfigures), $total);
    $csvwriter->add_data($footer);

    $csvwriter->download_file();
} else if ($csv == 'availablevets') {

    $csvwriter = new csv_export_writer();
    $csvwriter->set_filename(get_string('availablevets', 'block_ucpfigures'));
    $header = array(utf8_decode(get_string('ufr', 'block_ucpfigures')),
        utf8_decode(get_string('availablevets', 'block_ucpfigures')));
    $csvwriter->add_data($header);

    $listufrs = $DB->get_records('block_ucpfigures_ufr');

    $total1 = 0;
    $total2 = 0;

    foreach ($listufrs as $ufr) {

        $data = array();

        $data[] = utf8_decode($ufr->name);
        $data[] = $ufr->nbavailablevets."/".$ufr->nbvets." (".
                round($ufr->nbavailablevets *100/$ufr->nbvets, 1)."%)";
        $total1 += $ufr->nbavailablevets;
        $total2 += $ufr->nbvets;

        $csvwriter->add_data($data);
    }

    $total = $total1."/".$total2." (".round($total1 *100/$total2, 1)."%)";
    $footer = array(get_string('total', block_ucpfigures), $total);
    $csvwriter->add_data($footer);

    $csvwriter->download_file();
} else if ($csv == 'createdcourses') {

    $csvwriter = new csv_export_writer();
    $csvwriter->set_filename(get_string('createdcourses', 'block_ucpfigures'));
    $header = array(utf8_decode(get_string('ufr', 'block_ucpfigures')),
        utf8_decode(get_string('createdcourses', 'block_ucpfigures')));
    $csvwriter->add_data($header);

    $listufrs = $DB->get_records('block_ucpfigures_ufr');

    $total1 = 0;
    $total2 = 0;

    foreach ($listufrs as $ufr) {

        $data = array();

        $data[] = utf8_decode($ufr->name);
        $data[] = $ufr->nbcreatedcourses."/".$ufr->nbcourses." (".
                round($ufr->nbcreatedcourses *100/$ufr->nbcourses, 1)."%)";
        $total1 += $ufr->nbcreatedcourses;
        $total2 += $ufr->nbcourses;

        $csvwriter->add_data($data);
    }

    $total = $total1."/".$total2." (".round($total1 *100/$total2, 1)."%)";
    $footer = array(get_string('total', block_ucpfigures), $total);
    $csvwriter->add_data($footer);

    $csvwriter->download_file();
} else if ($csv == 'enroledstudents') {

    $csvwriter = new csv_export_writer();
    $csvwriter->set_filename(get_string('enroledstudents', 'block_ucpfigures'));
    $header = array(utf8_decode(get_string('ufr', 'block_ucpfigures')),
        utf8_decode(get_string('enroledstudents', 'block_ucpfigures')));
    $csvwriter->add_data($header);

    $listufrs = $DB->get_records('block_ucpfigures_ufr');

    $total1 = 0;
    $total2 = 0;

    foreach ($listufrs as $ufr) {

        $data = array();

        $data[] = utf8_decode($ufr->name);
        $data[] = $ufr->nbenroledstudents."/".$ufr->nbstudents." (".
                round($ufr->nbenroledstudents *100/$ufr->nbstudents, 1)."%)";
        $total1 += $ufr->nbenroledstudents;
        $total2 += $ufr->nbstudents;

        $csvwriter->add_data($data);
    }

    $total = $total1."/".$total2." (".round($total1 *100/$total2, 1)."%)";
    $footer = array(get_string('total', block_ucpfigures), $total);
    $csvwriter->add_data($footer);

    $csvwriter->download_file();
} else if ($csv == 'activestudents') {

    $csvwriter = new csv_export_writer();
    $csvwriter->set_filename(get_string('activestudents', 'block_ucpfigures'));
    $header = array(utf8_decode(get_string('ufr', 'block_ucpfigures')),
        utf8_decode(get_string('activestudents', 'block_ucpfigures')));
    $csvwriter->add_data($header);

    $listufrs = $DB->get_records('block_ucpfigures_ufr');

    $total1 = 0;
    $total2 = 0;

    foreach ($listufrs as $ufr) {

        $data = array();

        $data[] = utf8_decode($ufr->name);
        $data[] = $ufr->nbactivestudents."/".$ufr->nbstudents." (".
                round($ufr->nbactivestudents *100/$ufr->nbstudents, 1)."%)";
        $total1 += $ufr->nbactivestudents;
        $total2 += $ufr->nbstudents;

        $csvwriter->add_data($data);
    }

    $total = $total1."/".$total2." (".round($total1 *100/$total2, 1)."%)";
    $footer = array(get_string('total', block_ucpfigures), $total);
    $csvwriter->add_data($footer);

    $csvwriter->download_file();
} else if ($csv == 'createdvets') {

    $csvwriter = new csv_export_writer();
    $csvwriter->set_filename(get_string('createdvets', 'block_ucpfigures'));
    $header = array(utf8_decode(get_string('ufr', 'block_ucpfigures')),
        utf8_decode(get_string('createdvets', 'block_ucpfigures')));
    $csvwriter->add_data($header);

    $listufrs = $DB->get_records('block_ucpfigures_ufr');

    $total1 = 0;
    $total2 = 0;

    foreach ($listufrs as $ufr) {

        $data = array();

        $data[] = utf8_decode($ufr->name);
        $data[] = $ufr->nbcreatedvets."/".$ufr->nbvets." (".
                round($ufr->nbcreatedvets *100/$ufr->nbvets, 1)."%)";
        $total1 += $ufr->nbcreatedvets;
        $total2 += $ufr->nbvets;

        $csvwriter->add_data($data);
    }

    $total = $total1."/".$total2." (".round($total1 *100/$total2, 1)."%)";
    $footer = array(get_string('total', block_ucpfigures), $total);
    $csvwriter->add_data($footer);

    $csvwriter->download_file();
}
