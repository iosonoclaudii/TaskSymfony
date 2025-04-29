<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list')]
    public function list(SessionInterface $session): Response
    {
        $tasks = $session->get('tasks', []);

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/tasks/new', name: 'task_new')]
    public function new(Request $request, SessionInterface $session): Response
    {
        $task = $request->request->get('task');

        if ($request->isMethod('POST') && $task) {
            $tasks = $session->get('tasks', []);
            $tasks[] = $task;
            $session->set('tasks', $tasks);

            $this->addFlash('success', 'Attività aggiunta: ' . htmlspecialchars($task));
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/new.html.twig');
    }

    #[Route('/tasks/delete/{index}', name: 'task_delete')]
    public function delete(int $index, SessionInterface $session): Response
    {
        $tasks = $session->get('tasks', []);

        if (isset($tasks[$index])) {
            unset($tasks[$index]);
            $tasks = array_values($tasks); // Reindicizza l'array
            $session->set('tasks', $tasks);

            $this->addFlash('success', 'Attività rimossa.');
        }

        return $this->redirectToRoute('task_list');
    }
}
