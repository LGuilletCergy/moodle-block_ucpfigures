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

        if (!empty($this->config->text)) {
			
            $this->content->text = $this->config->text;
        }
        $this->content->text = '';
		
		// A supprimer.
        
        if (($USER->username != 'lguillet') && ($USER->username != 'berrando')) {
			
			return $this->content;
		}
		
		// Fin à supprimer.

	    if (isloggedin()) {
			
			$sqlenligne = "SELECT COUNT( DISTINCT s.userid) FROM {sessions} AS s";
			$resenligne = $DB->count_records_sql($sqlenligne);
			$this->content->text  .= "<strong> $resenligne</strong> connectés<br>";

			$sqlcourse = "SELECT COUNT( DISTINCT c.id) FROM {course} AS c WHERE idnumber LIKE '$CFG->yearprefix-%'";
			$rescourse = $DB->count_records_sql($sqlcourse);
			$nextyear = $CFG->thisyear + 1;
			$this->content->text .= "<strong> $rescourse</strong> cours $CFG->thisyear-$nextyear<br>";
			
			$nbdistinctteachers = 0;
			
			$rolelocalteacher = $DB->get_record('role', array('shortname' => 'localteacher'))->id;
			$roleeditingteacher = $DB->get_record('role', array('shortname' => 'editingteacher'))->id;
			$roleteacher = $DB->get_record('role', array('shortname' => 'teacher'))->id;
			$listteachers = $DB->get_records('role_assignments', array('roleid' => $rolelocalteacher, 'contextid' => 1));
			
			foreach ($listteachers as $teacher) {
				
				if ($DB->record_exists('role_assignments', array('userid' => $teacher->id, 'roleid' => $roleeditingteacher))) {
					
					$nbdistinctteachers++;
				} else if ($DB->record_exists('role_assignments', array('userid' => $teacher->id, 'roleid' => $roleteacher))) {
					
					$nbdistinctteachers++;
				}
			}

			$this->content->text .= "<strong> $nbdistinctteachers</strong> enseignants<br>";
			
			$context = context_system::instance();

			if (has_capability('block/ucpfigures:viewinfo', $context)) {
			
				$this->content->text .= "<br><a href = '$CFG->wwwroot/blocks/ucpfigures/figures.php'>Plus de chiffres...</a>";				
			}
            if (!empty($this->config->text)) {
				
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
}
