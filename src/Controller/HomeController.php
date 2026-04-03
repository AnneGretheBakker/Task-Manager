<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TaskRepository $taskRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->render('home/index.html.twig', [
                'message' => 'Log in om je taken te zien.',
            ]);
        }

        // Alle taken van de ingelogde gebruiker
        $allTasks = $taskRepository->findBy(['user' => $user]);

        // Statistieken
        $openTasks = array_filter($allTasks, fn($t) => $t->getStatus() !== 'Done');
        $highPriority = array_filter($allTasks, fn($t) => $t->getPriority() === 'High');

        $today = new \DateTime();
        $tomorrow = (clone $today)->modify('+1 day');
        $urgentTasks = array_filter($openTasks, function($t) use ($today, $tomorrow) {
            $deadline = $t->getDeadline();
            return $deadline && ($deadline->format('Y-m-d') === $today->format('Y-m-d') || $deadline->format('Y-m-d') === $tomorrow->format('Y-m-d'));
        });

        return $this->render('home/index.html.twig', [
            'openTasks' => $openTasks,
            'highPriority' => $highPriority,
            'urgentTasks' => $urgentTasks,
        ]);
    }
}
