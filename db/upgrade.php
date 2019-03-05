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
 * File : db/upgrade.php
 * Defines what to do when upgrading the block to a new version.
 */

function xmldb_block_ucpfigures_upgrade($oldversion, $block) {

    if ($oldversion < 2019030100) {

        global $DB;

        // Define table block_ucpfigures_teachertype to be created.
        $table = new xmldb_table('block_ucpfigures_teachertype');

        // Adding fields to table block_ucpfigures_teachertype.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('teachertype', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
        $table->add_field('coursecreated', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('totalusers', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table block_ucpfigures_teachertype.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        $dbman = $DB->get_manager();

        // Conditionally launch create table for block_ucpfigures_teachertype.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Ucpfigures savepoint reached.
        upgrade_block_savepoint(true, 2019030100, 'ucpfigures');
    }

    if ($oldversion < 2019030400) {

        global $DB;
        $dbman = $DB->get_manager();

        // Define field servicename to be added to block_ucpfigures_teachertype.
        $table = new xmldb_table('block_ucpfigures_teachertype');
        $field = new xmldb_field('servicename', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, 'empty', 'id');

        // Conditionally launch add field servicename.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define table block_ucpfigures_teacherinfo to be created.
        $table = new xmldb_table('block_ucpfigures_teacherinfo');

        // Adding fields to table block_ucpfigures_teacherinfo.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('servicename', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('teachertype', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
        $table->add_field('lastname', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
        $table->add_field('firstname', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
        $table->add_field('email', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_ucpfigures_teacherinfo.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_ucpfigures_teacherinfo.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Ucpfigures savepoint reached.
        upgrade_block_savepoint(true, 2019030400, 'ucpfigures');
    }

    if ($oldversion < 2019030500) {

        global $DB;
        $dbman = $DB->get_manager();

        // Define field servicename to be added to block_ucpfigures_teachertype.
        $table = new xmldb_table('block_ucpfigures_teachertype');
        $field = new xmldb_field('composantename', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, 'empty', 'id');

        // Conditionally launch add field servicename.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field servicename to be added to block_ucpfigures_teacherinfo.
        $table = new xmldb_table('block_ucpfigures_teacherinfo');
        $field = new xmldb_field('composantename', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, 'empty', 'id');

        // Conditionally launch add field servicename.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Ucpfigures savepoint reached.
        upgrade_block_savepoint(true, 2019030500, 'ucpfigures');
    }
}