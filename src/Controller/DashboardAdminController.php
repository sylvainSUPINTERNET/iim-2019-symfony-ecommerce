<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardAdminController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="dashboard_admin")
     */
    public function index()
    {
        $repoUser = $this->getDoctrine()->getRepository(User::class);
        $repoCmd = $this->getDoctrine()->getRepository(Command::class)->findAll();
        $total_amount = 0;
        foreach ($repoCmd as $command) {
            $total_amount += $command->getAmount();
        }

        return $this->render('dashboard_admin/index.html.twig', [
            'controller_name' => 'DashboardAdminController',
            'kpi_lastest_user' => $repoUser->findLastest(),
            'kpi_amount' => $total_amount,
            'kpi_members' => $repoUser->findByRole('ROLE_USER')
        ]);
    }

    /**
     * @Route("/admin/dashboard/commands", name="admin_commands")
     */
    public function commands()
    {
        $commands = $this->getDoctrine()->getRepository(User::class)->findByRole('ROLE_USER');

        return $this->render('admin/commands.html.twig', [
            "commands" => $commands
        ]);
    }

    /**
     * @Route("/admin/dashboard/commands/{id}/show", name="admin_command_show")
     */
    public function commandShow($id)
    {
        $command = $this->getDoctrine()->getRepository(Command::class)->find($id);

        return $this->render('admin/command_show.html.twig', [
            "command" => $command
        ]);
    }
}
