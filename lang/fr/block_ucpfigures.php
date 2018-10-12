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
 * File : lang/fr/block_ucpfigures.php
 * French language file.
 *
 */
$string['pluginname'] = 'CoursUCP en chiffres';
$string['ucpfigures'] = 'CoursUCP en chiffres';
$string['ucpfigures:addinstance'] = 'Ajouter le bloc CoursUCP en chiffres';
$string['ucpfigures:myaddinstance'] = 'Ajouter le bloc CoursUCP en chiffres à mon tableau de bord';
$string['ucpfigures:viewinfo'] = 'Voir les statistiques';
$string['textsection1'] = 'Nombre de promotions, de cours et d\'étudiants déclarés dans Apogée';
$string['textsection2'] = 'Nombre de cours et VETs avec cohortes';
$string['textsection3'] = 'Nombre de cours créés, de VETs avec cours, d\'étudiants inscrits à des cours'
        . ' et d\'étudiants réellement actifs';
$string['textsection4'] = 'Divers';
$string['ufr'] = 'Composante';
$string['dailystats'] = 'Remplit les tables de la base de données stockant les statistiques.';
$string['csvexport'] = 'Exporter vers un fichier CSV';
$string['total'] = 'Total';
$string['introsection1'] = '<br>Les cours déclarés dans Apogée apparaissent automatiquement dans l\'outil '
        . '"Ajout d\'un nouveau cours", en haut du tableau de bord.'
        . ' Les étudiants déclarés dans Apogée sont automatiquement inscrits à la plateforme pédagogique '
        . '(mais pas forcément à des cours).<br>';
$string['introsection2'] = '<br>Pour qu\'un cours apparaisse automatiquement dans la rubrique'
        .'"Cours disponibles à la création", le cours doit avoir au moins une cohorte lié au cours disponible.'
        .'Pour celà, la composante doit créer le groupe dans Apogée'
        .'et l\'associer à au moins un évènement futur dans CELCAT. ';
$string['commentactivestudents'] = ' Sont considérés comme actifs les étudiants qui, au cours des 30 derniers jours,'
        . ' se sont connectés à la plateforme.<br>';
$string['introexpectedpromos'] = 'Il y a un total de {$a} promotions déclarées sur la plateforme réparties comme suit : ';
$string['nbvets'] = 'Nombre de promotions';
$string['expectedpromos'] = 'Promotions déclarées';
$string['introstudents'] = 'Il y a un total de {$a} étudiants sur la plateforme répartis comme suit : ';
$string['nbstudents'] = 'Nombre d\'étudiants';
$string['students'] = 'Etudiants déclarées';
$string['introcourses'] = 'Il y a un total de {$a} cours déclarés sur la plateforme répartis comme suit : ';
$string['nbcourses'] = 'Nombre de cours';
$string['courses'] = 'Cours';
$string['introavailablecourses'] = 'Il y a {$a} cours avec une cohorte sur la plateforme.<br>';
$string['nbavailablecourses'] = 'Nombre de cours avec une cohorte';
$string['availablecourses'] = 'Cours avec une cohorte';
$string['introavailablevets'] = 'Il y a {$a} VETs avec une cohorte sur la plateforme.<br>';
$string['nbavailablevets'] = 'Nombre de VETs avec une cohorte';
$string['availablevets'] = 'VETs avec une cohorte';
$string['introcreatedcourses'] = 'Il y a {$a} cours créés sur la plateforme.<br>';
$string['nbcreatedcourses'] = 'Nombre de cours créés';
$string['createdcourses'] = 'Cours créés';
$string['introenroledstudents'] = 'Il y a {$a} étudiants inscrits à au moins un cours sur la plateforme.<br>';
$string['nbenroledstudents'] = 'Nombre d\'étudiants inscrits à un cours';
$string['enroledstudents'] = 'Etudiants inscrits à un cours';
$string['introactivestudents'] = 'Il y a {$a} étudiants actifs sur la plateforme.';
$string['nbactivestudents'] = 'Nombre d\'étudiants actifs';
$string['activestudents'] = 'Etudiants actifs';
$string['introcreatedvets'] = 'Il y a {$a} VETs avec cours sur la plateforme.<br>';
$string['nbcreatedvets'] = 'Nombre de VETs avec cours';
$string['createdvets'] = 'VETs avec cours';
$string['distinctteachers'] = '{$a->value} enseignants distincts ont créé des cours sur la '
        . 'plateforme depuis le {$a->startdate}.<br><br>';
$string['nblogin'] = '{$a} connexions depuis une semaine.<br><br>';
$string['nblogin2'] = 'Connexions depuis une semaine';
$string['nbgrades'] = '{$a->value} notes attribuées depuis le {$a->startdate}.<br><br>';
$string['nbfiles'] = '{$a->value} fichiers déposés depuis le {$a->startdate}.<br><br>';
$string['nbviews'] = '{$a->value} consultations de cours ou documents depuis le {$a->startdate}.<br><br>';
$string['nbactions'] = '{$a->value} actions réalisées sur la plateforme '
        . '(consultation d\'un document, envoi d\'un message, remise d\'un devoir, etc.) '
        . 'depuis le {$a->startdate}.<br><br>';
