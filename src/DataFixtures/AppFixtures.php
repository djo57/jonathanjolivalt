<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $skills = [
            "PHP8", "Vue3", "Symfony6", "EasyAdmin", "React", "MySQL", "Gsap3", "HTML5", "CSS3", "Javascript", "Docker", "Ajax", "Bootstrap", "API Rest", "Soap", "Tailwind", "JQuery", "Ajax", "Windows", "Ubuntu", "Twig", "VScode", "Netbeans", "Jira", "Trello", "Git", "GitLab", "SVN", "GitExt", "Agile", "Kanban", "AWS", "EC2", "SES", "Responsive", "Exalead", "Cheetahmail"
        ];
    
        foreach($skills as $skill) {
            $skillEntity = new Skill();
            $skillEntity->setName($skill);
            $manager->persist($skillEntity);
        }

        $manager->flush();
    }
}
