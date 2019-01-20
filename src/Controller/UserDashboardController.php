<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserDashboardController extends AbstractController
{

    /**
     * @Route("/dashboard/commands", name="dashboard_commands")
     */
    public function commands()
    {
        $commands = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId())->getCommands();

        return $this->render('user_dashboard/commands.html.twig', [
            "commands" => $commands
        ]);
    }

    /**
     * @Route("/dashboard/commands/{id}/show", name="command_show")
     */
    public function commandShow($id)
    {
        $command = $this->getDoctrine()->getRepository(Command::class)->find($id);

        return $this->render('user_dashboard/command_show.html.twig', [
            "command" => $command
        ]);
    }
}
