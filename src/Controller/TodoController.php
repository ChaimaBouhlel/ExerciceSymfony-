<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if(!$session->has('todo')){
            $todo = [
                'achat'=>'acheter clé usb',
                'cours'=>'Finaliser mon cours',
                'correction'=>'corriger mes examens'
            ];
            $session->set('todo',$todo);
            $this->addFlash('info',"Liste Todo Initialisée");
        }

        return $this->render('todo/index.html.twig');
    }

    #[Route('/todo/add/{name}/{content}',name:'todo.add')]
    public function addTodo(Request $request, $name, $content)
    {
        $session = $request->getSession();
        //check the existence of the table "todo"
        if($session->has('todo')){
            $todo = $session->get('todo');
            if (isset($todo[$name])){
                $this->addFlash('error',"tache d'id $name déjà ajouté");
            }else{
                $todo[$name] = $content;
                $this->addFlash('success','Tache ajoutée');
                $session->set('todo',$todo);
            }
        }else{
            $this->addFlash('error','Liste pas encore initialisée');
        }
        return $this->redirectToRoute('app_todo');
    }

}
