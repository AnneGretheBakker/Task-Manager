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

/**
 * Controller for the Home page
 *
 * Get all tasks of the user, and send arrays with the open tasks, the tasks with high priority and the urgent tasks
 *
 * @extends AbstractController
 */
final class HomeController extends AbstractController
{
    /**
     * Get all tasks of the user, and send arrays with the open tasks, the tasks with high priority and the urgent tasks
     *
     * @param TaskRepository $taskRepository The Repository for the Task Entity
     * @return Response of rendering the home page and sending back arrays with the open tasks, the tasks with high
     *         priority and the urgent tasks
     */
    #[Route('/', name: 'app_home')]
    public function index(TaskRepository $taskRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->render('home/index.html.twig', [
                'message' => 'Log in om je taken te zien.',
            ]);
        }

        $allTasks = $taskRepository->findBy(['user' => $user]);

        $openTasks = array_filter($allTasks, fn($t) => $t->getStatus() !== 'Done');
        $highPriority = array_filter($allTasks, fn($t) => $t->getPriority() === 'High');

        $today = new \DateTime();
        $tomorrow = (clone $today)->modify('+1 day');
        $urgentTasks = array_filter($openTasks, function($t) use ($today, $tomorrow) {
            $deadline = $t->getDeadline();
            return $deadline && ($deadline->format('Y-m-d') === $today->format('Y-m-d') || $deadline->format('Y-m-d')
            === $tomorrow->format('Y-m-d'));
        });

        return $this->render('home/index.html.twig', [
            'openTasks' => $openTasks,
            'highPriority' => $highPriority,
            'urgentTasks' => $urgentTasks,
        ]);
    }
}
