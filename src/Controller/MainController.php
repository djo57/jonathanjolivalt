<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MainController extends AbstractController
{
    /*#[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }*/

    #[Route('/{slug?}', name: 'app', requirements: ["slug" => ".+"])]
    public function app(): Response
    {
      return $this->render('app.html.twig');
    }

    #[Route("/mail/send", name: "app_mail", priority: 1, methods: ["POST", "GET"])]
    public function sendMail(Request $request, MailerInterface $mailer, ContainerBagInterface $params): JsonResponse
    {
      $send = false;
      $content = $request->toArray();
      $email = (new Email())
            ->from($content['email'])
            ->to($params->get('app.mail'))
            ->subject('Message Site Jojo de '. $content['name'] . " - " . $content['phone'])
            ->text($content['description'])
            ->html($content['description']);
      try {
        $mailer->send($email);
        $send = true;
      } catch (TransportExceptionInterface $e) {
        $send = false;
      } 

      return new JsonResponse($send);
    }

    #[Route("/api/skills/get", name: "api_skills", priority: 1, methods: ["GET"])]
    public function apiGetSkills(): JsonResponse
    {
      return new JsonResponse([
        "PHP 8", "Vue 3", "Symfony 6", "HTML 5", "CSS 3", "Javascript", "Docker", "React", "MySQL", "Gsap 3", "Ajax", "Bootstrap", "API Rest", "Soap", "Tailwind", "JQuery", "Ajax", "Windows", "Ubuntu", "Twig", "VScode", "Netbeans", "Jira", "Trello", "Git", "GitLab", "SVN", "GitExt", "Agile", "Kanban", "AWS", "EC2", "SES"
      ]);
    }

    #[Route("/api/backgrounds/get", name: "api_backgrounds", priority: 1, methods: ["GET"])]
    public function apiGetBackgrounds(): JsonResponse
    {
      return new JsonResponse([
        [
          "job" => "Autoformation",
          "date" => "06/2022 – " . date("m/Y"),
          "company" => "Congé parental",
          "description" => "<h3>Projet 1</h3>
          <p>Création de sites web
          <ul>
          <li>Maintenance et ajout de fonctionnalités sur la partie gestion des congés et gestion des articles de presse</li>
          <li>Rendre responsive le système d’administration avec Bootstrap</li>
          </ul>
          </p>",
          "environment" => ["PHP 7.2", "MySQL", "Linux", "Docker", "GIT", "Gitlab CI-CD", "gestion de tickets avec Trello", "IDE VScode"]
        ],
        [
          "job" => "Analyste programmeur PHP – WORT",
          "date" => "10/2021 – 01/2022",
          "company" => "IKE – Luxembourg",
          "description" => "<h3>Projet 1</h3>
          <p>Création de sites web
          <ul>
          <li>Maintenance et ajout de fonctionnalités sur la partie gestion des congés et gestion des articles de presse</li>
          <li>Rendre responsive le système d’administration avec Bootstrap</li>
          </ul>
          </p>",
          "environment" => ["PHP 7.2", "MySQL", "Linux", "Docker", "GIT", "Gitlab CI-CD", "gestion de tickets avec Trello", "IDE VScode"]
        ],
        [
          "job" => "Analyste programmeur PHP / Symfony – Biologistic (Chronopost)",
          "date" => "12/2017 – 03/2021",
          "company" => "IKE – Luxembourg",
          "description" => "<h3>Projet 1</h3>
          <p>Maintenance du back-office historique en collaboration avec 6 développeurs sur Paris.</p>
          <p>
          <ul>
            <li>Garder l’ancien code stable en fonction des modifications apportées par les nouveaux modules Symfony</li>
            <li>Correction de bugs rapportés par les utilisateurs</li>
            <li>Amélioration des performances</li>
          </ul>
          </p>

          <h3>Projet 2</h3>
          <p>Maintenance et amélioration des modules Symfony 3 concernant l’import des données clients, la gestion des courbes de températures des véhicules et du suivi des colis.</p>
          <p>
          <ul>
            <li>Uniformiser l’import des données de chaque nouveau client via un adaptateur pour simplifier le contrôle et l’intégration des données en base dans le système existant</li>
            <li>Adapter la mise à jour des courbes de température en temps réel en fonction des nouvelles fonctionnalités</li>
            <li>Adapter le suivi des colis en fonction des nouvelles fonctionnalités clients</li>
          </ul>
          </p>

          <h3>Projet 3</h3>
          <p>
          Création d’un module avec Symfony 3 qui automatise la gestion des tournées des chauffeurs
          dans toute la France auparavant géré manuellement. Le but étant de réduire le temps de
          travail du métier de 3h par jour à moins d’1h.
          </p>
          <p>
          <ul>
          <li>Comprendre le besoin métier en collaboration avec les utilisateurs et le chef de projet</li>
          <li>Délimiter les tâches par importance et chronologie</li>
          <li>Lier le module avec le webservice SAOP existant chez Chronopost qui calcule les itinéraires</li>
          <li>Créer une commande en tâche de fond qui va calculer chaque tournée de chaque secteur en parallèle et enregistrer les résultats en base</li>
          <li>Créer une interface utilisateur asynchrone qui va écouter cette tâche pour mettre à jour l’écran en temps réel afin que les utilisateurs puissent travailler sur les secteurs déjà calculés</li>
          <li>Créer une interface pour le résultat de chaque secteur, avec le détail de chaque tournée. L’utilisateur doit pouvoir modifier chaque étape par glisser-déposer afin d’en changer l’ordre ou de le déplacer dans une autre tournée avec re-calcul des tournées modifiés</li>
          <li>Tests unitaires et fonctionnels avec PHPUnit</li></ul></p>",
          "environment" => ["PHP 5.5", "Symfony 3 (Doctrine, EventDispatcher, Validators, PHPUnit, Twig)", "Javascript" , "Jquery", "Ajax", "MySQL", "Jira", "GIT", "CI/CD GitLab", "VM
          VirtualBox", "IDE Netbeans"]
        ],
        [
          "job" => "Analyste programmeur NPS Infopark / PHP / Symfony – CTIE",
          "date" => "10/2012 – 12/2017",
          "company" => "IKE - Luxembourg",
          "description" => "<h3>Projet 1</h3>
          <p>Refonte et maintenance des sites web de l’état dans le CMS NPS propre au CTIE.</p>
          <p>
          <ul>
          <li>Intégration de design en HTML, CSS et JS dans NPS</li>
          <li>Mise en place de la structure, de la navigation, des droits d’accès, des formulaires dans le langage NPS</li>
          <li>Intégration du moteur de recherche Exalead dans NPS pour la recherche sur les sites</li>
          <li>Intégration et gestion du système de newsletter Cheetahmail dans NPS</li>
          </ul>
          </p>

          <h3>Projet 2</h3>
          <p>Création du site www.public.budget.lu et d’applications internes en Symfony 3
          <ul>
          <li>Recueillir les besoins des utilisateurs</li>
          <li>Créer les interfaces et formulaires et gérer les droits d’accès et le multi-langue avec Symfony 3 et MySql</li>
          </ul>
          </p>",
          "environment" => ["CMS NPS Infopark", "PHP 5.5", "Symfony 3 (Doctrine, PHPUnit, SOAP, Twig)", "méthode Agile avec
          Kanban et Jira", "gestion de version avec SVN"]
        ],
        [
          "job" => "Développeur web",
          "date" => "09/2010 – 09/2012",
          "company" => "Markeasy - Rumelange",
          "description" => "<h3>Projet 1</h3>
          <p>Création de sites web
          <ul>
          <li>Intégration de design en HTML, CSS, JS avec Twig</li>
          <li>Création de back-office en PHP 5 avec MySQL</li>
          </ul>
          </p>

          <h3>Projet 2</h3>
          <p>Création de boutiques en ligne avec Prestashop
          <ul>
          <li>Installation, configuration et gestion des modules (Allopass, Paypal, Cetrel, La Poste, Relay colis, …)</li>
          <li>Création de modules spécifiques</li>
          <li>Intégration du design dans les templates</li>
          <li>Formation des clients</li>
          </ul>
          </p>",
          "environment" => ["PHP 5", "Javascript", "Jquery", "Ajax", "HTML", "CSS 2", "Twig", "Prestashop"]
        ],
        [
          "job" => "Développeur web",
          "date" => "07/2007 – 03/2010",
          "company" => "Netline - Luxembourg",
          "description" => "<h3>Projet 1</h3>
          <p>Création de sites web
          <ul>
          <li>Découpage et intégration de design en HTML, CSS et JS</li>
          <li>Création de back-office dédiés en PHP 5 avec MySQL ou avec le CMS SPIP</li>
          </ul>
          </p>

          <h3>Projet 2</h3>
          <p>Création de boutiques en ligne avec OSCommerce
          <ul>
          <li>Installation, configuration et gestion des modules</li>
          <li>Intégration du design dans les templates</li>
          <li>Formation des clients</li>
          </ul>
          </p>",
          "environment" => ["PHP 5", "Javascript", "Jquery", "Ajax", "HTML", "CSS", "Flash", "CMS SPIP", "OSCommerce"]
        ],
        [
          "job" => "Développeur web",
          "date" => "01/2007 – 06/2007",
          "company" => "Nvision - Steinsel",
          "description" => "<h3>Projet 1</h3>
          <p>Création de sites web
          <ul>
          <li>Découpage et intégration de design en HTML, CSS et JS</li>
          <li>Création de back-office en PHP 5 avec MySQL ou dans le CMS Joomla</li>
          </ul>
          </p>",
          "environment" => ["PHP 4", "MySQL", "Javascript", "HTML", "CSS", "CMS Joomla"]
        ],
      ]);
    }
}
