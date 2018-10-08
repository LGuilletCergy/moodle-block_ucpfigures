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

?>
<script type='text/javascript'>
function flipflop(id) {
    if (document.getElementById(id).style.display == 'none') document.getElementById(id).style.display = 'block';
    else document.getElementById(id).style.display = 'none';
}
</script>
<?php

$csv = optional_param('csv', null, PARAM_TEXT);

global $CFG;

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

$composantes = $DB->get_records('block_ucpfigures_ufr');

// Réinventer tout ce qu'il y a après.

echo "<div onclick=flipflop('section1'); style='text-align:center;width:100%;font-weight:bold;padding:5px;color:white;
		background-color:#731472;border-radius:5px 5px 0 0'>".get_string('textsection1', 'block_ucpfigures')."</div>
		<div id =section1 class=content style=width:100%;display:none><br>";

$totalknowncourses = 0;
$totalknownstudents = 0;
$totalnbrvets =0;

// Promotions déclarées.

$tableexpectedpromo = new html_table();
$tableexpectedpromo->head  = array(get_string('ufr', 'block_ucpfigures'), get_string('expectedpromos', 'block_ucpfigures'));
$tableexpectedpromo->colclasses = array('leftalign ufr', 'leftalign exceptedpromos');
$tableexpectedpromo->id = 'expectedpromos';
$tableexpectedpromo->attributes['class'] = 'admintable generaltable';

$listufrs = $DB->get_records('block_ucpfigures_ufr');

$data = array();

foreach ($listufrs as $ufr) {

    $line = array();
    $line[] = $ufr->name;
    $line[] = format_number($ufr->nbvets);

    $data[] = $row = new html_table_row($line);
}

$tableexpectedpromo->data = $data;
echo html_writer::table($tableexpectedpromo);

echo $OUTPUT->render(graphevets());

echo "<div><button href='figures?&csv=expectedpromos'>".get_string('csvexport', 'block_ucpfigures')
        ."</button></div></div>";

echo $OUTPUT->footer();

if ($csv == 'expectedpromo') {

    $csvwriter = new csv_export_writer();
}

//Groupe les chiffres d'un grand nombre par 3
function format_number($nb) {
    $nblength = strlen($nb);
    $nbgroups = ceil($nblength / 3);
    $modulo = $nblength % 3;
    $result = substr($nb, 0, $modulo);
    $nbremains = substr($nb, $modulo);
    for ($i = 1; $i < $nbgroups; $i++) {
        $result .= " ".substr($nbremains, 0, 3);
        $nbremains = substr($nbremains, 3);
    }
    if (!$modulo) {
        $result .= " ".$nbremains;
    }
    return $result;
}
