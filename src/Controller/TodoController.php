<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/todo")]

class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('todo')) {
            $todo = [
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger mes examens'
            ];
            $session->set('todo', $todo);
            $this->addFlash('info', "Liste Todo Initialisée");
        }

        return $this->render('todo/index.html.twig');
    }

    #[Route('/add/{name?test}/{content?test}',
        name: 'todo.add',
    )]
    public function addTodo(Request $request, $name, $content):RedirectResponse
    {
        $session = $request->getSession();
        //check the existence of the table "todo"
        if ($session->has('todo')) {
            $todo = $session->get('todo');
            if (isset($todo[$name])) {
                $this->addFlash('error', "tache d'id $name déjà ajouté");
            } else {
                $todo[$name] = $content;
                $this->addFlash('success', 'Tache ajoutée');
                $session->set('todo', $todo);
            }
        } else {
            $this->addFlash('error', 'Liste pas encore initialisée');
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/delete/{name}', name: 'todo.delete')]
    public function deleteTodo(Request $request, $name):RedirectResponse
    {
        $session = $request->getSession();
        //check the existence of the table "todo"
        if ($session->has('todo')) {
            $todo = $session->get('todo');
            if (isset($todo[$name])) {
                unset($todo[$name]);
                $this->addFlash('success', 'Tache supprimée');
                $session->set('todo', $todo);
            } else {
                $this->addFlash('error', "tache d'id $name n'existe pas!");
            }
        } else {
            $this->addFlash('error', 'Liste pas encore initialisée');
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(Request $request, $name, $content):RedirectResponse
    {
        $session = $request->getSession();
        //check the existence of the table "todo"
        if ($session->has('todo')) {
            $todo = $session->get('todo');
            if (isset($todo[$name])) {
                $todo[$name] = $content;
                $this->addFlash('success', 'Tache mise à jour');
                $session->set('todo', $todo);
            } else {
                $this->addFlash('error', "tache d'id $name n'existe pas!");
            }
        } else {
            $this->addFlash('error', 'Liste pas encore initialisée');
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/reset', name: 'todo.reset')]
    public function reset(Request $request): Response
    {
        $session = $request->getSession();
        $session->remove('todo');
        return $this->redirectToRoute('app_todo');
    }

}