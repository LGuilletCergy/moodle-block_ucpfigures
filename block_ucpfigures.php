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
 * File : block_ucpfigures.php
 * Block class definition
 * 
 */

defined('MOODLE_INTERNAL') || die();

class block_ucpfigures extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_ucpfigures');
    }
    function get_content() {
        global $CFG, $DB, $OUTPUT, $PAGE, $USER;
        if ($this->content !== null) {
            return $this->content;
        }
        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }
        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        if (! empty($this->config->text)) {
            $this->content->text = $this->config->text;
        }
        $this->content->text = '';

	    if(isloggedin()) {
			$sqlenline = "SELECT COUNT(id) AS nbr FROM mdl_sessions";
			$resenligne = $DB->get_record_sql($sqlenline);
			$this->content->text  .= "<strong> $resenligne->nbr</strong> connectés<br>";

/*			$sqlactif ="SELECT COUNT(DISTINCT l.userid) as nbr "
                                . "FROM mdl_log l, mdl_role_assignments ra, mdl_user u "
                                . "WHERE l.time > (UNIX_TIMESTAMP(NOW()) - 3600 * 24 * 25) AND l.course > 1 "
                                . "AND l.userid = ra.userid AND ra.roleid = 5 "
                                . "AND l.userid = u.id AND u.idnumber > 0";
			$resactif = $DB->get_record_sql($sqlactif);
			$this->content->text .= "<strong>$resactif->nbr</strong> étudiants actifs<br>";*/

			$sqlcourse = "SELECT COUNT(id) AS nbr FROM mdl_course WHERE `idnumber` LIKE 'Y2018-%'";
			$rescourse = $DB->get_record_sql($sqlcourse);
			$this->content->text .= "<strong> $rescourse->nbr</strong> cours 2018-2019<br>";

			$sql = "SELECT COUNT(DISTINCT ra.userid) AS nbdistinctteachers
			        FROM mdl_role_assignments ra, mdl_user u, mdl_context ctx, mdl_course c
			        WHERE ra.roleid = 3 AND ctx.id = ra.contextid AND c.id = ctx.instanceid AND c.idnumber LIKE 'Y2017-%' AND ra.userid = u.id AND u.email LIKE '%@u-cergy.fr'";
			$nbdistinctteachers = $DB->get_record_sql($sql);
			$this->content->text .= "<strong> $nbdistinctteachers->nbdistinctteachers</strong> enseignants<br>";

/*			$sql = "SELECT number FROM mdl_block_ucpfigures WHERE name = 'nbviews'";
			$nbviews = $DB->get_record_sql($sql);
			$this->content->text .= "<strong> $nbviews->number</strong> consultations <hr>";*/

			$verifrole = explode("@", $USER->email);
			if (($verifrole[1] == "u-cergy.fr")) {				
				$this->content->text .= "<br><a href = '$CFG->wwwroot/blocks/ucpfigures/figures.php'>Plus de chiffres...</a>";				
			}
            if (! empty($this->config->text)) {
                $this->content->text .= $this->config->text;
            }
            return $this->content;
	    }
    }

    public function applicable_formats() {
        return array('all' => true,
                     'site' => true,
                     'site-index' => true,
                     'course-view' => true,
                     'course-view-social' => false,
                     'mod' => true,
					 'my' => true,
                     'mod-quiz' => false);
    }

    public function instance_allow_multiple() {
          return false;
    }

    function has_config() {return true;}

    public function cron() {
            mtrace( "Hey, my cron script is running" );
            return true;
    }

}
