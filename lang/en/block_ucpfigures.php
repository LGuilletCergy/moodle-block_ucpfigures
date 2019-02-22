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
 * File : lang/en/block_ucpfigures.php
 * English language file.
 *
 */
$string['pluginname'] = 'Statistic';
$string['ucpfigures'] = 'Statistic';
$string['ucpfigures:addinstance'] = 'Add a new statistic block';
$string['ucpfigures:myaddinstance'] = 'Add a new statistic block to the My Moodle page';
$string['ucpfigures:viewinfo'] = 'View statistics';
$string['textsection1'] = 'Number of promotions, courses and students in Apogée';
$string['textsection2'] = 'Number of courses and VETs with cohorts';
$string['textsection3'] = 'Number of courses created, VETs with courses, students enroled and active students';
$string['textsection4'] = 'Other';
$string['ufr'] = 'UFR';
$string['dailystats'] = 'Fill statistics table.';
$string['csvexport'] = 'Export in a CSV file';
$string['total'] = 'Total';
$string['introsection1'] = 'Courses filled in Apogée automatically in the course creation tool.'
        . ' Students declared in Apogée are automatically registered to the website (but not to courses)';
$string['introsection2'] = 'A course appear in this category if at least one cohort is linked to it.'
        . ' To link a cohort to a course the UFR must create the group in Apogée'
        . ' and link it to at least one future event in CELCAT. ';
$string['commentactivestudents'] = ' A student is active if he connected at least once int the last 30 days to the website';
$string['introexpectedpromos'] = 'There are {$a} expected promotions on the website distributed as follow : ';
$string['nbvets'] = 'Number of VETs';
$string['expectedpromos'] = 'Expected promotions';
$string['introstudents'] = 'There are {$a} students on the website distributed as follow : ';
$string['nbstudents'] = 'Number of ecpected students';
$string['students'] = 'Expected tudents';
$string['introcourses'] = 'There are {$a} expected courses on the website distributed as follow : ';
$string['nbcourses'] = 'Number of expected courses';
$string['courses'] = 'Expected courses';
$string['introavailablecourses'] = 'There are {$a} courses with cohort on this website.<br>';
$string['nbavailablecourses'] = 'Number of courses with cohort';
$string['availablecourses'] = 'Courses with cohort';
$string['introavailablevets'] = 'There are {$a} VETs with cohort on this website.<br>';
$string['nbavailablevets'] = 'Number of VETs with cohort';
$string['availablevets'] = 'VETs with cohort';
$string['introcreatedcourses'] = 'There are {$a} created courses on this website.<br>';
$string['nbcreatedcourses'] = 'Number of created courses';
$string['createdcourses'] = 'Created courses';
$string['introenroledstudents'] = 'There are {$a} students enroled to at least one course on this website.<br>';
$string['nbenroledstudents'] = 'Number of students enroled to a course';
$string['enroledstudents'] = 'Students enroled to a course';
$string['introactivestudents'] = 'There are {$a} active students on this website.';
$string['nbactivestudents'] = 'Number of active students';
$string['activestudents'] = 'Active students';
$string['introcreatedvets'] = 'There are {$a} VETS with courses on this website.<br>';
$string['nbcreatedvets'] = 'Number of VETs with courses';
$string['createdvets'] = 'VETs with courses';
$string['distinctteachers'] = '{$a->value} different teachers enroled in courses since {$a->startdate}.<br><br>';
$string['nblogin'] = '{$a} logins this week.<br><br>';
$string['nblogin2'] = 'Logins this week';
$string['nbgrades'] = '{$a->value} grades given since {$a->startdate}.<br><br>';
$string['nbfiles'] = '{$a->value} files uploaded since {$a->startdate}.<br>';
$string['nbviews'] = '{$a->value} courses or files viewed since {$a->startdate}.<br><br>';
$string['nbactions'] = '{$a->value} actions taken since {$a->startdate}.<br><br>';
$string['nbdepots'] = '{$a->value} courses with student folders since {$a->startdate}.<br>';
$string['nbfolders'] = '{$a->value} courses with folders since {$a->startdate}.<br>';
$string['nbquizs'] = '{$a->value} courses with quizs since {$a->startdate}.<br><br>';
$string['nbassigns'] = '{$a->value} courses with assignments since {$a->startdate}.<br><br>';