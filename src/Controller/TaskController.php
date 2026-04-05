<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for the Task Entity
 *
 * Get all tasks of the user, or show, edit, create or delete a Task
 *
 * @extends AbstractController
 */
#[IsGranted('ROLE_USER')]
#[Route('/task')]
final class TaskController extends AbstractController
{
    /**
     * Gets all the tasks linked to the user in ascending order
     *
     * @param TaskRepository $taskRepository The repository of the Task Entity
     *
     * @return Response with the tasks found
     */
    #[Route('/', name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findBy(
            ['user' => $this->getUser()],
            ['deadline' => 'ASC']
        );

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * Creates a new Task and redirects user to the task index
     *
     * @param Request                $request       The new task request
     * @param EntityManagerInterface $entityManager The entity manager
     * @return Response with redirecting to the task index when form is submitted, or rendering of the new task page.
     */
    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * Shows the information of the chosen Task
     *
     * @param Task $task The Task to show the information of
     * @return Response of the rendering of the show task page with the task information
     */
    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * Creates edit Task form or saves the edited Task and redirects to the task index
     *
     * @param Request                $request       The task edit request
     * @param Task                   $task          The task to edit
     * @param EntityManagerInterface $entityManager The interface to handle the Entity Manager
     * @return Response with the redirection to the task index page if form is submitted, or rendering of the task edit
     *         form if not
     */
    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * Deletes the Task and redirects to the task index
     *
     * @param Request                $request       The task delete request
     * @param Task                   $task          The task to delete
     * @param EntityManagerInterface $entityManager The interface to handle the Entity Manager
     * @return Response with the redirection to the task index page
     */
    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }
}
